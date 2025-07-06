<?php
namespace WDV\WdvCustomer\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Page
 * @package WDV\WdvCustomer\Domain\Model
 */
class Page extends \In2code\Powermail\Domain\Model\Page
{

    /**
     * Powermail Fields
     *
     * @var ObjectStorage<Field>
     */
    protected $fields = NULL;

    /**
     * @return void
     */
    public function setFields(ObjectStorage $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return ObjectStorage
     */
    public function GetFields(): ObjectStorage
    {
        return $this->fields;
    }
}