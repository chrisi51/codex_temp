<?php
namespace WDV\WdvCustomer\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Form
 * @package WDV\WdvCustomer\Domain\Model
 */
class Form extends \In2code\Powermail\Domain\Model\Form
{

    /**
     * pages
     *
     * @var ObjectStorage<Page>
     */
    protected $pages;

    /**
     * @return void
     */
    public function setPages(ObjectStorage $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * @return ObjectStorage
     */
    public function getPages()
    {
        return $this->pages;
    }
}