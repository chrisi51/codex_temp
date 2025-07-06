<?php

use HDNET\Focuspoint\ViewHelpers\ImageViewHelper;
use In2code\Powermail\Domain\Model\Field;
use Mediadreams\MdNewsAuthor\Domain\Model\NewsAuthor;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\Writer\FileWriter;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use WDV\WdvCustomer\Controller\ListItemsFromFileCollectionsController;
use WDV\WdvCustomer\Controller\SmsDoiController;
use WDV\WdvCustomer\Domain\Model\Field as ExtendedField;
use WDV\WdvCustomer\Domain\Model\NewsAuthor as ExtendedNewsAuthor;
use WDV\WdvCustomer\Helpers\YouTubePlaylistHelper;
use WDV\WdvCustomer\Hooks\KesearchHooks;
use WDV\WdvCustomer\Resource\Rendering\YouTubePlaylistRenderer;
use WDV\WdvCustomer\Routing\Enhancer\CustomPageTypeDecorator;
use WDV\WdvCustomer\Upgrade\GridElementsFieldsUpgradeWizard;
use WDV\WdvCustomer\Upgrade\MaskBoxUpgradeWizard;
use WDV\WdvCustomer\Upgrade\MaskContentFieldsUpgradeWizard;
use WDV\WdvCustomer\ViewHelpers\ImageViewHelper as ExtendedImageViewHelper;

defined('TYPO3') || die('Access denied.');

// XClass of typo3 extension "focuspoint" viewhelper
// Needed for adding lazy loading functionality of images
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ImageViewHelper::class] = ['className' => ExtendedImageViewHelper::class];

/** Extend Powermail Domain Model */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][Field::class] = ['className' => ExtendedField::class];

/** Extend NewsAuthor Domain Model */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][NewsAuthor::class] = ['className' => ExtendedNewsAuthor::class];

// FE-Plugin for displaying elements of file collections
ExtensionUtility::configurePlugin(
    'WdvCustomer',
    'ListItemsFromFileCollections',
    [ ListItemsFromFileCollectionsController::class => 'index', ],
    // non-cacheable actions
	[ ListItemsFromFileCollectionsController::class => 'index', ]
);

// FE-Plugin for SMS-DOI
ExtensionUtility::configurePlugin(
    'WdvCustomer',
    'SmsDoiSend',
    [ SmsDoiController::class => 'send' ],
    // non-cacheable actions
	[ SmsDoiController::class => 'send' ]
);
ExtensionUtility::configurePlugin(
    'WdvCustomer',
    'SmsDoiVerify',
    [ SmsDoiController::class => 'verify' ],
    // non-cacheable actions
	[ SmsDoiController::class => 'verify' ]
);

// Register class(es) in this extension for news which extends georg ringers news domain model
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News']['wdv_customer'] = 'wdv_customer';

// Add a hook for displaying news as a list-view on detail pages based on displayed news related news
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['Controller/NewsController.php']['overrideSettings']['WdvCustomer'] = 'WDV\\WdvCustomer\\Hooks\\NewsControllerSettings->modify';

/***************
 * KE_SEARCH Indexer
 */
// Register custom indexer hook for custom news indexing
#$customernewsIndexerClassName = 'WDV\WdvCustomer\CustomernewsIndexer';
#$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] = $customernewsIndexerClassName;
#$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] = $customernewsIndexerClassName;
#$customerpowermailIndexerClassName = 'WDV\WdvCustomer\CustomerpowermailIndexer';
#$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] = $customerpowermailIndexerClassName;
#$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] = $customerpowermailIndexerClassName;

// Register custom indexer hook for custom news indexing
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyExtNewsIndexEntry'] = ['className' => KesearchHooks::class];

/***************
 * YouTube Playlist
 */
// Implement new filetype for YouTube playlists
$GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',youtubeplaylist';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['youtubeplaylist'] = YouTubePlaylistHelper::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['youtubeplaylist'] = 'video/youtubeplaylist';

// Revert onlineMediaHelpers because we want YouTube urls with playlist parameter not to be
// handled by the YouTube helper which is not able to deal with playlists
$tempOnlineMediaHelpers = $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers'];
$reverted = array_reverse($tempOnlineMediaHelpers);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers'] = $reverted;

/** @var RendererRegistry $rendererRegistry */
$rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
$rendererRegistry->registerRendererClass(YouTubePlaylistRenderer::class);
unset($rendererRegistry);

/***************
 * Set ViewHelper Namespace
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['w'] = [
	'WDV\WdvCustomer\ViewHelpers',
];

// Load CK Editor configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['vigo'] = 'EXT:wdv_customer/Configuration/RTE/vigo.yaml';

// Override Translation of wx_consentmanagement
// https://stackoverflow.com/questions/40998470/locallangxmloverride-is-not-working
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:wx_consentbanner/Resources/Private/Language/locallang.xlf'][] = 'EXT:wdv_customer/Resources/Private/Language/Overrides/de.wx_consentbanner.xlf';

/***************
 * Add PageTSConfig
 */
ExtensionManagementUtility::addUserTSConfig(
	'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:yoast_news/Configuration/TsConfig/Page.tsconfig">'
);
ExtensionManagementUtility::addUserTSConfig(
	'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:wdv_customer/Configuration/TsConfig/Page/page.tsconfig">'
);

/***************
 * Add UserTSConfig
 */
// Register custom PageTypeDecorator:
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers'] += ['CustomPageType' => CustomPageTypeDecorator::class];

// Register Upgrades
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['wdvCustomerGridElements'] = GridElementsFieldsUpgradeWizard::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['wdvCustomerMaskContent'] = MaskContentFieldsUpgradeWizard::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['wdvCustomerMaskBoxes'] = MaskBoxUpgradeWizard::class;

$GLOBALS['TYPO3_CONF_VARS']['LOG']['WDV']['WdvCustomer']["Middlewares"]["SmsDoiMiddleware"]['writerConfiguration'] = [
    LogLevel::INFO => [
        FileWriter::class => [
            'logFileInfix' => 'ewe_service'
        ]
    ]
];

$GLOBALS['TYPO3_CONF_VARS']['LOG']['WDV']['WdvCustomer']["Controller"]["SmsDoiController"]['writerConfiguration'] = [
    LogLevel::INFO => [
        FileWriter::class => [
            'logFileInfix' => 'ewe_service'
        ]
    ]
];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['WDV']['WdvCustomer']["Service"]["QueoeweService"]['writerConfiguration'] = [
    LogLevel::INFO => [
        FileWriter::class => [
            'logFileInfix' => 'ewe_service'
        ]
    ]
];
$GLOBALS['TYPO3_CONF_VARS']['LOG']['WDV']['WdvCustomer']["Finisher"]["QueoewePowermailFinisher"]['writerConfiguration'] = [
    LogLevel::INFO => [
        FileWriter::class => [
            'logFileInfix' => 'ewe_service'
        ]
    ]
];

$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['wdv_customer'] = 'EXT:wdv_customer/Resources/Public/CSS/RTE';
