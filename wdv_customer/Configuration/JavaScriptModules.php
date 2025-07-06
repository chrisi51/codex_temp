<?php

return [
    'dependencies' => ['backend'],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@wx/ckeditor/customFontColors' => 'EXT:wdv_customer/Resources/Public/JavaScript/rte-plugins/customFontColors/plugin.js',
        '@wdv/wdv_customer/timestamp-plugin.js' => 'EXT:wdv_customer/Resources/Public/JavaScript/rte-plugins/timestamp/timestamp-plugin.js',
    ],
];