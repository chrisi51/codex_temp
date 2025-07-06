<?php
return [
    'ctrl' => [
        'title'    => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,quiztype,questions,results',
        'iconfile' => 'EXT:vigo_quiz/Resources/Public/Icons/tx_vigoquiz_domain_model_vigoquiz.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, title, --palette--;;config, questions, results, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'palettes' => [
        'config' => [
            'label' => 'Quiz-Konfiguration',
            'showitem' => '
                quiztype,
                facebookcard,
                individualfeedback
            ',
        ],
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'behavior' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'behavior' => [
                    'allowLanguageSynchronization' => true,
                ],
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'quiztype' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.quiztype',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Wissenstest', 'value' => 1],
                    ['label' => 'Bewertungstest', 'value' => 2],
                ],
            ],
        ],
        'facebookcard' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.facebookcard',
            'config' => [
                'type' => 'check',
            ],
        ],
        'individualfeedback' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.individualfeedback',
            'config' => [
                'type' => 'check',
            ],
        ],
        'questions' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.questions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_vigoquiz_domain_model_questions',
                'foreign_field' => 'vigoquiz',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'results' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_vigoquiz.results',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_vigoquiz_domain_model_results',
                'foreign_field' => 'vigoquiz',
                'foreign_sortby' => 'sorting',
                'maxitems' => 5,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
    ],
];
