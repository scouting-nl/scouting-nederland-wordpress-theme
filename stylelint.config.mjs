import { createRequire } from 'node:module';
import path from 'node:path';
import { pathToFileURL } from 'node:url';

const globalModuleRoot = process.platform === 'win32' && process.env.APPDATA
	? path.join( process.env.APPDATA, 'npm', 'node_modules' )
	: path.resolve( path.dirname( process.execPath ), '..', 'lib', 'node_modules' );
const moduleRoot = process.env.STYLELINT_MODULE_ROOT || globalModuleRoot;

const require = createRequire(
	pathToFileURL( path.join( moduleRoot, 'stylelint', 'package.json' ) ),
);

const wordpressClasses = [
	'components-textarea-control__input',
	'current_page_ancestor',
	'current_page_item',
	'widget_block',
	'widget_search',
	'wp-block-button__link',
	'wp-block-search__button',
	'wp-block-search__input',
	'wp-block-search__inside-wrapper',
	'wp-block-search__label',
];

export default {
	extends: [ require.resolve( '@wordpress/stylelint-config/stylistic' ) ],
	ignoreFiles: [ '**/node_modules/**', '**/vendor/**' ],
	rules: {
		'selector-class-pattern': [
			'^(?:[a-z][a-z0-9]*(?:-[a-z0-9]+)*|' + wordpressClasses.join( '|' ) + ')$',
			{
				message: 'Use lowercase, hyphen-separated theme classes or an existing WordPress core class.',
			},
		],
	},
};
