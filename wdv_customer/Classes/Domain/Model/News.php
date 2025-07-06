<?php

namespace WDV\WdvCustomer\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class News extends \GeorgRinger\News\Domain\Model\News {

    /**
     * @var string
     */
    protected string $txWdvcustomerNewsTime = '';

    /**
     * @return string
     */
    public function getTxWdvcustomerNewsTime(): string
    {
        return $this->txWdvcustomerNewsTime;
    }

    /**
     * @param string $txWdvcustomerNewsTime
     */
    public function setTxWdvcustomerNewsTime(string $txWdvcustomerNewsTime): void {

        $this->txWdvcustomerNewsTime = $txWdvcustomerNewsTime;
    }

    /**
     * @var string
     */
    protected string $txWdvcustomerNewsMultipagetype = '';

    /**
     * @return string
     */
    public function getTxWdvcustomerNewsMultipagetype(): string
    {

        return $this->txWdvcustomerNewsMultipagetype;
    }

    /**
     * @param string $txWdvcustomerNewsMultipagetype
     */
    public function setTxWdvcustomerNewsMultipagetype(string $txWdvcustomerNewsMultipagetype): void {

        $this->txWdvcustomerNewsMultipagetype = $txWdvcustomerNewsMultipagetype;
    }

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>|null
     */
    #[Lazy]
    protected $txWdvcustomerMultipageImpliedNews;

    /**
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\News>|null
     */
    #[Lazy]
    protected $txWdvcustomerNewsImpliedFromMultipage;

    /**
     * Get txWdvcustomerMultipageImpliedNews news
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>|null
     */
    public function getTxWdvcustomerMultipageImpliedNews(): ?ObjectStorage
    {
        return $this->txWdvcustomerMultipageImpliedNews;
    }

    /**
     * Set txWdvcustomerMultipageImpliedNews from
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model\News> $impliedFrom
     */
    public function setTxWdvcustomerNewsImpliedFromMultipage(ObjectStorage $impliedFrom): void
    {
        $this->txWdvcustomerNewsImpliedFromMultipage = $impliedFrom;
    }

    /**
     * Get txWdvcustomerMultipageImpliedNews from
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model\News>|null
     */
    public function getTxWdvcustomerNewsImpliedFromMultipage(): ?ObjectStorage
    {
        return $this->txWdvcustomerNewsImpliedFromMultipage;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromMultipage items sorted by datetime
     *
     * @return ObjectStorage|array
     */
    public function getTxWdvcustomerNewsImpliedFromMultipageSorted(): ObjectStorage|array
    {
        $items = $this->getTxWdvcustomerNewsImpliedFromMultipage();
        if ($items) {
            $items = $items->toArray();
            usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $items;
    }

    /**
     * Return txWdvcustomerNewsImpliedFromMultipage items sorted by datetime
     *
     * @return array
     */
    public function getAllTxWdvcustomerMultipageImpliedNewsSorted(): array
    {
        $all = [];
        $itemsTxWdvcustomerMultipageImpliedNews = $this->getTxWdvcustomerMultipageImpliedNews();
        if ($itemsTxWdvcustomerMultipageImpliedNews) {
            $all = array_merge($all, $itemsTxWdvcustomerMultipageImpliedNews->toArray());
        }

        $itemsTxWdvcustomerNewsImpliedFromMultipage = $this->getTxWdvcustomerNewsImpliedFromMultipage();
        if ($itemsTxWdvcustomerNewsImpliedFromMultipage) {
            $all = [...$all, ...$itemsTxWdvcustomerNewsImpliedFromMultipage->toArray()];
        }

        $all = array_unique($all);

        if ($all !== []) {
            usort($all, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());
        }

        return $all;
    }

    /**
     * Return txWdvcustomerMultipageImpliedNews items sorted by datetime
     *
     * @return array
     */
    public function getTxWdvcustomerMultipageImpliedNewsSorted(): ObjectStorage|array
    {
        $items = $this->getTxWdvcustomerMultipageImpliedNews();
        $items = $items->toArray();
        usort($items, static fn($a, $b): bool => $a->getDatetime() < $b->getDatetime());

        return $items;
    }

    /**
     * Set txWdvcustomerMultipageImpliedNews news
     *
     * @param ObjectStorage $implied implied news
     */
    public function setTxWdvcustomerMultipageImpliedNews($implied): void
    {
        $this->txWdvcustomerMultipageImpliedNews = $implied;
    }
}