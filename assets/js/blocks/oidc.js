/**
 * Registers the optional Scouting OpenID Connect block and its live preview.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerOidcBlock(wp, blockEditor) {
    /**
     * @typedef {Object} OidcAttributes
     * @property {string} displayType Whether PHP renders a button or text link.
     * @property {string} buttonText The label used by the rendered login control.
     * @property {boolean} alignCenter Whether the public control is centered.
     */

    // Import the inspector controls and components required by this small settings block.
    const {
        registerBlockType,
        __,
        Fragment,
        InspectorControls,
        PanelBody,
        SelectControl,
        TextControl,
        ToggleControl
    } = blockEditor;
    const { createElement } = wp.element;

    // Resolve ServerSideRender from its modern package or its older components location.
    const ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender;
    const DISPLAY_TYPE_OPTIONS = [
        { label: __('Login-knop', 'scouting-nederland'), value: 'button' },
        { label: __('Tekstlink', 'scouting-nederland'), value: 'link' }
    ];

    /**
     * Renders the sidebar controls for OIDC display mode, text, and alignment.
     *
     * @param {OidcAttributes} attributes The current OIDC block attributes.
    * @param {string} displayType The active display mode with the button default applied.
     * @param {Function} setAttributes Persists a partial OIDC attribute update.
     * @returns {Object} A WordPress InspectorControls element.
     */
    function renderInspector(attributes, displayType, setAttributes) {
        // Keep secondary settings in the standard Gutenberg inspector sidebar.
        return createElement(
            InspectorControls,
            {},
            createElement(
                PanelBody,
                {
                    title: __('Scouting OIDC instellingen', 'scouting-nederland'),
                    initialOpen: true
                },
                createElement(SelectControl, {
                    label: __('Weergave', 'scouting-nederland'),
                    value: displayType,
                    options: DISPLAY_TYPE_OPTIONS,
                    onChange: (value) => setAttributes({ displayType: value })
                }),
                displayType === 'link'
                    ? createElement(TextControl, {
                        label: __('Linktekst', 'scouting-nederland'),
                        value: attributes.buttonText || '',
                        onChange: (buttonText) => setAttributes({ buttonText })
                    })
                    : null,
                createElement(ToggleControl, {
                    label: __('Centreren', 'scouting-nederland'),
                    checked: Boolean(attributes.alignCenter),
                    onChange: (alignCenter) => setAttributes({ alignCenter })
                })
            )
        );
    }

    /**
     * Renders a live PHP-backed block preview or a compatibility fallback message.
     *
     * @param {OidcAttributes} attributes The attributes passed to the PHP render callback.
     * @returns {Object} A WordPress element containing editor context and the preview.
     */
    function renderPreview(attributes) {
        // Use server-side rendering when WordPress exposes the required component.
        const preview = ServerSideRender
            ? createElement(ServerSideRender, {
                block: 'scouting-nederland/oidc',
                attributes
            })
            : createElement(
                'p',
                {},
                __('Voorbeeld niet beschikbaar.', 'scouting-nederland')
            );

        // Wrap the live result with a heading and editor-only explanation.
        return createElement(
            'div',
            {
                className: 'scouting-nederland-acf-like-block '
                    + 'scouting-nederland-oidc-editor-form'
            },
            createElement('h3', {}, __('Scouting OIDC', 'scouting-nederland')),
            createElement(
                'p',
                {},
                __('Dit blok toont de login van de plugin Scouting OpenID Connect.', 'scouting-nederland')
            ),
            preview
        );
    }

    registerBlockType('scouting-nederland/oidc', {
        title: __('Scouting OIDC', 'scouting-nederland'),
        description: __('Voeg een Scouting OpenID Connect login toe.', 'scouting-nederland'),
        icon: 'admin-users',
        category: 'scouting-nederland',
        attributes: {
            displayType: { type: 'string', default: 'button' },
            buttonText: { type: 'string', default: 'Inloggen met Scouting' },
            alignCenter: { type: 'boolean', default: false }
        },
        /**
         * Renders OIDC inspector settings beside the server-generated preview.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {OidcAttributes} props.attributes The current OIDC values.
         * @param {Function} props.setAttributes Persists partial OIDC updates.
         * @returns {Object} The OIDC editor element tree.
         */
        edit: ({ attributes, setAttributes }) => {
            // Normalize missing legacy values to the block's button default.
            const displayType = attributes.displayType || 'button';

            // Return inspector settings and preview as sibling elements without extra markup.
            return createElement(
                Fragment,
                {},
                renderInspector(attributes, displayType, setAttributes),
                renderPreview(attributes)
            );
        },
        /**
         * Delegates public markup generation to the PHP render callback.
         *
         * @returns {null} Dynamic blocks save attributes without static HTML.
         */
        save: () => {
            // Returning null tells Gutenberg that PHP owns the front-end markup.
            return null;
        }
    });
})(globalThis.wp, globalThis.scoutingNederlandBlockEditor);
