/**
 * Initializes the slider controls on the WordPress page-editing screen.
 *
 * @param {Object}   mediaApi       The global WordPress API object.
 * @param {Function} mediaApi.media The factory used to create a media-selection frame.
 * @return {void}
 */
( function initializeAdminSlider( mediaApi ) {
	/**
	 * Opens a single-selection WordPress media frame.
	 *
	 * @param {Object}   settings               Configuration for the media dialog.
	 * @param {string}   settings.title         The title displayed at the top of the dialog.
	 * @param {string}   settings.buttonText    The label of the confirmation button.
	 * @param {string}   [settings.libraryType] The optional media type used to filter the library.
	 * @param {Function} onSelect               Receives the normalized attachment selected by the user.
	 * @return {void}
	 */
	function openMediaFrame( settings, onSelect ) {
		// Create a single-selection frame with the requested title and optional media filter.
		const frame = mediaApi.media( {
			title: settings.title,
			button: { text: settings.buttonText },
			library: settings.libraryType ? { type: settings.libraryType } : undefined,
			multiple: false,
		} );

		// Convert WordPress's selected Backbone model into a plain attachment object.
		frame.on( 'select', function() {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			onSelect( attachment );
		} );

		// Display the configured media library to the editor.
		frame.open();
	}

	/**
	 * Connects slider form controls after the admin document is ready.
	 *
	 * @return {void}
	 */
	function initializeSliderControls() {
		const SELECTORS = {
			addSlideButton: '#scouting-nederland-admin-slider-add-slide',
			imageId: '.scouting-nederland-admin-slider-image-id',
			imagePreview: '.scouting-nederland-admin-slider-preview',
			removeSlideButton: '.scouting-nederland-admin-slider-remove-slide',
			removeVideoButton: '#scouting-nederland-admin-slider-remove-video',
			row: '.scouting-nederland-admin-slider-row',
			rowNumber: '.scouting-nederland-admin-slider-number',
			rows: '#scouting-nederland-admin-slider-rows',
			selectImageButton: '.scouting-nederland-admin-slider-select-image',
			selectVideoButton: '#scouting-nederland-admin-slider-select-video',
			slideTemplate: '#scouting-nederland-admin-slider-template',
			videoId: '#scouting-nederland-admin-slider-video-id',
			videoPreview: '#scouting-nederland-admin-slider-video-preview',
		};
		const SLIDE_FIELD_PATTERN = /scouting_nederland_slides\[\d+\]/;
		const rows = document.querySelector( SELECTORS.rows );
		const slideTemplate = document.querySelector( SELECTORS.slideTemplate );

		if ( ! rows || ! slideTemplate ) {
			return;
		}

		/**
		 * Rebuilds visible slide numbers and contiguous form-field indices.
		 *
		 * @return {void}
		 */
		function renumberRows() {
			rows.querySelectorAll( SELECTORS.row ).forEach( ( row, index ) => {
				const fieldPrefix = 'scouting_nederland_slides[' + index + ']';

				row.querySelector( SELECTORS.rowNumber ).textContent = index + 1;

				row.querySelectorAll( 'input, textarea, select' ).forEach( ( field ) => {
					if ( field.name ) {
						field.name = field.name.replace( SLIDE_FIELD_PATTERN, fieldPrefix );
					}
				} );
			} );
		}

		/**
		 * Replaces a slide's image preview with the selected attachment.
		 *
		 * @param {HTMLElement} container          The preview container belonging to the edited row.
		 * @param {Object}      attachment         The selected WordPress media attachment.
		 * @param {string}      attachment.url     The full-size attachment URL.
		 * @param {Object}      [attachment.sizes] Optional generated image sizes.
		 * @return {void}
		 */
		function renderImagePreview( container, attachment ) {
			const thumbnail = attachment.sizes && attachment.sizes.thumbnail;
			const imageUrl = thumbnail ? thumbnail.url : attachment.url;
			const image = document.createElement( 'img' );
			image.src = imageUrl;
			image.alt = '';
			image.style.maxWidth = '120px';
			image.style.height = 'auto';
			container.replaceChildren( image );
		}

		/**
		 * Replaces the shared video preview with the selected attachment.
		 *
		 * @param {Object} attachment            The selected WordPress video attachment.
		 * @param {string} attachment.url        The video file URL.
		 * @param {string} [attachment.filename] The human-readable video filename.
		 * @return {void}
		 */
		function renderVideoPreview( attachment ) {
			const fileName = attachment.filename || attachment.url.split( '/' ).pop();
			const video = document.createElement( 'video' );
			video.src = attachment.url;
			video.style.maxWidth = '320px';
			video.style.height = 'auto';
			video.controls = true;
			video.muted = true;
			const caption = document.createElement( 'p' );
			const fileNameText = document.createElement( 'code' );
			fileNameText.textContent = fileName;
			caption.append( fileNameText );
			document.querySelector( SELECTORS.videoPreview ).replaceChildren( video, caption );
		}

		/**
		 * Appends one empty slide row from the server-rendered template.
		 *
		 * @param {MouseEvent} event The add-slide button click event.
		 * @return {void}
		 */
		function addSlide( event ) {
			event.preventDefault();
			const nextIndex = rows.querySelectorAll( SELECTORS.row ).length;
			rows.insertAdjacentHTML( 'beforeend', slideTemplate.textContent.replaceAll( '__INDEX__', nextIndex ) );
			renumberRows();
		}

		/**
		 * Opens the image library and stores the chosen image on one slide row.
		 *
		 * @param {HTMLTableRowElement} row The row whose image is being selected.
		 * @return {void}
		 */
		function selectSlideImage( row ) {
			openMediaFrame(
				{ title: 'Afbeelding kiezen', buttonText: 'Gebruik afbeelding' },
				function( attachment ) {
					row.querySelector( SELECTORS.imageId ).value = attachment.id;
					renderImagePreview( row.querySelector( SELECTORS.imagePreview ), attachment );
				},
			);
		}

		/**
		 * Handles image selection and removal for existing and newly added rows.
		 *
		 * @param {MouseEvent} event The delegated row-button click event.
		 * @return {void}
		 */
		function handleRowAction( event ) {
			if ( ! ( event.target instanceof Element ) ) {
				return;
			}

			const button = event.target.closest( 'button' );
			const row = button?.closest( SELECTORS.row );
			if ( ! row || ! rows.contains( row ) ) {
				return;
			}

			if ( button.matches( SELECTORS.removeSlideButton ) ) {
				event.preventDefault();
				row.remove();
				renumberRows();
			} else if ( button.matches( SELECTORS.selectImageButton ) ) {
				event.preventDefault();
				selectSlideImage( row );
			}
		}

		/**
		 * Opens the video library and stores the shared slider video selection.
		 *
		 * @param {MouseEvent} event The video-selection button click event.
		 * @return {void}
		 */
		function selectVideo( event ) {
			event.preventDefault();
			openMediaFrame(
				{ title: 'Video kiezen', buttonText: 'Gebruik video', libraryType: 'video' },
				function( attachment ) {
					document.querySelector( SELECTORS.videoId ).value = attachment.id;
					renderVideoPreview( attachment );
				},
			);
		}

		/**
		 * Clears the selected slider video and its preview.
		 *
		 * @param {MouseEvent} event The remove-video button click event.
		 * @return {void}
		 */
		function removeVideo( event ) {
			event.preventDefault();
			document.querySelector( SELECTORS.videoId ).value = '';
			document.querySelector( SELECTORS.videoPreview ).replaceChildren();
		}

		document.querySelector( SELECTORS.addSlideButton ).addEventListener( 'click', addSlide );
		document.querySelector( SELECTORS.selectVideoButton ).addEventListener( 'click', selectVideo );
		document.querySelector( SELECTORS.removeVideoButton ).addEventListener( 'click', removeVideo );
		rows.addEventListener( 'click', handleRowAction );
		renumberRows();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initializeSliderControls, { once: true } );
	} else {
		initializeSliderControls();
	}
}( globalThis.wp ) );
