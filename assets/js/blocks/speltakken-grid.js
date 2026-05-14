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

registerBlockType('scoutingtc4/speltakken-grid', {
        title: __('Speltakken grid', 'scoutingtc4'),
        icon: 'grid-view',
        category: 'scoutingtc4',
        attributes: {
            title: {type:'string', default:''},
            items: {type:'array', default:[
                {title:'Bevers', text:'Voor kinderen van 5 tot 7 jaar: samen spelen, fantaseren en ontdekken.', imageUrl:'', buttonText:'Meer info', buttonUrl:'', buttonTarget:''},
                {title:'Welpen', text:'Voor kinderen van 7 tot 11 jaar: avonturen beleven in de jungle.', imageUrl:'', buttonText:'Meer info', buttonUrl:'', buttonTarget:''},
                {title:'Scouts', text:'Voor jongeren van 11 tot 15 jaar: buitenactiviteiten, samenwerken en nieuwe skills leren.', imageUrl:'', buttonText:'Meer info', buttonUrl:'', buttonTarget:''}
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

            return wp.element.createElement('div', {className:'tc4-acf-like-block tc4-speltakken-editor-form'},
                wp.element.createElement('div', {className:'tc4-acf-row'},
                    wp.element.createElement('label', {}, 'Kop'),
                    wp.element.createElement(TextControl, {
                        value:attrs.title || '',
                        onChange:v=>props.setAttributes({title:v}),
                        placeholder:'Optionele algemene kop, bijvoorbeeld Speltakken'
                    })
                ),
                wp.element.createElement('div', {className:'tc4-acf-section-title'}, 'Kaarten'),
                wp.element.createElement('div', {className:'tc4-acf-repeater'},
                    [0,1,2].map(i => {
                        const item = items[i] || {};
                        return wp.element.createElement('div', {className:'tc4-acf-repeater-row', key:i},
                            wp.element.createElement('div', {className:'tc4-acf-row-number'}, i + 1),
                            wp.element.createElement('div', {className:'tc4-acf-row-content'},
                                wp.element.createElement('div', {className:'tc4-acf-field'},
                                    wp.element.createElement('label', {}, 'Afbeelding'),
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
                                    wp.element.createElement('label', {}, 'Titel'),
                                    wp.element.createElement(TextControl, {
                                        value:item.title || '',
                                        onChange:v=>setItem(i,'title',v)
                                    })
                                ),
                                wp.element.createElement('div', {className:'tc4-acf-field'},
                                    wp.element.createElement('label', {}, 'Korte tekst'),
                                    wp.element.createElement(TextareaControl, {
                                        value:item.text || '',
                                        rows:4,
                                        onChange:v=>setItem(i,'text',v)
                                    })
                                ),
                                wp.element.createElement('fieldset', {className:'tc4-acf-button-box'},
                                    wp.element.createElement('legend', {}, 'Knop'),
                                    wp.element.createElement('p', {className:'tc4-acf-help'}, 'De knoptekst is altijd “Meer info”. Vul alleen een link in om de knop te tonen.'),
                                    wp.element.createElement('div', {className:'tc4-acf-field'},
                                        wp.element.createElement('label', {}, 'URL'),
                                        wp.element.createElement(LinkPickerField, {
                                            url:item.buttonUrl || '',
                                            text:'Meer info',
                                            target:item.buttonTarget || '',
                                            onChange:link=>{
                                                const next = items.slice();
                                                next[i] = Object.assign({}, next[i] || {}, {
                                                    buttonText: 'Meer info',
                                                    buttonUrl: link.url || '',
                                                    buttonTarget: link.target || ''
                                                });
                                                props.setAttributes({items: next});
                                            }
                                        })
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

})(window.wp, window.scoutingtc4BlockEditor);
