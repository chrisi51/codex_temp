<?php

/***
 *
 * This file is part of the "WX ImageLicenseView" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2025 Christian Hillebrand <typo3@webxass.de>, webxass.de
 *
 ***/

$EM_CONF[$_EXTKEY] = [
    'title' => 'WX VideoListing',
    'description' => 'Sucht nach Videos und listet diese mit den URLS, auf denen sie eingebettet sind',
    'category' => 'distribution',
    'author' => 'Christian Hillebrand',
    'author_email' => 'typo3@webxass.de',
    'author_company' => 'webxass.de',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => false,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => false,
    'lockType' => '',
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            'WX\\WxVideoListing\\' => 'Classes',
        ],
    ],
    'constraints' => [
        'depends' => [],
        'conflicts' => [],
        'suggests' => [],
    ],
];

