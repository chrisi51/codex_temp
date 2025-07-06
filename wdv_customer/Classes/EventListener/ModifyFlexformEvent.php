<?php

declare(strict_types=1);

namespace WDV\WdvCustomer\EventListener;

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ModifyFlexformEvent
{
    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $dataStructure = $event->getDataStructure();
        $identifier = $event->getIdentifier();

        // $identifier['dataStructureKey'] depends on the selected plugin!
        if ($identifier['type'] === 'tca'
            && $identifier['tableName'] === 'tt_content'
            && ($identifier['dataStructureKey'] === '*,news_pi1' || $identifier['dataStructureKey'] === '*,news_newsselectedlist')
        ) {
            $file = GeneralUtility::getFileAbsFileName('EXT:wdv_customer/Configuration/FlexForms/Extensions/news.xml');
            $content = file_get_contents($file);
            if ($content) {
                ArrayUtility::mergeRecursiveWithOverrule($dataStructure['sheets'], GeneralUtility::xml2array($content));
            }
        }

        $event->setDataStructure($dataStructure);
    }
}
