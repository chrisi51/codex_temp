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
 * Class GetAuthorInformationsFromUidViewHelper
 * @package WDV\WdvCustomer\ViewHelpers
 */
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class GetAuthorInformationsFromUidViewHelper extends AbstractViewHelper
{

	/**
  * Initialize arguments.
  *
  * @throws Exception
  */
 public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('authoruid', 'int', 'id of the news author');
		$this->registerArgument('field', 'string', 'field to search');
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

		$authoruid = $arguments['authoruid'] ?: 0;
		$field = $arguments['field'] ?: '';

		$result = '';
		// Default select mask dropdown expert results in $authoruid = null!
		if (!isset($authoruid) || $authoruid == '') return null;

		if ($field == 'image') {
			$fileRepository = GeneralUtility::makeInstance(FileRepository::class);
			$fileObjects = $fileRepository->findByRelation('tx_mdnewsauthor_domain_model_newsauthor', 'image', $authoruid);

			if (isset($fileObjects[0])) {
				$result = $fileObjects[0];
			}

		} elseif ($field > '') {
			$connection = GeneralUtility::makeInstance(ConnectionPool::class)
			                            ->getConnectionForTable('tx_mdnewsauthor_domain_model_newsauthor');

            $queryBuilder = $connection->createQueryBuilder();
            $query = $queryBuilder
                ->select($field)
                ->from('tx_mdnewsauthor_domain_model_newsauthor')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($authoruid, Connection::PARAM_INT)
                    )
                )
                ->executeQuery();
            $resultArray = $query->fetchAssociative();
            $result = trim((string) ($resultArray[$field] ?? ''));
		}

		return $result;
	}

//    /**
//     * @param $authoruid string
//     * @param $field string
//     *
//     * @return string
//     */
//    public function render(
//        $authoruid = null,
//        $field = '')
//    {
//        $result = '';
//        // Default select mask dropdown expert results in $authoruid = null!
//        if (!isset($authoruid) || $authoruid == '') return null;
//
//        if ($field == 'image') {
//            $fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
//            $fileObjects = $fileRepository->findByRelation('tx_mdnewsauthor_domain_model_newsauthor', 'image', $authoruid);
//
//            if (isset($fileObjects[0])) {
//                $result = $fileObjects[0];
//            }
//
//        } elseif ($field > '') {
//            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
//                ->getConnectionForTable('tx_mdnewsauthor_domain_model_newsauthor');
//
//            $queryBuilder = $connection->createQueryBuilder();
//
//            $query = $queryBuilder
//                ->select('' . $field . '')
//                ->from('tx_mdnewsauthor_domain_model_newsauthor')
//                ->where( $queryBuilder->expr()->eq( 'uid', ''. $authoruid . '' ) )
//                ->execute();
//
//            $resultArray = $query->fetch();
//            $result = trim($resultArray[$field]);
//        }
//        return $result;
//    }
}

