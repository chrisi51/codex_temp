<?php

namespace VIGO\VigoStoerer\Domain\Repository;

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
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Doctrine\DBAL\Driver\Statement;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class StoererRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Find stoerer by page uid
     *
     * @param integer $pageUid Uid of page
     * @return mixed
     */
    public function findByPageUid($pageUid)
    {

        $foreign = 'tx_vigostoerer_domain_model_stoerer';
        $mm = 'tx_vigostoerer_pages_stoerer_mm';
        $local = 'pages';
        $item = (int)$pageUid;
        $result = [];

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable($foreign);
        $expr = $queryBuilder->expr();

        /** @var Statement $stoerer */
        $stoerer = $queryBuilder
            ->select('foreign.*')
            ->from($foreign, 'foreign')
            ->innerJoin('foreign', $mm, 'mm', $expr->eq('foreign.uid', 'mm.uid_foreign'))
            ->innerJoin('mm', $local, 'local', $expr->eq('mm.uid_local', 'local.uid'))
            ->where(
                $expr->eq('local.uid', $queryBuilder->createNamedParameter($item, \PDO::PARAM_INT))
            )->orderBy('mm.sorting')->executeQuery()
            ->fetchAll();

	    return $stoerer;
    }

    /**
     * Find stoerer by news uid
     *
     * @param integer $newsUid Uid of news
     * @return mixed
     */
    public function findByNewsUid($newsUid)
    {

        $foreign = 'tx_vigostoerer_domain_model_stoerer';
        $mm = 'tx_vigostoerer_news_stoerer_mm';
        $local = 'tx_news_domain_model_news';
        $item = (int)$newsUid;
        $result = [];

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable($foreign);
        $expr = $queryBuilder->expr();

        /** @var Statement $stoerer */
        $stoerer = $queryBuilder
            ->select('foreign.*')
            ->from($foreign, 'foreign')
            ->innerJoin('foreign', $mm, 'mm', $expr->eq('foreign.uid', 'mm.uid_foreign'))
            ->innerJoin('mm', $local, 'local', $expr->eq('mm.uid_local', 'local.uid'))
            ->where(
                $expr->eq('local.uid', $queryBuilder->createNamedParameter($item, \PDO::PARAM_INT))
            )->orderBy('mm.sorting')->executeQuery()
            ->fetchAll();

        return $stoerer;
    }

	/**
	 * Liefert alle Störer mit all_pages=1 für diese Site
	 */
	public function findGlobalBySiteIdentifier(string $siteIdentifier): array
	{
	    $table = 'tx_vigostoerer_domain_model_stoerer';
	    $qb    = GeneralUtility::makeInstance(
	        \TYPO3\CMS\Core\Database\ConnectionPool::class
	    )->getQueryBuilderForTable($table);

	    return $qb->select('*')
	        ->from($table)
	        ->where(
	            $qb->expr()->eq('all_pages', $qb->createNamedParameter(1, \PDO::PARAM_INT)),
	            $qb->expr()->like('sites', $qb->createNamedParameter('%' . $siteIdentifier . '%'))
	        )
	        ->orderBy('sorting')
	        ->executeQuery()
	        ->fetchAll();
	}

}
