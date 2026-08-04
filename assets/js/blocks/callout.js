/**
 * Registers the server-rendered callout block and its editor form.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerCalloutBlock(wp, blockEditor) {
    /**
     * @typedef {Object} CalloutAttributes
     * @property {string} title The callout heading.
     * @property {string} text The callout HTML content.
     * @property {string} buttonText The call-to-action label.
     * @property {string} buttonUrl The call-to-action URL.
     * @property {string} buttonTarget The call-to-action browser target.
     * @property {string} buttonColor The selected theme button color.
     * @property {string} imageUrl The optional custom image URL.
     * @property {string} calloutPreset The selected built-in image key.
     * @property {boolean} useOwnImage Whether the custom image replaces the preset.
     */

    // Import only the shared APIs used by this block editor.
    const {
        registerBlockType,
        __,
        TextControl,
        ClassicHtmlEditor,
        ColorRadioControl,
        ImagePicker,
        LinkPickerField
    } = blockEditor;
    const { createElement } = wp.element;

    const DEFAULT_CALLOUT_TEXT = 'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen '
        + 'en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke '
        + 'kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!';
    const FALLBACK_CALLOUT_OPTIONS = [
        { label: 'bijl', value: 'bijl' },
        { label: 'boei', value: 'boei' },
        { label: 'gps', value: 'gps' },
        { label: 'haken', value: 'haken' },
        { label: 'hout', value: 'hout' },
        { label: 'kompas', value: 'kompas' },
        { label: 'lamp', value: 'lamp' },
        { label: 'touw', value: 'touw' },
        { label: 'zaklamp', value: 'zaklamp' },
        { label: 'zakmes', value: 'zakmes' }
    ];

    /**
     * Gets the callout image presets supplied by PHP or the built-in fallback list.
     *
     * @returns {Array<Object>} Options containing a display label and stored preset value.
     */
    function getCalloutOptions() {
        // Read server-generated options from the localized global when available.
        const localizedOptions = globalThis.scoutingNederlandBlocks?.calloutOptions;

        // Fall back to the matching built-in keys if localization failed or returned no options.
        return Array.isArray(localizedOptions) && localizedOptions.length
            ? localizedOptions
            : FALLBACK_CALLOUT_OPTIONS;
    }

    /**
     * Renders the radio controls that switch between a custom and preset image.
     *
     * @param {CalloutAttributes} attributes The current callout block attributes.
     * @param {Function} setAttributes Persists a partial callout attribute update.
     * @returns {Object} A WordPress element containing both image-mode choices.
     */
    function renderImageModeControl(attributes, setAttributes) {
        // Bind both radios to the same boolean so exactly one image mode is active.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-row' },
            createElement('label', {}, 'Eigen beeld gebruiken'),
            createElement(
                'div',
                {
                    className: 'scouting-nederland-editor-radio '
                        + 'scouting-nederland-editor-radio-inline'
                },
                createElement(
                    'label',
                    {},
                    createElement('input', {
                        type: 'radio',
                        checked: Boolean(attributes.useOwnImage),
                        onChange: () => setAttributes({ useOwnImage: true })
                    }),
                    ' ja'
                ),
                createElement(
                    'label',
                    {},
                    createElement('input', {
                        type: 'radio',
                        checked: !attributes.useOwnImage,
                        onChange: () => setAttributes({ useOwnImage: false })
                    }),
                    ' nee'
                )
            )
        );
    }

    /**
     * Renders the custom image picker when the editor selected custom-image mode.
     *
     * @param {CalloutAttributes} attributes The current callout block attributes.
     * @param {Function} setAttributes Persists a partial callout attribute update.
     * @returns {Object|null} The custom image field, or null while preset mode is active.
     */
    function renderCustomImageField(attributes, setAttributes) {
        // Keep the inactive field out of the editor without clearing its stored selection.
        if (!attributes.useOwnImage) {
            return null;
        }

        // Connect the shared image picker directly to the callout image URL attribute.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-row' },
            createElement('label', {}, 'Beeld [eigen]'),
            createElement(ImagePicker, {
                url: attributes.imageUrl || '',
                onSelect: (media) => setAttributes({ imageUrl: media.url }),
                onRemove: () => setAttributes({ imageUrl: '' })
            })
        );
    }

    /**
     * Renders the built-in image selector when the editor selected preset mode.
     *
     * @param {CalloutAttributes} attributes The current callout block attributes.
     * @param {Function} setAttributes Persists a partial callout attribute update.
     * @returns {Object|null} The preset select field, or null while custom-image mode is active.
     */
    function renderPresetField(attributes, setAttributes) {
        // Keep the inactive field out of the editor without clearing its stored preset.
        if (attributes.useOwnImage) {
            return null;
        }

        // Build one option for every localized or fallback preset.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-row' },
            createElement('label', {}, 'Beeld [lijst]'),
            createElement(
                'select',
                {
                    className: 'scouting-nederland-editor-form-select',
                    value: attributes.calloutPreset || 'kompas',
                    onChange: (event) => setAttributes({ calloutPreset: event.target.value })
                },
                getCalloutOptions().map((option) => createElement(
                    'option',
                    { key: option.value, value: option.value },
                    option.label
                ))
            )
        );
    }

    /**
     * Renders the title and rich-text content fields.
     *
     * @param {CalloutAttributes} attributes The current callout block attributes.
     * @param {Function} setAttributes Persists a partial callout attribute update.
     * @returns {Array<Object>} The heading and classic-editor field elements.
     */
    function renderContentFields(attributes, setAttributes) {
        // Return keyed sibling fields so React can preserve the classic editor instance.
        return [
            createElement(
                'div',
                { className: 'scouting-nederland-editor-form-row', key: 'title' },
                createElement('label', {}, 'Titel'),
                createElement(TextControl, {
                    value: attributes.title || '',
                    onChange: (title) => setAttributes({ title })
                })
            ),
            createElement(
                'div',
                {
                    className: 'scouting-nederland-editor-form-row scouting-nederland-editor-form-row-editor',
                    key: 'text'
                },
                createElement('label', {}, 'Tekst'),
                createElement(ClassicHtmlEditor, {
                    value: attributes.text || '',
                    onChange: (text) => setAttributes({ text })
                })
            )
        ];
    }

    /**
     * Renders the call-to-action text, link, target, and color controls.
     *
     * @param {CalloutAttributes} attributes The current callout block attributes.
     * @param {Function} setAttributes Persists a partial callout attribute update.
     * @returns {Object} A WordPress element containing the complete button fieldset.
     */
    function renderButtonFields(attributes, setAttributes) {
        // Group all call-to-action attributes in one fieldset for a coherent editing flow.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-row' },
            createElement('label', {}, ''),
            createElement(
                'fieldset',
                {
                    className: 'scouting-nederland-editor-button '
                        + 'scouting-nederland-editor-callout-button'
                },
                createElement('legend', {}, 'Knop'),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Tekst'),
                    createElement(TextControl, {
                        value: attributes.buttonText || '',
                        onChange: (buttonText) => setAttributes({ buttonText })
                    })
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-button-grid' },
                    createElement(
                        'div',
                        { className: 'scouting-nederland-editor-form-field' },
                        createElement('label', {}, 'URL'),
                        createElement(LinkPickerField, {
                            url: attributes.buttonUrl || '',
                            text: attributes.buttonText || '',
                            target: attributes.buttonTarget || '',
                            onUrl: (buttonUrl) => setAttributes({ buttonUrl }),
                            onText: (buttonText) => setAttributes({ buttonText }),
                            onTarget: (buttonTarget) => setAttributes({ buttonTarget })
                        })
                    ),
                    createElement(
                        'div',
                        { className: 'scouting-nederland-editor-form-field' },
                        createElement('label', {}, 'Kleur'),
                        createElement(ColorRadioControl, {
                            value: attributes.buttonColor,
                            defaultValue: 'zwart',
                            onChange: (buttonColor) => setAttributes({ buttonColor })
                        })
                    )
                )
            )
        );
    }

    registerBlockType('scouting-nederland/callout', {
        title: __('Callout', 'scouting-nederland'),
        icon: 'megaphone',
        category: 'scouting-nederland',
        attributes: {
            title: { type: 'string', default: 'Vrijwilligers gezocht' },
            text: { type: 'string', default: DEFAULT_CALLOUT_TEXT },
            buttonText: { type: 'string', default: 'Meld je aan' },
            buttonUrl: { type: 'string', default: '' },
            buttonTarget: { type: 'string', default: '' },
            buttonColor: { type: 'string', default: 'zwart' },
            imageUrl: { type: 'string', default: '' },
            calloutPreset: { type: 'string', default: 'kompas' },
            useOwnImage: { type: 'boolean', default: false }
        },
        /**
         * Renders the complete callout editing interface.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {CalloutAttributes} props.attributes The current callout values.
         * @param {Function} props.setAttributes Persists partial callout updates.
         * @returns {Object} The callout editor element tree.
         */
        edit: ({ attributes, setAttributes }) => {
            // Compose image-mode, content, and button controls in their visual form order.
            return createElement(
                'div',
                {
                    className: 'scouting-nederland-editor-form '
                        + 'scouting-nederland-editor-callout-form'
                },
                renderImageModeControl(attributes, setAttributes),
                renderCustomImageField(attributes, setAttributes),
                renderPresetField(attributes, setAttributes),
                renderContentFields(attributes, setAttributes),
                renderButtonFields(attributes, setAttributes)
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
