/*
* Icomoon Browser Plugin
*
* @author David Thenon <dthenon@emencia.com>
* @version 1.0.0
*
* Require ckeditor >=4.5.0 and ajax + xml plugin modules
*
* Plugin configuration variables "icomoon_manifest_path" is required to be set
* in editor configuration. Also "icomoon_css_path" is needed to stylize the
* plugin in classic editor mode (iframe).
*
*/
(function () {
    CKEDITOR.on('instanceReady',function () { CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('icomoon') + 'dialogs/browser.css');   });
    CKEDITOR.dtd.$removeEmpty.span = 0;
    CKEDITOR.plugins.add('icomoon', {
        icons: 'icomoon',
        requires: 'xml,ajax',

        //
        // Prepare data before plugin initialization
        //
        beforeInit: function( editor ) {
            // Load manifest JSON file so it will be available within dialogs
            // from a plugin config variable
            if (!editor.config.icomoon_manifest) {
                console.log("Get manifest:", editor.config.icomoon_manifest_path);
                const data = CKEDITOR.ajax.load(editor.config.icomoon_manifest_path);
                editor.config.icomoon_manifest = JSON.parse(data);
            }
        },

        //
        // Initialize plugin
        //
        init: function( editor ) {
            // Assign dialog to plugin reference
            editor.addCommand('icomoon',
                              new CKEDITOR.dialogCommand('icomoonBrowserDialog'));
            // Create a button in editor toolbar
            editor.ui.addButton( 'Icomoon', {
                label: 'Icomoon',
                command: 'icomoon',
                toolbar: 'insert',
                icon : this.path + 'images/icon.svg'
            });

            // Declare the plugin stylesheet if any
            if (editor.config.icomoon_css_path) {
                CKEDITOR.document.appendStyleSheet(editor.config.icomoon_css_path);
            }

            // Load the dialogs
            CKEDITOR.dialog.add('icomoonBrowserDialog',
                                this.path + 'dialogs/browser.js');

        }
    });
})();
