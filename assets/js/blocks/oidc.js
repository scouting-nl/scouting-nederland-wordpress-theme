(function(wp, tc4){
    const {
        registerBlockType,
        __,
        Fragment,
        PanelBody,
        SelectControl,
        TextControl,
        ToggleControl
    } = tc4;

    const ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender;
    const InspectorControls = (wp.blockEditor || wp.editor).InspectorControls;

    registerBlockType('scoutingtc4/oidc', {
        title: __('Scouting OIDC', 'scoutingtc4'),
        description: __('Voeg een Scouting OpenID Connect login toe.', 'scoutingtc4'),
        icon: 'admin-users',
        category: 'scoutingtc4',
        attributes: {
            displayType: {type:'string', default:'button'},
            buttonText: {type:'string', default:'Inloggen met Scouting'},
            alignCenter: {type:'boolean', default:false}
        },
        edit: (props) => {
            const a = props.attributes;

            return wp.element.createElement(Fragment, {},
                wp.element.createElement(InspectorControls, {},
                    wp.element.createElement(PanelBody, {title: __('Scouting OIDC instellingen', 'scoutingtc4'), initialOpen: true},
                        wp.element.createElement(SelectControl, {
                            label: __('Weergave', 'scoutingtc4'),
                            value: a.displayType || 'button',
                            options: [
                                {label: __('Login-knop', 'scoutingtc4'), value: 'button'},
                                {label: __('Tekstlink', 'scoutingtc4'), value: 'link'}
                            ],
                            onChange: value => props.setAttributes({displayType:value})
                        }),
                        (a.displayType || 'button') === 'link' && wp.element.createElement(TextControl, {
                            label: __('Linktekst', 'scoutingtc4'),
                            value: a.buttonText || '',
                            onChange: value => props.setAttributes({buttonText:value})
                        }),
                        wp.element.createElement(ToggleControl, {
                            label: __('Centreren', 'scoutingtc4'),
                            checked: !!a.alignCenter,
                            onChange: value => props.setAttributes({alignCenter:value})
                        })
                    )
                ),
                wp.element.createElement('div', {className:'tc4-acf-like-block tc4-oidc-editor-form'},
                    wp.element.createElement('h3', {}, __('Scouting OIDC', 'scoutingtc4')),
                    wp.element.createElement('p', {}, __('Dit blok toont de login van de plugin Scouting OpenID Connect.', 'scoutingtc4')),
                    ServerSideRender
                        ? wp.element.createElement(ServerSideRender, {
                            block: 'scoutingtc4/oidc',
                            attributes: a
                        })
                        : wp.element.createElement('p', {}, __('Voorbeeld niet beschikbaar.', 'scoutingtc4'))
                )
            );
        },
        save: () => null
    });

})(window.wp, window.scoutingtc4BlockEditor);
