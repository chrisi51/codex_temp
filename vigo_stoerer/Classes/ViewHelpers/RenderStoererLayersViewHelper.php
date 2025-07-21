<?php

namespace VIGO\VigoStoerer\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Christopher Wirsing, https://www.cwms.de
 *  (c) 2019 Christopher Wirsing, https://www.wdv.de
 *  (c) 2024 Christian Hillebrand, https://www.gms.de
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

use VIGO\VigoStoerer\Domain\Repository\StoererRepository;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class RenderStoererLayersViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     * @throws Exception
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('pageUid', 'integer', '', false);
        $this->registerArgument('newsUid', 'integer', '', false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return array
	 */
    public static function renderStatic(
        array                     $arguments,
        \Closure                  $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): array
	{
        $pageUid = $arguments['pageUid'] ?: false;
        $newsUid = $arguments['newsUid'] ?: false;

        $stoererRepository = GeneralUtility::makeInstance(StoererRepository::class);

        $arr_stoerer = [];
        $hasNewsStoerer = 0;

		// --- Aktuellen PSR-7 Request holen ---
        $psrRequest = $GLOBALS['TYPO3_REQUEST'] ?? null;
        $siteIdentifier = '';
        if ($psrRequest) {
            $site = $psrRequest->getAttribute('site');
            if ($site) {
                $siteIdentifier = $site->getIdentifier();
            }
        }

        // 1) Globale all_pages-Störer
        if ($siteIdentifier !== '') {
            foreach ($stoererRepository->findGlobalBySiteIdentifier($siteIdentifier) as $row) {
                $arr_stoerer[] = $stoererRepository->findByUid((int)$row['uid']);
            }
        }

        // 2) Spezifische News-Störer
		if (!empty($newsUid)) {
            foreach ($stoererRepository->findByNewsUid($newsUid) as $stoerer) {
                $hasNewsStoerer = 1;
                $arr_stoerer[] = $stoererRepository->findByUid($stoerer['uid']);
            }
        }

        // 3) Spezifische Seiten-Störer
        if ($hasNewsStoerer == 0 && !empty($pageUid)) {
            foreach ($stoererRepository->findByPageUid($pageUid) as $stoerer) {
                $arr_stoerer[] = $stoererRepository->findByUid($stoerer['uid']);
            }
        }

        return array_unique($arr_stoerer);
    }
}
