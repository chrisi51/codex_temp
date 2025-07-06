<?php

namespace WDV\WdvCustomer\Domain\Model;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

class NewsThemenSpecial extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerThemenspecialImpliedNews;

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerNewsImpliedFromThemenspecial;

    /**
     * Get txWdvcustomerThemenspecialImpliedNews news
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerThemenspecialImpliedNews()
    {
        return $this->txWdvcustomerThemenspecialImpliedNews;
    }

    /**
     * Set txWdvcustomerThemenspecialImpliedNews from
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $impliedFrom
     */
    public function setTxWdvcustomerNewsImpliedFromThemenspecial($impliedFrom): void
    {
        $this->txWdvcustomerNewsImpliedFromThemenspecial = $impliedFrom;
    }

    /**
     * Get txWdvcustomerThemenspecialImpliedNews from
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerNewsImpliedFromThemenspecial()
    {
        return $this->txWdvcustomerNewsImpliedFromThemenspecial;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromThemenspecial items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerNewsImpliedFromThemenspecialSorted()
    {
        $items = $this->getTxWdvcustomerNewsImpliedFromThemenspecial();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromThemenspecial items sorted by datetime
     *
     * @return array
     */
    public function getAllTxWdvcustomerThemenspecialImpliedNewsSorted()
    {
        $all = [];
        $itemsTxWdvcustomerThemenspecialImpliedNews = $this->getTxWdvcustomerThemenspecialImpliedNews();
        if ($itemsTxWdvcustomerThemenspecialImpliedNews) {
            $all = array_merge($all, $itemsTxWdvcustomerThemenspecialImpliedNews->toArray());
        }

        $itemsTxWdvcustomerNewsImpliedFromThemenspecial = $this->getTxWdvcustomerNewsImpliedFromThemenspecial();
        if ($itemsTxWdvcustomerNewsImpliedFromThemenspecial) {
            $all = [...$all, ...$itemsTxWdvcustomerNewsImpliedFromThemenspecial->toArray()];
        }

        $all = array_unique($all);

        if ($all !== []) {
            usort($all, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $all;
    }

    /**
     * Return txWdvcustomerThemenspecialImpliedNews items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerThemenspecialImpliedNewsSorted()
    {
        $items = $this->getTxWdvcustomerThemenspecialImpliedNews();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Set txWdvcustomerThemenspecialImpliedNews news
     *
     * @param ObjectStorage $implied implied news
     */
    public function setTxWdvcustomerThemenspecialImpliedNews($implied): void
    {
        $this->txWdvcustomerThemenspecialImpliedNews = $implied;
    }
}