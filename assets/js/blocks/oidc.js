(function(wp, scoutingNederland){
    const {
        registerBlockType,
        __,
        Fragment,
        PanelBody,
        SelectControl,
        TextControl,
        ToggleControl
    } = scoutingNederland;

    const ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender;
    const InspectorControls = (wp.blockEditor || wp.editor).InspectorControls;

    registerBlockType('scouting-nederland/oidc', {
        title: __('Scouting OIDC', 'scouting-nederland'),
        description: __('Voeg een Scouting OpenID Connect login toe.', 'scouting-nederland'),
        icon: 'admin-users',
        category: 'scouting-nederland',
        attributes: {
            displayType: {type:'string', default:'button'},
            buttonText: {type:'string', default:'Inloggen met Scouting'},
            alignCenter: {type:'boolean', default:false}
        },
        edit: (props) => {
            const a = props.attributes;

            return wp.element.createElement(Fragment, {},
                wp.element.createElement(InspectorControls, {},
                    wp.element.createElement(PanelBody, {title: __('Scouting OIDC instellingen', 'scouting-nederland'), initialOpen: true},
                        wp.element.createElement(SelectControl, {
                            label: __('Weergave', 'scouting-nederland'),
                            value: a.displayType || 'button',
                            options: [
                                {label: __('Login-knop', 'scouting-nederland'), value: 'button'},
                                {label: __('Tekstlink', 'scouting-nederland'), value: 'link'}
                            ],
                            onChange: value => props.setAttributes({displayType:value})
                        }),
                        (a.displayType || 'button') === 'link' && wp.element.createElement(TextControl, {
                            label: __('Linktekst', 'scouting-nederland'),
                            value: a.buttonText || '',
                            onChange: value => props.setAttributes({buttonText:value})
                        }),
                        wp.element.createElement(ToggleControl, {
                            label: __('Centreren', 'scouting-nederland'),
                            checked: !!a.alignCenter,
                            onChange: value => props.setAttributes({alignCenter:value})
                        })
                    )
                ),
                wp.element.createElement('div', {className:'scouting-nederland-acf-like-block scouting-nederland-oidc-editor-form'},
                    wp.element.createElement('h3', {}, __('Scouting OIDC', 'scouting-nederland')),
                    wp.element.createElement('p', {}, __('Dit blok toont de login van de plugin Scouting OpenID Connect.', 'scouting-nederland')),
                    ServerSideRender
                        ? wp.element.createElement(ServerSideRender, {
                            block: 'scouting-nederland/oidc',
                            attributes: a
                        })
                        : wp.element.createElement('p', {}, __('Voorbeeld niet beschikbaar.', 'scouting-nederland'))
                )
            );
        },
        save: () => null
    });

})(globalThis.wp, globalThis.scoutingNederlandBlockEditor);
