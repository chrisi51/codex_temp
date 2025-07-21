<?php
namespace VIGO\VigoStoerer\Tca;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SiteItemsProcFunc
{
    public function getItems(array &$config): void
    {
        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        foreach ($siteFinder->getAllSites() as $site) {
            $config['items'][] = [
                // Label: identifier + Base-URL
                //$site->getIdentifier() . ' (' . (string)$site->getBase() . ')',
                (string)$site->getBase(),
                // Value: nur der Identifier
                $site->getIdentifier()
            ];
        }
    }
}
