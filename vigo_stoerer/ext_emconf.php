<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Vigo Stoerer',
	'description' => '',
	'category' => 'misc',
	'author' => 'Christian Hillebrand',
	'state' => 'stable',
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0-12.4.99',
            'wdv_customer' => '1.0.0-0.0.0'
        ],
		'conflicts' => [
        ],
		'suggests' => [
        ],
    ],
	'autoload' => [
		'psr-4' => [
			'VIGO\\VigoStoerer\\' => 'Classes'
		],
	],
];