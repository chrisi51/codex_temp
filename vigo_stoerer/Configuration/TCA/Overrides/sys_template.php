<?php
defined('TYPO3') || die('Access denied.');

/**
 * Temporary variables
 */
$extensionKey = 'vigo_stoerer';

/**
 * Default TypoScript for vigo_stoerer
 */
// @extensionScannerIgnoreLine
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $extensionKey,
    'Configuration/TypoScript',
    'Vigo Störer'
);