<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\Upgrade;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate mask content fields
 */
class MaskContentFieldsUpgradeWizard implements UpgradeWizardInterface
{
    public function getTitle(): string
    {
        return '[MASK] Migrate mask values';
    }

    public function getDescription(): string
    {
        return 'Changes field tx_mask_content_role and tx_mask_content_tablenames';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function updateNecessary(): bool
    {
        return $this->hasRecordsToUpdate();
    }

    public function executeUpdate(): bool
    {
        // Alles zurücksetzen
        // UPDATE tt_content SET tx_mask_content_tablenames = 'tt_content' WHERE tx_mask_content_role = 'tx_mask_content'

        /**
        $tableName = 'tt_content';
        $queryBuilder = $this->getPreparedQueryBuilder($tableName);
        $queryBuilder
            ->update($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    'tx_mask_content_role',
                    $queryBuilder->createNamedParameter('tx_mask_content')
                ),
            );
        $queryBuilder->set('tx_mask_content_tablenames', 'tt_content');
        $queryBuilder->executeStatement();
         */

        // Gehe durch die Tabelle tx_mask_item mit parenttable = 'tt_content'
        $tableName = 'tx_mask_item';
        $queryBuilder = $this->getPreparedQueryBuilder($tableName);
        $rows = $queryBuilder
            ->select('*')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq('parenttable', $queryBuilder->createNamedParameter('tt_content'))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($rows as $row) {
            //$parentId = $row['parentid'];
            $parentId = $row['uid'];

            /**
            $queryBuilder2 = $this->getPreparedQueryBuilder($tableName);
            $queryBuilder2
                ->update('tt_content')
                ->where(
                    $queryBuilder2->expr()->eq(
                        'uid',
                        $queryBuilder2->createNamedParameter($parentId, Connection::PARAM_INT)
                    ),
                );
            $queryBuilder2->set('tx_mask_content_tablenames', 'tx_mask_item');
            $queryBuilder2->executeStatement();
             */
            $queryBuilder2 = $this->getPreparedQueryBuilder($tableName);
            $queryBuilder2
                ->update('tt_content')
                ->where(
                    $queryBuilder2->expr()->eq(
                        'tx_mask_content_parent_uid',
                        $queryBuilder2->createNamedParameter($parentId, Connection::PARAM_INT)
                    ),
                );
            $queryBuilder2->set('tx_mask_content_tablenames', 'tx_mask_item');
            $queryBuilder2->executeStatement();
        }

        return true;
    }

    protected function hasRecordsToUpdate(): bool
    {
        return true;
    }

    protected function getPreparedQueryBuilder(string $tableName): QueryBuilder
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function getIdentifier(): string
    {
        return 'wdvCustomerMaskContent';
    }
}
