/**
 * Registers the server-rendered news-items block and its selection controls.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @param {Object} blockEditor Shared Scouting Nederland editor components and helpers.
 * @returns {void}
 */
(function registerNewsItemsBlock(wp, blockEditor) {
    /**
     * @typedef {Object} NewsCategory
     * @property {number|string} id The WordPress category ID.
     * @property {string} name The category label shown in the editor.
     */

    /**
     * @typedef {Object} NewsPost
     * @property {number|string} id The WordPress post ID.
     * @property {string} title The post title shown in selection controls.
     * @property {Array<number|string>} categoryIds The categories assigned to the post.
     */

    // Import only the shared APIs used by this block editor.
    const {
        registerBlockType,
        __,
        TextControl,
        RangeControl,
        ColorRadioControl,
        LinkPickerField
    } = blockEditor;
    const { createElement } = wp.element;

    /**
     * Reads one collection localized by PHP for the block editor.
     *
     * @param {string} key The property name on the scoutingNederlandBlocks global.
     * @returns {Array<Object>} The localized collection, or an empty array when unavailable.
     */
    function getLocalizedCollection(key) {
        // Read the requested value without assuming the localized global exists.
        const collection = globalThis.scoutingNederlandBlocks?.[key];

        // Guarantee an array so downstream filters and maps cannot throw.
        return Array.isArray(collection) ? collection : [];
    }

    /**
     * Converts a WordPress ID from form or localized data into a usable integer.
     *
     * @param {number|string|null|undefined} value The untrusted ID value.
     * @returns {number} The parsed ID, or zero when parsing fails.
     */
    function normalizeId(value) {
        // Parse string and numeric IDs using the decimal representation WordPress emits.
        const id = Number.parseInt(value, 10);

        // Use zero as the invalid sentinel removed by normalizeIds.
        return Number.isNaN(id) ? 0 : id;
    }

    /**
     * Normalizes an optional collection of WordPress IDs.
     *
     * @param {Array<number|string>|null|undefined} values The stored or localized ID values.
     * @returns {Array<number>} Valid non-zero integer IDs in their original order.
     */
    function normalizeIds(values) {
        // Normalize each value and remove the zero sentinel while preserving order.
        return Array.isArray(values)
            ? values.map(normalizeId).filter(Boolean)
            : [];
    }

    /**
     * Adds an absent ID or removes an already selected ID.
     *
     * @param {Array<number>} values The currently selected IDs.
     * @param {number} id The normalized ID to toggle.
     * @returns {Array<number>} A new selection array without mutating the input.
     */
    function toggleId(values, id) {
        // Return a filtered copy for removal or a concatenated copy for addition.
        return values.includes(id)
            ? values.filter((value) => value !== id)
            : values.concat([id]);
    }

    /**
     * Filters available posts to those sharing at least one selected category.
     *
     * @param {Array<NewsPost>} posts All posts localized for the editor.
     * @param {Array<number>} selectedCategoryIds The active category filter IDs.
     * @returns {Array<NewsPost>} Posts matching the active filter, or all posts without a filter.
     */
    function filterPostsByCategory(posts, selectedCategoryIds) {
        // An empty category selection means that every localized post remains available.
        if (!selectedCategoryIds.length) {
            return posts;
        }

        // Keep posts that share at least one normalized ID with the selected categories.
        return posts.filter((post) => normalizeIds(post.categoryIds).some(
            (categoryId) => selectedCategoryIds.includes(categoryId)
        ));
    }

    /**
     * Resolves selected IDs to localized post objects in the stored selection order.
     *
     * @param {Array<NewsPost>} posts All posts localized for the editor.
     * @param {Array<number>} selectedPostIds The ordered selected post IDs.
     * @returns {Array<NewsPost>} Resolved post objects whose IDs exist in localized data.
     */
    function getSelectedPosts(posts, selectedPostIds) {
        // Resolve each stored ID in order, then discard IDs missing from localized data.
        return selectedPostIds
            .map((postId) => posts.find((post) => normalizeId(post.id) === postId))
            .filter(Boolean);
    }

    /**
     * Renders category checkboxes used to filter the post selector.
     *
     * @param {Array<NewsCategory>} categories Categories localized by WordPress.
     * @param {Array<number>} selectedCategoryIds The currently checked category IDs.
     * @param {Function} onToggle Toggles one normalized category ID.
     * @returns {Object} A WordPress element containing the category selection row.
     */
    function renderCategorySelector(categories, selectedCategoryIds, onToggle) {
        // Build controlled checkboxes, or a clear empty state when no categories exist.
        const categoryOptions = categories.length
            ? categories.map((category) => {
                const categoryId = normalizeId(category.id);

                return createElement(
                    'label',
                    { key: category.id, className: 'scouting-nederland-news-checkbox' },
                    createElement('input', {
                        type: 'checkbox',
                        checked: selectedCategoryIds.includes(categoryId),
                        onChange: () => onToggle(categoryId)
                    }),
                    ' ' + category.name
                );
            })
            : createElement('em', {}, 'Geen categorieën gevonden');

        // Place all category choices in the block editor's standard row structure.
        return createElement(
            'div',
            { className: 'scouting-nederland-acf-row' },
            createElement('label', {}, 'Titel'),
            createElement(
                'div',
                { className: 'scouting-nederland-news-category-box' },
                categoryOptions
            )
        );
    }

    /**
     * Renders removable tokens for explicitly selected news posts.
     *
     * @param {Array<NewsPost>} selectedPosts The resolved posts in selected order.
     * @param {Function} onRemove Removes one selected post by ID.
     * @returns {Object} A WordPress element containing the selected-post tokens.
     */
    function renderSelectedPostTokens(selectedPosts, onRemove) {
        // Give every selected post an accessible removal button.
        return createElement(
            'div',
            { className: 'scouting-nederland-news-token-field' },
            selectedPosts.map((post) => createElement(
                'span',
                { key: post.id, className: 'scouting-nederland-news-token' },
                '× ' + post.title,
                createElement(
                    'button',
                    {
                        type: 'button',
                        onClick: () => onRemove(post.id),
                        'aria-label': 'Verwijder ' + post.title
                    },
                    '×'
                )
            ))
        );
    }

    /**
     * Renders the dropdown used to append another filtered post selection.
     *
     * @param {Array<NewsPost>} availablePosts Posts allowed by the category filter.
     * @param {Array<number>} selectedPostIds IDs that must be excluded from the dropdown.
     * @param {Function} onAdd Adds one post ID to the explicit selection.
     * @returns {Object} A WordPress select element containing unselected posts.
     */
    function renderPostSelector(availablePosts, selectedPostIds, onAdd) {
        // Exclude posts already represented by a selected token.
        const selectablePosts = availablePosts.filter(
            (post) => !selectedPostIds.includes(normalizeId(post.id))
        );

        return createElement(
            'select',
            {
                className: 'scouting-nederland-acf-select',
                value: '',
                onChange: (event) => {
                    // Add the selected ID, then reset the control to its placeholder.
                    onAdd(event.target.value);
                    event.target.value = '';
                }
            },
            createElement('option', { value: '' }, 'Nieuwsitem toevoegen…'),
            selectablePosts.map((post) => createElement(
                'option',
                { key: post.id, value: post.id },
                post.title
            ))
        );
    }

    /**
     * Combines selected-post tokens, the add-post dropdown, and explanatory text.
     *
     * @param {Object} context The derived selection data and mutation callbacks.
     * @param {Array<NewsPost>} context.selectedPosts Resolved selected posts.
     * @param {Array<NewsPost>} context.availablePosts Posts allowed by category filters.
     * @param {Array<number>} context.selectedPostIds Explicitly selected post IDs.
     * @param {Function} context.addPost Adds an explicit post selection.
     * @param {Function} context.removePost Removes an explicit post selection.
     * @returns {Object} A WordPress element containing the complete news selection row.
     */
    function renderNewsSelection(context) {
        // Compose the related selection controls under one labelled form row.
        return createElement(
            'div',
            { className: 'scouting-nederland-acf-row' },
            createElement('label', {}, 'Nieuwsitems'),
            createElement(
                'div',
                { className: 'scouting-nederland-news-select-wrap' },
                renderSelectedPostTokens(context.selectedPosts, context.removePost),
                renderPostSelector(context.availablePosts, context.selectedPostIds, context.addPost),
                createElement(
                    'p',
                    { className: 'description' },
                    'Kies eerst één of meer categorieën. De lijst met nieuwsitems wordt daarop gefilterd.'
                )
            )
        );
    }

    /**
     * Renders the range control that limits automatically queried posts.
     *
     * @param {Object} attributes The current news-items block attributes.
     * @param {Function} setAttributes Persists a partial news-items attribute update.
     * @returns {Object} A WordPress element containing the count slider.
     */
    function renderCountControl(attributes, setAttributes) {
        // Constrain the public query count to the supported one-through-six range.
        return createElement(
            'div',
            { className: 'scouting-nederland-acf-row' },
            createElement('label', {}, 'Aantal'),
            createElement(RangeControl, {
                value: attributes.count || 3,
                min: 1,
                max: 6,
                onChange: (count) => setAttributes({ count })
            })
        );
    }

    /**
     * Renders the news overview call-to-action fields.
     *
     * @param {Object} attributes The current news-items block attributes.
     * @param {Function} setAttributes Persists a partial news-items attribute update.
     * @returns {Object} A WordPress element containing text, link, target, and color controls.
     */
    function renderButtonFields(attributes, setAttributes) {
        // Group all overview-button attributes in one fieldset.
        return createElement(
            'div',
            { className: 'scouting-nederland-acf-row' },
            createElement('label', {}, 'Knop'),
            createElement(
                'fieldset',
                {
                    className: 'scouting-nederland-acf-button-box '
                        + 'scouting-nederland-news-button-box'
                },
                createElement(
                    'div',
                    { className: 'scouting-nederland-acf-field' },
                    createElement('label', {}, 'Tekst'),
                    createElement(TextControl, {
                        value: attributes.buttonText || '',
                        onChange: (buttonText) => setAttributes({ buttonText })
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
                        { className: 'scouting-nederland-acf-field' },
                        createElement('label', {}, 'Kleur'),
                        createElement(ColorRadioControl, {
                            value: attributes.buttonColor,
                            defaultValue: 'blauw',
                            onChange: (buttonColor) => setAttributes({ buttonColor })
                        })
                    )
                )
            )
        );
    }

    registerBlockType('scouting-nederland/news-items', {
        title: __('Nieuws items', 'scouting-nederland'),
        icon: 'welcome-write-blog',
        category: 'scouting-nederland',
        attributes: {
            title: { type: 'string', default: 'Nieuws' },
            categoryIds: { type: 'array', default: [] },
            postIds: { type: 'array', default: [] },
            count: { type: 'number', default: 3 },
            buttonText: { type: 'string', default: 'Meer nieuws' },
            buttonUrl: { type: 'string', default: '' },
            buttonTarget: { type: 'string', default: '' },
            buttonColor: { type: 'string', default: 'blauw' }
        },
        /**
         * Renders the news filtering, explicit selection, count, and button controls.
         *
         * @param {Object} props The WordPress block edit properties.
         * @param {Object} props.attributes The current news-items attributes.
         * @param {Function} props.setAttributes Persists partial news-items updates.
         * @returns {Object} The news-items editor element tree.
         */
        edit: ({ attributes, setAttributes }) => {
            // Load server-localized categories and posts with safe empty-array fallbacks.
            const categories = getLocalizedCollection('newsCategories');
            const posts = getLocalizedCollection('newsPosts');

            // Normalize stored IDs before comparisons because WordPress may provide strings or numbers.
            const selectedCategoryIds = normalizeIds(attributes.categoryIds);
            const selectedPostIds = normalizeIds(attributes.postIds);

            // Derive the filtered dropdown choices and ordered selected-token objects.
            const availablePosts = filterPostsByCategory(posts, selectedCategoryIds);
            const selectedPosts = getSelectedPosts(posts, selectedPostIds);

            /**
             * Toggles one category in the active filter.
             *
             * @param {number|string} categoryId The category ID supplied by a checkbox.
             * @returns {void}
             */
            const toggleCategory = (categoryId) => {
                // Normalize and immutably toggle the category before persisting the full list.
                setAttributes({
                    categoryIds: toggleId(selectedCategoryIds, normalizeId(categoryId))
                });
            };

            /**
             * Appends one valid, previously unselected post ID.
             *
             * @param {number|string} postId The post ID supplied by the select control.
             * @returns {void}
             */
            const addPost = (postId) => {
                // Normalize the form value before validation or comparison.
                const normalizedPostId = normalizeId(postId);

                // Ignore placeholder, invalid, and duplicate selections.
                if (!normalizedPostId || selectedPostIds.includes(normalizedPostId)) {
                    return;
                }

                // Append the ID so explicit front-end ordering follows editor selection order.
                setAttributes({ postIds: selectedPostIds.concat([normalizedPostId]) });
            };

            /**
             * Removes one post ID from the explicit selection.
             *
             * @param {number|string} postId The post ID supplied by a removal token.
             * @returns {void}
             */
            const removePost = (postId) => {
                // Normalize the token ID, then persist a filtered copy of the selection.
                const normalizedPostId = normalizeId(postId);
                setAttributes({
                    postIds: selectedPostIds.filter((id) => id !== normalizedPostId)
                });
            };

            // Compose the four editor sections from normalized data and immutable callbacks.
            return createElement(
                'div',
                {
                    className: 'scouting-nederland-acf-like-block '
                        + 'scouting-nederland-news-editor-form'
                },
                renderCategorySelector(categories, selectedCategoryIds, toggleCategory),
                renderNewsSelection({
                    selectedPosts,
                    availablePosts,
                    selectedPostIds,
                    addPost,
                    removePost
                }),
                renderCountControl(attributes, setAttributes),
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
