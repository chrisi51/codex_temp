<?php

namespace WDV\WdvCustomer\Domain\Model;

class NewsAuthor extends \Mediadreams\MdNewsAuthor\Domain\Model\NewsAuthor
{
    /**
     * txWdvcustomerNewsauthorPosition2
     *
     * @var string
     */
    protected string $txWdvcustomerNewsauthorPosition2 = '';

    /**
     * Returns the txWdvcustomerNewsauthorPosition2
     *
     * @return string $txWdvcustomerNewsauthorPosition2
     */
    public function getTxWdvcustomerNewsauthorPosition2(): string
    {
        return $this->txWdvcustomerNewsauthorPosition2;
    }

    /**
     * Sets the txWdvcustomerNewsauthorPosition2
     *
     * @param string $txWdvcustomerNewsauthorPosition2
     * @return void
     */
    public function setTxWdvcustomerNewsauthorPosition2(string $txWdvcustomerNewsauthorPosition2): void
    {

        $this->txWdvcustomerNewsauthorPosition2 = $txWdvcustomerNewsauthorPosition2;
    }
}