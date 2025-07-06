<?php
namespace WDV\WdvCustomer\Resource\Rendering;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * YouTube renderer class
 */
class YouTubePlaylistRenderer implements FileRendererInterface
{
    /**
     * @var OnlineMediaHelperInterface
     */
    protected $onlineMediaHelper;

    /**
     * Returns the priority of the renderer
     * This way it is possible to define/overrule a renderer
     * for a specific file type/context.
     * For example create a video renderer for a certain storage/driver type.
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 1;
    }

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        return ($file->getMimeType() === 'video/youtubeplaylist' || $file->getExtension() === 'youtubeplaylist') && $this->getOnlineMediaHelper($file) !== false;
    }

    /**
     * Get online media helper
     *
     * @return bool|OnlineMediaHelperInterface
     */
    protected function getOnlineMediaHelper(FileInterface $file)
    {
        if (!$this->onlineMediaHelper instanceof OnlineMediaHelperInterface) {
            $orgFile = $file;
            if ($orgFile instanceof FileReference) {
                $orgFile = $orgFile->getOriginalFile();
            }

            if ($orgFile instanceof File) {
                $this->onlineMediaHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($orgFile);
            } else {
                $this->onlineMediaHelper = false;
            }
        }

        return $this->onlineMediaHelper;
    }

    /**
     * Render for given File(Reference) html output
     *
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false): string
    {
        $options = $this->collectOptions($options, $file);
        $src = $this->createYouTubeUrl($options, $file);
        $attributes = $this->collectIframeAttributes($width, $height, $options);

        return sprintf(
            '<iframe src="%s"%s></iframe>',
            htmlspecialchars($src, ENT_QUOTES | ENT_HTML5),
            $attributes === [] ? '' : ' ' . $this->implodeAttributes($attributes)
        );
    }

    /**
     * @return array
     */
    protected function collectOptions(array $options, FileInterface $file)
    {
        // Check for an autoplay option at the file reference itself, if not overridden yet.
        if (!isset($options['autoplay']) && $file instanceof FileReference) {
            $autoplay = $file->getProperty('autoplay');
            if ($autoplay !== null) {
                $options['autoplay'] = $autoplay;
            }
        }

        $options['controls'] ??= 2;
        $options['controls'] = MathUtility::canBeInterpretedAsInteger($options['controls']) ? MathUtility::forceIntegerInRange($options['controls'], 0, 2) : 2;

//        if (!isset($options['allow'])) {
//            $options['allow'] = 'fullscreen';
//            if (!empty($options['autoplay'])) {
//                $options['allow'] = 'autoplay; fullscreen';
//            }
//        }
        return $options;
    }

    /**
     * @return string
     */
    protected function createYouTubeUrl(array $options, FileInterface $file): string
    {
        $playList = $this->getVideoIdFromFile($file);

        return sprintf(
            'https://www.youtube%s.com/embed/videoseries/?%s',
            !isset($options['no-cookie']) || !empty($options['no-cookie']) ? '-nocookie' : '',
            'autoplay=0w&list=' . rawurlencode($playList)
        );
    }

    /**
     * @return string
     */
    protected function getVideoIdFromFile(FileInterface $file)
    {
        $orgFile = $file instanceof FileReference ? $file->getOriginalFile() : $file;

        return $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
    }

    /**
     * @param int|string $width
     * @param int|string $height
     * @return array<string, mixed>&mixed[] pairs of key/value; not yet html-escaped
     */
    protected function collectIframeAttributes($width, $height, array $options): array
    {
        $attributes = [];
        $attributes['allowfullscreen'] = true;

        if ((int)$width > 0) {
            $attributes['width'] = (int)$width;
        }

        if ((int)$height > 0) {
            $attributes['height'] = (int)$height;
        }

        if (isset($GLOBALS['TSFE']) && is_object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->config['config']['doctype'] !== 'html5') {
            $attributes['frameborder'] = 0;
        }

        foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'poster', 'preload', 'allow'] as $key) {
            if (!empty($options[$key])) {
                $attributes[$key] = $options[$key];
            }
        }

        return $attributes;
    }

    /**
     * @internal
     * @return string
     */
    protected function implodeAttributes(array $attributes): string
    {
        $attributeList = [];
        foreach ($attributes as $name => $value) {
            $name = preg_replace('/[^\p{L}0-9_.-]/u', '', $name);
            if ($value === true) {
                $attributeList[] = $name;
            } else {
                $attributeList[] = $name . '="' . htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5) . '"';
            }
        }

        return implode(' ', $attributeList);
    }
}
