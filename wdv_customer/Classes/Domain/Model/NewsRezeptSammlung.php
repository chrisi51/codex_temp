<?php

namespace WDV\WdvCustomer\Domain\Model;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

class NewsRezeptSammlung extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerRezeptsammlungImpliedNews;

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected $txWdvcustomerNewsImpliedFromRezeptsammlung;

    /**
     * Get txWdvcustomerRezeptsammlungImpliedNews news
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerRezeptsammlungImpliedNews()
    {
        return $this->txWdvcustomerRezeptsammlungImpliedNews;
    }

    /**
     * Set txWdvcustomerRezeptsammlungImpliedNews from
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $impliedFrom
     */
    public function setTxWdvcustomerNewsImpliedFromRezeptsammlung($impliedFrom): void
    {
        $this->txWdvcustomerNewsImpliedFromRezeptsammlung = $impliedFrom;
    }

    /**
     * Get txWdvcustomerRezeptsammlungImpliedNews from
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerNewsImpliedFromRezeptsammlung()
    {
        return $this->txWdvcustomerNewsImpliedFromRezeptsammlung;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromRezeptsammlung items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerNewsImpliedFromRezeptsammlungSorted()
    {
        $items = $this->getTxWdvcustomerNewsImpliedFromRezeptsammlung();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromRezeptsammlung items sorted by datetime
     *
     * @return array
     */
    public function getAllTxWdvcustomerRezeptsammlungImpliedNewsSorted()
    {
        $all = [];
        $itemsTxWdvcustomerRezeptsammlungImpliedNews = $this->getTxWdvcustomerRezeptsammlungImpliedNews();
        if ($itemsTxWdvcustomerRezeptsammlungImpliedNews) {
            $all = array_merge($all, $itemsTxWdvcustomerRezeptsammlungImpliedNews->toArray());
        }

        $itemsTxWdvcustomerNewsImpliedFromRezeptsammlung = $this->getTxWdvcustomerNewsImpliedFromRezeptsammlung();
        if ($itemsTxWdvcustomerNewsImpliedFromRezeptsammlung) {
            $all = [...$all, ...$itemsTxWdvcustomerNewsImpliedFromRezeptsammlung->toArray()];
        }

        $all = array_unique($all);

        if ($all !== []) {
            usort($all, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $all;
    }

    /**
     * Return txWdvcustomerRezeptsammlungImpliedNews items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerRezeptsammlungImpliedNewsSorted()
    {
        $items = $this->getTxWdvcustomerRezeptsammlungImpliedNews();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Set txWdvcustomerRezeptsammlungImpliedNews news
     *
     * @param ObjectStorage $implied implied news
     */
    public function setTxWdvcustomerRezeptsammlungImpliedNews($implied): void
    {
        $this->txWdvcustomerRezeptsammlungImpliedNews = $implied;
    }
}