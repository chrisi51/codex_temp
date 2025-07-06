<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\ViewHelpers\Condition\Iterator;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ContainsViewHelper extends \FluidTYPO3\Vhs\ViewHelpers\Condition\Iterator\ContainsViewHelper
{
    /**
     * Patch for bug https://github.com/FluidTYPO3/vhs/issues/1909
     *
     * @param array|DomainObjectInterface[]|QueryResult|ObjectStorage $haystack
     * @param integer|DomainObjectInterface $needle
     * @return boolean|integer
     */
    protected static function assertHaystackIsObjectStorageAndHasNeedle($haystack, $needle)
    {
        $index = 0;
        if ($needle instanceof AbstractDomainObject) {
            $needle = $needle->getUid();
        }
        /** @var DomainObjectInterface $candidate */
        foreach ($haystack as $candidate) {
            if ($candidate->getUid() === (int)$needle) {
                return $index;
            }
            $index++;
        }
        return false;
    }
}
