<?php
return [
    'ctrl' => [
        'title'    => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_answers',
        'label' => 'answer',
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
        'searchFields' => 'answer,points',
        'iconfile' => 'EXT:vigo_quiz/Resources/Public/Icons/tx_vigoquiz_domain_model_answers.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, answer, points, feedback, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
                ],
            ],
        ],
        'answer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_answers.answer',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'feedback' => [
            'exclude' => true,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_answers.feedback',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ],
        ],
        'points' => [
            'exclude' => false,
            'label' => 'LLL:EXT:vigo_quiz/Resources/Private/Language/locallang_db.xlf:tx_vigoquiz_domain_model_answers.points',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'required' => true
            ]
        ],
        'questions' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
