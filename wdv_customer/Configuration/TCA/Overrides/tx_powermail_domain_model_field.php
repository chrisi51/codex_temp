<?php

defined('TYPO3') || die('Access denied.');

/**
 * extend powermail fields tx_powermail_domain_model_field
 */
$tempColumns = ['tx_wdvcustomer_powermail_text' => ['label' => 'Text für Broschüre', 'config' => [
    'type' => 'text',
    'enableRichtext' => true,
]], 'tx_wdvcustomer_powermail_image' => ['label' => 'Bild für Broschüre', 'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
    'image',
    [
        'appearance' => [
            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
        ],
        // custom configuration for displaying fields in the overlay/reference table
        // to use the image overlay palette instead of the basic overlay palette
        'overrideChildTca' => [
            'types' => [
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
            ],
        ],
    ],
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
)]];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_powermail_domain_model_field',
    $tempColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_powermail_domain_model_field',
    '--div--;Broschüren-Konfiguration, tx_wdvcustomer_powermail_text, tx_wdvcustomer_powermail_image',
    '',
    'after:own_marker_select'
);

$tempColumns = ['tx_wdvcustomer_powermail_bootstrap_cols' => ['exclude' => 0, 'label' => '(Bootstrap-)Breite die das Feld einnehmen soll', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [['label' => 'Bitte wählen, wenn nicht col-md-6', 'value' => 6], ['label' => 'col-md-1', 'value' => 1], ['label' => 'col-md-2', 'value' => 2], ['label' => 'col-md-3', 'value' => 3], ['label' => 'col-md-4', 'value' => 4], ['label' => 'col-md-5', 'value' => 5], ['label' => 'col-md-6', 'value' => 6], ['label' => 'col-md-7', 'value' => 7], ['label' => 'col-md-8', 'value' => 8], ['label' => 'col-md-9', 'value' => 9], ['label' => 'col-md-10', 'value' => 10], ['label' => 'col-md-11', 'value' => 11], ['label' => 'col-md-12', 'value' => 12]]]], 'tx_wdvcustomer_powermail_row_cols' => ['exclude' => 0, 'label' => 'Gesamtanzahl Felder der Reihe (Desktopansicht), zu der dieses Feld gehört (Standard ist 2)', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [['label' => 'Bitte wählen', 'value' => 'items_2'], ['label' => 'Feld allein in Reihe', 'value' => 'items_1'], ['label' => 'Feld ist eines von 2 in Reihe', 'value' => 'items_2'], ['label' => 'Feld ist eines von 3 in Reihe', 'value' => 'items_3'], ['label' => 'Feld ist eines von 4 in Reihe', 'value' => 'items_4']]]], 'tx_wdvcustomer_powermail_radio_horiz' => ['exclude' => 0, 'label' => 'Radiobuttons horizontal anordnen', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [
    ['label' => 'vertikal', 'value' => 0],
    ['label' => 'horizontal', 'value' => 1],
]]]];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_powermail_domain_model_field',
    $tempColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_powermail_domain_model_field',
    '--div--;Feld-Layout, tx_wdvcustomer_powermail_row_cols, tx_wdvcustomer_powermail_bootstrap_cols, tx_wdvcustomer_powermail_radio_horiz',
    '',
    'after:own_marker_select'
);
