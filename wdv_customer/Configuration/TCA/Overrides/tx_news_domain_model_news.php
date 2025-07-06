<?php
defined('TYPO3') || die('Access denied.');

// Register new news type "themenspecial" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['3'] = ['Themenspecial', 3];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['3'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "interview" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['4'] = ['Interview', 4];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['4'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "question" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['5'] = ['Frage der Woche', 5];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['5'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "video" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['6'] = ['Video', 6];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['6'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "rezept" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['7'] = ['Rezept', 7];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['7'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "rezeptsammlung" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['8'] = ['Rezeptsammlung', 8];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['8'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "multipage" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['9'] = ['Multipage', 9];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['9'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "gewinnspiel" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['10'] = ['Gewinnspiel', 10];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['10'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "externalurlsammlung" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['11'] = ['Sammlung externer Seiten', 11];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['11'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "landingpage" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['12'] = ['Landingpage', 12];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['12'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "podcast" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['13'] = ['Podcast', 13];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['13'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "podcast" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['14'] = ['Bestellung', 14];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['14'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];

// Register new news type "Playlist" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['140'] = ['Soundtrack - Playlist', 140];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['140'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['140']['showitem'] = '
    --palette--;;paletteCore,title,teaser,--palette--;;paletteSlug,
    internalurl,
    bodytext,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
        fal_media,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;paletteHidden,
        --palette--;;paletteAccess,
';
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['140']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Link zur Playlistseite',
            ],
        ];

// Register new news type "Playlist" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['141'] = ['Soundtrack - Artikel', 141];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['141'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['140'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['141']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Link zum Artikel',
            ],
        ];
// Register new news type "Playlist" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['142'] = ['Soundtrack - Podcast', 142];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['142'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['141'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['142']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Link zum Podcast',
            ],
        ];
// Register new news type "Gewinnspiel" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['143'] = ['Soundtrack - Gewinnspiel', 143];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['143'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['142'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['143']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Mailto',
            ],
        ];
// Register new news type "Formular" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['144'] = ['Soundtrack - Gewinnspiel mit Formular', 144];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['144'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['142'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['144']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            /*'internalurl' => [
                'label' => 'Mailto',
            ],*/
        ];
// Register new news type "Infoartikel" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['145'] = ['Soundtrack - Infoartikel', 145];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['145'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['142'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['145']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Link zum Artikel',
            ],
        ];
// Register new news type "Veranstaltung" and configure tca for it
$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items']['146'] = ['Soundtrack - Veranstaltung', 146];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['146'] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['142'];
$GLOBALS['TCA']['tx_news_domain_model_news']['types']['146']['columnsOverrides'] = [
            'teaser' => [
                'label' => 'Subline',
                'config' => [
                    'type' => 'input',
                ],
            ],
            'internalurl' => [
                'label' => 'Link zur Veranstaltung 1',
                'config' => [
                    'size' => 50,
                    'required' => false,
                ]
            ],
            'externalurl' => [
                'label' => 'Link zur Veranstaltung 2',
                'config' => [
                    'type' => 'link',
                    'required' => false,
                ],
            ],
        ];


$GLOBALS['TCA']['tx_news_domain_model_news']['types']['146']['showitem'] = '
    --palette--;;paletteCore,title,teaser,--palette--;;paletteSlug,
    internalurl,
    externalurl,
    bodytext,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
        fal_media,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;paletteHidden,
        --palette--;;paletteAccess,
';

/* -- */

// Register new field "tx_wdvcustomer_news_time"
$temporaryColumns = ['tx_wdvcustomer_news_time' => ['exclude' => 1, 'label' => 'Lesezeit oder Länge in Minuten (Bspl.: 3:00)', 'config' => ['type' => 'input', 'size' => 15]]];

// Add the new field to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field in an existing palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_news_domain_model_news',
    'paletteDate',
    'tx_wdvcustomer_news_time',
    'after:archive'
);

/* -- */

// Register new field "tx_wdvcustomer_news_multipagetype"
$temporaryColumns = ['tx_wdvcustomer_news_multipagetype' => [
    'exclude' => false,
    'label' => 'Multipage: Override Icon u. Beschriftung im Teaser',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
            ['label' => 'Kein Override', 'value' => 0],
            ['label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.type.I.1', 'value' => 1],
            ['label' => 'LLL:EXT:news/Resources/Private/Language/locallang_db.xlf:tx_news_domain_model_news.type.I.2', 'value' => 2],
            ['label' => 'Interview', 'value' => 4],
            ['label' => 'Frage der Woche', 'value' => 5],
            ['label' => 'Video', 'value' => 6],
            ['label' => 'Rezept', 'value' => 7],
            ['label' => 'Gewinnspiel', 'value' => 10],
            ['label' => 'Podcast', 'value' => 13],
            ['label' => 'Bestellung', 'value' => 14],
        ],
        'fieldWizard' => [
            'selectIcons' => [
                'disabled' => false,
            ],
        ],
        'size' => 1,
        'maxitems' => 1,
    ],
]];

// Add the new field to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field in an existing palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_news_domain_model_news',
    'paletteCore',
    'tx_wdvcustomer_news_multipagetype',
    'after:type'
);


// Add new fields "tx_wdvcustomer_themenspecial_implied_news" and "tx_wdvcustomer_news_implied_from_themenspecial"
$temporaryColumns = ['tx_wdvcustomer_themenspecial_implied_news' => [
    'exclude' => true,
    'label' => 'Beinhaltende Artikel',
    'config' => [
        'type' => 'group',
        'allowed' => 'tx_news_domain_model_news',
        'foreign_table' => 'tx_news_domain_model_news',
        'MM_opposite_field' => 'tx_wdvcustomer_news_implied_from_themenspecial',
        'size' => 5,
        'minitems' => 0,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_themenspecial_mm',
        'suggestOptions' => [
            'default' => [
                'suggestOptions' => true,
                'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
], 'tx_wdvcustomer_news_implied_from_themenspecial' => [
    'exclude' => true,
    'label' => 'Zugehörig zu Themenspecial',
    'config' => [
        'type' => 'group',
        'foreign_table' => 'tx_news_domain_model_news',
        'allowed' => 'tx_news_domain_model_news',
        'size' => 5,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_themenspecial_mm',
        'readOnly' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field "tx_wdvcustomer_news_implied" only when news type is "3" (Themenspecial)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Themenspecial (Einstellungen),tx_wdvcustomer_themenspecial_implied_news',
    '3'
);

// Add new fields "tx_wdvcustomer_landingpage_implied_news" and "tx_wdvcustomer_news_implied_from_landingpage"
$temporaryColumns = ['tx_wdvcustomer_landingpage_implied_news' => [
    'exclude' => true,
    'label' => 'Beinhaltende Artikel',
    'config' => [
        'type' => 'group',
        'allowed' => 'tx_news_domain_model_news',
        'foreign_table' => 'tx_news_domain_model_news',
        'MM_opposite_field' => 'tx_wdvcustomer_news_implied_from_landingpage',
        'size' => 5,
        'minitems' => 0,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_landingpage_mm',
        'suggestOptions' => [
            'default' => [
                'suggestOptions' => true,
                'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
], 'tx_wdvcustomer_news_implied_from_landingpage' => [
    'exclude' => true,
    'label' => 'Zugehörig zu Landingpage',
    'config' => [
        'type' => 'group',
        'foreign_table' => 'tx_news_domain_model_news',
        'allowed' => 'tx_news_domain_model_news',
        'size' => 5,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_landingpage_mm',
        'readOnly' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field "tx_wdvcustomer_news_implied" only when news type is "12" (Landingpage)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Landingpage (Einstellungen),tx_wdvcustomer_landingpage_implied_news',
    '12'
);

// Add some new fields for type tx_domain_model_news (Rezept)
$temporaryColumns = ['tx_wdvcustomer_news_recipe_cookingtime' => ['exclude' => 1, 'label' => 'Zubereitungszeit in Minuten (Bspl.: 3:00)', 'config' => ['type' => 'input', 'size' => 15]], 'tx_wdvcustomer_news_recipe_difficulty' => ['exclude' => 1, 'label' => 'Schwierigkeit', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [['label' => 'Leicht', 'value' => '0'], ['label' => 'Mittel', 'value' => '1'], ['label' => 'Schwer', 'value' => '2']], 'size' => 1, 'maxitems' => 1]], 'tx_wdvcustomer_news_recipe_type' => ['exclude' => 1, 'label' => 'Typ', 'config' => ['type' => 'select', 'renderType' => 'selectSingle', 'items' => [['label' => 'Standard', 'value' => '0'], ['label' => 'Vegetarisch', 'value' => '1'], ['label' => 'Vegan', 'value' => '2']], 'size' => 1, 'maxitems' => 1]]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the fields only when news type is "7" (Rezept)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Rezept (Einstellungen),tx_wdvcustomer_news_recipe_cookingtime,tx_wdvcustomer_news_recipe_difficulty,tx_wdvcustomer_news_recipe_type',
    '7'
);

$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Model\Dto\EmConfiguration::class);

// Add new fields for extra Content Elements when type is "Rezept"
$temporaryColumns = ['tx_wdvcustomer_news_recipe_content_elements' => [
    'exclude' => true,
    'label' => 'Zutaten',
    'config' => [
        'type' => 'inline',
        'allowed' => 'tt_content',
        'foreign_table' => 'tt_content',
        'foreign_sortby' => 'sorting',
        'foreign_field' => 'tx_wdvcustomer_related_news_recipe',
        'minitems' => 0,
        'maxitems' => 99,
        'appearance' => [
            #'useXclassedVersion' => $configuration->getContentElementPreview(),
            'collapseAll' => true,
            'expandSingle' => true,
            'levelLinksPosition' => 'bottom',
            'useSortable' => true,
            'showPossibleLocalizationRecords' => true,
            'showRemovedLocalizationRecords' => true,
            'showAllLocalizationLink' => true,
            'showSynchronizationLink' => true,
            'enabledControls' => [
                'info' => false,
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
]];

//if (!\GeorgRinger\News\Utility\EmConfiguration::getSettings()->getContentElementRelation()) {
if (!$configuration->getContentElementRelation()) {
    unset($temporaryColumns['tx_wdvcustomer_news_recipe_content_elements']);
} else {

    // Add the new fields to tx_domain_model_news
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tx_news_domain_model_news',
        $temporaryColumns
    );

    // Show the field "tx_wdvcustomer_news_recipe_content_elements" only when news type is "7" (Rezept)
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tx_news_domain_model_news',
        'tx_wdvcustomer_news_recipe_content_elements',
        '7',
        'before:content_elements'
    );
}

// Add new fields for type tx_domain_model_news (Rezeptsammlung)
$temporaryColumns = ['tx_wdvcustomer_rezeptsammlung_implied_news' => [
    'exclude' => true,
    'label' => 'Beinhaltende Rezepte',
    'config' => [
        'type' => 'group',
        'allowed' => 'tx_news_domain_model_news',
        'foreign_table' => 'tx_news_domain_model_news',
        'MM_opposite_field' => 'tx_wdvcustomer_news_implied_from_rezeptsammlung',
        'size' => 5,
        'minitems' => 0,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_rezeptsammlung_mm',
        'suggestOptions' => [
            'default' => [
                'suggestOptions' => true,
                'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
], 'tx_wdvcustomer_news_implied_from_rezeptsammlung' => [
    'exclude' => true,
    'label' => 'Zugehörig zu Rezeptsammlung',
    'config' => [
        'type' => 'group',
        'foreign_table' => 'tx_news_domain_model_news',
        'allowed' => 'tx_news_domain_model_news',
        'size' => 5,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_rezeptsammlung_mm',
        'readOnly' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field "tx_wdvcustomer_news_implied" when news type is "8" (Rezeptsammlung)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Rezeptsammlung (Einstellungen),tx_wdvcustomer_rezeptsammlung_implied_news',
    '8'
);

// Add new fields for type tx_domain_model_news (Sammlung externer Seiten)
$temporaryColumns = ['tx_wdvcustomer_externalurlsammlung_implied_news' => [
    'exclude' => true,
    'label' => 'Beinhaltende externe Seiten',
    'config' => [
        'type' => 'group',
        'allowed' => 'tx_news_domain_model_news',
        'foreign_table' => 'tx_news_domain_model_news',
        'MM_opposite_field' => 'tx_wdvcustomer_news_implied_from_externalurlsammlung',
        'size' => 5,
        'minitems' => 0,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_externalurlsammlung_mm',
        'suggestOptions' => [
            'default' => [
                'suggestOptions' => true,
                'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
], 'tx_wdvcustomer_news_implied_from_externalurlsammlung' => [
    'exclude' => true,
    'label' => 'Zugehörig zu Sammlung externer Seiten',
    'config' => [
        'type' => 'group',
        'foreign_table' => 'tx_news_domain_model_news',
        'allowed' => 'tx_news_domain_model_news',
        'size' => 5,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_externalurlsammlung_mm',
        'readOnly' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field "tx_wdvcustomer_news_implied" when news type is "11" (Sammlung externer Seiten)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Sammlung externer Seiten (Einstellungen),tx_wdvcustomer_externalurlsammlung_implied_news',
    '11'
);

// Show the field "tx_wdvcustomer_news_implied_from_multipage" when news type is "2" (External Page)
$newFieldString = '--div--;Sammlung externer Seiten (Informationen),tx_wdvcustomer_news_implied_from_externalurlsammlung';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '2');

// Add new fields for type tx_domain_model_news (Multipage)
$temporaryColumns = ['tx_wdvcustomer_multipage_implied_news' => [
    'exclude' => true,
    'label' => 'Beinhaltende Artikel',
    'config' => [
        'type' => 'group',
        'allowed' => 'tx_news_domain_model_news',
        'foreign_table' => 'tx_news_domain_model_news',
        'MM_opposite_field' => 'tx_wdvcustomer_news_implied_from_multipage',
        'size' => 5,
        'minitems' => 0,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_multipage_mm',
        'suggestOptions' => [
            'default' => [
                'suggestOptions' => true,
                'addWhere' => ' AND tx_news_domain_model_news.uid != ###THIS_UID###'
            ]
        ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
    ]
], 'tx_wdvcustomer_news_implied_from_multipage' => [
    'exclude' => true,
    'label' => 'Inkludiert in Multipage-Artikel',
    'config' => [
        'type' => 'group',
        'foreign_table' => 'tx_news_domain_model_news',
        'allowed' => 'tx_news_domain_model_news',
        'size' => 5,
        'maxitems' => 100,
        'MM' => 'tx_news_domain_model_news_multipage_mm',
        'readOnly' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field "tx_wdvcustomer_multipage_implied_news" when news type is "9" (Multipage)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tx_news_domain_model_news',
    '--div--;Multipage (Einstellungen),tx_wdvcustomer_multipage_implied_news',
    '9'
);

// Show the field "tx_wdvcustomer_news_implied_from_multipage" when news type is 0, 1, 2, 3, 4, 5, 6, 7, 8, 10, 11
// 9 is Multipage itselfs!
$newFieldString = '--div--;Multipage (Informationen),tx_wdvcustomer_news_implied_from_multipage';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '0');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '1');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '2');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '3');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '4');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '5');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '6');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '7');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '8');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '10');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_news_domain_model_news', $newFieldString, '11');

$temporaryColumns = ['tx_wdvcustomer_news_canonical_cat' => [
    'label' => 'Für Canonical zu priorisierende Kategorie',
    'exclude' => true,
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'items' => [
          ['label' => 'Haupt-Kategorie Wählen', 'value' => -1],
        ],
        'foreign_table' => 'sys_category',
        'minitems' => 0,
        'maxitems' => 1,
    ]
]];

// Add the new fields to tx_domain_model_news
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_news_domain_model_news',
    $temporaryColumns
);

// Show the field in an existing palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tx_news_domain_model_news',
    'yoast-metadata',
    'tx_wdvcustomer_news_canonical_cat',
    'after:description'
);