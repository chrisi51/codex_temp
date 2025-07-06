<?php
return [
    'ctrl' => [
        'title'    => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_questions',
        'label' => 'question',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'hideTable' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'question,answers',
        'iconfile' => 'EXT:vigo_quiz/Resources/Public/Icons/tx_vigoquiz_domain_model_questions.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, infotext, introtext, question, answers, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
        'infotext' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_questions.infotext',
            'config' => [
                'type' => 'check',
            ],
        ],
        'question' => [
            'exclude' => false,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_questions.question',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'max' => 256,
                'required' => true
            ],
        ],
        'introtext' => [
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_questions.introtext',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
        'answers' => [
            'exclude' => true,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_questions.answers',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_vigoquiz_domain_model_answers',
                'foreign_field' => 'questions',
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
        'vigoquiz' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
