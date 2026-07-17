/**
 * Boots the public home-page slider module without leaking names into the global scope.
 *
 * @returns {void}
 */
(function initializeHomeSliderModule() {
  const ACTIVE_CLASS = 'is-active';
  const SLIDE_SELECTOR = '.scouting-nederland-home-slide';
  const SLIDER_SELECTOR = '[data-scouting-nederland-slider]';
  const SLIDE_DURATION_MS = 6000;

  /**
   * Starts automatic rotation for one slider containing at least two slides.
   *
   * @param {Element} slider The slider root whose child slides should rotate.
   * @returns {void}
   */
  function initializeSlider(slider) {
    // Capture the slider's static slide collection once during initialization.
    const slides = slider.querySelectorAll(SLIDE_SELECTOR);

    // A single slide needs no timer or class changes.
    if (slides.length < 2) {
      return;
    }

    // PHP marks the first rendered slide active, so rotation starts at index zero.
    let currentIndex = 0;

    /**
     * Moves the active class from the current slide to the next slide.
     *
     * @returns {void}
     */
    function showNextSlide() {
      // Deactivate the slide that is currently visible.
      slides[currentIndex].classList.remove(ACTIVE_CLASS);

      // Wrap the next index back to zero after the final slide.
      currentIndex = (currentIndex + 1) % slides.length;

      // Activate the newly selected slide.
      slides[currentIndex].classList.add(ACTIVE_CLASS);
    }

    // Keep rotating this slider at the theme's configured six-second interval.
    globalThis.setInterval(function () {
      showNextSlide();
    }, SLIDE_DURATION_MS);
  }

  /**
   * Finds and initializes every slider after the public document is available.
   *
   * @returns {void}
   */
  function initializeSliders() {
    // Initialize each independent slider root on the current page.
    document.querySelectorAll(SLIDER_SELECTOR).forEach(initializeSlider);
  }

  // Delay DOM queries until all slider markup has been parsed.
  document.addEventListener('DOMContentLoaded', initializeSliders);
})();
