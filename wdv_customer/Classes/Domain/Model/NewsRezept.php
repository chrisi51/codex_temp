<?php

namespace WDV\WdvCustomer\Domain\Model;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use GeorgRinger\News\Domain\Model\TtContent;

class NewsRezept extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\TtContent>
     */
    #[Lazy]
    protected ObjectStorage $txWdvcustomerNewsRecipeContentElements;

    /**
     * @var string
     */
    protected string $txWdvcustomerNewsRecipeCookingtime = '';

    /**
     * @var string
     */
    protected string $txWdvcustomerNewsRecipeDifficulty = '';

    /**
     * @var string
     */
    protected string $txWdvcustomerNewsRecipeType ='';

    /**
     * Initializing
     *
     * @return \GeorgRinger\News\Domain\Model\News
     */
    public function __construct()
    {
        $this->txWdvcustomerNewsRecipeContentElements = new ObjectStorage();
    }

    /**
     * Get recipe content elements
     *
     * @return ObjectStorage
     */
    public function getTxWdvcustomerNewsRecipeContentElements(): ObjectStorage
    {
        return $this->txWdvcustomerNewsRecipeContentElements;
    }

    /**
     * Set recipe content element list
     *
     * @param ObjectStorage $txWdvcustomerNewsRecipeContentElements recipe content elements
     */
    public function setTxWdvcustomerNewsRecipeContentElements($txWdvcustomerNewsRecipeContentElements): void
    {
        $this->txWdvcustomerNewsRecipeContentElements = $txWdvcustomerNewsRecipeContentElements;
    }

    /**
     * Adds a recipe content element to the record
     */
    public function addTxWdvcustomerNewsRecipeContentElement(TtContent $txWdvcustomerNewsRecipeContentElement): void
    {
        if (!$this->getTxWdvcustomerNewsRecipeContentElements() instanceof ObjectStorage) {
            $this->txWdvcustomerNewsRecipeContentElements = new ObjectStorage();
        }

        $this->txWdvcustomerNewsRecipeContentElements->attach($txWdvcustomerNewsRecipeContentElement);
    }

    /**
     * Get id list of recipe content elements
     *
     * @return string
     */
    public function getTxWdvcustomerNewsRecipeContentElementIdList(): string
    {
        return $this->getIdOfTxWdvcustomerNewsRecipeContentElements();
    }

    /**
     * Get translated id list of recpie content elements
     *
     * @return string
     */
    public function getTranslatedTxWdvcustomerNewsRecipeContentElementIdList(): string
    {
        return $this->getIdOfTxWdvcustomerNewsRecipeContentElements(false);
    }

    /**
     * Collect id list
     *
     * @param bool $original
     * @return string
     */
    protected function getIdOfTxWdvcustomerNewsRecipeContentElements($original = true): string
    {
        $idList = [];
        $txWdvcustomerNewsRecipeContentElements = $this->getTxWdvcustomerNewsRecipeContentElements();
        if ($txWdvcustomerNewsRecipeContentElements) {
            foreach ($this->getTxWdvcustomerNewsRecipeContentElements() as $txWdvcustomerNewsRecipeContentElement) {
                $idList[] = $original ? $txWdvcustomerNewsRecipeContentElement->getUid() : $txWdvcustomerNewsRecipeContentElement->_getProperty('_localizedUid');
            }
        }

        return implode(',', $idList);
    }

    /**
     * @return string
     */
    public function getTxWdvcustomerNewsRecipeCookingtime()
    {

        return $this->txWdvcustomerNewsRecipeCookingtime;
    }

    /**
     * @return string
     */
    public function getTxWdvcustomerNewsRecipeDifficulty()
    {

        return $this->txWdvcustomerNewsRecipeDifficulty;
    }

    /**
     * @return string
     */
    public function getTxWdvcustomerNewsRecipeType()
    {

        return $this->txWdvcustomerNewsRecipeType;
    }

    /**
     * @param string $txWdvcustomerNewsRecipeCookingtime
     */
    public function setTxWdvcustomerNewsRecipeCookingtime($txWdvcustomerNewsRecipeCookingtime): void
    {

        $this->txWdvcustomerNewsRecipeCookingtime = $txWdvcustomerNewsRecipeCookingtime;
    }

    /**
     * @param string $txWdvcustomerNewsRecipeDifficulty
     */
    public function setTxWdvcustomerNewsRecipeDifficulty($txWdvcustomerNewsRecipeDifficulty): void
    {

        $this->txWdvcustomerNewsRecipeDifficulty = $txWdvcustomerNewsRecipeDifficulty;
    }

    /**
     * @param string $txWdvcustomerNewsRecipeType
     */
    public function setTxWdvcustomerNewsRecipeType($txWdvcustomerNewsRecipeType): void
    {

        $this->txWdvcustomerNewsRecipeType = $txWdvcustomerNewsRecipeType;
    }
}