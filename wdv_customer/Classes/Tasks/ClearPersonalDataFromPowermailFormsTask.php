<?php

namespace WDV\WdvCustomer\Tasks;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Harald Schäfer, https://www.wdv.de
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
 * Class ClearPersonalDataFromPowermailFormsTask
 * @package WDV\WdvCustomer\Tasks
 */
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClearPersonalDataFromPowermailFormsTask extends AbstractTask {

    public function execute(): bool {

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_powermail_domain_model_mail');

        $result = $queryBuilder
            ->select('*')->from('tx_powermail_domain_model_mail')->executeQuery();

//        $rows = $result->fetchAll();


        $queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);

        $competitionUids = [];
        while ($row = $result->fetch()) {

            // get all pids in the competition folder
            $resCompetitionTreeList = explode(',', (string) $queryGenerator->getTreeList(326, 5, 0, 1));

            // competition forms
            if (in_array($row['pid'], $resCompetitionTreeList)) {

//                debug($row['pid']);
//                debug($row);
                $competitionUids[] = $row['uid'];
            }

        }

        return true;
    }

    /**
     * Deletes all emails after a given period of time for DS-GVO reason.
     * Deletes double optin data not confirmed and older than 2 weeks.
     * Deletes confirmed data older than 90 days
     *
     * @return void
     */
    public function cleanMailDataCommand(): void {

        // DOI not confirmed
//        $resultDoiNotConfirmed = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
//            'uid',
//            'tx_powermail_domain_model_mail',
//            'crdate < ' . strtotime("-3 days", time()) . ' AND hidden = 1'
//        );
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_powermail_domain_model_mail');
        $queryBuilder = $connection->createQueryBuilder();
        $resultDoiNotConfirmed = $queryBuilder->select('uid')
	                                          ->from('tx_powermail_domain_model_mail')
	                                          ->where('crdate < ' . strtotime("-3 days", time()) . ' AND hidden = 1');

	    $this->deleteMailData($resultDoiNotConfirmed);

        // contact form
//        $resultContactForm = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
//            'uid',
//            'tx_powermail_domain_model_mail',
//            'crdate < ' . strtotime("-1 days", time()) . ' AND hidden = 0 AND pid = 13'
//        );
	    $resultContactForm = $queryBuilder->select('uid')
	                                          ->from('tx_powermail_domain_model_mail')
	                                          ->where('crdate < ' . strtotime("-1 days", time()) . ' AND hidden = 0 AND pid = 13');
        $this->deleteMailData($resultContactForm);

        // online order form
//        $resultOrderForm = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
//            'uid',
//            'tx_powermail_domain_model_mail',
//            'crdate < ' . strtotime("-1 month", time()) . ' AND hidden = 0 AND pid = 32'
//        );
	    $resultOrderForm = $queryBuilder->select('uid')
	                                      ->from('tx_powermail_domain_model_mail')
	                                      ->where('crdate < ' . strtotime("-1 month", time()) . ' AND hidden = 0 AND pid = 32');
        $this->deleteMailData($resultOrderForm);

        // speakers service
//        $resultSpeakerServiceForm = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
//            'uid',
//            'tx_powermail_domain_model_mail',
//            'crdate < ' . strtotime("-1 month", time()) . ' AND hidden = 0 AND pid = 47'
//        );
	    $resultSpeakerServiceForm = $queryBuilder->select('uid')
	                                    ->from('tx_powermail_domain_model_mail')
	                                    ->where('crdate < ' . strtotime("-1 month", time()) . ' AND hidden = 0 AND pid = 47');
        $this->deleteMailData($resultSpeakerServiceForm);

    }

    /**
     * Remove all array entries in mails and related answers table
     *
     * @param array $datasetToRemove
     * @return void
     */
    public function deleteMailData($datasetToRemove): void {

        $mailIds = [];

        // delete all data for all given mailIds
        if ($datasetToRemove !== []) {

            foreach ($datasetToRemove as $toRemove) {

                $mailIds[] = $toRemove['uid'];

            }

            $mailIdsImplode = implode(',', $mailIds);

            // delete mail entries
//            $GLOBALS['TYPO3_DB']->exec_DELETEquery(
//                'tx_powermail_domain_model_mail',
//                'uid in (' . $mailIdsImplode . ')'
//            );
//
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_powermail_domain_model_mail');

            $arrayWhere = [
                'deleted' => 1,
                'uid' => 'IN (' . $mailIdsImplode . ')'
            ];
            $connection->delete('tx_powermail_domain_model_mail', $arrayWhere);


            // delete the related answers
//            $GLOBALS['TYPO3_DB']->exec_DELETEquery(
//                'tx_powermail_domain_model_answers',
//                'mail in (' . $mailIdsImplode . ')'
//            );

            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_powermail_domain_model_answers');
            $connection->delete('tx_powermail_domain_model_answers', $arrayWhere);

        }
    }


}