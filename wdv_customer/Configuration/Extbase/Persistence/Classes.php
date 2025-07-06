<?php
declare(strict_types = 1);

return [
	# DB mapping for powermail field
    \In2code\Powermail\Domain\Model\Field::class => [
        'subclasses' => [\WDV\WdvCustomer\Domain\Model\Field::class],
    ],
	\WDV\WdvCustomer\Domain\Model\Field::class => [
		'tableName' => 'tx_powermail_domain_model_field',
	],

    \In2code\Powermail\Domain\Model\Form::class => [
        'subclasses' => [\WDV\WdvCustomer\Domain\Model\Form::class],
    ],
    \WDV\WdvCustomer\Domain\Model\Form::class => [
        'tableName' => 'tx_powermail_domain_model_form',
    ],

    \In2code\Powermail\Domain\Model\Page::class => [
        'subclasses' => [\WDV\WdvCustomer\Domain\Model\Page::class],
    ],
    \WDV\WdvCustomer\Domain\Model\Page::class => [
        'tableName' => 'tx_powermail_domain_model_page',
    ],


	\GeorgRinger\News\Domain\Model\Dto\NewsDemand::class => [
		'tableName' => 'tx_news_domain_model_news',
	],

	\GeorgRinger\News\Domain\Model\Category::class => [
		'tableName' => 'sys_category',
	],

	# DB mapping for domain model extension of "NewsAuthor"
	\WDV\WdvCustomer\Domain\Model\NewsAuthor::class => [
		'tableName' => 'tx_mdnewsauthor_domain_model_newsauthor',
	],

	# Register new news types
	\GeorgRinger\News\Domain\Model\News::class => [
		'subclasses' => [
            0 => \GeorgRinger\News\Domain\Model\NewsDefault::class,
            1 => \GeorgRinger\News\Domain\Model\NewsInternal::class,
            2 => \GeorgRinger\News\Domain\Model\NewsExternal::class,
            3 => \WDV\WdvCustomer\Domain\Model\NewsThemenSpecial::class,
			4 => \WDV\WdvCustomer\Domain\Model\NewsInterview::class,
			5 => \WDV\WdvCustomer\Domain\Model\NewsQuestion::class,
			6 => \WDV\WdvCustomer\Domain\Model\NewsVideo::class,
			7 => \WDV\WdvCustomer\Domain\Model\NewsRezept::class,
			8 => \WDV\WdvCustomer\Domain\Model\NewsRezeptSammlung::class,
			9 => \WDV\WdvCustomer\Domain\Model\NewsMultiPage::class,
			10 => \WDV\WdvCustomer\Domain\Model\NewsGewinnspiel::class,
			11 => \WDV\WdvCustomer\Domain\Model\NewsExternalSammlung::class,
			12 => \WDV\WdvCustomer\Domain\Model\NewsLandingpage::class,
			13 => \WDV\WdvCustomer\Domain\Model\NewsPodcast::class,
			14 => \WDV\WdvCustomer\Domain\Model\NewsBestellung::class,
			140 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackPlaylist::class,
			141 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackArtikel::class,
			142 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackPodcast::class,
			143 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspiel::class,
			144 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspielWithForm::class,
			145 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackInfoartikel::class,
			146 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackVeranstaltung::class,
		]
	],

	# Register new news types for md_news_author Extension
	\Mediadreams\MdNewsAuthor\Domain\Model\News::class => [
		'subclasses' => [
            0 => \GeorgRinger\News\Domain\Model\NewsDefault::class,
            1 => \GeorgRinger\News\Domain\Model\NewsInternal::class,
            2 => \GeorgRinger\News\Domain\Model\NewsExternal::class,
			3 => \WDV\WdvCustomer\Domain\Model\NewsThemenSpecial::class,
			4 => \WDV\WdvCustomer\Domain\Model\NewsInterview::class,
			5 => \WDV\WdvCustomer\Domain\Model\NewsQuestion::class,
			6 => \WDV\WdvCustomer\Domain\Model\NewsVideo::class,
			7 => \WDV\WdvCustomer\Domain\Model\NewsRezept::class,
			8 => \WDV\WdvCustomer\Domain\Model\NewsRezeptSammlung::class,
			9 => \WDV\WdvCustomer\Domain\Model\NewsMultiPage::class,
			10 => \WDV\WdvCustomer\Domain\Model\NewsGewinnspiel::class,
			11 => \WDV\WdvCustomer\Domain\Model\NewsExternalSammlung::class,
			12 => \WDV\WdvCustomer\Domain\Model\NewsLandingpage::class,
			13 => \WDV\WdvCustomer\Domain\Model\NewsPodcast::class,
			14 => \WDV\WdvCustomer\Domain\Model\NewsBestellung::class,
			140 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackPlaylist::class,
			141 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackArtikel::class,
			142 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackPodcast::class,
			143 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspiel::class,
			144 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspielWithForm::class,
			145 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackInfoartikel::class,
			146 => \WDV\WdvCustomer\Domain\Model\NewsSoundtrackVeranstaltung::class,
		]
	],

	# DB mapping for news type "Themenspecial"
	\WDV\WdvCustomer\Domain\Model\NewsThemenSpecial::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 3,
	],

	# DB mapping for news type "Interview"
	\WDV\WdvCustomer\Domain\Model\NewsInterview::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 4,
	],

	# DB mapping for news type "Question"
	\WDV\WdvCustomer\Domain\Model\NewsQuestion::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 5,
	],

	# DB mapping for news type "Video"
	\WDV\WdvCustomer\Domain\Model\NewsVideo::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 6,
	],

	# DB mapping for news type "Rezept"
	\WDV\WdvCustomer\Domain\Model\NewsRezept::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 7,
	],

	# DB mapping for news type "Rezeptsammlung"
	\WDV\WdvCustomer\Domain\Model\NewsRezeptSammlung::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 8,
	],

	# DB mapping for news type "Multipage"
	\WDV\WdvCustomer\Domain\Model\NewsMultiPage::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 9,
	],

	# DB mapping for news type "Gewinnspiel"
	\WDV\WdvCustomer\Domain\Model\NewsGewinnspiel::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 10,
	],

	# DB mapping for news type "Sammlung externer Seiten"
	\WDV\WdvCustomer\Domain\Model\NewsExternalSammlung::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 11,
	],

	# DB mapping for news type "Landingpage"
	\WDV\WdvCustomer\Domain\Model\NewsLandingpage::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 12,
	],

	# DB mapping for news type "Podcast"
	\WDV\WdvCustomer\Domain\Model\NewsPodcast::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 13,
	],

	# DB mapping for news type "Bestellung"
	\WDV\WdvCustomer\Domain\Model\NewsBestellung::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 14,
	],
    
	# DB mapping for news type "Playlist"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackPlaylist::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 140,
	],
    
	# DB mapping for news type "Artikel"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackArtikel::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 141,
	],
	# DB mapping for news type "Podcast"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackPodcast::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 142,
	],
	# DB mapping for news type "Gewinnspiel"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspiel::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 143,
	],
	# DB mapping for news type "Gewinnspiel mit Formular"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackGewinnspielWithForm::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 144,
	],
	# DB mapping for news type "Infoartikel"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackInfoartikel::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 145,
	],
	# DB mapping for news type "Veranstaltung"
	\WDV\WdvCustomer\Domain\Model\NewsSoundtrackVeranstaltung::class => [
		'tableName' => 'tx_news_domain_model_news',
		'recordType' => 146,
	],
];