<?php

defined('TYPO3') || die('Access denied.');

// Register new ke_search indexer for tx_news without handling controller and action parameters
// to prevent double slashes in urls
/*
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['startingpoints_recursive']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['sysfolder']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['contenttypes']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['index_news_category_mode']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['index_news_archived']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['index_extnews_category_selection']['displayCond'] .= ',' . 'customindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['index_use_page_tags']['displayCond'] .= ',' . 'customindexer';
*/

// Register new ke_search indexer for tx_powermail content
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['startingpoints_recursive']['displayCond'] .= ',powermailindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['sysfolder']['displayCond'] .= ',powermailindexer';
$GLOBALS['TCA']['tx_kesearch_indexerconfig']['columns']['targetpid']['displayCond'] .= ',powermailindexer';
