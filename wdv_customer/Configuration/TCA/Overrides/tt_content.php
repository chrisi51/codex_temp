<?php

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die('Access denied.');

// Content-Element "Unterseiten mit Anzahl von News (Filter)" zur Typenauswahl hinter "Menü -> Unterseiten" anfügen
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Unterseiten mit Anzahl von News (Filter)',
        'wdvcustomer_menuSubpagesAmountNewsItemsFilter',
        'EXT:core/Resources/Public/Icons/T3Icons/content/content-menu-pages.svg'
    ],
    'menu_section_pages',
    'after'
);

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['wdvcustomer_menuSubpagesAmountNewsItemsFilter'] = ['showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.menu_accessibility;menu_accessibility,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    '];

// --

// Content-Element "Unterseiten mit Anzahl von News (Dropdown)" zur Typenauswahl hinter "Menü -> Unterseiten" anfügen
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Unterseiten mit Anzahl von News (Dropdown)',
        'wdvcustomer_menuSubpagesAmountNewsItemsDropdown',
        'EXT:core/Resources/Public/Icons/T3Icons/content/content-menu-pages.svg'
    ],
    'menu_section_pages',
    'after'
);

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types']['wdvcustomer_menuSubpagesAmountNewsItemsDropdown'] = ['showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.menu_accessibility;menu_accessibility,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    '];

// Add a new fields for type tt_content
$temporaryColumns = [
    'tx_wdvcustomer_content_paddingtop' => [
        'exclude' => 1,
        'label' => 'Abstand innen / oben',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Kein Abstand', 'value' => '0'],
                ['label' => 'Kleiner Abstand (3)', 'value' => '3'],
                ['label' => 'Normaler Abstand (5)', 'value' => '5'],
                ['label' => 'Großer Abstand (7)', 'value' => '7']
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],
    'tx_wdvcustomer_content_paddingbottom' => [
        'exclude' => 1,
        'label' => 'Abstand innen / unten',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Kein Abstand', 'value' => '0'],
                ['label' => 'Kleiner Abstand (3)', 'value' => '3'],
                ['label' => 'Normaler Abstand (5)', 'value' => '5'],
                ['label' => 'Großer Abstand (7)', 'value' => '7']
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],
    'tx_wdvcustomer_content_breakoutleft' => [
        'exclude' => true,
        'label' => 'Links "ausbrechen"?',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
    'tx_wdvcustomer_content_breakoutright' => [
        'exclude' => true,
        'label' => 'Rechts "ausbrechen"?',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
];

// Add the new fields to tt_content
ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $temporaryColumns
);

// Add the new fields to an existing palette
ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'frames',
    'tx_wdvcustomer_content_paddingtop, tx_wdvcustomer_content_paddingbottom, tx_wdvcustomer_content_breakoutleft, tx_wdvcustomer_content_breakoutright',
    'after:space_after_class'
);

//$GLOBALS['TCA']['tt_content']['types']['mask_rotatingcard']['showitem'] = '--div--;Test, tx_wdvcustomer_content_breakoutleft, tx_wdvcustomer_content_breakoutright';

/*
// Geht nicht, weil MASK der Meinung ist, es kann machen was es will und das TCA großzügig überschreiben,
// deswegen kann leider kein typeList angewendet werden, denn der jeweilige Wert ist nicht gültig...
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'tx_wdvcustomer_content_breakoutleft,tx_wdvcustomer_content_breakoutright',
    'mask_rotatingcard',
    'after:space_before_class'
);
*/

/***************
 * Register Frontend Plugins
 * FE-Plugin for displaying elements of file collections
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'WdvCustomer',
    'ListItemsFromFileCollections',
    'Elemente aus Dateisammlungen auflisten'
);

/***************
 * Register Flexform Config
 */
$pluginSignature = str_replace('_', '', 'wdv_customer') . '_listitemsfromfilecollections';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:wdv_customer/Configuration/FlexForms/Extensions/tx_wdvcustomer.listItemsFromFileCollections.xml');


GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-achtsamkeit',
        'Achtsamkeits-Container',
        'diverse Container für das Achtsamkeits-Layout',
        [
            [
                ['name' => 'Inhalt', 'colPos' => 3100],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-box_container',
        'Box-Container',
        'A container for content elements with selectable background image or color',
        [
            [
                ['name' => 'Box-Container', 'colPos' => 3100],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-box_width',
        'Achtsamkeits-Breiten-Container',
        'ein Breitenlimitierer für das Achtsamkeits-Layout',
        [
            [
                ['name' => 'Inhalt', 'colPos' => 3100],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-color_container',
        'Color-Container',
        'Ein Container, der die Hintergrundfarbe setzt',
        [
            [
                ['name' => 'Inhalt', 'colPos' => 3100],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-collapsebox',
        'Collapse-Box',
        'Box zum einfügen von viel Content, welcher erst nach klick vollständig sichtbar wird',
        [
            [
                ['name' => 'direkt sichtbar', 'colPos' => 3100],
            ],
            [
                ['name' => 'aufklappbar', 'colPos' => 3101],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(
    (
    new ContainerConfiguration(
        'b13-displayanzeige',
        'Displayanzeigen-Rotator',
        'Wrapper für Displayanzeigenrotation',
        [
            [
                ['name' => 'Displayanzeige', 'colPos' => 3100],
            ],
        ],
    )
    )
        ->setIcon('EXT:wdv_elements/Resources/Public/Icons/Gridelements/dummy.png')
);

$temporaryColumns = [
    'flexform_css_classes' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
                'FIELD:CType:=:b13-collapsebox',
                'FIELD:CType:=:b13-two_columns',
                'FIELD:CType:=:b13-three_columns',
                'FIELD:CType:=:b13-four_columns',
            ],
        ],
        'exclude' => 1,
        'label' => 'Zusaetzliche CSS-Klassen fuer ganzes Element',
        'config' => [
            'type' => 'input',
            'size' => 48,
            'eval' => 'trim',
        ]
    ],

    'flexform_cssid' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'eigene CSS ID',
        'config' => [
            'type' => 'input',
            'size' => 48,
            'eval' => 'trim',
        ]
    ],

    'flexform_layout' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-achtsamkeit',
            ],
        ],
        'exclude' => 1,
        'label' => 'Layout',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Standard Content mir runden Ecken', 'value' => 'default'],
                ['label' => 'grünes V', 'value' => 'vribbon'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_scrollbutton' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-achtsamkeit',
            ],
        ],
        'exclude' => 1,
        'label' => 'Button zum runterscrollen',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'keiner', 'value' => ''],
                ['label' => 'Pfeil im Kreis', 'value' => 'default'],
                ['label' => 'Pfeil im Kreis mit grünem Ribbon', 'value' => 'ribbon'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_top_space' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-achtsamkeit',
            ],
        ],
        'exclude' => 1,
        'label' => 'Extra viel Abstand am Anfang? (z.B. zum Überlappen von Elementen)',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ]
    ],

    'flexform_bottom_space' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-achtsamkeit',
            ],
        ],
        'exclude' => 1,
        'label' => 'Extra viel Abstand am Ende? (z.B. zum Überlappen von Elementen)',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ]
    ],

    'flexform_boxtype' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Typ der Box?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Transparenter Hintergrund (Standard)', 'value' => 'transparent'],
                ['label' => 'Grauer Hintergrund', 'value' => 'grey'],
                ['label' => 'Weißer Hintergrund', 'value' => 'white'],
                ['label' => 'Intro-Box', 'value' => 'intro'],
                ['label' => 'Absprung-Box', 'value' => 'absprung'],
                ['label' => 'Grüner Hintergrund', 'value' => 'green'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_fullwidth' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Elementbreite über Seitenbreite ausbrechen?',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ]
    ],

    'flexform_contentwidth' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Inhaltsbreite wie "normaler" Contentbereich?',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ]
    ],

    'flexform_bgimage' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Hintergrundbild(er)',
        'config' => [
            'type' => 'inline',
            'appearance' => [
                'collapseAll' => true,
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                'enabledControls' => [
                    'delete' => true,
                    'dragdrop' => true,
                    'hide' => true,
                    'info' => true,
                    'localize' => true,
                ],
                'headerThumbnail' => [
                    'field' => 'uid_local',
                    'height' => '45c',
                    'width' => '45',
                ],
                'useSortable' => true,
            ],

            'foreign_table' => 'sys_file_reference',
            'foreign_field' => 'uid_foreign',
            'foreign_sortby' => 'sorting_foreign',
            'foreign_table_field' => 'tablenames',

            'foreign_label' => 'uid_local',
            'foreign_match_fields' => [
                'fieldname' => 'flexform_bgimage'
            ],
            'foreign_selector' => 'uid_local',
            'overrideChildTca' => [
                'columns' => [
                    'uid_local' => [
                        'config' => [
                            'appearance' => [
                                'elementBrowserType' => 'file',
                                'elementBrowserAllowed' => 'jpg, jpeg, png',
                            ],
                        ],
                    ],
                ],
                'types' => [
                    2 => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                    ],
                ],
            ],
        ],
    ],

    'flexform_bgvideo' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Hintergrundvideo',
        'config' => [
            'type' => 'inline',
            'appearance' => [
                'collapseAll' => true,
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                'enabledControls' => [
                    'delete' => true,
                    'dragdrop' => true,
                    'hide' => true,
                    'info' => true,
                    'localize' => true,
                ],
                'headerThumbnail' => [
                    'field' => 'uid_local',
                    'height' => '45c',
                    'width' => '45',
                ],
                'useSortable' => true,
            ],

            'foreign_table' => 'sys_file_reference',
            'foreign_field' => 'uid_foreign',
            'foreign_sortby' => 'sorting_foreign',
            'foreign_table_field' => 'tablenames',

            'foreign_label' => 'uid_local',
            'foreign_match_fields' => [
                'fieldname' => 'flexform_bgvideo'
            ],
            'foreign_selector' => 'uid_local',
            'overrideChildTca' => [
                'columns' => [
                    'uid_local' => [
                        'config' => [
                            'appearance' => [
                                'elementBrowserType' => 'file',
                                'elementBrowserAllowed' => 'mp4, ogg, webm',
                            ],
                        ],
                    ],
                ],
                'types' => [
                    2 => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                    ],
                ],
            ],
        ],
    ],

    'flexform_layout_gib8' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Layout für Gib8',
        'config' => [
            'type' => 'check',
            'default' => 0,
        ]
    ],

    'flexform_boxalign' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'horizontale Ausrichtung der Box?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Links (Standard)', 'value' => 'left'],
                ['label' => 'Mittig', 'value' => 'center'],
                ['label' => 'Rechts', 'value' => 'right'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_boxvalign' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'vertikale Ausrichtung der Box?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Oben (Standard)', 'value' => 'top'],
                ['label' => 'Normal (Standard)', 'value' => 'normal'],
                ['label' => 'Unten', 'value' => 'bottom'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_width' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-box_width',
            ],
        ],
        'exclude' => 1,
        'label' => 'Breite',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'volle Breite', 'value' => 'fullwidth'],
                ['label' => 'normale Breite', 'value' => 'normwidth'],
                ['label' => 'schmalere Breite', 'value' => 'smallwidth'],
                ['label' => 'sehr schmale Breite', 'value' => 'miniwidth'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],

    'flexform_rotationtime' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-displayanzeige',
            ],
        ],
        'exclude' => 1,
        'label' => 'Rotation zwischen den Anzeigen in Minuten',
        'config' => [
            'type' => 'input',
            'default' => 1,
            'eval' => 'trim',
        ]
    ],
    
    'flexform_backgroundcolor' => [
        'displayCond' => [
            'OR' => [
                'FIELD:CType:=:b13-color_container',
            ],
        ],
        'exclude' => 1,
        'label' => 'Hintergrundfarbe',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'weiß', 'value' => 'aok-white'],
                ['label' => 'Blautöne', 'value' => '--div--'],
                ['label' => 'hellblau', 'value' => 'aok-light-blue'],
                ['label' => 'pastelblau', 'value' => 'aok-pastel-blue'],
                ['label' => 'Grüntöne', 'value' => '--div--'],
                ['label' => 'pastelblau', 'value' => 'aok-pastel-green'],
                ['label' => 'grün', 'value' => 'aok-green'],
                ['label' => 'Sonstiges', 'value' => '--div--'],
                ['label' => 'Sand', 'value' => 'aok-sand'],
            ],
            'size' => 1,
            'maxitems' => 1
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $temporaryColumns
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '
    --div--;Container Einstellungen,flexform_css_classes,flexform_layout,flexform_scrollbutton,flexform_cssid,flexform_boxtype,flexform_fullwidth, flexform_contentwidth,flexform_top_space,flexform_bottom_space,flexform_width, flexform_rotationtime,
    --div--;Hintergrund-Optionen,flexform_backgroundcolor,flexform_bgimage, flexform_bgvideo,
    --div--;Optionen für Intro-Box,flexform_layout_gib8, flexform_boxalign, flexform_boxvalign',
    '',
    ''
);