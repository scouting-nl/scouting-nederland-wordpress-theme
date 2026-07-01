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

registerBlockType('scouting-nederland/featured-items', {
        title: __('Featured items', 'scouting-nederland'),
        icon: 'screenoptions',
        category: 'scouting-nederland',
        attributes: {
            title: {type:'string', default:''},
            items: {type:'array', default:[
                {title:'Wie zijn wij?', text:'Scouting staat voor uitdaging! Scouting biedt leuke en spannende activiteiten waarmee meiden en jongens worden uitgedaagd zich persoonlijk te ontwikkelen.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonColor:'blauw'},
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

            const colorRadios = (i, item) => wp.element.createElement('div', {className:'scouting-nederland-editor-radio-row'},
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

            return wp.element.createElement('div', {className:'scouting-nederland-acf-like-block scouting-nederland-featured-editor-form'},
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Kop'),
                    wp.element.createElement(TextControl, {
                        value:attrs.title || '',
                        onChange:v=>props.setAttributes({title:v}),
                        placeholder:'Optionele algemene kop'
                    })
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-section-title'}, 'Items'),
                wp.element.createElement('div', {className:'scouting-nederland-acf-repeater'},
                    [0,1,2].map(i => {
                        const item = items[i] || {};
                        return wp.element.createElement('div', {className:'scouting-nederland-acf-repeater-row', key:i},
                            wp.element.createElement('div', {className:'scouting-nederland-acf-row-number'}, i + 1),
                            wp.element.createElement('div', {className:'scouting-nederland-acf-row-content'},
                                wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                    wp.element.createElement('label', {}, 'Kop'),
                                    wp.element.createElement(TextControl, {
                                        value:item.title || '',
                                        onChange:v=>setItem(i,'title',v)
                                    })
                                ),
                                wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                    wp.element.createElement('label', {}, 'Beeld'),
                                    wp.element.createElement(MediaUploadCheck, {},
                                        wp.element.createElement(MediaUpload, {
                                            onSelect:media=>setItem(i,'imageUrl',media.url),
                                            allowedTypes:['image'],
                                            render:({open})=>wp.element.createElement('div', {className:'scouting-nederland-acf-image-field'},
                                                item.imageUrl
                                                    ? wp.element.createElement('img', {src:item.imageUrl, alt:'', onClick:open})
                                                    : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, 'Afbeelding kiezen'),
                                                item.imageUrl ? wp.element.createElement(Button, {onClick:()=>setItem(i,'imageUrl',''), variant:'link', isDestructive:true}, 'Verwijderen') : null
                                            )
                                        })
                                    )
                                ),
                                wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                    wp.element.createElement('label', {}, 'Tekst'),
                                    wp.element.createElement(ClassicHtmlEditor, {
                                        value:item.text || '',
                                        onChange:v=>setItem(i,'text',v)
                                    })
                                ),
                                wp.element.createElement('fieldset', {className:'scouting-nederland-acf-button-box'},
                                    wp.element.createElement('legend', {}, 'Knop'),
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                        wp.element.createElement('label', {}, 'Tekst'),
                                        wp.element.createElement(TextControl, {
                                            value:item.buttonText || '',
                                            onChange:v=>setItem(i,'buttonText',v)
                                        })
                                    ),
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-button-grid'},
                                        wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                            wp.element.createElement('label', {}, 'URL'),
                                            wp.element.createElement(LinkPickerField, {
                                                url:item.buttonUrl || '',
                                                text:item.buttonText || '',
                                                target:item.buttonTarget || '',
                                                onChange:link=>{
                                                    const next = items.slice();
                                                    next[i] = Object.assign({}, next[i] || {}, {
                                                        buttonUrl: link.url || '',
                                                        buttonText: link.text || item.buttonText || '',
                                                        buttonTarget: link.target || ''
                                                    });
                                                    props.setAttributes({items: next});
                                                }
                                            })
                                        ),
                                        wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
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

})(globalThis.wp, globalThis.scoutingNederlandBlockEditor);
