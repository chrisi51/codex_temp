<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_results',
        'label' => 'resulttitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'delete' => 'deleted',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'resulttitle,pointrange,resultimage',
        'iconfile' => 'EXT:vigo_quiz/Resources/Public/Icons/tx_vigoquiz_domain_model_results.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, resulttitle, resulttext, pointrange, resultimage, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],

        'resulttitle' => [
            'exclude' => false,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_results.resulttitle',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim'
            ],
        ],
        'resulttext' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_results.resulttext',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
                'required' => true
            ]
        ],
        'pointrange' => [
            'exclude' => false,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_results.pointrange',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ],
        ],
        'resultimage' => [
            'exclude' => false,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_results.resultimage',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'resultimage',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'maxitems' => 1,
                    'overrideChildTca' => ['types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ]]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    
    ],
];
