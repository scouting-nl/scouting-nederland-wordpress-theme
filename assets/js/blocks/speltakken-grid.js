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

registerBlockType('scouting-nederland/speltakken-grid', {
        title: __('Speltakken grid', 'scouting-nederland'),
        icon: 'grid-view',
        category: 'scouting-nederland',
        attributes: {
            title: {type:'string', default:''},
            items: {type:'array', default:[
                {title:'Bevers', text:'Voor kinderen van 5 tot 7 jaar: samen spelen, fantaseren en ontdekken.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''},
                {title:'Welpen', text:'Voor kinderen van 7 tot 11 jaar: avonturen beleven in de jungle.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''},
                {title:'Scouts', text:'Voor jongeren van 11 tot 15 jaar: buitenactiviteiten, samenwerken en nieuwe skills leren.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''}
            ]}
        },
        edit: (props) => {
            const attrs = props.attributes;
            const defaultItem = {title:'', text:'', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''};
            const items = Array.isArray(attrs.items) ? attrs.items : [];
            const normalizedItems = items.length ? items : [
                {title:'Bevers', text:'Voor kinderen van 5 tot 7 jaar: samen spelen, fantaseren en ontdekken.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''},
                {title:'Welpen', text:'Voor kinderen van 7 tot 11 jaar: avonturen beleven in de jungle.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''},
                {title:'Scouts', text:'Voor jongeren van 11 tot 15 jaar: buitenactiviteiten, samenwerken en nieuwe skills leren.', imageUrl:'', buttonText:'Meer informatie', buttonUrl:'', buttonTarget:''}
            ];

            const updateItems = (next) => props.setAttributes({items: next});
            const setItem = (i, key, val) => {
                const next = normalizedItems.slice();
                next[i] = Object.assign({}, defaultItem, next[i] || {}, {[key]: val});
                updateItems(next);
            };
            const addItem = () => {
                updateItems(normalizedItems.concat([Object.assign({}, defaultItem)]));
            };
            const removeItem = (i) => {
                const next = normalizedItems.filter((item, index) => index !== i);
                updateItems(next.length ? next : [Object.assign({}, defaultItem)]);
            };
            const moveItem = (i, direction) => {
                const newIndex = i + direction;
                if (newIndex < 0 || newIndex >= normalizedItems.length) {
                    return;
                }
                const next = normalizedItems.slice();
                const current = next[i];
                next[i] = next[newIndex];
                next[newIndex] = current;
                updateItems(next);
            };

            const addButton = wp.element.createElement(Button, {
                onClick:addItem,
                isPrimary:true,
                className:'scouting-nederland-speltakken-add-button'
            }, '+ Speltak toevoegen');

            return wp.element.createElement(Fragment, {},
                wp.element.createElement(InspectorControls, {},
                    wp.element.createElement(PanelBody, {title:'Speltakken', initialOpen:true},
                        wp.element.createElement('p', {}, 'Aantal speltakken: ' + normalizedItems.length),
                        wp.element.createElement(Button, {onClick:addItem, isPrimary:true}, '+ Speltak toevoegen')
                    )
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-like-block scouting-nederland-speltakken-editor-form'},
                    wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                        wp.element.createElement('label', {}, 'Kop'),
                        wp.element.createElement(TextControl, {
                            value:attrs.title || '',
                            onChange:v=>props.setAttributes({title:v}),
                            placeholder:'Optionele algemene kop, bijvoorbeeld Speltakken'
                        })
                    ),
                    wp.element.createElement('div', {className:'scouting-nederland-acf-section-title scouting-nederland-acf-section-title-with-action'},
                        wp.element.createElement('span', {}, 'Kaarten (' + normalizedItems.length + ')'),
                        addButton
                    ),
                    wp.element.createElement('div', {className:'scouting-nederland-acf-repeater'},
                        normalizedItems.map((item, i) => {
                            item = Object.assign({}, defaultItem, item || {});
                            return wp.element.createElement('div', {className:'scouting-nederland-acf-repeater-row', key:i},
                                wp.element.createElement('div', {className:'scouting-nederland-acf-row-number'}, i + 1),
                                wp.element.createElement('div', {className:'scouting-nederland-acf-row-content'},
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-row-actions'},
                                        wp.element.createElement(Button, {onClick:()=>moveItem(i, -1), isSecondary:true, disabled:i === 0}, 'Omhoog'),
                                        wp.element.createElement(Button, {onClick:()=>moveItem(i, 1), isSecondary:true, disabled:i === normalizedItems.length - 1}, 'Omlaag'),
                                        wp.element.createElement(Button, {onClick:()=>removeItem(i), isDestructive:true, isSecondary:true}, 'Speltak verwijderen')
                                    ),
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                        wp.element.createElement('label', {}, 'Afbeelding'),
                                        wp.element.createElement(MediaUploadCheck, {},
                                            wp.element.createElement(MediaUpload, {
                                                onSelect:media=>setItem(i,'imageUrl',media.url),
                                                allowedTypes:['image'],
                                                render:({open})=>wp.element.createElement('div', {className:'scouting-nederland-acf-image-field'},
                                                    item.imageUrl
                                                        ? wp.element.createElement('img', {src:item.imageUrl, alt:'', onClick:open})
                                                        : wp.element.createElement(Button, {onClick:open, isSecondary:true}, 'Afbeelding kiezen'),
                                                    item.imageUrl ? wp.element.createElement(Button, {onClick:()=>setItem(i,'imageUrl',''), isDestructive:true, isLink:true}, 'Afbeelding verwijderen') : null
                                                )
                                            })
                                        )
                                    ),
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                        wp.element.createElement('label', {}, 'Titel'),
                                        wp.element.createElement(TextControl, {
                                            value:item.title || '',
                                            onChange:v=>setItem(i,'title',v)
                                        })
                                    ),
                                    wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                        wp.element.createElement('label', {}, 'Korte tekst'),
                                        wp.element.createElement(TextareaControl, {
                                            value:item.text || '',
                                            rows:4,
                                            onChange:v=>setItem(i,'text',v)
                                        })
                                    ),
                                    wp.element.createElement('fieldset', {className:'scouting-nederland-acf-button-box'},
                                        wp.element.createElement('legend', {}, 'Knop'),
                                        wp.element.createElement('p', {className:'scouting-nederland-acf-help'}, 'De knoptekst is altijd “Meer informatie”. Vul alleen een link in om de knop te tonen.'),
                                        wp.element.createElement('div', {className:'scouting-nederland-acf-field'},
                                            wp.element.createElement('label', {}, 'URL'),
                                            wp.element.createElement(LinkPickerField, {
                                                url:item.buttonUrl || '',
                                                text:'Meer informatie',
                                                target:item.buttonTarget || '',
                                                onChange:link=>{
                                                    const next = normalizedItems.slice();
                                                    next[i] = Object.assign({}, defaultItem, next[i] || {}, {
                                                        buttonText: 'Meer informatie',
                                                        buttonUrl: link.url || '',
                                                        buttonTarget: link.target || ''
                                                    });
                                                    updateItems(next);
                                                }
                                            })
                                        )
                                    )
                                )
                            );
                        }),
                        wp.element.createElement('div', {className:'scouting-nederland-acf-repeater-add'},
                            wp.element.createElement(Button, {onClick:addItem, isPrimary:true}, '+ Speltak toevoegen')
                        )
                    )
                )
            );
        },
        save: () => null
    });

})(globalThis.wp, globalThis.scoutingNederlandBlockEditor);
