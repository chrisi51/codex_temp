<?php

if (!defined('TYPO3')) {

    die ('Access denied.');
}

if(isset($GLOBALS['TCA']['tx_waconcookiemanagement_domain_model_cookie'])){
	$GLOBALS['TCA']['tx_waconcookiemanagement_domain_model_cookie']['columns']['kategorie']['config']['items'] = [
                    ['Notwendig', 0],
                    ['Komfort', 1],
                    ['Marketing', 2],
                    ['Externe Inhalte', 3],
        ];
}



