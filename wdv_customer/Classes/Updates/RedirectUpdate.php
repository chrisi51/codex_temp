<?php
declare(strict_types=1);

namespace WDV\WdvCustomer\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * BackendLayoutUpdate
 */
class RedirectUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string
     */
    protected $tableNew = 'sys_redirect';

    /**
     * @var string
     */
    protected $tableOld = 'tx_urlredirect_domain_model_config';

    /**
     * @var array
     */
    protected $mapping = [
        'uid' => 'uid',
        'request_uri' => 'source_path',
        'target_uri' => 'target',
        'http_status' => 'target_statuscode',
        'tstamp' => 'updatedon',
        'crdate' => 'createdon',
    ];

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return '[WDV Customer] Migrate redirects';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Migrate redirects from old tx_urlredirect Extension to Core Extension "Redirects"';
    }

    /**
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * @return bool
     */
    public function updateNecessary(): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableOld);
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $elementCount = $queryBuilder->count('uid')
            ->from($this->tableOld)
            ->executeQuery()
            ->fetchOne();
        return (bool)$elementCount;
    }

    /**
     * @return bool
     */
    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->tableOld);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder
            ->select('uid', 'request_uri', 'target_uri', 'http_status', 'tstamp', 'crdate')
            ->from($this->tableOld)
            ->executeQuery();

        while ($record = $statement->fetchAllAssociative()) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->insert($this->tableNew)
                ->values([
                    'uid' => $record['uid'],
                    'pid' => 0,
                    'updatedon' => $record['tstamp'],
                    'createdon' => $record['crdate'],
                    'createdby' => 32,
                    'deleted' => 0,
                    'disabled' => 0,
                    'starttime' => 0,
                    'endtime' => 0,
                    'source_host' => '*',
                    'source_path' => $record['request_uri'],
                    'is_regexp' => 0,
                    'force_https' => 0,
                    'respect_query_parameters' => 0,
                    'keep_query_parameters' => 0,
                    'target' => $record['target_uri'],
                    'target_statuscode' => $record['http_status'],
                    'hitcount' => 0,
                    'lasthiton' => 0,
                    'disable_hitcount' => 0
                ]);
            $queryBuilder->executeQuery();
        }

        return true;
    }
}
