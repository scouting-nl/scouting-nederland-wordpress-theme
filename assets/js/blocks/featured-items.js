/**
 * Registers the fixed three-card featured-items block and its editor form.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerFeaturedItemsBlock(wp, blockEditor) {
    /**
     * @typedef {Object} FeaturedItem
     * @property {string} title The card heading.
     * @property {string} text The card HTML content.
     * @property {string} imageUrl The optional card image URL.
     * @property {string} buttonText The optional call-to-action label.
     * @property {string} buttonUrl The optional call-to-action URL.
     * @property {string} [buttonTarget] The optional call-to-action browser target.
     * @property {string} buttonColor The selected theme button color.
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

    const FEATURED_ITEM_INDEXES = [0, 1, 2];
    const DEFAULT_ITEMS = [
        {
            title: 'Wie zijn wij?',
            text: 'Scouting staat voor uitdaging! Scouting biedt leuke en spannende activiteiten waarmee '
                + 'meiden en jongens worden uitgedaagd zich persoonlijk te ontwikkelen.',
            imageUrl: '',
            buttonText: 'Meer informatie',
            buttonUrl: '',
            buttonColor: 'blauw'
        },
        {
            title: 'Lid worden',
            text: 'Laat je uitdagen en ontdek Scouting!',
            imageUrl: '',
            buttonText: 'Kom een keer meedoen',
            buttonUrl: '',
            buttonColor: 'blauw'
        },
        {
            title: 'Snel naar',
            text: 'Verhuur<br>Stockfoto’s<br>Scouting Nederland',
            imageUrl: '',
            buttonText: '',
            buttonUrl: '',
            buttonColor: 'blauw'
        }
    ];

    /**
     * Renders the media picker for one featured card.
     *
     * @param {FeaturedItem} item The card currently being edited.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {Function} updateItem Merges a partial update into one card.
     * @returns {Object} A WordPress element containing the card image field.
     */
    function renderImageField(item, index, updateItem) {
        // Route image selection and removal through the immutable item updater.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-field' },
            createElement('label', {}, 'Beeld'),
            createElement(ImagePicker, {
                url: item.imageUrl || '',
                onSelect: (media) => updateItem(index, { imageUrl: media.url }),
                onRemove: () => updateItem(index, { imageUrl: '' })
            })
        );
    }

    /**
     * Renders the call-to-action fields for one featured card.
     *
     * @param {FeaturedItem} item The card currently being edited.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {Function} updateItem Merges a partial update into one card.
     * @returns {Object} A WordPress element containing text, link, target, and color controls.
     */
    function renderButtonFields(item, index, updateItem) {
        // Keep all button-related values grouped in one fieldset for this card.
        return createElement(
            'fieldset',
            { className: 'scouting-nederland-editor-button' },
            createElement('legend', {}, 'Knop'),
            createElement(
                'div',
                { className: 'scouting-nederland-editor-form-field' },
                createElement('label', {}, 'Tekst'),
                createElement(TextControl, {
                    value: item.buttonText || '',
                    onChange: (buttonText) => updateItem(index, { buttonText })
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
                        url: item.buttonUrl || '',
                        text: item.buttonText || '',
                        target: item.buttonTarget || '',
                        onChange: (link) => updateItem(index, {
                            buttonUrl: link.url || '',
                            buttonText: link.text || item.buttonText || '',
                            buttonTarget: link.target || ''
                        })
                    })
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Kleur'),
                    createElement(ColorRadioControl, {
                        value: item.buttonColor,
                        defaultValue: 'blauw',
                        onChange: (buttonColor) => updateItem(index, { buttonColor })
                    })
                )
            )
        );
    }

    /**
     * Renders all editable fields for one numbered featured card.
     *
     * @param {FeaturedItem} item The card currently being edited.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {Function} updateItem Merges a partial update into one card.
     * @returns {Object} A WordPress element containing the complete card editor.
     */
    function renderItemEditor(item, index, updateItem) {
        // Compose the heading, image, rich text, and button fields under one numbered row.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-repeater-row', key: index },
            createElement('div', { className: 'scouting-nederland-editor-repeater-number' }, index + 1),
            createElement(
                'div',
                { className: 'scouting-nederland-editor-repeater-content' },
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Kop'),
                    createElement(TextControl, {
                        value: item.title || '',
                        onChange: (title) => updateItem(index, { title })
                    })
                ),
                renderImageField(item, index, updateItem),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Tekst'),
                    createElement(ClassicHtmlEditor, {
                        value: item.text || '',
                        onChange: (text) => updateItem(index, { text })
                    })
                ),
                renderButtonFields(item, index, updateItem)
            )
        );
    }

    registerBlockType('scouting-nederland/featured-items', {
        title: __('Featured items', 'scouting-nederland'),
        icon: 'screenoptions',
        category: 'scouting-nederland',
        supports: { anchor: true },
        attributes: {
            title: { type: 'string', default: '' },
            items: { type: 'array', default: DEFAULT_ITEMS }
        },
        /**
         * Renders the fixed three-card editor and immutable item-update operations.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {Object} props.attributes The current featured-items attributes.
         * @param {Array<FeaturedItem>} props.attributes.items The stored card collection.
         * @param {Function} props.setAttributes Persists partial block updates.
         * @returns {Object} The featured-items editor element tree.
         */
        edit: ({ attributes, setAttributes }) => {
            // Normalize malformed or missing item data before any immutable updates.
            const items = Array.isArray(attributes.items) ? attributes.items : [];

            /**
             * Merges a partial value object into one featured card.
             *
             * @param {number} index The zero-based card index to update.
             * @param {Object} values The card properties that should be replaced.
             * @returns {void}
             */
            const updateItem = (index, values) => {
                // Clone the collection so Gutenberg receives a new array reference.
                const nextItems = items.slice();

                // Merge the patch without mutating the existing nested card object.
                nextItems[index] = Object.assign({}, nextItems[index] || {}, values);

                // Persist the complete updated collection as one block-attribute change.
                setAttributes({ items: nextItems });
            };

            // Render the optional block heading followed by exactly three card editors.
            return createElement(
                'div',
                {
                    className: 'scouting-nederland-editor-form '
                        + 'scouting-nederland-editor-featured-items-form'
                },
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-row' },
                    createElement('label', {}, 'Kop'),
                    createElement(TextControl, {
                        value: attributes.title || '',
                        onChange: (title) => setAttributes({ title }),
                        placeholder: 'Optionele algemene kop'
                    })
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-section-title' },
                    'Items'
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-repeater' },
                    FEATURED_ITEM_INDEXES.map((index) => {
                        // Supply an empty object when an older block has fewer than three items.
                        const item = items[index] || {};

                        // PHP treats card three without button text as a quick-link list on the front end.
                        return renderItemEditor(item, index, updateItem);
                    })
                )
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
