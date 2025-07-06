<?php
namespace WDV\WdvCustomer\Hooks;

class KesearchHooks {

    public function modifyExtNewsIndexEntry (&$title, &$abstract, &$fullContent, &$params, &$tags, &$newsRecord): void {

        $params = str_replace("&tx_news_pi1[controller]=News", "", (string) $params);
        $params = str_replace("&tx_news_pi1[action]=detail", "", $params);
    }
}
