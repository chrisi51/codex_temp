<?php
namespace WDV\WdvCustomer\Resource\DisplayCondition;

use TYPO3\CMS\Core\Utility\DebugUtility;

class ContainerCondition {
    public function displayField(array $data): bool
    {
        if ($data['record']['CType'][0] === 'b13-two_columns') {

        }

        DebugUtility::debug($data);
        return true;
    }
}