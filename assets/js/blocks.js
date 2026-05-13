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
            this.editorRef = wp.element.createRef();
            this.lastValue = props.value || '';
            this.exec = this.exec.bind(this);
            this.handleInput = this.handleInput.bind(this);
            this.handlePaste = this.handlePaste.bind(this);
        }

        componentDidMount() {
            if (this.editorRef.current) {
                this.editorRef.current.innerHTML = this.props.value || '';
            }
        }

        componentDidUpdate(prevProps) {
            const nextValue = this.props.value || '';
            if (nextValue !== this.lastValue && this.editorRef.current && document.activeElement !== this.editorRef.current) {
                this.editorRef.current.innerHTML = nextValue;
                this.lastValue = nextValue;
            }
        }

        exec(command, value) {
            const editor = this.editorRef.current;
            if (!editor) return;
            editor.focus();

            if (command === 'createLink') {
                const url = window.prompt('Link URL');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            } else {
                document.execCommand(command, false, value || null);
            }

            this.handleInput();
        }

        handleInput() {
            const editor = this.editorRef.current;
            if (!editor) return;
            this.lastValue = editor.innerHTML;
            this.props.onChange(this.lastValue);
        }

        handlePaste(event) {
            event.preventDefault();

            let html = '';
            if (event.clipboardData) {
                html = event.clipboardData.getData('text/html') || '';
                if (!html) {
                    const text = event.clipboardData.getData('text/plain') || '';
                    html = text
                        .split(/\n{2,}/)
                        .map(function(paragraph) {
                            return '<p>' + paragraph.replace(/\n/g, '<br>') + '</p>';
                        })
                        .join('');
                }
            }

            html = html
                .replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '')
                .replace(/<style[\s\S]*?>[\s\S]*?<\/style>/gi, '')
                .replace(/\son\w+="[^"]*"/gi, '')
                .replace(/\son\w+='[^']*'/gi, '');

            document.execCommand('insertHTML', false, html);
            this.handleInput();
        }

        renderButton(label, command, value, extraClass) {
            return wp.element.createElement('button', {
                type: 'button',
                className: 'tc4-real-toolbar-button ' + (extraClass || ''),
                title: label,
                onMouseDown: (event) => {
                    event.preventDefault();
                    this.exec(command, value);
                }
            }, label);
        }

        render() {
            return wp.element.createElement('div', {className:'tc4-real-classic-editor'},
                wp.element.createElement('div', {className:'tc4-real-tabs', 'aria-hidden':'true'},
                    wp.element.createElement('span', {className:'is-active'}, 'Visueel'),
                    wp.element.createElement('span', {}, 'Tekst')
                ),
                wp.element.createElement('div', {className:'tc4-real-toolbar'},
                    this.renderButton('B', 'bold', null, 'is-bold'),
                    this.renderButton('I', 'italic', null, 'is-italic'),
                    this.renderButton('U', 'underline', null, 'is-underline'),
                    this.renderButton('“', 'formatBlock', 'blockquote'),
                    this.renderButton('ABC', 'strikeThrough', null, 'is-small'),
                    this.renderButton('•', 'insertUnorderedList'),
                    this.renderButton('1.', 'insertOrderedList'),
                    this.renderButton('≡', 'justifyLeft'),
                    this.renderButton('≣', 'justifyCenter'),
                    this.renderButton('≡', 'justifyRight'),
                    this.renderButton('↶', 'undo'),
                    this.renderButton('↷', 'redo'),
                    this.renderButton('🔗', 'createLink'),
                    this.renderButton('×', 'removeFormat')
                ),
                wp.element.createElement('div', {
                    ref: this.editorRef,
                    className:'tc4-real-editor-content',
                    contentEditable:true,
                    suppressContentEditableWarning:true,
                    onInput:this.handleInput,
                    onBlur:this.handleInput,
                    onPaste:this.handlePaste
                })
            );
        }
    }

    function ClassicHtmlEditor(props) {
        return wp.element.createElement(StableClassicEditor, props);
    }

    registerBlockType('scoutingtc4/featured-items', {
        title: __('Featured items', 'scoutingtc4'),
        icon: 'screenoptions',
        category: 'scoutingtc4',
        attributes: {
            title: {type:'string', default:''},
            items: {type:'array', default:[
                {title:'Wie zijn wij?', text:'Scouting staat voor uitdaging! Scouting biedt leuke en spannende activiteiten waarmee meiden en jongens worden uitgedaagd zich persoonlijk te ontwikkelen.', imageUrl:'', buttonText:'Meer info', buttonUrl:'', buttonColor:'blauw'},
                {title:'Lid worden', text:'Laat je uitdagen en ontdek Scouting!', imageUrl:'', buttonText:'Kom een keer meedoen', buttonUrl:'', buttonColor:'blauw'},
                {title:'Snel naar', text:'Verhuur<br>Stockfoto’s<br>Scouting Nederland', imageUrl:'', buttonText:'', buttonUrl:'', buttonColor:'blauw'}
            ]}
        },
        edit: (props) => {
            const attrs = props.attributes;
            const items = attrs.items || [];
            const setItem = (i, key, val) => {
                const next = items.slice();
                next[i] = Object.assign({}, next[i] || {}, {[key]: val});
                props.setAttributes({items: next});
            };

            const colorRadios = (i, item) => wp.element.createElement('div', {className:'tc4-editor-radio-row'},
                ['blauw','groen','zwart'].map(color =>
                    wp.element.createElement('label', {key:color},
                        wp.element.createElement('input', {
                            type:'radio',
                            checked:(item.buttonColor || 'blauw') === color,
                            onChange:()=>setItem(i, 'buttonColor', color)
                        }),
                        ' ' + color
                    )
                )
            );

            return wp.element.createElement('div', {className:'tc4-acf-like-block tc4-featured-editor-form'},
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Kop'),
                    wp.element.createElement(TextControl, {
                        value:attrs.title || '',
                        onChange:v=>props.setAttributes({title:v}),
                        placeholder:'Optionele algemene kop'
                    })
                ),
                wp.element.createElement('div', {className:'tc4-acf-section-title'}, 'Items'),
                wp.element.createElement('div', {className:'tc4-acf-repeater'},
                    [0,1,2].map(i => {
                        const item = items[i] || {};
                        return wp.element.createElement('div', {className:'tc4-acf-repeater-row', key:i},
                            wp.element.createElement('div', {className:'tc4-acf-row-number'}, i + 1),
                            wp.element.createElement('div', {className:'tc4-acf-row-content'},
                                wp.element.createElement('div', {className:'tc4-acf-field'},
                                    wp.element.createElement('label', {}, 'Kop'),
                                    wp.element.createElement(TextControl, {
                                        value:item.title || '',
                                        onChange:v=>setItem(i,'title',v)
                                    })
                                ),
                                wp.element.createElement('div', {className:'tc4-acf-field'},
                                    wp.element.createElement('label', {}, 'Beeld'),
                                    wp.element.createElement(MediaUploadCheck, {},
                                        wp.element.createElement(MediaUpload, {
                                            onSelect:media=>setItem(i,'imageUrl',media.url),
                                            allowedTypes:['image'],
                                            render:({open})=>wp.element.createElement('div', {className:'tc4-acf-image-field'},
                                                item.imageUrl
                                                    ? wp.element.createElement('img', {src:item.imageUrl, alt:'', onClick:open})
                                                    : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, 'Afbeelding kiezen'),
                                                item.imageUrl ? wp.element.createElement(Button, {onClick:()=>setItem(i,'imageUrl',''), variant:'link', isDestructive:true}, 'Verwijderen') : null
                                            )
                                        })
                                    )
                                ),
                                wp.element.createElement('div', {className:'tc4-acf-field'},
                                    wp.element.createElement('label', {}, 'Tekst'),
                                    wp.element.createElement(ClassicHtmlEditor, {
                                        value:item.text || '',
                                        onChange:v=>setItem(i,'text',v)
                                    })
                                ),
                                wp.element.createElement('fieldset', {className:'tc4-acf-button-box'},
                                    wp.element.createElement('legend', {}, 'Knop'),
                                    wp.element.createElement('div', {className:'tc4-acf-field'},
                                        wp.element.createElement('label', {}, 'Tekst'),
                                        wp.element.createElement(TextControl, {
                                            value:item.buttonText || '',
                                            onChange:v=>setItem(i,'buttonText',v)
                                        })
                                    ),
                                    wp.element.createElement('div', {className:'tc4-acf-button-grid'},
                                        wp.element.createElement('div', {className:'tc4-acf-field'},
                                            wp.element.createElement('label', {}, 'URL'),
                                            wp.element.createElement(TextControl, {
                                                value:item.buttonUrl || '',
                                                onChange:v=>setItem(i,'buttonUrl',v),
                                                placeholder:'https://...'
                                            })
                                        ),
                                        wp.element.createElement('div', {className:'tc4-acf-field'},
                                            wp.element.createElement('label', {}, 'Kleur'),
                                            colorRadios(i, item)
                                        )
                                    )
                                )
                            )
                        );
                    })
                )
            );
        },
        save: () => null
    });

    registerBlockType('scoutingtc4/callout', {
        title: __('Callout', 'scoutingtc4'),
        icon: 'megaphone',
        category: 'scoutingtc4',
        attributes: {
            title:{type:'string', default:'Vrijwilligers gezocht'},
            text:{type:'string', default:'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!'},
            buttonText:{type:'string', default:'Meld je aan'},
            buttonUrl:{type:'string', default:''},
            buttonColor:{type:'string', default:'zwart'},
            imageUrl:{type:'string', default:''},
            calloutPreset:{type:'string', default:'kompas'},
            useOwnImage:{type:'boolean', default:false}
        },
        edit: (props) => {
            const a = props.attributes;
            const options = (window.scoutingtc4Blocks && scoutingtc4Blocks.calloutOptions) ? scoutingtc4Blocks.calloutOptions : [
                {label:'bijl', value:'bijl'},
                {label:'boei', value:'boei'},
                {label:'gps', value:'gps'},
                {label:'haken', value:'haken'},
                {label:'hout', value:'hout'},
                {label:'kompas', value:'kompas'},
                {label:'lamp', value:'lamp'},
                {label:'touw', value:'touw'},
                {label:'zaklamp', value:'zaklamp'},
                {label:'zakmes', value:'zakmes'}
            ];

            const colorRadios = () => wp.element.createElement('div', {className:'tc4-editor-radio-row'},
                ['blauw','groen','zwart'].map(color =>
                    wp.element.createElement('label', {key:color},
                        wp.element.createElement('input', {
                            type:'radio',
                            checked:(a.buttonColor || 'zwart') === color,
                            onChange:()=>props.setAttributes({buttonColor:color})
                        }),
                        ' ' + color
                    )
                )
            );

            return wp.element.createElement('div', {className:'tc4-acf-like-block tc4-callout-editor-form'},
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Eigen beeld gebruiken'),
                    wp.element.createElement('div', {className:'tc4-editor-radio-row tc4-editor-radio-inline'},
                        wp.element.createElement('label', {},
                            wp.element.createElement('input', {
                                type:'radio',
                                checked:!!a.useOwnImage,
                                onChange:()=>props.setAttributes({useOwnImage:true})
                            }),
                            ' ja'
                        ),
                        wp.element.createElement('label', {},
                            wp.element.createElement('input', {
                                type:'radio',
                                checked:!a.useOwnImage,
                                onChange:()=>props.setAttributes({useOwnImage:false})
                            }),
                            ' nee'
                        )
                    )
                ),
                a.useOwnImage && wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Beeld [eigen]'),
                    wp.element.createElement(MediaUploadCheck, {},
                        wp.element.createElement(MediaUpload, {
                            onSelect:media=>props.setAttributes({imageUrl:media.url}),
                            allowedTypes:['image'],
                            render:({open})=>wp.element.createElement('div', {className:'tc4-acf-image-field'},
                                a.imageUrl
                                    ? wp.element.createElement('img', {src:a.imageUrl, alt:'', onClick:open})
                                    : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, 'Afbeelding kiezen'),
                                a.imageUrl ? wp.element.createElement(Button, {onClick:()=>props.setAttributes({imageUrl:''}), variant:'link', isDestructive:true}, 'Verwijderen') : null
                            )
                        })
                    )
                ),
                !a.useOwnImage && wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Beeld [lijst]'),
                    wp.element.createElement('select', {
                        className:'tc4-acf-select',
                        value:a.calloutPreset || 'kompas',
                        onChange:e=>props.setAttributes({calloutPreset:e.target.value})
                    }, options.map(opt => wp.element.createElement('option', {key:opt.value, value:opt.value}, opt.label)))
                ),
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Titel'),
                    wp.element.createElement(TextControl, {
                        value:a.title || '',
                        onChange:v=>props.setAttributes({title:v})
                    })
                ),
                wp.element.createElement('div', {className:'tc4-acf-row tc4-acf-row-editor'},
                    wp.element.createElement('label', {}, 'Tekst'),
                    wp.element.createElement(ClassicHtmlEditor, {
                        value:a.text || '',
                        onChange:v=>props.setAttributes({text:v})
                    })
                ),
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, ''),
                    wp.element.createElement('fieldset', {className:'tc4-acf-button-box tc4-callout-button-box'},
                        wp.element.createElement('legend', {}, 'Knop'),
                        wp.element.createElement('div', {className:'tc4-acf-field'},
                            wp.element.createElement('label', {}, 'Tekst'),
                            wp.element.createElement(TextControl, {
                                value:a.buttonText || '',
                                onChange:v=>props.setAttributes({buttonText:v})
                            })
                        ),
                        wp.element.createElement('div', {className:'tc4-acf-button-grid'},
                            wp.element.createElement('div', {className:'tc4-acf-field'},
                                wp.element.createElement('label', {}, 'URL'),
                                wp.element.createElement(TextControl, {
                                    value:a.buttonUrl || '',
                                    onChange:v=>props.setAttributes({buttonUrl:v}),
                                    placeholder:'https://...'
                                })
                            ),
                            wp.element.createElement('div', {className:'tc4-acf-field'},
                                wp.element.createElement('label', {}, 'Kleur'),
                                colorRadios()
                            )
                        )
                    )
                )
            );
        },
        save: () => null
    });

    registerBlockType('scoutingtc4/news-items', {
        title: __('Nieuws items', 'scoutingtc4'),
        icon: 'welcome-write-blog',
        category: 'scoutingtc4',
        attributes: {
            title:{type:'string', default:'Nieuws'},
            categoryIds:{type:'array', default:[]},
            postIds:{type:'array', default:[]},
            count:{type:'number', default:3},
            buttonText:{type:'string', default:'Meer nieuws'},
            buttonUrl:{type:'string', default:''},
            buttonColor:{type:'string', default:'blauw'}
        },
        edit: (props) => {
            const a = props.attributes;
            const categories = (window.scoutingtc4Blocks && scoutingtc4Blocks.newsCategories) ? scoutingtc4Blocks.newsCategories : [];
            const posts = (window.scoutingtc4Blocks && scoutingtc4Blocks.newsPosts) ? scoutingtc4Blocks.newsPosts : [];

            const selectedCategories = Array.isArray(a.categoryIds) ? a.categoryIds : [];
            const selectedPosts = Array.isArray(a.postIds) ? a.postIds : [];

            const toggleCategory = (id) => {
                id = parseInt(id, 10);
                const next = selectedCategories.includes(id)
                    ? selectedCategories.filter(v => v !== id)
                    : selectedCategories.concat([id]);
                props.setAttributes({categoryIds: next});
            };

            const addPost = (id) => {
                id = parseInt(id, 10);
                if (!id || selectedPosts.includes(id)) return;
                props.setAttributes({postIds: selectedPosts.concat([id])});
            };

            const removePost = (id) => {
                id = parseInt(id, 10);
                props.setAttributes({postIds: selectedPosts.filter(v => v !== id)});
            };

            const categoryFilteredPosts = selectedCategories.length
                ? posts.filter(p => (p.categoryIds || []).some(cid => selectedCategories.includes(parseInt(cid, 10))))
                : posts;

            const selectedPostObjects = selectedPosts
                .map(id => posts.find(p => parseInt(p.id, 10) === parseInt(id, 10)))
                .filter(Boolean);

            const colorRadios = () => wp.element.createElement('div', {className:'tc4-editor-radio-row'},
                ['blauw','groen','zwart'].map(color =>
                    wp.element.createElement('label', {key:color},
                        wp.element.createElement('input', {
                            type:'radio',
                            checked:(a.buttonColor || 'blauw') === color,
                            onChange:()=>props.setAttributes({buttonColor:color})
                        }),
                        ' ' + color
                    )
                )
            );

            return wp.element.createElement('div', {className:'tc4-acf-like-block tc4-news-editor-form'},
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Titel'),
                    wp.element.createElement('div', {className:'tc4-news-category-box'},
                        categories.length ? categories.map(cat =>
                            wp.element.createElement('label', {key:cat.id, className:'tc4-news-checkbox'},
                                wp.element.createElement('input', {
                                    type:'checkbox',
                                    checked:selectedCategories.includes(parseInt(cat.id, 10)),
                                    onChange:()=>toggleCategory(cat.id)
                                }),
                                ' ' + cat.name
                            )
                        ) : wp.element.createElement('em', {}, 'Geen categorieën gevonden')
                    )
                ),
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Nieuwsitems'),
                    wp.element.createElement('div', {className:'tc4-news-select-wrap'},
                        wp.element.createElement('div', {className:'tc4-news-token-field'},
                            selectedPostObjects.map(post =>
                                wp.element.createElement('span', {key:post.id, className:'tc4-news-token'},
                                    '× ' + post.title,
                                    wp.element.createElement('button', {
                                        type:'button',
                                        onClick:()=>removePost(post.id),
                                        'aria-label':'Verwijder ' + post.title
                                    }, '×')
                                )
                            )
                        ),
                        wp.element.createElement('select', {
                            className:'tc4-acf-select',
                            value:'',
                            onChange:e=>{ addPost(e.target.value); e.target.value=''; }
                        },
                            wp.element.createElement('option', {value:''}, 'Nieuwsitem toevoegen…'),
                            categoryFilteredPosts
                                .filter(post => !selectedPosts.includes(parseInt(post.id, 10)))
                                .map(post => wp.element.createElement('option', {key:post.id, value:post.id}, post.title))
                        ),
                        wp.element.createElement('p', {className:'description'}, 'Kies eerst één of meer categorieën. De lijst met nieuwsitems wordt daarop gefilterd.')
                    )
                ),
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Aantal'),
                    wp.element.createElement(RangeControl, {
                        value:a.count || 3,
                        min:1,
                        max:6,
                        onChange:v=>props.setAttributes({count:v})
                    })
                ),
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Knop'),
                    wp.element.createElement('fieldset', {className:'tc4-acf-button-box tc4-news-button-box'},
                        wp.element.createElement('div', {className:'tc4-acf-field'},
                            wp.element.createElement('label', {}, 'Tekst'),
                            wp.element.createElement(TextControl, {
                                value:a.buttonText || '',
                                onChange:v=>props.setAttributes({buttonText:v})
                            })
                        ),
                        wp.element.createElement('div', {className:'tc4-acf-button-grid'},
                            wp.element.createElement('div', {className:'tc4-acf-field'},
                                wp.element.createElement('label', {}, 'URL'),
                                wp.element.createElement(TextControl, {
                                    value:a.buttonUrl || '',
                                    onChange:v=>props.setAttributes({buttonUrl:v}),
                                    placeholder:'https://...'
                                })
                            ),
                            wp.element.createElement('div', {className:'tc4-acf-field'},
                                wp.element.createElement('label', {}, 'Kleur'),
                                colorRadios()
                            )
                        )
                    )
                )
            );
        },
        save: () => null
    });

    registerBlockType('scoutingtc4/two-columns', {
        title: __('Twee kolommen', 'scoutingtc4'),
        icon: 'columns',
        category: 'scoutingtc4',
        attributes: {
            leftTitle:{type:'string', default:'Gratis sponsoren'},
            leftText:{type:'string', default:'Binnen onze Scoutinggroep hebben we sponsoracties om op die manier Scouting betaalbaar te maken voor iedereen.'},
            leftImageUrl:{type:'string', default:''},
            leftButtonText:{type:'string', default:'Lees hier meer!'},
            leftButtonUrl:{type:'string', default:''},
            leftButtonColor:{type:'string', default:'blauw'},
            rightTitle:{type:'string', default:'Vacatures'},
            rightText:{type:'string', default:'Meer weten over vrijwilligerswerk bij Scouting? Laat je verrassen door één van de uitdagende vacatures of lees meer over vrijwilliger worden bij Scouting.<ul><li>Welpenleiding</li><li>Secretaris</li></ul><h2>Maatschappelijke stage</h2><p>Iedere scholier krijgt er wel mee te maken: de maatschappelijke stage.</p>'},
            rightButtonText:{type:'string', default:'Meer vacatures'},
            rightButtonUrl:{type:'string', default:''},
            rightButtonColor:{type:'string', default:'zwart'}
        },
        edit: (props) => {
            const a = props.attributes;

            const radioColors = (value, onChange) => wp.element.createElement('div', {className:'tc4-editor-radio-row'},
                ['blauw','groen','zwart'].map(color =>
                    wp.element.createElement('label', {key:color},
                        wp.element.createElement('input', {
                            type:'radio',
                            checked:(value || 'blauw') === color,
                            onChange:()=>onChange(color)
                        }),
                        ' ' + color
                    )
                )
            );

            const buttonBox = (prefix, colorDefault) => {
                const textKey = prefix + 'ButtonText';
                const urlKey = prefix + 'ButtonUrl';
                const colorKey = prefix + 'ButtonColor';
                return wp.element.createElement('fieldset', {className:'tc4-acf-button-box tc4-two-button-box'},
                    wp.element.createElement('legend', {}, 'Knop'),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Tekst'),
                        wp.element.createElement(TextControl, {
                            value:a[textKey] || '',
                            onChange:v=>props.setAttributes({[textKey]:v})
                        })
                    ),
                    wp.element.createElement('div', {className:'tc4-acf-button-grid'},
                        wp.element.createElement('div', {className:'tc4-acf-field'},
                            wp.element.createElement('label', {}, 'URL'),
                            wp.element.createElement(TextControl, {
                                value:a[urlKey] || '',
                                onChange:v=>props.setAttributes({[urlKey]:v}),
                                placeholder:'https://...'
                            })
                        ),
                        wp.element.createElement('div', {className:'tc4-acf-field'},
                            wp.element.createElement('label', {}, 'Kleur'),
                            radioColors(a[colorKey] || colorDefault, v=>props.setAttributes({[colorKey]:v}))
                        )
                    )
                );
            };

            return wp.element.createElement('div', {className:'tc4-acf-like-block tc4-two-columns-editor-form'},
                wp.element.createElement('fieldset', {className:'tc4-two-column-fieldset'},
                    wp.element.createElement('legend', {}, 'Kolom 1 (headerbeeld + kop, tekst en knop)'),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Beeld'),
                        wp.element.createElement(MediaUploadCheck, {},
                            wp.element.createElement(MediaUpload, {
                                onSelect:media=>props.setAttributes({leftImageUrl:media.url}),
                                allowedTypes:['image'],
                                render:({open})=>wp.element.createElement('div', {className:'tc4-acf-image-field'},
                                    a.leftImageUrl
                                        ? wp.element.createElement('img', {src:a.leftImageUrl, alt:'', onClick:open})
                                        : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, 'Afbeelding kiezen'),
                                    a.leftImageUrl ? wp.element.createElement(Button, {onClick:()=>props.setAttributes({leftImageUrl:''}), variant:'link', isDestructive:true}, 'Verwijderen') : null
                                )
                            })
                        )
                    ),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Kop'),
                        wp.element.createElement(TextControl, {
                            value:a.leftTitle || '',
                            onChange:v=>props.setAttributes({leftTitle:v})
                        })
                    ),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Tekst'),
                        wp.element.createElement(TextareaControl, {
                            value:a.leftText || '',
                            onChange:v=>props.setAttributes({leftText:v}),
                            rows:7
                        })
                    ),
                    buttonBox('left', 'blauw')
                ),

                wp.element.createElement('fieldset', {className:'tc4-two-column-fieldset'},
                    wp.element.createElement('legend', {}, 'Kolom 2 (kop, WYSIWYG en knop)'),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Kop'),
                        wp.element.createElement(TextControl, {
                            value:a.rightTitle || '',
                            onChange:v=>props.setAttributes({rightTitle:v})
                        })
                    ),
                    wp.element.createElement('div', {className:'tc4-acf-field'},
                        wp.element.createElement('label', {}, 'Tekst'),
                        wp.element.createElement(ClassicHtmlEditor, {
                            value:a.rightText || '',
                            onChange:v=>props.setAttributes({rightText:v})
                        })
                    ),
                    buttonBox('right', 'zwart')
                )
            );
        },
        save: () => null
    });

})(window.wp);
