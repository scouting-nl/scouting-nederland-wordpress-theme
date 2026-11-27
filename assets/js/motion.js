/**
 * Reveals content once and controls the repeating decorative footer butterfly.
 *
 * @return {void}
 */
( function initializeMotionModule() {
	if ( ! globalThis.IntersectionObserver || ! globalThis.matchMedia ) {
		return;
	}

	/** @type {string} Selector for the decorative footer butterfly. */
	const BUTTERFLY_SELECTOR = '.scouting-nederland-footer-butterfly';
	/** @type {string} Class that starts one short content entrance. */
	const REVEAL_CLASS = 'scouting-nederland-layout-motion-reveal';
	/** @type {string} Class that starts the repeating butterfly flight. */
	const BUTTERFLY_CLASS = 'scouting-nederland-motion-reveal';
	/** @type {string} Class that pauses the butterfly without resetting its position. */
	const PAUSED_CLASS = 'scouting-nederland-footer-butterfly-paused';
	/** @type {string} Theme-owned cards corresponding to the upstream reveal targets. */
	const CONTENT_SELECTOR = [
		'.scouting-nederland-news-item',
		'.scouting-nederland-blocks-callout',
		'.scouting-nederland-blocks-featured-items-item',
		'.scouting-nederland-blocks-news-items-item',
		'.scouting-nederland-category-card',
		'.scouting-nederland-search-card',
		'main .type-post',
	].join( ',' );
	const targets = document.querySelectorAll( CONTENT_SELECTOR + ',' + BUTTERFLY_SELECTOR );
	const butterfly = document.querySelector( BUTTERFLY_SELECTOR );
	const motionPreference = globalThis.matchMedia( '(prefers-reduced-motion: reduce)' );
	const revealed = new WeakSet();
	let observer = null;

	/**
	 * Pauses the decorative butterfly while the browser tab is inactive.
	 *
	 * @return {void}
	 */
	function updateButterflyVisibility() {
		if ( ! butterfly ) {
			return;
		}

		butterfly.classList.toggle( PAUSED_CLASS, document.hidden );
	}

	/**
	 * Starts a target's animation once it enters the viewport.
	 *
	 * @param {IntersectionObserverEntry[]} entries Newly intersecting targets.
	 * @return {void}
	 */
	function revealEntries( entries ) {
		entries.forEach( ( entry ) => {
			const target = entry.target;
			if ( target.matches( BUTTERFLY_SELECTOR ) ) {
				target.classList.toggle( BUTTERFLY_CLASS, entry.isIntersecting && ! motionPreference.matches );
				updateButterflyVisibility();
				return;
			}

			if ( ! entry.isIntersecting || motionPreference.matches ) {
				return;
			}

			target.classList.add( REVEAL_CLASS );
			revealed.add( target );
			observer.unobserve( target );
		} );
	}

	/**
	 * Disconnects motion when reduced movement is preferred, including live changes.
	 *
	 * @return {void}
	 */
	function updateMotionPreference() {
		if ( observer ) {
			observer.disconnect();
		}

		if ( motionPreference.matches ) {
			targets.forEach( ( target ) => {
				target.classList.remove( REVEAL_CLASS, BUTTERFLY_CLASS );
			} );
			updateButterflyVisibility();
			return;
		}

		observer = new globalThis.IntersectionObserver( revealEntries, {
			rootMargin: '0px 0px -24px 0px',
			threshold: 0.1,
		} );

		targets.forEach( ( target ) => {
			if ( target.matches( BUTTERFLY_SELECTOR ) || ! revealed.has( target ) ) {
				observer.observe( target );
			}
		} );
	}

	updateMotionPreference();
	motionPreference.addEventListener( 'change', updateMotionPreference );
	document.addEventListener( 'visibilitychange', updateButterflyVisibility );
}() );
