<?php

namespace WDV\WdvCustomer\EventListener;

use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureParsedEvent;

final class FlexFormParsingModifyEventListener
{
    public function setDataStructure(BeforeFlexFormDataStructureParsedEvent $event): void
    {
        $identifier = $event->getIdentifier();
        if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['dataStructureKey'] === 'news_pi1,list') {
            $event->setDataStructure('FILE:EXT:wdv_customer/Configuration/FlexForms/Extensions/tx_wdvcustomer.additionalNewsFields.xml');
        }
    }
}