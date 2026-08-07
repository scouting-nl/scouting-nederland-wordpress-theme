/**
 * Registers the editable speltakken card grid and its repeater controls.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerSpeltakkenGridBlock(wp, blockEditor) {
    /**
     * @typedef {Object} SpeltakItem
     * @property {string} title The age-group name displayed on the card.
     * @property {string} text The short age-group description.
     * @property {string} imageUrl The optional card image URL.
     * @property {string} buttonText The fixed call-to-action label.
     * @property {string} buttonUrl The optional call-to-action URL.
     * @property {string} buttonTarget The optional call-to-action browser target.
     */

    /**
     * @typedef {Object} SpeltakEditorContext
     * @property {number} itemCount The number of cards currently being edited.
     * @property {Function} moveItem Moves a card by a relative offset.
     * @property {Function} removeItem Removes one card by index.
     * @property {Function} setItem Replaces one property on one card.
     * @property {Function} updateItemLink Replaces one card's link values.
     */

    // Import the WordPress controls and shared link helper used by this repeater.
    const {
        registerBlockType,
        __,
        Fragment,
        InspectorControls,
        MediaUpload,
        MediaUploadCheck,
        PanelBody,
        TextControl,
        TextareaControl,
        Button,
        LinkPickerField
    } = blockEditor;
    const { createElement } = wp.element;

    const INFORMATION_BUTTON_TEXT = 'Meer informatie';
    const EMPTY_ITEM = {
        title: '',
        text: '',
        imageUrl: '',
        buttonText: INFORMATION_BUTTON_TEXT,
        buttonUrl: '',
        buttonTarget: ''
    };
    const DEFAULT_ITEMS = [
        {
            title: 'Bevers',
            text: 'Voor kinderen van 5 tot 7 jaar: samen spelen, fantaseren en ontdekken.'
        },
        {
            title: 'Welpen',
            text: 'Voor kinderen van 7 tot 11 jaar: avonturen beleven in de jungle.'
        },
        {
            title: 'Scouts',
            text: 'Voor jongeren van 11 tot 15 jaar: buitenactiviteiten, samenwerken en nieuwe skills leren.'
        }
    ].map(createItem);

    /**
     * Creates a complete speltak item by applying values over empty defaults.
     *
     * @param {Object} [values] Partial item values to merge into the default shape.
     * @returns {SpeltakItem} A new normalized item object.
     */
    function createItem(values) {
        // Always allocate a new object so callers can update collections immutably.
        return Object.assign({}, EMPTY_ITEM, values || {});
    }

    /**
     * Renders image selection and removal controls for one speltak card.
     *
     * @param {SpeltakItem} item The normalized card currently being edited.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {Function} setItem Replaces one item property and persists the collection.
     * @returns {Object} A WordPress element containing the image field.
     */
    function renderImageField(item, index, setItem) {
        // Wrap media access in WordPress's permission-aware upload controls.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-form-field' },
            createElement('label', {}, 'Afbeelding'),
            createElement(
                MediaUploadCheck,
                {},
                createElement(MediaUpload, {
                    onSelect: (media) => setItem(index, 'imageUrl', media.url),
                    allowedTypes: ['image'],
                    render: ({ open }) => createElement(
                        'div',
                        { className: 'scouting-nederland-editor-image-picker' },
                        item.imageUrl
                            ? createElement('img', { src: item.imageUrl, alt: '', onClick: open })
                            : createElement(Button, { onClick: open, isSecondary: true }, 'Afbeelding kiezen'),
                        item.imageUrl
                            ? createElement(
                                Button,
                                {
                                    onClick: () => setItem(index, 'imageUrl', ''),
                                    isDestructive: true,
                                    isLink: true
                                },
                                'Afbeelding verwijderen'
                            )
                            : null
                    )
                })
            )
        );
    }

    /**
     * Renders the fixed-label link picker for one speltak card.
     *
     * @param {SpeltakItem} item The normalized card currently being edited.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {Function} updateItemLink Persists a complete link value for one item.
     * @returns {Object} A WordPress element containing the card link fieldset.
     */
    function renderLinkField(item, index, updateItemLink) {
        // Explain the fixed label and expose only URL and target selection to editors.
        return createElement(
            'fieldset',
            { className: 'scouting-nederland-editor-button' },
            createElement('legend', {}, 'Knop'),
            createElement(
                'p',
                { className: 'scouting-nederland-editor-form-help' },
                'De knoptekst is altijd “Meer informatie”. Vul alleen een link in om de knop te tonen.'
            ),
            createElement(
                'div',
                { className: 'scouting-nederland-editor-form-field' },
                createElement('label', {}, 'URL'),
                createElement(LinkPickerField, {
                    url: item.buttonUrl,
                    text: INFORMATION_BUTTON_TEXT,
                    target: item.buttonTarget,
                    onChange: (link) => updateItemLink(index, link)
                })
            )
        );
    }

    /**
     * Renders all editable controls and movement actions for one repeater row.
     *
     * @param {SpeltakItem} item The stored card values to normalize and render.
     * @param {number} index The zero-based card index in the items attribute.
     * @param {SpeltakEditorContext} context Shared collection operations and row count.
     * @returns {Object} A WordPress element containing the complete card editor row.
     */
    function renderItemEditor(item, index, context) {
        // Fill missing legacy properties before binding values to controlled fields.
        const normalizedItem = createItem(item);

        // Derive movement boundaries used to disable invalid actions.
        const isFirstItem = index === 0;
        const isLastItem = index === context.itemCount - 1;

        // Compose row actions, media, text, and link controls under one numbered card.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-repeater-row', key: index },
            createElement('div', { className: 'scouting-nederland-editor-repeater-number' }, index + 1),
            createElement(
                'div',
                { className: 'scouting-nederland-editor-repeater-content' },
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-repeater-actions' },
                    createElement(
                        Button,
                        {
                            onClick: () => context.moveItem(index, -1),
                            isSecondary: true,
                            disabled: isFirstItem
                        },
                        'Omhoog'
                    ),
                    createElement(
                        Button,
                        {
                            onClick: () => context.moveItem(index, 1),
                            isSecondary: true,
                            disabled: isLastItem
                        },
                        'Omlaag'
                    ),
                    createElement(
                        Button,
                        {
                            onClick: () => context.removeItem(index),
                            isDestructive: true,
                            isSecondary: true
                        },
                        'Speltak verwijderen'
                    )
                ),
                renderImageField(normalizedItem, index, context.setItem),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Titel'),
                    createElement(TextControl, {
                        value: normalizedItem.title,
                        onChange: (value) => context.setItem(index, 'title', value)
                    })
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-editor-form-field' },
                    createElement('label', {}, 'Korte tekst'),
                    createElement(TextareaControl, {
                        value: normalizedItem.text,
                        rows: 4,
                        onChange: (value) => context.setItem(index, 'text', value)
                    })
                ),
                renderLinkField(normalizedItem, index, context.updateItemLink)
            )
        );
    }

    registerBlockType('scouting-nederland/speltakken-grid', {
        title: __('Speltakken grid', 'scouting-nederland'),
        icon: 'grid-view',
        category: 'scouting-nederland',
        supports: { anchor: true },
        attributes: {
            title: { type: 'string', default: '' },
            items: { type: 'array', default: DEFAULT_ITEMS }
        },
        /**
         * Renders the speltakken inspector, repeater controls, and immutable item operations.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {Object} props.attributes The current grid attributes.
         * @param {Array<SpeltakItem>} props.attributes.items The stored card collection.
         * @param {Function} props.setAttributes Persists partial grid updates.
         * @returns {Object} The speltakken grid editor element tree.
         */
        edit: (props) => {
            // Separate the frequently used attributes and persistence callback.
            const { attributes, setAttributes } = props;

            // Reject malformed item values before deciding whether defaults are required.
            const storedItems = Array.isArray(attributes.items) ? attributes.items : [];

            // Server-rendered blocks can arrive empty, so retain visible starter rows in the editor.
            const items = storedItems.length ? storedItems : DEFAULT_ITEMS;

            /**
             * Persists a complete replacement for the card collection.
             *
             * @param {Array<SpeltakItem>} nextItems The next immutable item array.
             * @returns {void}
             */
            const updateItems = (nextItems) => {
                // Keep collection persistence centralized for every repeater operation.
                setAttributes({ items: nextItems });
            };

            /**
             * Replaces one property on one speltak card.
             *
             * @param {number} index The zero-based item index to update.
             * @param {string} key The item property name to replace.
             * @param {string} value The new property value.
             * @returns {void}
             */
            const setItem = (index, key, value) => {
                // Clone the collection so Gutenberg receives a new array reference.
                const nextItems = items.slice();

                // Merge and normalize the updated card without mutating the stored object.
                nextItems[index] = createItem(Object.assign({}, nextItems[index], { [key]: value }));

                // Persist the complete updated collection.
                updateItems(nextItems);
            };

            /**
             * Appends one empty editable card to the repeater.
             *
             * @returns {void}
             */
            const addItem = () => {
                // Concatenate a fresh normalized item without mutating the current array.
                updateItems(items.concat([createItem()]));
            };

            /**
             * Removes one card while preserving at least one editable repeater row.
             *
             * @param {number} indexToRemove The zero-based item index to remove.
             * @returns {void}
             */
            const removeItem = (indexToRemove) => {
                // Build a new collection that excludes the requested index.
                const nextItems = items.filter((item, index) => index !== indexToRemove);

                // Insert one empty row when removal would otherwise leave an editor dead end.
                updateItems(nextItems.length ? nextItems : [createItem()]);
            };

            /**
             * Moves one card up or down by a relative offset.
             *
             * @param {number} index The zero-based item index to move.
             * @param {number} direction The relative offset, normally -1 or 1.
             * @returns {void}
             */
            const moveItem = (index, direction) => {
                // Calculate and validate the destination before cloning any data.
                const destinationIndex = index + direction;

                if (destinationIndex < 0 || destinationIndex >= items.length) {
                    return;
                }

                // Clone the collection and swap source and destination entries.
                const nextItems = items.slice();
                const currentItem = nextItems[index];
                nextItems[index] = nextItems[destinationIndex];
                nextItems[destinationIndex] = currentItem;

                // Persist the reordered collection as one attribute update.
                updateItems(nextItems);
            };

            /**
             * Applies a selected URL and target to one card's fixed-label button.
             *
             * @param {number} index The zero-based item index to update.
             * @param {Object} link The values returned by the shared link picker.
             * @param {string} link.url The selected link URL.
             * @param {string} link.target The selected browser target.
             * @returns {void}
             */
            const updateItemLink = (index, link) => {
                // Clone the collection before replacing one normalized item.
                const nextItems = items.slice();

                // Preserve the fixed label while applying normalized link values.
                nextItems[index] = createItem(Object.assign({}, nextItems[index], {
                    buttonText: INFORMATION_BUTTON_TEXT,
                    buttonUrl: link.url || '',
                    buttonTarget: link.target || ''
                }));

                // Persist the complete updated collection.
                updateItems(nextItems);
            };

            // Package shared row operations once instead of recreating an argument list per helper call.
            const itemEditorContext = {
                itemCount: items.length,
                moveItem,
                removeItem,
                setItem,
                updateItemLink
            };

            // Reuse the same add-card element in the section heading.
            const addButton = createElement(
                Button,
                {
                    onClick: addItem,
                    isPrimary: true,
                    className: 'scouting-nederland-editor-speltakken-grid-add'
                },
                '+ Speltak toevoegen'
            );

            // Render sidebar metadata and the complete card repeater as sibling editor regions.
            return createElement(
                Fragment,
                {},
                createElement(
                    InspectorControls,
                    {},
                    createElement(
                        PanelBody,
                        { title: 'Speltakken', initialOpen: true },
                        createElement('p', {}, 'Aantal speltakken: ' + items.length),
                        createElement(Button, { onClick: addItem, isPrimary: true }, '+ Speltak toevoegen')
                    )
                ),
                createElement(
                    'div',
                    {
                        className: 'scouting-nederland-editor-form '
                            + 'scouting-nederland-editor-speltakken-grid-form'
                    },
                    createElement(
                        'div',
                        { className: 'scouting-nederland-editor-form-row' },
                        createElement('label', {}, 'Kop'),
                        createElement(TextControl, {
                            value: attributes.title || '',
                            onChange: (title) => setAttributes({ title }),
                            placeholder: 'Optionele algemene kop, bijvoorbeeld Speltakken'
                        })
                    ),
                    createElement(
                        'div',
                        {
                            className: 'scouting-nederland-editor-form-section-title '
                                + 'scouting-nederland-editor-form-section-title-with-action'
                        },
                        createElement('span', {}, 'Kaarten (' + items.length + ')'),
                        addButton
                    ),
                    createElement(
                        'div',
                        { className: 'scouting-nederland-editor-repeater' },
                        items.map((item, index) => renderItemEditor(item, index, itemEditorContext)),
                        createElement(
                            'div',
                            { className: 'scouting-nederland-editor-repeater-add' },
                            createElement(Button, { onClick: addItem, isPrimary: true }, '+ Speltak toevoegen')
                        )
                    )
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
