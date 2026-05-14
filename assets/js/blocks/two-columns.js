(function(wp, tc4){
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
    } = tc4;

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
            leftButtonTarget:{type:'string', default:''},
            leftButtonColor:{type:'string', default:'blauw'},
            rightTitle:{type:'string', default:'Vacatures'},
            rightText:{type:'string', default:'Meer weten over vrijwilligerswerk bij Scouting? Laat je verrassen door één van de uitdagende vacatures of lees meer over vrijwilliger worden bij Scouting.<ul><li>Welpenleiding</li><li>Secretaris</li></ul><h2>Maatschappelijke stage</h2><p>Iedere scholier krijgt er wel mee te maken: de maatschappelijke stage.</p>'},
            rightButtonText:{type:'string', default:'Meer vacatures'},
            rightButtonUrl:{type:'string', default:''},
            rightButtonTarget:{type:'string', default:''},
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
                            wp.element.createElement(LinkPickerField, {
                                url:a[urlKey] || '',
                                text:a[textKey] || '',
                                target:a[prefix + 'ButtonTarget'] || '',
                                onUrl:v=>props.setAttributes({[urlKey]:v}),
                                onText:v=>props.setAttributes({[textKey]:v}),
                                onTarget:v=>props.setAttributes({[prefix + 'ButtonTarget']:v})
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
                        wp.element.createElement(ClassicHtmlEditor, {
                            value:a.leftText || '',
                            onChange:v=>props.setAttributes({leftText:v})
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

})(window.wp, window.scoutingtc4BlockEditor);
