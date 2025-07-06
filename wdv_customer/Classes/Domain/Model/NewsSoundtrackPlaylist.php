<?php

namespace WDV\WdvCustomer\Domain\Model;

class NewsSoundtrackPlaylist extends \GeorgRinger\News\Domain\Model\News
{
    public function isCategorizedWith(string $demandedCategory): bool
    {
        foreach ($this->categories as $category) {
            if ($category->getUid() === (int)$demandedCategory) {
                return true;
            }
        }
        return false;
    }
}
