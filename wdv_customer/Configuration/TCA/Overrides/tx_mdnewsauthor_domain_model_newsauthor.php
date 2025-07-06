<?php

defined('TYPO3') || die('Access denied.');

// Register new field "tx_wdvcustomer_newsauthor_position2"
$temporaryColumns = ['tx_wdvcustomer_newsauthor_position2' => ['exclude' => 1, 'label' => 'Position 2', 'config' => ['type' => 'input', 'size' => 15]]];

// Add the new field to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_mdnewsauthor_domain_model_newsauthor',
    $temporaryColumns
);

// Show the field in an existing palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_mdnewsauthor_domain_model_newsauthor',
    'palette_company',
    'tx_wdvcustomer_newsauthor_position2',
    'after:position'
);