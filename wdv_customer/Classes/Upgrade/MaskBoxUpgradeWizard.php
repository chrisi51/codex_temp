<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\Upgrade;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate mask content boxes
 */
class MaskBoxUpgradeWizard implements UpgradeWizardInterface
{
    public function getTitle(): string
    {
        return '[MASK] Migrate mask boxes';
    }

    public function getDescription(): string
    {
        return 'Changes field tx_mask_content_role for text / textmedia';
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
        $tableName = 'tt_content';
        $queryBuilder = $this->getPreparedQueryBuilder($tableName);
        $rows = $queryBuilder
            ->select('uid')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq(
                        'CType',
                        $queryBuilder->createNamedParameter('mask_greybox')
                    ),
                    $queryBuilder->expr()->eq(
                        'CType',
                        $queryBuilder->createNamedParameter('mask_greenbox')
                    ),
                ),
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $queryBuilder2 = $this->getPreparedQueryBuilder($tableName);
            $queryBuilder2
                ->update($tableName)
                ->where(
                    $queryBuilder2->expr()->eq(
                        'tx_mask_content_parent_uid',
                        $queryBuilder2->createNamedParameter($row['uid'], Connection::PARAM_INT)
                    ),
                    $queryBuilder2->expr()->eq(
                        'tx_mask_content_role',
                        $queryBuilder2->createNamedParameter('tx_mask_content')
                    ),
                    $queryBuilder2->expr()->or(
                        $queryBuilder2->expr()->eq(
                            'CType',
                            $queryBuilder2->createNamedParameter('text')
                        ),
                        $queryBuilder2->expr()->eq(
                            'CType',
                            $queryBuilder2->createNamedParameter('textmedia')
                        ),
                    ),
                );
            $queryBuilder2->set('tx_mask_content_tablenames', 'tt_content');
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
        return 'wdvCustomerMaskBoxes';
    }
}
