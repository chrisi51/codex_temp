<?php

namespace WDV\WdvCustomer\Hooks;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Christopher Wirsing, https://www.cwms.de
 *  (c) 2019 Christopher Wirsing, https://www.wdv.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NewsControllerSettings
{

    public function modify(
        array $params
    )
    {
        // get the original settings which are active for this rendering
        $settings = $params['originalSettings'];

        // here, we handle $settings['hideIdList']
        // used to hide news which are included in a multi-page
        // in list views.
        // this is depending on the setting showImpliedInMultiPage and when its '0'
        // the news items will be hidden. if its '1', the will be showen.
        // this is the case in news-similar list view!

        if ((int)($settings['showImpliedInMultiPage'] === 0)) {

            // if we have a list view, dont render news which
            // are included in a multipage-article.
            // do this, by faking the list-views setting to ignore the id.
            // we need this logic every time!
            // the parameter hideIdList will only be respected in list-views!
            // we have to do it here, because the "implied_from"-field is just fake for extbase
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_news_domain_model_news_multipage_mm');

            $queryBuilder = $connection
                ->createQueryBuilder();

            $query = $queryBuilder
                ->select('uid_local')
                ->from('tx_news_domain_model_news_multipage_mm')
                ->executeQuery();

            $multiPageNewsCounter = 0;
            $hideIdListString = '>';

            $rows = $query->fetchAllAssociative();
            foreach ($rows as $row) {
                if ($multiPageNewsCounter == 0) {
                    $hideIdListString = $row['uid_local'];
                } else {
                    $hideIdListString .= ',' . $row['uid_local'];
                }
                ++$multiPageNewsCounter;
            }

            if ($multiPageNewsCounter > 0) {
                $settings['hideIdList'] = $hideIdListString;
            }
        }

        // Get all news with category 73 (hide in list views) and add them to $settings['hideIdList']
        $connectionSysCategory = GeneralUtility::makeInstance(
            ConnectionPool::class)
            ->getConnectionForTable('sys_category_record_mm');

        $queryBuilder = $connectionSysCategory
            ->createQueryBuilder();

        $querySysCategory = $queryBuilder
            ->select('uid_foreign')
            ->from('sys_category_record_mm')
            ->where('uid_local=73')
            ->executeQuery();

        $settingsHideinListArray = explode(',', (string)($settings['hideIdList'] ?? ''));

        $rows = $querySysCategory->fetchAllAssociative();
        foreach ($rows as $row) {
            $settingsHideinListArray[] = (string)$row['uid_foreign'];
        }

        $settings['hideIdList'] = implode(',', array_unique($settingsHideinListArray));

        // from here, we now handle $settings['selectedList']


        // We have no list view for landingpages, so when calling the detail view of a landingpage
        // the get parameter of the  news id is missing. Since we need this parameter for lib.news-landingpage
        // we fake it.
#        if (isset($settings['singleNews']) && !empty($settings['singleNews'])) {
#
#            GeneralUtility::_GP(['news' => $settings['singleNews']]);
#        }
# not today
        
        // Get the parameters of tx_news_pi1 from url
        $getData = GeneralUtility::_GP('tx_news_pi1');

        // If there are parameters of tx_news_pi1 in the url...
        // (we have it if we display a news on a detailpage)
        if (isset($getData['news']) || isset($getData['news_preview'])) {
            // No modifications on $settings['selectedList'] nessecary on dropdown menus (templateLayouts 8, 9, 10)
            if ($settings['templateLayout'] == 8 ||
                $settings['templateLayout'] == 9 ||
                $settings['templateLayout'] == 10 ||
                $settings['templateLayout'] == 40
            ) {

                return $settings;
            }

            // Grab the UID of the news
            $newsId = 0;
            if (isset($getData['news'])) {
                $newsId = (int)$getData['news'];
            } elseif (isset($getData['news_preview'])) {
                $newsId = (int)$getData['news_preview'];
            }
            
            // get the news object by its uid
            $newsRepository = GeneralUtility::makeInstance(NewsRepository::class);
            $news = $newsRepository->findByUid($newsId);

            // If we have an object
            if (isset($news)) {
                $categories_uids = null;
                // Get Categories of current news for later use
                $categories = $news->getCategories();
                foreach ($categories as $category) {
                    $categories_uids[] = $category->getUid();
                }

                if (is_array($categories_uids))
                    $settings['categories'] = implode(",", $categories_uids);

                // Do some stuff based on the type of the news object
                switch ($news->getType()) {

                    // get implied news for themenspecial
                    // news type is '3' (themenspecial)
                    case '3':

                        $impliedNews = $news->getTxWdvcustomerThemenspecialImpliedNews();
                        $impliedNewsString = '>';
                        $impliedNewsCounter = 0;

                        foreach ($impliedNews as $impliedNewsSingle) {

                            if ($impliedNewsCounter == 0) {

                                $impliedNewsString = $impliedNewsSingle->getUid();

                            } else {

                                $impliedNewsString .= ',' . $impliedNewsSingle->getUid();
                            }

                            ++$impliedNewsCounter;
                        }

                        $settings['selectedList'] = $impliedNewsString;

                        // when using selectedList we need to empty order informations to use editors sorting order
                        $settings['orderBy'] = '';
                        $settings['orderDirection'] = '';

                        break;

                    // get implied news for rezeptsammlung
                    // news type is '8' (rezeptsammlung)
                    case '8':

                        $impliedNews = $news->getTxWdvcustomerRezeptsammlungImpliedNews();
                        $impliedNewsString = '>';
                        $impliedNewsCounter = 0;

                        foreach ($impliedNews as $impliedNewsSingle) {

                            if ($impliedNewsCounter == 0) {

                                $impliedNewsString = $impliedNewsSingle->getUid();

                            } else {

                                $impliedNewsString .= ',' . $impliedNewsSingle->getUid();
                            }

                            ++$impliedNewsCounter;
                        }

                        $settings['selectedList'] = $impliedNewsString;

                        // when using selectedList we need to empty order informations to use editors sorting order
                        $settings['orderBy'] = '';
                        $settings['orderDirection'] = '';

                        break;

                    // get implied news for Externalurlsammlung
                    // news type is '11' (Externalurlsammlung)
                    case '11':

                        $impliedNews = $news->getTxWdvcustomerExternalurlsammlungImpliedNews();
                        $impliedNewsString = '>';
                        $impliedNewsCounter = 0;

                        foreach ($impliedNews as $impliedNewsSingle) {

                            if ($impliedNewsCounter == 0) {

                                $impliedNewsString = $impliedNewsSingle->getUid();

                            } else {

                                $impliedNewsString .= ',' . $impliedNewsSingle->getUid();
                            }

                            ++$impliedNewsCounter;
                        }

                        $settings['selectedList'] = $impliedNewsString;

                        break;

                    // get implied news for landingpage
                    // news type is '12' (landingpage)
                    case '12':

                        $impliedNews = $news->getTxWdvcustomerLandingpageImpliedNews();
                        $impliedNewsString = '>';
                        $impliedNewsCounter = 0;

                        foreach ($impliedNews as $impliedNewsSingle) {

                            if ($impliedNewsCounter == 0) {

                                $impliedNewsString = $impliedNewsSingle->getUid();

                            } else {

                                $impliedNewsString .= ',' . $impliedNewsSingle->getUid();
                            }

                            ++$impliedNewsCounter;
                        }

                        $settings['selectedList'] = $impliedNewsString;
                        $settings['recursive'] = 250;
                        $settings['categoryConjunction'] = '';

                        // when using selectedList we need to empty order informations to use editors sorting order
                        $settings['orderBy'] = '';
                        $settings['orderDirection'] = '';

                        break;

                    // get related news for other news types
                    default:
                        $relatedNews = $news->getRelated();
                        // $relatedNews = $news->getAllRelatedSorted();
                        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($relatedNews);

                        $relatedNewsString = '>';
                        $relatedNewsCounter = 0;

                        foreach ($relatedNews as $relatedNewsSingle) {
                            if ($relatedNewsCounter == 0) {
                                $relatedNewsString = $relatedNewsSingle->getUid();
                            } else {
                                $relatedNewsString .= ',' . $relatedNewsSingle->getUid();
                            }

                            ++$relatedNewsCounter;
                        }

                        $settings['selectedList'] = $relatedNewsString;
                        break;
                }
            }
        }

        return $settings;
    }
}