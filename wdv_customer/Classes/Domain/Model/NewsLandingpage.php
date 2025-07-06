<?php

namespace WDV\WdvCustomer\Domain\Model;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

class NewsLandingpage extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerLandingpageImpliedNews;

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerNewsImpliedFromLandingpage;

    /**
     * Get txWdvcustomerLandingpageImpliedNews news
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerLandingpageImpliedNews()
    {
        return $this->txWdvcustomerLandingpageImpliedNews;
    }

    /**
     * Set txWdvcustomerLandingpageImpliedNews from
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $impliedFrom
     */
    public function setTxWdvcustomerNewsImpliedFromLandingpage($impliedFrom): void
    {
        $this->txWdvcustomerNewsImpliedFromLandingpage = $impliedFrom;
    }

    /**
     * Get txWdvcustomerLandingpageImpliedNews from
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerNewsImpliedFromLandingpage()
    {
        return $this->txWdvcustomerNewsImpliedFromLandingpage;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromLandingpage items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerNewsImpliedFromLandingpageSorted()
    {
        $items = $this->getTxWdvcustomerNewsImpliedFromLandingpage();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromLandingpage items sorted by datetime
     *
     * @return array
     */
    public function getAllTxWdvcustomerLandingpageImpliedNewsSorted()
    {
        $all = [];
        $itemsTxWdvcustomerLandingpageImpliedNews = $this->getTxWdvcustomerLandingpageImpliedNews();
        if ($itemsTxWdvcustomerLandingpageImpliedNews) {
            $all = array_merge($all, $itemsTxWdvcustomerLandingpageImpliedNews->toArray());
        }

        $itemsTxWdvcustomerNewsImpliedFromLandingpage = $this->getTxWdvcustomerNewsImpliedFromLandingpage();
        if ($itemsTxWdvcustomerNewsImpliedFromLandingpage) {
            $all = [...$all, ...$itemsTxWdvcustomerNewsImpliedFromLandingpage->toArray()];
        }

        $all = array_unique($all);

        if ($all !== []) {
            usort($all, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $all;
    }

    /**
     * Return txWdvcustomerLandingpageImpliedNews items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerLandingpageImpliedNewsSorted()
    {
        $items = $this->getTxWdvcustomerLandingpageImpliedNews();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Set txWdvcustomerLandingpageImpliedNews news
     *
     * @param ObjectStorage $implied implied news
     */
    public function setTxWdvcustomerLandingpageImpliedNews($implied): void
    {
        $this->txWdvcustomerLandingpageImpliedNews = $implied;
    }
}