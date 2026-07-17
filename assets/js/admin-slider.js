/* global jQuery, wp */

/**
 * Initializes the slider controls on the WordPress page-editing screen.
 *
 * @param {JQueryStatic} $ The WordPress-provided jQuery instance.
 * @param {Object} mediaApi The global WordPress API object.
 * @param {Function} mediaApi.media The factory used to create a media-selection frame.
 * @returns {void}
 */
(function initializeAdminSlider($, mediaApi) {
    /**
     * Opens a single-selection WordPress media frame.
     *
     * @param {Object} settings Configuration for the media dialog.
     * @param {string} settings.title The title displayed at the top of the dialog.
     * @param {string} settings.buttonText The label of the confirmation button.
     * @param {string} [settings.libraryType] The optional media type used to filter the library.
     * @param {Function} onSelect Receives the normalized attachment selected by the user.
     * @returns {void}
     */
    function openMediaFrame(settings, onSelect) {
        // Create a single-selection frame with the requested title and optional media filter.
        const frame = mediaApi.media({
            title: settings.title,
            button: { text: settings.buttonText },
            library: settings.libraryType ? { type: settings.libraryType } : undefined,
            multiple: false
        });

        // Convert WordPress's selected Backbone model into a plain attachment object.
        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            onSelect(attachment);
        });

        // Display the configured media library to the editor.
        frame.open();
    }

    /**
     * Connects slider form controls after the admin document is ready.
     *
     * @returns {void}
     */
    function initializeSliderControls() {
        // Keep every DOM selector in one place so handlers use the same form contract.
        const SELECTORS = {
            addSlideButton: '#scouting-nederland-add-slide',
            imageId: '.scouting-nederland-slide-image-id',
            imagePreview: '.scouting-nederland-slide-preview',
            removeSlideButton: '.scouting-nederland-remove-slide',
            removeVideoButton: '#scouting-nederland-remove-video',
            row: '.scouting-nederland-slide-row',
            rowNumber: '.scouting-nederland-slide-number',
            rows: '#scouting-nederland-slides-rows',
            selectImageButton: '.scouting-nederland-select-image',
            selectVideoButton: '#scouting-nederland-select-video',
            slideTemplate: '#scouting-nederland-slide-template',
            videoId: '#scouting_nederland_slider_video_id',
            videoPreview: '#scouting-nederland-slider-video-preview'
        };
        const SLIDE_FIELD_PATTERN = /scouting_nederland_slides\[\d+\]/;

        /**
         * Rebuilds visible slide numbers and contiguous form-field indices.
         *
         * @returns {void}
         */
        function renumberRows() {
            // Process rows in their current DOM order so displayed and submitted indices match.
            $(SELECTORS.rows).find(SELECTORS.row).each(function (index) {
                const row = $(this);
                const fieldPrefix = 'scouting_nederland_slides[' + index + ']';

                // Update the one-based number shown to the editor.
                row.find(SELECTORS.rowNumber).text(index + 1);

                // Rewrite every named control to the row's new zero-based PHP array index.
                row.find('input, textarea, select').each(function () {
                    const field = $(this);
                    const name = field.attr('name');

                    if (name) {
                        field.attr('name', name.replace(SLIDE_FIELD_PATTERN, fieldPrefix));
                    }
                });
            });
        }

        /**
         * Replaces a slide's image preview with the selected attachment.
         *
         * @param {JQuery} container The preview container belonging to the edited row.
         * @param {Object} attachment The selected WordPress media attachment.
         * @param {string} attachment.url The full-size attachment URL.
         * @param {Object} [attachment.sizes] Optional generated image sizes.
         * @returns {void}
         */
        function renderImagePreview(container, attachment) {
            // Prefer the smaller WordPress thumbnail and fall back to the original image.
            const thumbnail = attachment.sizes && attachment.sizes.thumbnail;
            const imageUrl = thumbnail ? thumbnail.url : attachment.url;

            // Build the image as a DOM node so attachment values are never interpolated into HTML.
            const image = $('<img>', { src: imageUrl, alt: '' }).css({
                maxWidth: '120px',
                height: 'auto'
            });

            // Remove the previous preview before showing the newly selected image.
            container.empty().append(image);
        }

        /**
         * Replaces the shared video preview with the selected attachment.
         *
         * @param {Object} attachment The selected WordPress video attachment.
         * @param {string} attachment.url The video file URL.
         * @param {string} [attachment.filename] The human-readable video filename.
         * @returns {void}
         */
        function renderVideoPreview(attachment) {
            // Derive a readable filename when WordPress does not provide one explicitly.
            const fileName = attachment.filename || attachment.url.split('/').pop();

            // Create a muted, controllable preview and a text-safe filename caption.
            const video = $('<video>', { src: attachment.url }).css({
                maxWidth: '320px',
                height: 'auto'
            }).prop({ controls: true, muted: true });
            const caption = $('<p>').append($('<code>').text(fileName));

            // Replace any previous video preview with the current selection.
            $(SELECTORS.videoPreview).empty().append(video, caption);
        }

        /**
         * Appends one empty slide row from the server-rendered template.
         *
         * @param {JQuery.Event} event The add-slide button click event.
         * @returns {void}
         */
        function addSlide(event) {
            // Prevent the metabox button from submitting the surrounding page form.
            event.preventDefault();

            // Use the current row count as the template's provisional array index.
            const rows = $(SELECTORS.rows);
            const nextIndex = rows.find(SELECTORS.row).length;
            const template = $(SELECTORS.slideTemplate).html();

            // Materialize the template and normalize all indices after insertion.
            rows.append(template.replaceAll('__INDEX__', nextIndex));
            renumberRows();
        }

        /**
         * Removes the selected slide row and closes any gap in submitted indices.
         *
         * @param {JQuery.Event} event The remove-slide button click event.
         * @returns {void}
         */
        function removeSlide(event) {
            // Prevent the row button from submitting the page form.
            event.preventDefault();

            // Remove the row that owns the clicked button, then rebuild field names.
            $(event.currentTarget).closest('tr').remove();
            renumberRows();
        }

        /**
         * Opens the image library and stores the chosen image on one slide row.
         *
         * @param {JQuery.Event} event The image-selection button click event.
         * @returns {void}
         */
        function selectSlideImage(event) {
            // Prevent submission and capture the originating row before the async dialog opens.
            event.preventDefault();
            const row = $(event.currentTarget).closest('tr');

            // Open the media library and update only the row that launched it.
            openMediaFrame(
                { title: 'Afbeelding kiezen', buttonText: 'Gebruik afbeelding' },
                function (attachment) {
                    // Persist the attachment ID for PHP and rebuild the visual preview.
                    row.find(SELECTORS.imageId).val(attachment.id);
                    renderImagePreview(row.find(SELECTORS.imagePreview), attachment);
                }
            );
        }

        /**
         * Opens the video library and stores the shared slider video selection.
         *
         * @param {JQuery.Event} event The video-selection button click event.
         * @returns {void}
         */
        function selectVideo(event) {
            // Prevent the metabox button from submitting the surrounding page form.
            event.preventDefault();

            // Restrict the media frame to videos and persist the selected attachment.
            openMediaFrame(
                { title: 'Video kiezen', buttonText: 'Gebruik video', libraryType: 'video' },
                function (attachment) {
                    // Store the attachment ID and replace the current video preview.
                    $(SELECTORS.videoId).val(attachment.id);
                    renderVideoPreview(attachment);
                }
            );
        }

        /**
         * Clears the selected slider video and its preview.
         *
         * @param {JQuery.Event} event The remove-video button click event.
         * @returns {void}
         */
        function removeVideo(event) {
            // Prevent form submission before clearing both persisted and visible state.
            event.preventDefault();
            $(SELECTORS.videoId).val('');
            $(SELECTORS.videoPreview).empty();
        }

        // Bind controls that already exist when the metabox is initialized.
        $(SELECTORS.addSlideButton).on('click', addSlide);
        $(SELECTORS.selectVideoButton).on('click', selectVideo);
        $(SELECTORS.removeVideoButton).on('click', removeVideo);

        // Delegate row actions because newly appended slide rows need the same handlers.
        $(document).on('click', SELECTORS.removeSlideButton, removeSlide);
        $(document).on('click', SELECTORS.selectImageButton, selectSlideImage);

        // Normalize stored field names in case previously saved slide indices contain gaps.
        renumberRows();
    }

    // Wait until WordPress has rendered the slider metabox before querying its controls.
    $(initializeSliderControls);
})(globalThis.jQuery, globalThis.wp);
