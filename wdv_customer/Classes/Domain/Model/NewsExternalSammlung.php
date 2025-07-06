<?php

namespace WDV\WdvCustomer\Domain\Model;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

class NewsExternalSammlung extends \GeorgRinger\News\Domain\Model\News
{


    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected ObjectStorage $txWdvcustomerExternalurlsammlungImpliedNews;

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    #[Lazy]
    protected ObjectStorage $txWdvcustomerNewsImpliedFromExternalurlsammlung;

    /**
     * Get txWdvcustomerExternalurlsammlungImpliedNews news
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerExternalurlsammlungImpliedNews(): ObjectStorage
    {
        return $this->txWdvcustomerExternalurlsammlungImpliedNews;
    }

    /**
     * Set txWdvcustomerExternalurlsammlungImpliedNews from
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $impliedFrom
     */
    public function setTxWdvcustomerNewsImpliedFromExternalurlsammlung(ObjectStorage $impliedFrom): void
    {
        $this->txWdvcustomerNewsImpliedFromExternalurlsammlung = $impliedFrom;
    }

    /**
     * Get txWdvcustomerExternalurlsammlungImpliedNews from
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    public function getTxWdvcustomerNewsImpliedFromExternalurlsammlung(): ObjectStorage
    {
        return $this->txWdvcustomerNewsImpliedFromExternalurlsammlung;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromExternalurlsammlung items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerNewsImpliedFromExternalurlsammlungSorted(): ObjectStorage|array
    {
        $items = $this->getTxWdvcustomerNewsImpliedFromExternalurlsammlung();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromExternalurlsammlung items sorted by datetime
     *
     * @return array
     */
    public function getAllTxWdvcustomerExternalurlsammlungImpliedNewsSorted(): array
    {
        $all = [];
        $itemsTxWdvcustomerExternalurlsammlungImpliedNews = $this->getTxWdvcustomerExternalurlsammlungImpliedNews();
        if ($itemsTxWdvcustomerExternalurlsammlungImpliedNews) {
            $all = array_merge($all, $itemsTxWdvcustomerExternalurlsammlungImpliedNews->toArray());
        }

        $itemsTxWdvcustomerNewsImpliedFromExternalurlsammlung = $this->getTxWdvcustomerNewsImpliedFromExternalurlsammlung();
        if ($itemsTxWdvcustomerNewsImpliedFromExternalurlsammlung) {
            $all = [...$all, ...$itemsTxWdvcustomerNewsImpliedFromExternalurlsammlung->toArray()];
        }

        $all = array_unique($all);

        if ($all !== []) {
            usort($all, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $all;
    }

    /**
     * Return txWdvcustomerExternalurlsammlungImpliedNews items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerExternalurlsammlungImpliedNewsSorted(): ObjectStorage|array
    {
        $items = $this->getTxWdvcustomerExternalurlsammlungImpliedNews();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Set txWdvcustomerExternalurlsammlungImpliedNews news
     *
     * @param ObjectStorage $implied implied news
     */
    public function setTxWdvcustomerExternalurlsammlungImpliedNews(ObjectStorage $implied): void
    {
        $this->txWdvcustomerExternalurlsammlungImpliedNews = $implied;
    }
}