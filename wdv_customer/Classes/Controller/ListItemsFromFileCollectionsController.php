<?php

namespace WDV\WdvCustomer\Controller;

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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Resource\FileCollectionRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection;
use TYPO3\CMS\Core\Resource\Exception;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Class ListItemsFromFileCollectionsController
 * @package WDV\WdvCustomer\Controller
 */
class ListItemsFromFileCollectionsController extends ActionController {

    /**
     * @var FileCollectionRepository
     */
    protected $fileCollectionRepository;

    /**
     * Renders the list of all existing collections and their content
     *
     * @return void
     */
    public function indexAction(): ResponseInterface
    {

        $fileObjects = [];

        $collectionUids = $this->getCollectionsToDisplay($this->settings['collections']);
        $collectionUidsCount = count($collectionUids);

        if($collectionUidsCount > 0) {

            foreach($collectionUids as $collectionUid) {

                try {

                    $fileCollection = $this->fileCollectionRepository->findByUid($collectionUid);

                    if($fileCollection instanceof AbstractFileCollection) {

                        $fileCollection->loadContents();
                        $this->addToArray($fileCollection->getItems(), $fileObjects);
                    }

                } catch (Exception) {

                    /** @var Logger $logger */
                    $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                    $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
                }
            }

            foreach($fileObjects as $key => $file) {

                // file collection returns different types depending on the static or folder type
                if($file instanceof FileReference) {

                    $fileObjects[$key] = $file->getOriginalFile();
                }
            }

            $fileObjects = $this->cleanFiles($fileObjects);

            // Check every file in the collection to have references (in typo3 backend context)
            // If no reference is present, dont render the file in fluid template
            // This works by removing the file from $fileObjects-Array which late will be rendered
            $fileObjects = $this->checkAgainstReferencesAndRemoveNotReferencedFiles($fileObjects);

            // assign result to template
            $this->view->assign('items', $fileObjects);

        } else {
            $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction($GLOBALS['TYPO3_REQUEST'], 'There is no file collection selected so this plugin could not show anything!');
            throw new ImmediateResponseException($response);
        }

        return $this->htmlResponse();
    }

    /**
     * @param string $collections
     * @return string[]
     */
    private function getCollectionsToDisplay($collections): array {

        $collectionUids = GeneralUtility::trimExplode(',', $collections, true);
        $fileCollections = [];

        foreach($collectionUids as $collectionUid) {

            try {

                $fileCollection = $this->fileCollectionRepository->findByUid((int)$collectionUid);

                if($fileCollection instanceof AbstractFileCollection) {

                    $fileCollections[] = $collectionUid;
                }

            } catch (\Exception) {

                /** @var Logger $logger */
                $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger();
                $logger->warning('The file-collection with uid  "' . $collectionUid . '" could not be found or contents could not be loaded and won\'t be included in frontend output');
            }
        }

        return $fileCollections;
    }

    /**
     * http://forge.typo3.org/issues/58806
     *
     * @param $fileObjects
     *
     * @return $tmpArray array
     */
    private function cleanFiles($fileObjects): array {

        $tmpArray = [];

        foreach($fileObjects as $file) {

            /** @var File $file */
            $tmpArray[$file->getUid()] = $file;
        }

        return $tmpArray;
    }

    /**
     * @param $fileObjects
     *
     * @return $fileObjects array
     */
    private function checkAgainstReferencesAndRemoveNotReferencedFiles($fileObjects) {

        foreach($fileObjects as $key => $file) {

            if(BackendUtility::referenceCount('sys_file', $file->getUid(), '') == '') {

                unset($fileObjects[$file->getUid()]);
            }
        }

        return $fileObjects;
    }

    /**
     * Adds $newItems to $theArray, which is passed by reference. Array must only consist of numerical keys.
     *
     * @param mixed $newItems Array with new items or single object that's added.
     * @param array $theArray The array the new items should be added to. Must only contain numeric keys (for
     *                        array_merge() to add items instead of replacing).
     */
    private function addToArray(mixed $newItems, array &$theArray): void {

        if(is_array($newItems)) {

            $theArray = array_merge($theArray, $newItems);

        } elseif(is_object($newItems)) {

            $theArray[] = $newItems;
        }
    }

    public function injectFileCollectionRepository(FileCollectionRepository $fileCollectionRepository): void
    {
        $this->fileCollectionRepository = $fileCollectionRepository;
    }
}