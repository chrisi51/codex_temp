<?php

namespace WDV\WdvCustomer\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Christopher Wirsing, http://cwms.de
 *  (c) 2019 Christopher Wirsing, http://www.wdv.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetYoutubeVideoPreviewImageViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments(): void {

        parent::initializeArguments();
        $this->registerArgument('filepath', 'string', 'File path');
    }

    /**
     * @return string
     */
    public function render() {

        $basePath = GeneralUtility::getFileAbsFileName('fileadmin');
        $ytFilePath = $basePath . $this->arguments['filepath'];

        // fallback if we can't get youtube preview image
        $result = "typo3conf/ext/wdv_customer/Resources/Public/Images/Layout/blank.png";

        $handle = fopen($ytFilePath, 'r');
        if ($handle) {
            $line = fgets($handle);
            if ($line !== false) {
                $charCount = strlen($line);
                // youtube list
                if ($charCount > 11) {
                    $line = $this->getFirstVideoId($line);
                }

                $tryNames = ['maxresdefault.jpg', 'mqdefault.jpg', '0.jpg'];
                foreach ($tryNames as $tryName) {
                    $previewImage = GeneralUtility::getUrl(
                        sprintf('https://img.youtube.com/vi/%s/%s', $line, $tryName)
                    );
                    if ($previewImage !== false) {
                        $previewImagePath = "https://img.youtube.com/vi/" . $line . "/" . $tryName;
                        $imgBase64 = base64_encode(file_get_contents($previewImagePath));
                        $result = "data:image/jpg;base64," . $imgBase64;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get first video of playlist. In case that only playlist code is given
     * we can get a title and preview image anyway.
     *
     * @param string $playListId
     * @return string
     */
    protected function getFirstVideoId($playListId)
    {
        $videoId = null;

        $url = "https://www.youtube-nocookie.com/embed/?list=" . $playListId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $fileContents = curl_exec($ch);

        preg_match('/<link rel="canonical" href="https:\/\/www.youtube.com\/watch\?v=(.*)">/', $fileContents, $match);

        if (isset($match[1])) {

            $videoId = $match[1];
        }

        return $videoId;
    }

}
