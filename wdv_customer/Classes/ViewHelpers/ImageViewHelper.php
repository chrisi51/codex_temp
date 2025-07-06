<?php

namespace WDV\WdvCustomer\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 Christopher Wirsing, https://www.cwms.de
 *  (c) 2019 Christopher Wirsing, https://www.wdv.de
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

use HDNET\Focuspoint\Service\FocusCropService;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageViewHelper extends \HDNET\Focuspoint\ViewHelpers\ImageViewHelper
{
    public $arguments;
    public $tag;

    /**
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     *
     * @return string Rendered tag
     */
    public function render(): string
    {

        $tagModified = null;
        /** @var FocusCropService $service */
        $service = GeneralUtility::makeInstance(FocusCropService::class);
        $internalImage = null;

        try {

            $internalImage = $service->getViewHelperImage($this->arguments['src'], $this->arguments['image'], $this->arguments['treatIdAsReference']);

            if ($this->arguments['realCrop'] && $internalImage instanceof FileInterface) {

                $this->arguments['src'] = $service->getCroppedImageSrcByFile($internalImage, $this->arguments['ratio']);
                $this->arguments['treatIdAsReference'] = false;
                $image = null;
            }

        } catch (\Exception) {

            $this->arguments['realCrop'] = true;
        }

        try {

            parent::render();

        } catch (\Exception) {

            return 'Missing image!';
        }

        if ($this->arguments['realCrop']) {

            return $this->tag->render();
        }

        // cwirsing, 22.10.2019
        if($this->tag->hasAttribute('src') && !empty($this->tag->getAttribute('src'))) {

            $this->tag->addAttribute('data-src', $this->tag->getAttribute('src'));
            $this->tag->addAttribute('src', '/typo3conf/ext/wdv_customer/Resources/Public/Images/Layout/blank.png');

            $tagModified = true;
        }

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->tag);

        // Ratio calculation
        if (null !== $internalImage) {

            $focusPointY = $internalImage->getProperty('focus_point_y');
            $focusPointX = $internalImage->getProperty('focus_point_x');

            $additionalClassDiv = 'focuspoint';

            if (!empty($this->arguments['additionalClassDiv'])) {

                $additionalClassDiv .= ' ' . $this->arguments['additionalClassDiv'];
            }

            $srcValue = $tagModified ? $this->tag->getAttribute('data-src') : $this->tag->getAttribute('src');
            $focusTag = '<div class="' . $additionalClassDiv . '" data-image-imageSrc="' . $srcValue . '" data-focus-x="' . ($focusPointX / 100) . '" data-focus-y="' . ($focusPointY / 100) . '" data-image-w="' . $this->tag->getAttribute('width') . '" data-image-h="' . $this->tag->getAttribute('height') . '">';

            // cwirsing, 24.10.2019
            return $focusTag . $this->tag->render() . '<noscript><img src="'. $srcValue .'" width="'. $this->tag->getAttribute('width') .'" height="'. $this->tag->getAttribute('height')  .'" alt="'. $this->tag->getAttribute('alt') .'" title="'. $this->tag->getAttribute('title') .'"></noscript></div>';
        }

        return 'Missing internal image!';
    }
}