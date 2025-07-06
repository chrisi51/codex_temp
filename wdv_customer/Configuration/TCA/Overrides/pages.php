<?php

defined('TYPO3') || die('Access denied.');

/**
 * Temporary variables
 */
$extensionKey = 'wdv_customer';

/**
 * Default PageTS for wdv
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
	$extensionKey,
	'Configuration/TsConfig/All.tsconfig',
	'EXT:'.$extensionKey.' :: Overrides'
);


// adding fields for additional page options
$temporaryColumns = [
    'tx_wdvcustomer_page_paddingtop' => [
        'exclude' => 1,
        'label' => 'Soll es einen Abstand nach oben im Inhaltsbereich geben?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Nein', 'value' => '0'],
                ['label' => 'Ja', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_background' => [
        'exclude' => 1,
        'label' => 'Soll das Hintergrundbild mit den grünen Blättern angezeigt werden?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Nein', 'value' => '0'],
                ['label' => 'Ja', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_icon' => [
        'exclude' => 1,
        'label' => 'Welches Icon soll dem Menüeintrag vorran gestellt werden?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Keins', 'value' => '0'],
                ['label' => 'Suche', 'value' => 'search'],
                ['label' => 'Ernährung und Kochen', 'value' => 'ernaehrung-kochen'],
                ['label' => 'Bewegung und Sport', 'value' => 'bewegung-sport'],
                ['label' => 'Körper und Seele', 'value' => 'koerper-seele'],
                ['label' => 'Gesundheit und Vorsorge', 'value' => 'gesundheit-vorsorge'],
                ['label' => 'Familie und Kinder', 'value' => 'familie-kinder'],
                ['label' => 'Krankheit und Therapie', 'value' => 'krankheit-therapie'],
                ['label' => 'Frage der Woche', 'value' => 'frage-der-woche'],
                ['label' => 'Bestellungen', 'value' => 'bestellung'],
                ['label' => 'Gewinnspiele', 'value' => 'gewinnspiele'],
                ['label' => 'Aus dem aktuellen Magazin', 'value' => 'aus-dem-magazin'],
                ['label' => 'Durchstarter', 'value' => 'durchstarter'],
                ['label' => 'Laufliebe', 'value' => 'laufliebe'],
                ['label' => 'Sendepause', 'value' => 'sendepause'],
                ['label' => 'Gib8', 'value' => 'gib8'],
                ['label' => 'Morphium & Ingwer', 'value' => 'morphium-ingwer'],
                ['label' => 'Experten', 'value' => 'experten'],
                ['label' => 'Organspende', 'value' => 'organspende'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_amountnews' => [
        'exclude' => 1,
        'label' => 'Soll die Anzahl der verfügbaren News der selben Kategorie angezeigt werden?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Nein', 'value' => '0'],
                ['label' => 'Ja', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_breadcrumb_show' => [
        'exclude' => 1,
        'label' => 'Breadcrumb einblenden?',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Nein', 'value' => '0'],
                ['label' => 'Ja', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_breadcrumb_color' => [
        'exclude' => 1,
        'label' => 'Hintergrundfarbe des Breadcrumbs',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'Hell', 'value' => '0'],
                ['label' => 'Dunkel (AOK Grün)', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
    'tx_wdvcustomer_page_breadcrumb_spacing' => [
        'exclude' => 1,
        'label' => 'Größere des Breadcrumb Containers (Groß default, klein z. B. für Artikelseite)',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => 'groß', 'value' => '0'],
                ['label' => 'klein', 'value' => '1'],
            ],
            'size' => 1,
            'maxitems' => 1,
        ],
    ],
];


// Füge die Optionen zu den Seiteneigenschaften hinzu
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $temporaryColumns
);

// Mache die Optionen auf Seiten vom Doktype 1 (Normale Seiten) in einem eigenen Reiter verfuegbar
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--div--;Erweiterte Einstellungen, tx_wdvcustomer_page_icon, tx_wdvcustomer_page_amountnews, tx_wdvcustomer_page_paddingtop, tx_wdvcustomer_page_background, tx_wdvcustomer_page_breadcrumb_show, tx_wdvcustomer_page_breadcrumb_color, tx_wdvcustomer_page_breadcrumb_spacing',
    '1'
);

// Mache die Optionen auf Seiten vom und 3 (Link zu externer URL) und Doktype 4 (Verweis) in einem eigenen Reiter verfuegbar
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--div--;Erweiterte Einstellungen, tx_wdvcustomer_page_icon',
    '3,4'
);
