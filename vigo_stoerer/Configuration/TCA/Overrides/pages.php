<?php

defined('TYPO3') || die('Access denied.');

$temporaryColumns = ['tx_vigostoerer_related_stoerer' => [
    'label' => 'Anzuzeigende Störer',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_vigostoerer_domain_model_stoerer',
        'foreign_table_where' => 'AND tx_vigostoerer_domain_model_stoerer.deleted = 0 AND tx_vigostoerer_domain_model_stoerer.hidden = 0 ORDER BY tx_vigostoerer_domain_model_stoerer.sorting',
        'MM' => 'tx_vigostoerer_pages_stoerer_mm',
        'size' => 10,
        'autoSizeMax' => 30,
        'maxitems' => 99,
        'behaviour' => [
            'allowLanguageSynchronization' => true
        ],
    ],
]];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $temporaryColumns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--div--;Störer, tx_vigostoerer_related_stoerer',
);