(function(wp, scoutingNederland){
    const {
        registerBlockType,
        __,
        Fragment,
        InspectorControls,
        MediaUpload,
        MediaUploadCheck,
        RichText,
        URLInputButton,
        PanelBody,
        TextControl,
        TextareaControl,
        Button,
        RangeControl,
        SelectControl,
        ToggleControl,
        ButtonFields,
        InlineImage,
        btnClass,
        StableClassicEditor,
        ClassicHtmlEditor,
        LinkPickerField
    } = scoutingNederland;

registerBlockType('scouting-nederland/news-items', {
        title: __('Nieuws items', 'scouting-nederland'),
        icon: 'welcome-write-blog',
        category: 'scouting-nederland',
        attributes: {
            title:{type:'string', default:'Nieuws'},
            categoryIds:{type:'array', default:[]},
            postIds:{type:'array', default:[]},
            count:{type:'number', default:3},
            buttonText:{type:'string', default:'Meer nieuws'},
            buttonUrl:{type:'string', default:''},
            buttonTarget:{type:'string', default:''},
            buttonColor:{type:'string', default:'blauw'}
        },
        edit: (props) => {
            const a = props.attributes;
            const categories = (globalThis.scoutingNederlandBlocks && scoutingNederlandBlocks.newsCategories) ? scoutingNederlandBlocks.newsCategories : [];
            const posts = (globalThis.scoutingNederlandBlocks && scoutingNederlandBlocks.newsPosts) ? scoutingNederlandBlocks.newsPosts : [];

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

            const colorRadios = () => wp.element.createElement('div', {className:'scouting-nederland-editor-radio-row'},
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

            return wp.element.createElement('div', {className:'scouting-nederland-acf-like-block scouting-nederland-news-editor-form'},
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Titel'),
                    wp.element.createElement('div', {className:'scouting-nederland-news-category-box'},
                        categories.length ? categories.map(cat =>
                            wp.element.createElement('label', {key:cat.id, className:'scouting-nederland-news-checkbox'},
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
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Nieuwsitems'),
                    wp.element.createElement('div', {className:'scouting-nederland-news-select-wrap'},
                        wp.element.createElement('div', {className:'scouting-nederland-news-token-field'},
                            selectedPostObjects.map(post =>
                                wp.element.createElement('span', {key:post.id, className:'scouting-nederland-news-token'},
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
                            className:'scouting-nederland-acf-select',
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
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Aantal'),
                    wp.element.createElement(RangeControl, {
                        value:a.count || 3,
                        min:1,
                        max:6,
                        onChange:v=>props.setAttributes({count:v})
                    })
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Knop'),
                    wp.element.createElement('fieldset', {className:'scouting-nederland-acf-button-box scouting-nederland-news-button-box'},
                        wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                            wp.element.createElement('label', {}, 'Tekst'),
                            wp.element.createElement(TextControl, {
                                value:a.buttonText || '',
                                onChange:v=>props.setAttributes({buttonText:v})
                            })
                        ),
                        wp.element.createElement('div', {className:'scouting-nederland-acf-button-grid'},
                            wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                wp.element.createElement('label', {}, 'URL'),
                                wp.element.createElement(LinkPickerField, {
                                    url:a.buttonUrl || '',
                                    text:a.buttonText || '',
                                    target:a.buttonTarget || '',
                                    onUrl:v=>props.setAttributes({buttonUrl:v}),
                                    onText:v=>props.setAttributes({buttonText:v}),
                                    onTarget:v=>props.setAttributes({buttonTarget:v})
                                })
                            ),
                            wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
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

})(globalThis.wp, globalThis.scoutingNederlandBlockEditor);
