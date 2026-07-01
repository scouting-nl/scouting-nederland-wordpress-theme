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

registerBlockType('scouting-nederland/callout', {
        title: __('Callout', 'scouting-nederland'),
        icon: 'megaphone',
        category: 'scouting-nederland',
        attributes: {
            title:{type:'string', default:'Vrijwilligers gezocht'},
            text:{type:'string', default:'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!'},
            buttonText:{type:'string', default:'Meld je aan'},
            buttonUrl:{type:'string', default:''},
            buttonTarget:{type:'string', default:''},
            buttonColor:{type:'string', default:'zwart'},
            imageUrl:{type:'string', default:''},
            calloutPreset:{type:'string', default:'kompas'},
            useOwnImage:{type:'boolean', default:false}
        },
        edit: (props) => {
            const a = props.attributes;
            const options = (globalThis.scoutingNederlandBlocks && scoutingNederlandBlocks.calloutOptions) ? scoutingNederlandBlocks.calloutOptions : [
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

            const colorRadios = () => wp.element.createElement('div', {className:'scouting-nederland-editor-radio-row'},
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

            return wp.element.createElement('div', {className:'scouting-nederland-acf-like-block scouting-nederland-callout-editor-form'},
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Eigen beeld gebruiken'),
                    wp.element.createElement('div', {className:'scouting-nederland-editor-radio-row scouting-nederland-editor-radio-inline'},
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
                a.useOwnImage && wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Beeld [eigen]'),
                    wp.element.createElement(MediaUploadCheck, {},
                        wp.element.createElement(MediaUpload, {
                            onSelect:media=>props.setAttributes({imageUrl:media.url}),
                            allowedTypes:['image'],
                            render:({open})=>wp.element.createElement('div', {className:'scouting-nederland-acf-image-field'},
                                a.imageUrl
                                    ? wp.element.createElement('img', {src:a.imageUrl, alt:'', onClick:open})
                                    : wp.element.createElement(Button, {onClick:open, variant:'secondary'}, 'Afbeelding kiezen'),
                                a.imageUrl ? wp.element.createElement(Button, {onClick:()=>props.setAttributes({imageUrl:''}), variant:'link', isDestructive:true}, 'Verwijderen') : null
                            )
                        })
                    )
                ),
                !a.useOwnImage && wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Beeld [lijst]'),
                    wp.element.createElement('select', {
                        className:'scouting-nederland-acf-select',
                        value:a.calloutPreset || 'kompas',
                        onChange:e=>props.setAttributes({calloutPreset:e.target.value})
                    }, options.map(opt => wp.element.createElement('option', {key:opt.value, value:opt.value}, opt.label)))
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, 'Titel'),
                    wp.element.createElement(TextControl, {
                        value:a.title || '',
                        onChange:v=>props.setAttributes({title:v})
                    })
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-row scouting-nederland-acf-row-editor'},
                    wp.element.createElement('label', {}, 'Tekst'),
                    wp.element.createElement(ClassicHtmlEditor, {
                        value:a.text || '',
                        onChange:v=>props.setAttributes({text:v})
                    })
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-row'},
                    wp.element.createElement('label', {}, ''),
                    wp.element.createElement('fieldset', {className:'scouting-nederland-acf-button-box scouting-nederland-callout-button-box'},
                        wp.element.createElement('legend', {}, 'Knop'),
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
