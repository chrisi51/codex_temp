<?php
defined('TYPO3') || die('Access denied.');

/**
 * Temporary variables
 */
$extensionKey = 'wdv_customer';

/**
 * Default TypoScript for wdv_customer
 */
// @extensionScannerIgnoreLine
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $extensionKey,
    'Configuration/TypoScript',
    'WDV Costumer'
);