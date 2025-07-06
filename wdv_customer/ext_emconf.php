<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'WDV Kundenkonfiguration',
	'description' => '',
	'category' => 'misc',
	'author' => 'wdv TYPO3-Team',
	'author_email' => 'typo3@wdv.de',
	'author_company' => 'wdv Gesellschaft für Medien & Kommunikation mbH & Co. OHG',
	'state' => 'stable',
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '8.7.0-12.4.99',
			'extbase' => '8.7.0-12.4.99',
			'fluid' => '8.7.0-12.4.99',
			'wdv_basics' => '1.0.0-0.0.0',
			'wdv_elements' => '1.0.0-0.0.0',
		],
		'conflicts' => [],
		'suggests' => [],
	],
	'autoload' => [
		'psr-4' => [
			'WDV\\WdvCustomer\\' => 'Classes'
		],
	],
];