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
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GetPublicUrlForVideoViewHelper extends AbstractViewHelper
{

    /**
     * @var OnlineMediaHelperInterface
     */
    protected $onlineMediaHelper;

    /**
     * @return string
     */
    public function render()
    {
        $options = [];
        $videoId = null;
        $originalResource = $this->arguments['originalResource'];
        /** @var File $originalFile */
        $originalFile = $originalResource->getOriginalFile();
        /** @var OnlineMediaHelperInterface $helper */
        $helper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($originalFile);
        $originalFileId = $helper->getOnlineMediaId($originalFile);
        // \TYPO3\CMS\Core\Utility\DebugUtility::debug($originalFileId);
        // die();
        if ($originalFile->getMimeType() === 'video/youtube' || $originalFile->getExtension() === 'youtube')
        {

            $urlParams = ['autohide=1'];
            $urlParams[] = 'controls=' . $options['controls'];
            if (!empty($options['autoplay'])) {
                $urlParams[] = 'autoplay=1';
            }

            if (!empty($options['modestbranding'])) {
                $urlParams[] = 'modestbranding=1';
            }

            if (!empty($options['loop'])) {
                $urlParams[] = 'loop=1&playlist=' . rawurlencode((string) $videoId);
            }

            if (isset($options['relatedVideos'])) {
                $urlParams[] = 'rel=' . (int)(bool)$options['relatedVideos'];
            }

            if (!isset($options['enablejsapi']) || !empty($options['enablejsapi'])) {
                $urlParams[] = 'enablejsapi=1&origin=' . rawurlencode((string) GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST'));
            }

            $urlParams[] = 'showinfo=' . (int)!empty($options['showinfo']);

            $result = sprintf(
                'https://www.youtube%s.com/embed/%s?%s',
                !isset($options['no-cookie']) || !empty($options['no-cookie']) ? '-nocookie' : '',
                rawurlencode($originalFileId),
                implode('&', $urlParams)
            );

        } else {

            $result = $originalFile->getPublicUrl();
        }

        // \TYPO3\CMS\Core\Utility\DebugUtility::debug($result);
        // die();
        return $result;
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('originalResource', FileReference::class, '', true);
    }
}
