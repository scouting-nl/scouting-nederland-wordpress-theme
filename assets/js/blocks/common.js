/**
 * Builds the shared API used by every custom Scouting Nederland editor block.
 *
 * @param {Object} wp The global WordPress JavaScript API.
 * @returns {void}
 */
(function initializeBlockEditorApi(wp) {
    /**
     * @typedef {Object} StableClassicEditorProps
     * @property {string} [value] The current HTML stored in the block attribute.
     * @property {Function} onChange Receives updated HTML from TinyMCE or Quicktags.
     */

    /**
     * @typedef {Object} LinkValue
     * @property {string} url The selected link URL.
     * @property {string} text The selected link label.
     * @property {string} target The selected browser target, normally an empty string or `_blank`.
     */

    /**
     * @typedef {Object} LinkPickerProps
     * @property {string} [url] The currently selected link URL.
     * @property {string} [text] The currently selected link label.
     * @property {string} [target] The currently selected browser target.
     * @property {Function} [onUrl] Receives an updated URL when fields are controlled separately.
     * @property {Function} [onText] Receives updated link text when fields are controlled separately.
     * @property {Function} [onTarget] Receives an updated browser target.
     * @property {Function} [onChange] Receives one complete LinkValue object.
     */

    // Read WordPress packages once so block files can consume a stable, compact shared API.
    const { registerBlockType } = wp.blocks;
    const { __ } = wp.i18n;
    const { Component, Fragment, createElement, createRef } = wp.element;
    const {
        InspectorControls,
        MediaUpload,
        MediaUploadCheck
    } = wp.blockEditor || wp.editor;
    const {
        PanelBody,
        TextControl,
        TextareaControl,
        Button,
        RangeControl,
        SelectControl,
        ToggleControl
    } = wp.components;
    const BUTTON_COLORS = ['blauw', 'groen', 'zwart'];
    const CLASSIC_EDITOR_PLUGINS = [
        'charmap',
        'colorpicker',
        'hr',
        'lists',
        'paste',
        'tabfocus',
        'textcolor',
        'wordpress',
        'wpautoresize',
        'wpeditimage',
        'wplink'
    ].join(' ');
    const CLASSIC_EDITOR_TOOLBAR = [
        'bold',
        'italic',
        'underline',
        'blockquote',
        'strikethrough',
        'bullist',
        'numlist',
        'alignleft',
        'aligncenter',
        'alignright',
        'undo',
        'redo',
        'link',
        'unlink',
        'fullscreen'
    ].join(' ');

    let classicEditorId = 0;

    /**
     * Creates a unique DOM ID for one classic editor instance.
     *
     * @returns {string} A deterministic ID that is unique during the current editor session.
     */
    function createClassicEditorId() {
        // Increment before formatting so the first generated editor ends in `1`.
        classicEditorId += 1;

        // Prefix the counter to avoid collisions with unrelated WordPress controls.
        return 'scouting-nederland-tinymce-editor-' + classicEditorId;
    }

    /**
     * Finds a mounted TinyMCE editor when the TinyMCE global is available.
     *
     * @param {string} editorId The DOM ID assigned to the editor textarea.
     * @returns {Object|null} The TinyMCE editor instance, or null before initialization.
     */
    function getTinyMceEditor(editorId) {
        // TinyMCE is loaded lazily by WordPress, so its global may not exist yet.
        if (!globalThis.tinymce) {
            return null;
        }

        // Ask TinyMCE for the instance associated with this component's textarea.
        return globalThis.tinymce.get(editorId);
    }

    /**
     * Renders the shared blue, green, and black button-color radio controls.
     *
     * @param {Object} props The color-control properties.
     * @param {string} [props.value] The explicitly selected color.
     * @param {string} [props.defaultValue] The fallback color when no value is stored.
     * @param {Function} props.onChange Receives the color selected by the editor.
     * @returns {Object} A WordPress element containing the radio-button row.
     */
    function ColorRadioControl({ value, defaultValue, onChange }) {
        // Resolve one selected color before constructing the controlled radio inputs.
        const selectedColor = value || defaultValue || 'blauw';

        // Render every supported color from the central list to keep blocks consistent.
        return createElement(
            'div',
            { className: 'scouting-nederland-editor-radio-row' },
            BUTTON_COLORS.map((color) => createElement(
                'label',
                { key: color },
                createElement('input', {
                    type: 'radio',
                    checked: selectedColor === color,
                    onChange: () => onChange(color)
                }),
                ' ' + color
            ))
        );
    }

    /**
     * Renders an authorized WordPress image picker with preview and optional removal.
     *
     * @param {Object} props The image-picker properties.
     * @param {string} [props.url] The URL of the currently selected image.
     * @param {Function} props.onSelect Receives the selected WordPress media attachment.
     * @param {Function} [props.onRemove] Clears the selected image when invoked.
     * @param {string} [props.selectButtonText] Optional label for the selection button.
     * @param {string} [props.removeButtonText] Optional label for the removal button.
     * @returns {Object} A WordPress element containing the image field.
     */
    function ImagePicker({ url, onSelect, onRemove, selectButtonText, removeButtonText }) {
        // Wrap MediaUpload in WordPress's permission check before exposing library access.
        return createElement(
            MediaUploadCheck,
            {},
            createElement(MediaUpload, {
                onSelect,
                allowedTypes: ['image'],
                render: ({ open }) => createElement(
                    'div',
                    { className: 'scouting-nederland-acf-image-field' },
                    url
                        ? createElement('img', { src: url, alt: '', onClick: open })
                        : createElement(
                            Button,
                            { onClick: open, variant: 'secondary' },
                            selectButtonText || 'Afbeelding kiezen'
                        ),
                    url && onRemove
                        ? createElement(
                            Button,
                            { onClick: onRemove, variant: 'link', isDestructive: true },
                            removeButtonText || 'Verwijderen'
                        )
                        : null
                )
            })
        );
    }

    /**
     * Keeps a WordPress TinyMCE/Quicktags editor synchronized with one block HTML attribute.
     */
    class StableClassicEditor extends Component {
        /**
         * Creates one stable editor component and binds callbacks passed to WordPress APIs.
         *
         * @param {StableClassicEditorProps} props The controlled editor value and change callback.
         * @returns {StableClassicEditor} The initialized component instance.
         */
        constructor(props) {
            super(props);

            // Allocate persistent instance state that must survive React rerenders.
            this.editorId = createClassicEditorId();
            this.textareaRef = createRef();
            this.lastValue = props.value || '';

            // Preserve `this` when WordPress invokes methods as external event callbacks.
            this.handleChange = this.handleChange.bind(this);
            this.handleEditorClick = this.handleEditorClick.bind(this);
            this.mountEditor = this.mountEditor.bind(this);
            this.setupTinyMce = this.setupTinyMce.bind(this);
            this.syncTextareaValue = this.syncTextareaValue.bind(this);
        }

        /**
         * Starts classic-editor initialization after React mounts the textarea.
         *
         * @returns {void}
         */
        componentDidMount() {
            // Initialize only after the textarea ref can point to a real DOM node.
            this.initializeEditor();
        }

        /**
         * Applies externally changed block HTML without echoing locally emitted updates.
         *
         * @param {StableClassicEditorProps} prevProps The properties from the previous render.
         * @returns {void}
         */
        componentDidUpdate(prevProps) {
            // Normalize an omitted value to the empty string used by both editor modes.
            const nextValue = this.props.value || '';

            // Ignore unchanged props and values this component already emitted itself.
            if (nextValue === prevProps.value || nextValue === this.lastValue) {
                return;
            }

            // Cache the accepted external value before updating either editor surface.
            this.lastValue = nextValue;

            // Prefer TinyMCE when mounted; otherwise synchronize the underlying textarea.
            const editor = getTinyMceEditor(this.editorId);
            if (editor && editor.getContent() !== nextValue) {
                editor.setContent(nextValue);
            } else if (this.textareaRef.current && this.textareaRef.current.value !== nextValue) {
                this.textareaRef.current.value = nextValue;
            }
        }

        /**
         * Removes WordPress and TinyMCE resources before the block component disappears.
         *
         * @returns {void}
         */
        componentWillUnmount() {
            // Let WordPress remove its wrapper, Quicktags instance, and TinyMCE editor first.
            if (wp.editor && wp.editor.remove) {
                try {
                    wp.editor.remove(this.editorId);
                    return;
                } catch (error) {
                    globalThis.console.warn('WordPress editor cleanup failed; trying TinyMCE directly.', error);
                }
            }

            // Fall back to direct TinyMCE cleanup if WordPress could not remove the editor.
            const editor = getTinyMceEditor(this.editorId);
            if (editor) {
                try {
                    editor.remove();
                } catch (error) {
                    globalThis.console.warn('TinyMCE editor cleanup failed.', error);
                }
            }
        }

        /**
         * Stores editor HTML locally and forwards it to the owning block attribute.
         *
         * @param {string|null|undefined} value The latest HTML from an editor surface.
         * @returns {void}
         */
        handleChange(value) {
            // Normalize empty values so all editor paths publish the same data type.
            this.lastValue = value || '';

            // Notify the block only after updating the loop-prevention cache.
            this.props.onChange(this.lastValue);
        }

        /**
         * Detects controls that mutate Quicktags or switch the active editor mode.
         *
         * @param {Object} event The React click event raised inside the editor wrapper.
         * @returns {void}
         */
        handleEditorClick(event) {
            // Ignore ordinary clicks and react only to Quicktags or editor-mode controls.
            const editorControl = event.target.closest?.('.quicktags-toolbar, .wp-switch-editor');

            if (editorControl) {
                // Wait until WordPress finishes mutating the textarea, then publish its value.
                globalThis.setTimeout(this.syncTextareaValue, 0);
            }
        }

        /**
         * Publishes the current raw textarea value to the owning block.
         *
         * @returns {void}
         */
        syncTextareaValue() {
            // The ref can be empty if the block unmounts before a deferred callback runs.
            if (this.textareaRef.current) {
                this.handleChange(this.textareaRef.current.value);
            }
        }

        /**
         * Seeds the textarea and schedules WordPress classic-editor initialization.
         *
         * @returns {void}
         */
        initializeEditor() {
            // Stop if React has not committed the textarea or has already removed it.
            if (!this.textareaRef.current) {
                return;
            }

            // Seed Code mode before TinyMCE or Quicktags reads the textarea.
            this.textareaRef.current.value = this.props.value || '';

            // Retain a plain textarea when the legacy WordPress editor API is unavailable.
            if (!wp.editor || !wp.editor.initialize) {
                return;
            }

            // Defer setup so WordPress sees the fully committed editor DOM.
            globalThis.setTimeout(this.mountEditor, 0);
        }

        /**
         * Converts the mounted textarea into synchronized TinyMCE and Quicktags editors.
         *
         * @returns {void}
         */
        mountEditor() {
            // A deferred callback may run after React has removed the block.
            if (!document.getElementById(this.editorId)) {
                return;
            }

            // Initialize both Visual and Code modes with shared toolbar configuration.
            wp.editor.initialize(this.editorId, {
                tinymce: {
                    wpautop: true,
                    menubar: false,
                    branding: false,
                    height: 300,
                    plugins: CLASSIC_EDITOR_PLUGINS,
                    toolbar1: CLASSIC_EDITOR_TOOLBAR,
                    toolbar2: '',
                    setup: this.setupTinyMce
                },
                quicktags: true,
                mediaButtons: false
            });
        }

        /**
         * Connects one TinyMCE instance to initial content and block updates.
         *
         * @param {Object} editor The TinyMCE editor being initialized by WordPress.
         * @returns {void}
         */
        setupTinyMce(editor) {
            // Restore the controlled block value when TinyMCE completes initialization.
            editor.on('init', () => editor.setContent(this.props.value || ''));

            // Publish every content-changing TinyMCE action through the shared handler.
            editor.on(
                'change keyup paste input undo redo',
                () => this.handleChange(editor.getContent())
            );
        }

        /**
         * Renders the stable textarea that WordPress enhances in place.
         *
         * @returns {Object} A WordPress element containing the classic-editor textarea.
         */
        render() {
            // Keep the textarea mounted under a predictable wrapper for Quicktags click handling.
            return createElement(
                'div',
                {
                    className: 'scouting-nederland-tinymce-editor-wrap',
                    onClick: this.handleEditorClick
                },
                createElement('textarea', {
                    id: this.editorId,
                    ref: this.textareaRef,
                    defaultValue: this.props.value || '',
                    onChange: event => this.handleChange(event.target.value)
                })
            );
        }
    }

    /**
     * Exposes the stable classic-editor component through a function-component API.
     *
     * @param {StableClassicEditorProps} props The controlled editor properties.
     * @returns {Object} A WordPress element containing StableClassicEditor.
     */
    function ClassicHtmlEditor(props) {
        // Forward the complete controlled-editor contract without transforming values.
        return createElement(StableClassicEditor, props);
    }

    let activeLinkField = null;
    let linkFieldId = 0;
    let linkModalHandlersRegistered = false;

    /**
     * Creates a unique DOM ID for one WordPress link-modal anchor.
     *
     * @returns {string} A session-unique hidden textarea ID.
     */
    function createLinkFieldId() {
        // Increment once per component instance so rerenders keep their original ID.
        linkFieldId += 1;

        // Use a theme-specific prefix to avoid collisions with core editor controls.
        return 'scouting-nederland-link-field-' + linkFieldId;
    }

    /**
     * Reads and normalizes the current values from the global WordPress link modal.
     *
     * @returns {LinkValue} The URL, label, and target selected by the editor.
     */
    function readLinkModalValues() {
        // Prefer wpLink's normalized attributes and retain a jQuery fallback for older WordPress versions.
        const linkAttributes = globalThis.wpLink.getAttrs
            ? globalThis.wpLink.getAttrs()
            : {};

        // Convert modal controls into the stable shape consumed by every custom block.
        return {
            url: linkAttributes.href || globalThis.jQuery('#wp-link-url').val() || '',
            text: globalThis.jQuery('#wp-link-text').val() || '',
            target: globalThis.jQuery('#wp-link-target').is(':checked') ? '_blank' : ''
        };
    }

    /**
     * Sends a selected link through either the aggregate or individual callback contract.
     *
     * @param {LinkPickerProps} field The callbacks registered by the active link field.
     * @param {LinkValue} link The values read from the WordPress link modal.
     * @returns {void}
     */
    function applyLinkModalValues(field, link) {
        // Prefer one atomic update when the consumer provides an aggregate callback.
        if (field.onChange) {
            field.onChange(link);
            return;
        }

        // Otherwise update each separately controlled block attribute when supported.
        if (field.onUrl) {
            field.onUrl(link.url);
        }

        // Preserve separately entered button text when the modal's optional label is empty.
        if (link.text && field.onText) {
            field.onText(link.text);
        }

        if (field.onTarget) {
            field.onTarget(link.target);
        }
    }

    /**
     * Registers the document-level handlers shared by all WordPress link picker instances.
     *
     * @returns {void}
     */
    function ensureLinkModalHandlers() {
        // Register at most once and retain no-op behavior when jQuery is unavailable.
        if (linkModalHandlersRegistered || !globalThis.jQuery) {
            return;
        }

        linkModalHandlersRegistered = true;

        // Commit the modal values only to the field that opened the shared dialog.
        globalThis.jQuery(document).on(
            'click.scoutingNederlandLinkPicker',
            '#wp-link-submit',
            function (event) {
                if (!activeLinkField || !globalThis.wpLink) {
                    return;
                }

                event.preventDefault();

                // Capture values and clear global ownership before invoking consumer callbacks.
                const field = activeLinkField;
                const link = readLinkModalValues();
                activeLinkField = null;

                applyLinkModalValues(field, link);
                globalThis.wpLink.close();
            }
        );

        // Release field ownership when the editor explicitly cancels or closes the dialog.
        globalThis.jQuery(document).on(
            'click.scoutingNederlandLinkPicker',
            '#wp-link-cancel, #wp-link-close',
            function () {
                activeLinkField = null;
            }
        );

        // Also handle Escape, backdrop, and programmatic close paths emitted by WordPress.
        globalThis.jQuery(document).on(
            'wplink-close.scoutingNederlandLinkPicker',
            function () {
                activeLinkField = null;
            }
        );
    }

    /**
     * Integrates one custom block link field with WordPress's shared global link modal.
     */
    class LinkPickerField extends Component {
        /**
         * Creates a stable modal anchor and binds callbacks used by WordPress controls.
         *
         * @param {LinkPickerProps} props The controlled link values and update callbacks.
         * @returns {LinkPickerField} The initialized component instance.
         */
        constructor(props) {
            super(props);

            // Allocate a persistent anchor ID for this component instance.
            this.fieldId = createLinkFieldId();

            // Preserve component context when buttons invoke these methods.
            this.openLinkModal = this.openLinkModal.bind(this);
            this.removeLink = this.removeLink.bind(this);
        }

        /**
         * Releases global ownership and removes this field's hidden modal anchor.
         *
         * @returns {void}
         */
        componentWillUnmount() {
            // Prevent later modal submissions from updating a field that no longer exists.
            if (activeLinkField?.fieldId === this.fieldId) {
                activeLinkField = null;
            }

            // Remove the hidden textarea created lazily for wpLink.
            const modalAnchor = document.getElementById(this.fieldId);
            modalAnchor?.remove();
        }

        /**
         * Finds or creates the hidden textarea required by WordPress's link modal.
         *
         * @returns {HTMLTextAreaElement} The persistent modal anchor for this field.
         */
        getModalAnchor() {
            // Reuse the existing anchor across repeated modal openings.
            let modalAnchor = document.getElementById(this.fieldId);

            if (!modalAnchor) {
                // Create the real textarea ID required by wpLink's selection API.
                modalAnchor = document.createElement('textarea');
                modalAnchor.id = this.fieldId;
                modalAnchor.style.display = 'none';
                modalAnchor.setAttribute('aria-hidden', 'true');

                // Keep the anchor in the document until this React component unmounts.
                document.body.appendChild(modalAnchor);
            }

            return modalAnchor;
        }

        /**
         * Opens and populates WordPress's global link modal for this field.
         *
         * @param {Object} event The React click event from the insert/edit-link button.
         * @returns {void}
         */
        openLinkModal(event) {
            // Prevent the field button from submitting an enclosing editor form.
            event.preventDefault();

            // Keep the field inert when WordPress did not load its link-dialog dependencies.
            if (!globalThis.wpLink || !globalThis.jQuery) {
                return;
            }

            // Ensure shared submit and close listeners exist before the modal can respond.
            ensureLinkModalHandlers();

            // Read current controlled values and prepare the modal's required anchor element.
            const currentUrl = this.props.url || '';
            const currentText = this.props.text || '';
            const modalAnchor = this.getModalAnchor();

            // Record which field should receive the next shared modal submission.
            activeLinkField = {
                fieldId: this.fieldId,
                onUrl: this.props.onUrl,
                onText: this.props.onText,
                onTarget: this.props.onTarget,
                onChange: this.props.onChange
            };

            // Seed the anchor and ask WordPress to open the modal against its ID.
            modalAnchor.value = currentUrl;
            globalThis.wpLink.open(this.fieldId);

            // Populate controls after WordPress finishes rendering and resetting the modal.
            globalThis.setTimeout(() => {
                globalThis.jQuery('#wp-link-url').val(currentUrl);
                globalThis.jQuery('#wp-link-text').val(currentText);
                globalThis.jQuery('#wp-link-target').prop(
                    'checked',
                    this.props.target === '_blank'
                );
            }, 50);
        }

        /**
         * Clears the selected URL and target through the consumer's callback contract.
         *
         * @returns {void}
         */
        removeLink() {
            // Prefer one atomic clear operation for aggregate consumers.
            if (this.props.onChange) {
                this.props.onChange({ url: '', text: '', target: '' });
                return;
            }

            // Otherwise clear each independently controlled value that has a callback.
            if (this.props.onUrl) {
                this.props.onUrl('');
            }

            if (this.props.onTarget) {
                this.props.onTarget('');
            }
        }

        /**
         * Renders the selected-link summary and its edit/remove actions.
         *
         * @returns {Object} A WordPress element containing the complete link field.
         */
        render() {
            // Normalize missing controlled values before choosing summary and button labels.
            const currentUrl = this.props.url || '';
            const currentText = this.props.text || '';

            // Render either the selected link details or an explicit empty state.
            return createElement(
                'div',
                { className: 'scouting-nederland-link-field-acf' },
                createElement(
                    'div',
                    { className: 'scouting-nederland-link-field-acf__summary' },
                    currentUrl
                        ? createElement(
                            Fragment,
                            {},
                            createElement('strong', {}, currentText || currentUrl),
                            createElement('span', {}, currentUrl)
                        )
                        : createElement(
                            'span',
                            { className: 'scouting-nederland-link-field-acf__empty' },
                            'Geen link gekozen'
                        )
                ),
                createElement(
                    'div',
                    { className: 'scouting-nederland-link-field-acf__actions' },
                    createElement(
                        Button,
                        { variant: 'secondary', onClick: this.openLinkModal },
                        currentUrl ? 'Link bewerken' : 'Link invoegen'
                    ),
                    currentUrl
                        ? createElement(
                            Button,
                            {
                                variant: 'link',
                                isDestructive: true,
                                onClick: this.removeLink
                            },
                            'Link verwijderen'
                        )
                        : null
                )
            );
        }
    }

    // Publish only the APIs needed by individual block registration scripts.
    globalThis.scoutingNederlandBlockEditor = {
        registerBlockType: registerBlockType,
        __: __,
        Fragment: Fragment,
        InspectorControls: InspectorControls,
        MediaUpload: MediaUpload,
        MediaUploadCheck: MediaUploadCheck,
        PanelBody: PanelBody,
        TextControl: TextControl,
        TextareaControl: TextareaControl,
        Button: Button,
        RangeControl: RangeControl,
        SelectControl: SelectControl,
        ToggleControl: ToggleControl,
        ColorRadioControl: ColorRadioControl,
        ImagePicker: ImagePicker,
        StableClassicEditor: StableClassicEditor,
        ClassicHtmlEditor: ClassicHtmlEditor,
        LinkPickerField: LinkPickerField
    };
})(globalThis.wp);
