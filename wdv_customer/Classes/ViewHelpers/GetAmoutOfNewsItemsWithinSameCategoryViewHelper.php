<?php

namespace WDV\WdvCustomer\ViewHelpers;

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
/**
 * Class GetAmoutOfNewsItemsWithinSameCategoryViewHelper
 * @package WDV\WdvCustomer\ViewHelpers
 */

use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class GetAmoutOfNewsItemsWithinSameCategoryViewHelper extends AbstractViewHelper
{
	/**
  * Initialize arguments.
  *
  * @throws Exception
  */
 public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('pageuid', 'string', 'pageuid function');
		$this->registerArgument('recursive', 'boolean', 'recursive function');
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return string
	 */
	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	) {
		$pageuid = $arguments['pageuid'] ?: NULL;
		$recursive = $arguments['recursive'] ?: false;

		$newsRepository = GeneralUtility::makeInstance(NewsRepository::class);

		// Count all news for given pageuid leaving out all news not exist in multipage_mm table and which have set
		// category 73 (don't show in list views)
		$sql = "SELECT COUNT(*) FROM `tx_news_domain_model_news`,`sys_category_record_mm` 
                WHERE `sys_category_record_mm`.`uid_foreign` = `tx_news_domain_model_news`.`uid` 
                AND `sys_category_record_mm`.`uid_local` IN ( 
                    SELECT `sys_category_record_mm`.`uid_local` FROM `sys_category_record_mm` 
                    WHERE `sys_category_record_mm`.`uid_foreign` = '" . $pageuid . "'
                    AND `sys_category_record_mm`.`tablenames` = 'pages' )  
                AND `tx_news_domain_model_news`.`deleted` = 0 
                AND `tx_news_domain_model_news`.`hidden` = 0 
                AND NOT EXISTS (SELECT 1 FROM `sys_category_record_mm` 
                                WHERE `sys_category_record_mm`.`uid_foreign` = `tx_news_domain_model_news`.`uid`
                                AND `sys_category_record_mm`.`uid_local` = 73)
                AND `tablenames` = 'tx_news_domain_model_news'";

		$countQuery = $newsRepository->createQuery();
		$countQuery->statement($sql);

		$result = $countQuery->execute(true);

		return $result[0]['COUNT(*)'];
	}
}
