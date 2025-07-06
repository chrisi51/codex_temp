<?php

namespace WDV\WdvCustomer\Helpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOEmbedHelper;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Youtube helper class
 */
class YouTubePlaylistHelper extends AbstractOEmbedHelper
{

    private ?string $playListId = null;

    /**
     * Get public url
     *
     * @param File $file
     * @return string
     */
    public function getPublicUrl(File $file)
    {
        $videoId = $this->getOnlineMediaId($file);
        return sprintf('https://www.youtube.com/watch?v=%s', rawurlencode($videoId));
    }

    /**
     * Get local absolute file path to preview image
     *
     * @param File $file
     * @return string
     */
    public function getPreviewImage(File $file)
    {
        $playListId = $this->getOnlineMediaId($file);
        $videoId = $this->getFirstVideoId($playListId);
        if ($videoId === '' || $videoId === null) {
            return '';
        }

        $temporaryFileName = $this->getTempFolderPath() . 'youtube_' . md5($videoId) . '.jpg';
        if (!file_exists($temporaryFileName)) {
            $tryNames = ['maxresdefault.jpg', 'sddefault.jpg', 'hqdefault.jpg', 'mqdefault.jpg', '0.jpg'];
            foreach ($tryNames as $tryName) {
                $previewImage = GeneralUtility::getUrl(
                    sprintf('https://img.youtube.com/vi/%s/%s', $videoId, $tryName)
                );
                if ($previewImage !== false) {
                    file_put_contents($temporaryFileName, $previewImage);
                    GeneralUtility::fixPermissions($temporaryFileName);
                    break;
                }
            }
        }

        return $temporaryFileName;
    }

    /**
     * Try to transform given URL to a File
     *
     * @param string $url
     * @param Folder $targetFolder
     * @return File|null
     */
    public function transformUrlToFile($url, Folder $targetFolder)
    {
        $videoId = null; // id of the first video (if given) used to get a preview image
        if (preg_match('%[&?]list=([^&]+)%i', $url, $match)) {
            $this->playListId = $match[1];
        }

        if (empty($this->playListId)) {
            return null;
        }

        // Try to get the YouTube code from given url.
        // These formats are supported with and without http(s)://
        // - youtu.be/<code> # Share URL
        // - www.youtube.com/watch?v=<code> # Normal web link
        // - www.youtube.com/v/<code>
        // - www.youtube-nocookie.com/v/<code> # youtube-nocookie.com web link
        // - www.youtube.com/embed/<code> # URL form iframe embed code, can also get code from full iframe snippet
        // - www.youtube.com/shorts/<code>
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $videoId = $match[1];
        } else {
            // try to get the videoId of the first video by curling the playlist and get the first video url from the canonical link
            $videoId = $this->getFirstVideoId($this->playListId);
        }

        // If we can't get the id of the first video we can't save this playlist!
        if ($videoId === '') {
            return null;
        }

        return $this->transformMediaIdToFile($videoId, $targetFolder, $this->extension);
    }

    /**
     * Get oEmbed url to retrieve oEmbed data
     *
     * @param string $mediaId
     * @param string $format
     * @return string
     */
    protected function getOEmbedUrl($mediaId, $format = 'json')
    {
        return sprintf(
            'https://www.youtube.com/oembed?url=%s&format=%s&maxwidth=2048&maxheight=2048',
            rawurlencode(sprintf('https://www.youtube.com/watch?v=%s', rawurlencode($mediaId))),
            rawurlencode($format)
        );
    }

    /**
     * Transform mediaId to File
     *
     * @param string $mediaId
     * @param Folder $targetFolder
     * @param string $fileExtension
     * @return File
     */
    protected function transformMediaIdToFile($mediaId, Folder $targetFolder, $fileExtension)
    {
        $file = $this->findExistingFileByOnlineMediaId($this->playListId, $targetFolder, $fileExtension);

        // no existing file create new
        if (!$file instanceof File) {
            $oEmbed = $this->getOEmbedData($mediaId);
            if ($oEmbed !== null && $oEmbed !== []) {
                $fileName = $oEmbed['title'] . '___' . rawurlencode($mediaId) . '.' . $fileExtension;
            } else {
                $fileName = $mediaId . '.' . $fileExtension;
            }

            $file = $this->createNewFile($targetFolder, $fileName, $this->playListId);
        }

        return $file;
    }

    /**
     * Get first video of playlist. In case that only playlist code is given
     * we can get a title and preview image anyway.
     *
     * @param string $playListId
     * @return string
     */
    protected function getFirstVideoId(string $playListId)
    {
        $videoId = null;

        $url = "https://www.youtube-nocookie.com/embed/?list=" . $playListId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $fileContents = curl_exec($ch);

        // alt
        // preg_match('/<link rel="canonical" href="https:\/\/www.youtube.com\/watch\?v=(.*)">/', $fileContents, $match);
        // neu
        preg_match('/"video_id":"(.*)","list"/', $fileContents, $match);

        if (isset($match[1])) {
            $videoId = $match[1];
        }

        return $videoId;
    }

}
