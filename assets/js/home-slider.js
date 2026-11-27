/**
 * Boots the public home-page slider module without leaking names into the global scope.
 *
 * @return {void}
 */
( function initializeHomeSliderModule() {
	const ACTIVE_CLASS = 'is-active';
	const ICON_SELECTOR = 'i';
	const REDUCED_MOTION_QUERY = '(prefers-reduced-motion: reduce)';
	const SLIDE_SELECTOR = '.scouting-nederland-homepage-slide';
	const SLIDER_SELECTOR = '[data-scouting-nederland-slider]';
	const SLIDE_DURATION_MS = 6000;
	const TOGGLE_SELECTOR = '.scouting-nederland-homepage-slider-toggle';
	const VIDEO_SELECTOR = '.scouting-nederland-homepage-video-player';
	const motionPreference = globalThis.matchMedia
		? globalThis.matchMedia( REDUCED_MOTION_QUERY )
		: null;

	/**
	 * Starts automatic rotation for one slider containing at least two slides.
	 *
	 * @param {Element} slider The slider root whose child slides should rotate.
	 * @return {void}
	 */
	function initializeSlider( slider ) {
		// Capture the slider's static slide collection once during initialization.
		const slides = slider.querySelectorAll( SLIDE_SELECTOR );

		if ( ! slides.length ) {
			return;
		}

		const toggle = slider.querySelector( TOGGLE_SELECTOR );
		const reducedMotion = motionPreference?.matches || false;
		let currentIndex = Array.from( slides ).findIndex( ( slide ) => slide.classList.contains( ACTIVE_CLASS ) );
		let intervalId = null;

		if ( currentIndex < 0 ) {
			currentIndex = 0;
		}

		/**
		 * Synchronizes visual state and accessibility-tree visibility for every slide.
		 *
		 * @return {void}
		 */
		function updateSlides() {
			slides.forEach( ( slide, index ) => {
				const isActive = index === currentIndex;
				slide.classList.toggle( ACTIVE_CLASS, isActive );
				slide.setAttribute( 'aria-hidden', isActive ? 'false' : 'true' );
			} );
		}

		/**
		 * Moves the active class from the current slide to the next slide.
		 *
		 * @return {void}
		 */
		function showNextSlide() {
			// Wrap the next index back to zero after the final slide.
			currentIndex = ( currentIndex + 1 ) % slides.length;
			updateSlides();
		}

		/**
		 * Updates the toggle's label, pressed state, and familiar play/pause icon.
		 *
		 * @return {void}
		 */
		function updateToggle() {
			if ( ! toggle ) {
				return;
			}

			const isPaused = intervalId === null;
			const icon = toggle.querySelector( ICON_SELECTOR );
			toggle.setAttribute( 'aria-pressed', isPaused ? 'true' : 'false' );
			toggle.setAttribute( 'aria-label', isPaused ? toggle.dataset.playLabel : toggle.dataset.pauseLabel );

			if ( icon ) {
				icon.classList.toggle( 'fa-play', isPaused );
				icon.classList.toggle( 'fa-pause', ! isPaused );
			}
		}

		/**
		 * Starts automatic rotation unless it is already running.
		 *
		 * @return {void}
		 */
		function startSlider() {
			if ( intervalId !== null || slides.length < 2 ) {
				return;
			}

			intervalId = globalThis.setInterval( showNextSlide, SLIDE_DURATION_MS );
			updateToggle();
		}

		/**
		 * Stops automatic rotation while preserving the currently visible slide.
		 *
		 * @return {void}
		 */
		function pauseSlider() {
			if ( intervalId !== null ) {
				globalThis.clearInterval( intervalId );
				intervalId = null;
			}

			updateToggle();
		}

		updateSlides();

		if ( toggle ) {
			toggle.addEventListener( 'click', () => {
				if ( intervalId === null ) {
					startSlider();
				} else {
					pauseSlider();
				}
			} );
		}

		if ( reducedMotion || slides.length < 2 ) {
			pauseSlider();
		} else {
			startSlider();
		}

		motionPreference?.addEventListener?.( 'change', ( event ) => {
			if ( event.matches ) {
				pauseSlider();
			} else {
				startSlider();
			}
		} );
	}

	/**
	 * Stops autoplaying library videos when reduced motion is requested.
	 *
	 * @return {void}
	 */
	function initializeVideos() {
		document.querySelectorAll( VIDEO_SELECTOR ).forEach( ( video ) => {
			const updateVideoMotion = ( reducedMotion ) => {
				video.autoplay = ! reducedMotion;

				if ( reducedMotion ) {
					video.pause();
				} else if ( video.dataset.autoplay !== undefined ) {
					video.play().catch( () => { } );
				}
			};

			updateVideoMotion( motionPreference?.matches || false );
			motionPreference?.addEventListener?.( 'change', ( event ) => updateVideoMotion( event.matches ) );
		} );
	}

	/**
	 * Finds and initializes every slider after the public document is available.
	 *
	 * @return {void}
	 */
	function initializeSliders() {
		// Initialize each independent slider root on the current page.
		document.querySelectorAll( SLIDER_SELECTOR ).forEach( initializeSlider );
		initializeVideos();
		document.documentElement.classList.add( 'scouting-nederland-homepage-media-ready' );
	}

	// Delay DOM queries until all slider markup has been parsed.
	document.addEventListener( 'DOMContentLoaded', initializeSliders );
}() );
