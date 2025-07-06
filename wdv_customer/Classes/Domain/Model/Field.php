<?php

namespace WDV\WdvCustomer\Domain\Model;

/**
 * Class Field
 * @package WDV\WdvCustomer\Domain\Model
 */

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;

class Field extends \In2code\Powermail\Domain\Model\Field
{
    /**
     * @var FileReference
     */
    public FileReference $resultimage;

    /**
     * New property text
     *
     * @var int $txWdvcustomerPowermailBootstrapCols
     */
    protected mixed $txWdvcustomerPowermailBootstrapCols;

    /**
     * New property text
     *
     * @var string $txWdvcustomerPowermailRowCols
     */
    protected string $txWdvcustomerPowermailRowCols;

    /**
     * New property text
     *
     * @var int $txWdvcustomerPowermailRadioHoriz
     */
    protected mixed $txWdvcustomerPowermailRadioHoriz;

    /**
     * New property text
     *
     * @var string $txWdvcustomerPowermailText
     */
    protected string $txWdvcustomerPowermailText;

    /**
     * Broschürenbild
     *
     * @var FileReference
     */
    #[Cascade(['value' => 'remove'])]
    protected $txWdvcustomerPowermailImage = null;

    /**
     * @param string $txWdvcustomerPowermailRowCols
     * @return void
     */
    public function setTxWdvcustomerPowermailRowCols($txWdvcustomerPowermailRowCols): void
    {
        $this->txWdvcustomerPowermailRowCols = $txWdvcustomerPowermailRowCols;
    }

    public function getTxWdvcustomerPowermailRowCols(): string
    {
        return $this->txWdvcustomerPowermailRowCols;
    }

    /**
     * @param string $txWdvcustomerPowermailText
     * @return void
     */
    public function setTxWdvcustomerPowermailText(string $txWdvcustomerPowermailText): void
    {
        $this->txWdvcustomerPowermailText = $txWdvcustomerPowermailText;
    }

    /**
     * @return string
     */
    public function getTxWdvcustomerPowermailText(): string
    {
        return $this->txWdvcustomerPowermailText;
    }

    public function getTxWdvcustomerPowermailImage(): ?FileReference
    {
        return $this->txWdvcustomerPowermailImage;
    }

    public function setTxWdvcustomerPowermailImage(FileReference $txWdvcustomerPowermailImage): void
    {
        $this->resultimage = $txWdvcustomerPowermailImage;
    }

    public function getTxWdvcustomerPowermailBootstrapCols(): mixed
    {
        return $this->txWdvcustomerPowermailBootstrapCols;
    }

    public function setTxWdvcustomerPowermailBootstrapCols(mixed $txWdvcustomerPowermailBootstrapCols): void
    {
        $this->txWdvcustomerPowermailBootstrapCols = $txWdvcustomerPowermailBootstrapCols;
    }

    public function getTxWdvcustomerPowermailRadioHoriz(): mixed
    {
        return $this->txWdvcustomerPowermailRadioHoriz;
    }

    public function setTxWdvcustomerPowermailRadioHoriz(mixed $txWdvcustomerPowermailRadioHoriz): void
    {
        $this->txWdvcustomerPowermailRadioHoriz = $txWdvcustomerPowermailRadioHoriz;
    }
}
