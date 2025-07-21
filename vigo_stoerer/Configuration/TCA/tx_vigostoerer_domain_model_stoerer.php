<?php

defined('TYPO3') || die('Access denied.');

return [
    'ctrl' => [
        'title' => 'Störer',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => TRUE,
        'delete' => 'deleted',
        'prependAtCopy' => '(Kopie)',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title',
        // 'iconfile' => ''
    ],
    'types' => [
        '1' => ['showitem' => 'title,all_pages,sites,link,content,image,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,hidden'],
    ],
    'palettes' => [
    ],
    'columns' => [
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'Titel',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim, required'
            ],
        ],
        'all_pages' => [
            'exclude' => true,
            'label' => 'Auf allen Seiten ausspielen',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'sites' => [
            'exclude'   => true,
            'label'     => 'LLL:EXT:vigo_stoerer/Resources/Private/Language/locallang_db.xlf:stoerer.sites',
            'config'    => [
                'type'            => 'select',
                'renderType'      => 'selectMultipleSideBySide',
                'itemsProcFunc'   => \VIGO\VigoStoerer\Tca\SiteItemsProcFunc::class . '->getItems',
                'size'            => 5,
                'autoSizeMax'     => 10,
                'maxitems'        => 20,
            ],
        ],
        'layout' => [
            'exclude' => 1,
            'label' => 'Welches Layout?',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Standard', 'value' => ''],
                    ['label' => 'EPA', 'value' => 'epa'],
                ],
            ],
        ],
        'link' => [
            'label' => 'Link',
            'exclude' => true,
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'required' => true
            ],
        ],
        'content' => [
            'exclude' => true,
            'label' => 'Content',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'Symbol',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;LLL::EXT:core/Resources/Private/Language/locallang_general.xlf::sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ]
                        ],
                    ],
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'maxitems' => 1
                ],
                //$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                'gif,jpg,jpeg,png,svg'
            ),
        ]
    ],
];