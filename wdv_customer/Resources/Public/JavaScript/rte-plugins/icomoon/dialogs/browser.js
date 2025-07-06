/*
 * Icomoon browser dialog
 */

var IcomoonBrowserMethods = {
//
// Build icomoon browser items from manifest
//
    'build_items': function(manifest) {
        var output = '',
            prefix = manifest.preferences.fontPref.prefix;

        console.log("IcomoonBrowserMethods.build_items");
        console.log(manifest);
        for (var i = 0; i < manifest.icons.length; i++) {
            let item = manifest.icons[i],
                name = item.properties.name,
                code = item.properties.code,
                class_name = name,
                button = '<button data-icon-class="' + prefix+class_name + '" onclick="IcomoonBrowserMethods.select(this);return false;">' +
                        '<i class="' + prefix+class_name + '"></i>' +
                        '<span>' + name + '</span>' +
                        '</button>';

                output += button;
        }

        return output;
    },

    //
    // Clean browser from every selection by setting button attribute to false
    //
    'reset': function() {
        console.log("IcomoonBrowserMethods.reset");
        const $buttons = document.querySelectorAll('#icomoon-browser button');
        if ($buttons.length > 0) {
            $buttons.forEach(function(itemObject, itemIndex) {
                itemObject.setAttribute('data-browser-selection', 'false');
                itemObject.classList.remove('selected');
            });
        }
    },

    //
    // Set button attribute to select an icon
    //
    'select': function(el) {
        console.log("IcomoonBrowserMethods.select");
        // Ensure there will be only one selection
        IcomoonBrowserMethods.reset();
        // Set to true
        el.setAttribute('data-browser-selection', 'true');
        el.classList.add('selected');
    },

    //
    // Get selected button if any
    //
    'get_selection': function(el) {
        console.log("IcomoonBrowserMethods.select");
        const $selection = document.querySelector('#icomoon-browser button[data-browser-selection="true"]');

        return $selection;
    }
};


/*
 * Browser dialog definition
 *
 * NOTE: Icon choices could have been builded with dialog element type 'radio'
 * but instead is build as an HTML list of buttons, which are easier to build
 * and use. Finally, we have to use querySelector methods to reach the selected
 * choice.
 */
CKEDITOR.dialog.add('icomoonBrowserDialog', function(editor) {
    return {
        title: 'Icomoon browser',
        minWidth: 600,
        minHeight: 300,

        //
        // Add dialog form and content structure
        //
        contents: [
            {
                id: 'tab-selection',
                label: 'Selection',
                elements: [
                    {
                        type: 'html',
                        html: '<h5>' + 'Select an icon to insert' + '</h5><hr>'
                    },
                    {
                        type: 'html',
                        html: '<div id="icomoon-browser">' + IcomoonBrowserMethods.build_items(editor.config.icomoon_manifest) + '</div>'
                    }
                ]
            }
        ],

        //
        // Triggered event when dialog is displayed
        //
        onShow: function() {
            console.log("icomoonBrowserDialog.onShow");
        },

        //
        // Triggered event when dialog button "ok" is clicked
        //
        onOk: function() {
            console.log("icomoonBrowserDialog.onOk");
            const dialog = this,
                $selection = IcomoonBrowserMethods.get_selection();

            if ($selection) {
                console.log("icomoonBrowserDialog.onOk insert selection");
                // Build element to insert
                var icon_marker = editor.document.createElement('span');
                icon_marker.setAttribute(
                    'class',
                    $selection.getAttribute('data-icon-class')
                );
                icon_marker.setHtml('');

                // Insert element
                editor.insertElement(icon_marker);
                console.log("icomoonBrowserDialog.onOk end");
            }
        }
    };
});
