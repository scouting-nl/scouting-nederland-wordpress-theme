/**
 * Prints the Scouting IT volunteer message with the Scout-In console styling.
 *
 * @see https://scout-in.scouting.nl/
 * @param {Object} labels           Translated recruitment text supplied by WordPress.
 * @param {string} labels.heading   Recruitment heading.
 * @param {string} labels.message   Volunteer recruitment message.
 * @param {string} labels.vacancies Vacancy link label.
 * @return {void}
 */
( function printScoutingConsoleMessage( labels ) {
	/** @type {string} Official Scouting IT volunteer vacancies. */
	const VACANCY_URL = 'https://vrijwilligers.scouting.nl/vacatures/?sort=latest&field[]=b412e479-d2ae-403a-9457-257327738a7b';
	/** @type {string} Burgundy heading style used by Scout-In. */
	const HEADER_STYLE = [
		'background: #5f112b',
		'color: #ffffff',
		'font-size: 15px',
		'font-weight: bold',
		'padding: 8px 14px',
		'border-radius: 6px 6px 0 0',
		'display: block',
	].join( ';' );
	/** @type {string} Lighter burgundy recruitment text style used by Scout-In. */
	const BODY_STYLE = [
		'background: #7a394e',
		'color: #ffffff',
		'font-size: 13px',
		'padding: 10px 14px',
		'line-height: 1.6',
		'display: block',
	].join( ';' );
	/** @type {string} Purple campaign tag style used by Scout-In. */
	const TAG_STYLE = [
		'background: #4e2567',
		'color: #ffffff',
		'font-size: 13px',
		'font-weight: bold',
		'letter-spacing: 0.5px',
		'padding: 6px 14px',
		'display: block',
	].join( ';' );
	/** @type {string} Green vacancy link style used by Scout-In. */
	const LINK_STYLE = [
		'background: #2f8a6c',
		'color: #ffffff',
		'font-size: 13px',
		'font-weight: bold',
		'padding: 8px 14px',
		'border-radius: 0 0 6px 6px',
		'display: block',
	].join( ';' );

	globalThis.console.log( '%c\u269c\ufe0f ' + labels.heading, HEADER_STYLE );
	globalThis.console.log( '%c' + labels.message, BODY_STYLE );
	globalThis.console.log( '%c#TogetherWeBuild', TAG_STYLE );
	globalThis.console.log( `%c\ud83d\udc49 ${ labels.vacancies } ${ VACANCY_URL }`, LINK_STYLE );
}( globalThis.scoutingNederlandConsoleL10n ) );
