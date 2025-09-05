(function() {
    tinymce.PluginManager.add('gavickpro_tc_button', function( editor, url ) {
        editor.addButton( 'gavickpro_tc_button', {
            title: 'Custom Variable',
            text: 'Email Custom Variable',
            type: 'menubutton',
            icon: 'icon gavickpro-own-icon',
            menu: [
                {
                    text: "First Name",
                    value: '{first_name}',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Last Name',
                    value: '{last_name}',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Email',
                    value: '{email}',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },

            ]
        });
    });
})();