<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\Upgrade;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate gridelements fields
 */
class GridElementsFieldsUpgradeWizard implements UpgradeWizardInterface
{
    /**
     * @var string
     */
    private const TABLE_NAME = 'tt_content';

    /**
     * @var string
     */
    private const FLEXFORM_FIELD = 'pi_flexform';

    /**
     * @var string
     */
    private const CTYPE_FIELD = 'CType';

    protected array $allowedFields = [
        'css-classes' => 'flexform_css_classes',
        'cssclasses' => 'flexform_css_classes',
        'col1_css-override' => 'flexform_col1_css_override',
        'col1_css-classes' => 'flexform_col1_css_classes',
        'col2_css-override' => 'flexform_col2_css_override',
        'col2_css-classes' => 'flexform_col2_css_classes',
        'col3_css-override' => 'flexform_col3_css_override',
        'col3_css-classes' => 'flexform_col3_css_classes',
        'col4_css-override' => 'flexform_col4_css_override',
        'col4_css-classes' => 'flexform_col4_css_classes',
        'layout' => 'flexform_layout',
        'scrollbutton' => 'flexform_scrollbutton',
        'top_space' => 'flexform_top_space',
        'bottom_space' => 'flexform_bottom_space',
        'cssid' => 'flexform_cssid',
        'boxtype' => 'flexform_boxtype',
        'fullwidth' => 'flexform_fullwidth',
        'contentwidth' => 'flexform_contentwidth',
        'bgimage' => 'flexform_bgimage',
        'bgvideo' => 'flexform_bgvideo',
        'layout_gib8' => 'flexform_layout_gib8',
        'boxalign' => 'flexform_boxalign',
        'boxvalign' => 'flexform_boxvalign',
        'width' => 'flexform_width',
        'rotationtime' => 'flexform_rotationtime',
    ];

    public function getTitle(): string
    {
        return '[GRIDELEMENTS] Migrate field tt_content.pi_flexform to separate fields.';
    }

    public function getDescription(): string
    {
        return 'Changes field tt_content.pi_flexform and move value into separate fields.';
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
        $queryBuilder = $this->getPreparedQueryBuilder();
        $rows = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)->where($queryBuilder->expr()->eq(
            self::CTYPE_FIELD,
            $queryBuilder->createNamedParameter('gridelements_pi1')
        ))->executeQuery()->fetchAllAssociative();

        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        foreach ($rows as $row) {
            $flexform = $flexFormService->convertFlexFormContentToArray($row[self::FLEXFORM_FIELD]);
            $updates = [];
            foreach ($flexform as $flexformKey => $flexformValue) {
                if (isset($this->allowedFields[$flexformKey]) && ($flexformValue !== null && $flexformValue !== '')) {
                    $updates[$this->allowedFields[$flexformKey]] = $flexformValue;
                }
            }

            if ($updates !== []) {
                $queryBuilder = $this->getPreparedQueryBuilder();
                $queryBuilder
                    ->update(self::TABLE_NAME)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($row['uid'], Connection::PARAM_INT)
                        ),
                    );
                foreach ($updates as $updateKey => $updateValue) {
                    $queryBuilder->set($updateKey, $updateValue);
                }

                $queryBuilder->executeStatement();
            }
        }

        return true;
    }

    protected function hasRecordsToUpdate(): bool
    {
        return true;
    }

    protected function getPreparedQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable(self::TABLE_NAME);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function getIdentifier(): string
    {
        return 'wdvCustomerGridElements';
    }
}
