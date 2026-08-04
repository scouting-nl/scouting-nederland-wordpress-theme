/**
 * Registers the server-rendered two-column content block and its editor fields.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerTwoColumnsBlock(wp, blockEditor) {
    /**
     * @typedef {Object} ColumnConfig
     * @property {string} key The stable React key for this column.
     * @property {string} legend The fieldset label shown to editors.
     * @property {string} titleKey The block attribute holding the column heading.
     * @property {string} textKey The block attribute holding the column HTML.
     * @property {string} [imageKey] The optional block attribute holding an image URL.
     * @property {string} buttonTextKey The block attribute holding the button label.
     * @property {string} buttonUrlKey The block attribute holding the button URL.
     * @property {string} buttonTargetKey The block attribute holding the browser target.
     * @property {string} buttonColorKey The block attribute holding the theme color.
     * @property {string} defaultButtonColor The fallback color for this column.
     */

    // Import the shared rich-text, image, link, and color controls used by both columns.
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

    const DEFAULT_RIGHT_TEXT = 'Meer weten over vrijwilligerswerk bij Scouting? Laat je verrassen door één '
        + 'van de uitdagende vacatures of lees meer over vrijwilliger worden bij Scouting.'
        + '<ul><li>Welpenleiding</li><li>Secretaris</li></ul>'
        + '<h2>Maatschappelijke stage</h2>'
        + '<p>Iedere scholier krijgt er wel mee te maken: de maatschappelijke stage.</p>';
    const COLUMN_CONFIGS = [
        {
            key: 'left',
            legend: 'Kolom 1 (headerbeeld + kop, tekst en knop)',
            titleKey: 'leftTitle',
            textKey: 'leftText',
            imageKey: 'leftImageUrl',
            buttonTextKey: 'leftButtonText',
            buttonUrlKey: 'leftButtonUrl',
            buttonTargetKey: 'leftButtonTarget',
            buttonColorKey: 'leftButtonColor',
            defaultButtonColor: 'blauw'
        },
        {
            key: 'right',
            legend: 'Kolom 2 (kop, WYSIWYG en knop)',
            titleKey: 'rightTitle',
            textKey: 'rightText',
            buttonTextKey: 'rightButtonText',
            buttonUrlKey: 'rightButtonUrl',
            buttonTargetKey: 'rightButtonTarget',
            buttonColorKey: 'rightButtonColor',
            defaultButtonColor: 'zwart'
        }
    ];

    /**
     * Persists one dynamically selected block attribute.
     *
     * @param {Function} setAttributes The Gutenberg attribute persistence callback.
     * @param {string} key The attribute name selected by a column configuration.
     * @param {string} value The new attribute value.
     * @returns {void}
     */
    function updateAttribute(setAttributes, key, value) {
        // Use a computed property so one renderer can support left and right attributes.
        setAttributes({ [key]: value });
    }

    /**
     * Renders the optional image field configured for a column.
     *
     * @param {ColumnConfig} config The attribute mapping for the current column.
     * @param {Object} attributes The current two-column block attributes.
     * @param {Function} setAttributes Persists a partial two-column attribute update.
     * @returns {Object|null} The image picker, or null for columns without image support.
     */
    function renderImageField(config, attributes, setAttributes) {
        // The right column intentionally has no image attribute or image field.
        if (!config.imageKey) {
            return null;
        }

        // Bind the shared image picker to the attribute named by this column configuration.
        return createElement(
            'div',
            { className: 'scouting-nederland-acf-field' },
            createElement('label', {}, 'Beeld'),
            createElement(ImagePicker, {
                url: attributes[config.imageKey] || '',
                onSelect: (media) => updateAttribute(
                    setAttributes,
                    config.imageKey,
                    media.url
                ),
                onRemove: () => updateAttribute(setAttributes, config.imageKey, '')
            })
        );
    }

    /**
     * Renders button text, URL, target, and color controls for one column.
     *
     * @param {ColumnConfig} config The attribute mapping for the current column.
     * @param {Object} attributes The current two-column block attributes.
     * @param {Function} setAttributes Persists a partial two-column attribute update.
     * @returns {Object} A WordPress element containing the column button fieldset.
     */
    function renderButtonFields(config, attributes, setAttributes) {
        // Resolve every controlled value through explicit keys in the column configuration.
        return createElement(
            'fieldset',
            {
                className: 'scouting-nederland-acf-button-box '
                    + 'scouting-nederland-two-button-box'
            },
            createElement('legend', {}, 'Knop'),
            createElement(
                'div',
                { className: 'scouting-nederland-acf-field' },
                createElement('label', {}, 'Tekst'),
                createElement(TextControl, {
                    value: attributes[config.buttonTextKey] || '',
                    onChange: (value) => updateAttribute(
                        setAttributes,
                        config.buttonTextKey,
                        value
                    )
                })
            ),
            createElement(
                'div',
                { className: 'scouting-nederland-acf-button-grid' },
                createElement(
                    'div',
                    { className: 'scouting-nederland-acf-field' },
                    createElement('label', {}, 'URL'),
                    createElement(LinkPickerField, {
                        url: attributes[config.buttonUrlKey] || '',
                        text: attributes[config.buttonTextKey] || '',
                        target: attributes[config.buttonTargetKey] || '',
                        onUrl: (value) => updateAttribute(
                            setAttributes,
                            config.buttonUrlKey,
                            value
                        ),
                        onText: (value) => updateAttribute(
                            setAttributes,
                            config.buttonTextKey,
                            value
                        ),
                        onTarget: (value) => updateAttribute(
                            setAttributes,
                            config.buttonTargetKey,
                            value
                        )
                    })
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-acf-field' },
                    createElement('label', {}, 'Kleur'),
                    createElement(ColorRadioControl, {
                        value: attributes[config.buttonColorKey],
                        defaultValue: config.defaultButtonColor,
                        onChange: (value) => updateAttribute(
                            setAttributes,
                            config.buttonColorKey,
                            value
                        )
                    })
                )
            )
        );
    }

    /**
     * Renders image, title, rich-text, and button fields for one configured column.
     *
     * @param {ColumnConfig} config The attribute mapping and labels for the column.
     * @param {Object} attributes The current two-column block attributes.
     * @param {Function} setAttributes Persists a partial two-column attribute update.
     * @returns {Object} A WordPress fieldset containing the complete column editor.
     */
    function renderColumnEditor(config, attributes, setAttributes) {
        // Compose common fields while allowing the configuration to omit the image control.
        return createElement(
            'fieldset',
            { className: 'scouting-nederland-two-column-fieldset', key: config.key },
            createElement('legend', {}, config.legend),
            renderImageField(config, attributes, setAttributes),
            createElement(
                'div',
                { className: 'scouting-nederland-acf-field' },
                createElement('label', {}, 'Kop'),
                createElement(TextControl, {
                    value: attributes[config.titleKey] || '',
                    onChange: (value) => updateAttribute(setAttributes, config.titleKey, value)
                })
            ),
            createElement(
                'div',
                { className: 'scouting-nederland-acf-field' },
                createElement('label', {}, 'Tekst'),
                createElement(ClassicHtmlEditor, {
                    value: attributes[config.textKey] || '',
                    onChange: (value) => updateAttribute(setAttributes, config.textKey, value)
                })
            ),
            renderButtonFields(config, attributes, setAttributes)
        );
    }

    registerBlockType('scouting-nederland/two-columns', {
        title: __('Twee kolommen', 'scouting-nederland'),
        icon: 'columns',
        category: 'scouting-nederland',
        supports: { anchor: true },
        attributes: {
            leftTitle: { type: 'string', default: 'Gratis sponsoren' },
            leftText: {
                type: 'string',
                default: 'Binnen onze Scoutinggroep hebben we sponsoracties om op die manier Scouting '
                    + 'betaalbaar te maken voor iedereen.'
            },
            leftImageUrl: { type: 'string', default: '' },
            leftButtonText: { type: 'string', default: 'Lees hier meer!' },
            leftButtonUrl: { type: 'string', default: '' },
            leftButtonTarget: { type: 'string', default: '' },
            leftButtonColor: { type: 'string', default: 'blauw' },
            rightTitle: { type: 'string', default: 'Vacatures' },
            rightText: { type: 'string', default: DEFAULT_RIGHT_TEXT },
            rightButtonText: { type: 'string', default: 'Meer vacatures' },
            rightButtonUrl: { type: 'string', default: '' },
            rightButtonTarget: { type: 'string', default: '' },
            rightButtonColor: { type: 'string', default: 'zwart' }
        },
        /**
         * Renders both column editors from their explicit attribute configurations.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {Object} props.attributes The current two-column attributes.
         * @param {Function} props.setAttributes Persists partial two-column updates.
         * @returns {Object} The two-column editor element tree.
         */
        edit: ({ attributes, setAttributes }) => {
            // Map both configurations to independent fieldsets inside the shared editor form.
            return createElement(
                'div',
                {
                    className: 'scouting-nederland-acf-like-block '
                        + 'scouting-nederland-two-columns-editor-form'
                },
                COLUMN_CONFIGS.map((config) => renderColumnEditor(
                    config,
                    attributes,
                    setAttributes
                ))
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
