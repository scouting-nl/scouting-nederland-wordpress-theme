(function(wp){
    const { registerBlockType } = wp.blocks;
    const { __ } = wp.i18n;
    const { Fragment } = wp.element;
    const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, URLInputButton } = wp.blockEditor || wp.editor;
    const { PanelBody, TextControl, TextareaControl, Button, RangeControl, SelectControl, ToggleControl } = wp.components;

    function ButtonFields({label, text, url, color, onText, onUrl, onColor}) {
        return wp.element.createElement('div', {className:'tc4-editor-button-fields'},
            wp.element.createElement('strong', {}, label || 'Knop'),
            wp.element.createElement(TextControl, {label:'Knoptekst', value:text || '', onChange:onText}),
            wp.element.createElement(TextControl, {label:'Knop URL', value:url || '', onChange:onUrl}),
            wp.element.createElement(SelectControl, {
                label:'Kleur',
                value: color || 'blauw',
                options:[
                    {label:'blauw', value:'blauw'},
                    {label:'groen', value:'groen'},
                    {label:'zwart', value:'zwart'}
                ],
                onChange:onColor
            })
        );
    }

    function InlineImage({url, onSelect, buttonText}) {
        return wp.element.createElement(MediaUploadCheck, {},
            wp.element.createElement(MediaUpload, {
                onSelect: onSelect,
                allowedTypes: ['image'],
                render: ({open}) => wp.element.createElement('div', {className:'tc4-editor-image-picker'},
                    url
                        ? wp.element.createElement('img', {src:url, alt:'', onClick:open})
                        : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, buttonText || 'Afbeelding kiezen')
                )
            })
        );
    }

    function btnClass(color) {
        if (color === 'groen') return 'tc4-btn tc4-btn-green';
        if (color === 'zwart') return 'tc4-btn tc4-btn-black';
        return 'tc4-btn tc4-btn-blue';
    }

    class StableClassicEditor extends wp.element.Component {
        constructor(props) {
            super(props);
            this.editorId = 'tc4-tinymce-editor-' + Math.random().toString(36).substr(2, 9);
            this.textareaRef = wp.element.createRef();
            this.isReady = false;
            this.lastValue = props.value || '';
            this.handleChange = this.handleChange.bind(this);
        }

        componentDidMount() {
            this.initializeEditor();
        }

        componentDidUpdate(prevProps) {
            const nextValue = this.props.value || '';

            if (nextValue !== prevProps.value && nextValue !== this.lastValue) {
                this.lastValue = nextValue;

                if (window.tinymce && window.tinymce.get(this.editorId)) {
                    const editor = window.tinymce.get(this.editorId);
                    if (editor && editor.getContent() !== nextValue) {
                        editor.setContent(nextValue || '');
                    }
                } else if (this.textareaRef.current && this.textareaRef.current.value !== nextValue) {
                    this.textareaRef.current.value = nextValue;
                }
            }
        }

        componentWillUnmount() {
            if (window.wp && wp.editor && wp.editor.remove) {
                try {
                    wp.editor.remove(this.editorId);
                    return;
                } catch (error) {}
            }

            if (window.tinymce && window.tinymce.get(this.editorId)) {
                try {
                    window.tinymce.get(this.editorId).remove();
                } catch (error) {}
            }
        }

        handleChange(value) {
            this.lastValue = value || '';
            this.props.onChange(this.lastValue);
        }

        initializeEditor() {
            if (!this.textareaRef.current) {
                return;
            }

            this.textareaRef.current.value = this.props.value || '';

            if (!window.wp || !wp.editor || !wp.editor.initialize) {
                return;
            }

            const self = this;

            window.setTimeout(function() {
                if (!document.getElementById(self.editorId)) {
                    return;
                }

                wp.editor.initialize(self.editorId, {
                    tinymce: {
                        wpautop: true,
                        menubar: false,
                        branding: false,
                        height: 300,
                        plugins: 'charmap colorpicker hr lists paste tabfocus textcolor wordpress wpautoresize wpeditimage wplink',
                        toolbar1: 'bold italic underline blockquote strikethrough bullist numlist alignleft aligncenter alignright undo redo link unlink fullscreen',
                        toolbar2: '',
                        setup: function(editor) {
                            editor.on('init', function() {
                                self.isReady = true;
                                editor.setContent(self.props.value || '');
                            });

                            editor.on('change keyup paste input undo redo', function() {
                                self.handleChange(editor.getContent());
                            });
                        }
                    },
                    quicktags: true,
                    mediaButtons: false
                });
            }, 0);
        }

        render() {
            return wp.element.createElement('div', {className:'tc4-tinymce-editor-wrap'},
                wp.element.createElement('textarea', {
                    id: this.editorId,
                    ref: this.textareaRef,
                    defaultValue: this.props.value || '',
                    onChange: event => this.handleChange(event.target.value)
                })
            );
        }
    }

    function ClassicHtmlEditor(props) {
        return wp.element.createElement(StableClassicEditor, props);
    }

    let scoutingtc4ActiveLinkField = null;
    let scoutingtc4ActiveTextEditorLink = null;
    let scoutingtc4LinkFieldId = 0;

    function ensureScoutingTc4LinkModalHandler() {
        if (window.scoutingtc4FeaturedLinkHandlerReady) {
            return;
        }
        window.scoutingtc4FeaturedLinkHandlerReady = true;

        if (!window.jQuery) {
            return;
        }

        window.jQuery(document).on('click', '#wp-link-submit', function(event) {
            if ((!scoutingtc4ActiveLinkField && !scoutingtc4ActiveTextEditorLink) || !window.wpLink) {
                return;
            }

            event.preventDefault();

            const attrs = window.wpLink.getAttrs ? window.wpLink.getAttrs() : {};
            const href = attrs.href || window.jQuery('#wp-link-url').val() || '';
            const text = window.jQuery('#wp-link-text').val() || '';
            const target = window.jQuery('#wp-link-target').is(':checked') ? '_blank' : '';

            if (scoutingtc4ActiveTextEditorLink) {
                scoutingtc4ActiveTextEditorLink.apply(href, text, target);
                scoutingtc4ActiveTextEditorLink = null;
                window.wpLink.close();
                return;
            }

            if (scoutingtc4ActiveLinkField.onChange) {
                scoutingtc4ActiveLinkField.onChange({
                    url: href,
                    text: text,
                    target: target
                });
            } else {
                scoutingtc4ActiveLinkField.onUrl(href);

                if (text && scoutingtc4ActiveLinkField.onText) {
                    scoutingtc4ActiveLinkField.onText(text);
                }

                if (scoutingtc4ActiveLinkField.onTarget) {
                    scoutingtc4ActiveLinkField.onTarget(target);
                }
            }

            scoutingtc4ActiveLinkField = null;
            window.wpLink.close();
        });

        window.jQuery(document).on('click', '#wp-link-cancel, #wp-link-close', function() {
            scoutingtc4ActiveLinkField = null;
            scoutingtc4ActiveTextEditorLink = null;
        });
    }

    function LinkPickerField({url, text, target, onUrl, onText, onTarget, onChange}) {
        ensureScoutingTc4LinkModalHandler();

        const id = 'tc4-featured-link-' + (++scoutingtc4LinkFieldId);
        const currentUrl = url || '';
        const currentText = text || '';

        const openLinkModal = function(event) {
            event.preventDefault();

            if (!window.wpLink || !window.jQuery) {
                return;
            }

            scoutingtc4ActiveLinkField = {
                onUrl: onUrl,
                onText: onText,
                onTarget: onTarget,
                onChange: onChange
            };

            let textarea = document.getElementById(id);
            if (!textarea) {
                textarea = document.createElement('textarea');
                textarea.id = id;
                textarea.style.display = 'none';
                document.body.appendChild(textarea);
            }

            textarea.value = currentUrl;
            window.wpLink.open(id);

            window.setTimeout(function() {
                window.jQuery('#wp-link-url').val(currentUrl);
                window.jQuery('#wp-link-text').val(currentText);
                window.jQuery('#wp-link-target').prop('checked', target === '_blank');
            }, 50);
        };

        return wp.element.createElement('div', {className:'tc4-link-field-acf'},
            wp.element.createElement('div', {className:'tc4-link-field-acf__summary'},
                currentUrl
                    ? wp.element.createElement(Fragment, {},
                        wp.element.createElement('strong', {}, currentText || currentUrl),
                        wp.element.createElement('span', {}, currentUrl)
                    )
                    : wp.element.createElement('span', {className:'tc4-link-field-acf__empty'}, 'Geen link gekozen')
            ),
            wp.element.createElement('div', {className:'tc4-link-field-acf__actions'},
                wp.element.createElement(Button, {variant:'secondary', onClick:openLinkModal}, currentUrl ? 'Link bewerken' : 'Link invoegen'),
                currentUrl ? wp.element.createElement(Button, {
                    variant:'link',
                    isDestructive:true,
                    onClick:function(){
                        if (onChange) {
                            onChange({url:'', text:'', target:''});
                        } else {
                            onUrl('');
                            if (onTarget) {
                                onTarget('');
                            }
                        }
                    }
                }, 'Link verwijderen') : null
            )
        );
    }


    window.scoutingtc4BlockEditor = {
        registerBlockType: registerBlockType,
        __: __,
        Fragment: Fragment,
        InspectorControls: InspectorControls,
        MediaUpload: MediaUpload,
        MediaUploadCheck: MediaUploadCheck,
        RichText: RichText,
        URLInputButton: URLInputButton,
        PanelBody: PanelBody,
        TextControl: TextControl,
        TextareaControl: TextareaControl,
        Button: Button,
        RangeControl: RangeControl,
        SelectControl: SelectControl,
        ToggleControl: ToggleControl,
        ButtonFields: ButtonFields,
        InlineImage: InlineImage,
        btnClass: btnClass,
        StableClassicEditor: StableClassicEditor,
        ClassicHtmlEditor: ClassicHtmlEditor,
        LinkPickerField: LinkPickerField
    };

})(window.wp);
