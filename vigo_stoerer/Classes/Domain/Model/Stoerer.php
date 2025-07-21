<?php

namespace VIGO\VigoStoerer\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Stoerer extends AbstractEntity
{

    /**
     * title
     * @var string
     */
    protected $title = '';

	/**
     * layout
     * @var string
     */
    protected $layout = '';

	/**
     * link
     * @var string
     */
    protected $link = '';

    /**
     * content
     * @var string
     */
    protected $content = '';

    /**
     * image
     * @var FileReference
     */
    protected $image;

    /**
     * Returns the title
     * @return string $title
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Sets the title
     * @param string $title
     * @return void
     */
    public function setTitle($title): void
    {

        $this->title = $title;
    }

/**
     * Returns the layout
     * @return string $layout
     */
    public function getLayout()
    {

        return $this->layout;
    }

    /**
     * Sets the layout
     * @param string $layout
     * @return void
     */
    public function setLayout($layout): void
    {

        $this->layout = $layout;
    }


/**
     * Returns the link
     * @return string $link
     */
    public function getLink()
    {

        return $this->link;
    }

    /**
     * Sets the link
     * @param string $link
     * @return void
     */
    public function setLink($link): void
    {

        $this->link = $link;
    }

    /**
     * Returns the content
     * @return string $content
     */
    public function getContent()
    {
        // @extensionScannerIgnoreLine
        return $this->content;
    }

    /**
     * Sets the content
     *
     * @param string $content
     * @return void
     */
    public function setContent($content): void
    {
        // @extensionScannerIgnoreLine
        $this->content = $content;
    }

    /**
     * Returns the image
     *
     * @return ObjectStorage<FileReference> $image
     */
    public function getImage()
    {

        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param ObjectStorage<FileReference> $image
     * @return void
     */
    public function setImage($image): void
    {

        $this->image = $image;
    }
}
