#
# Table structure for table 'pages'
#
CREATE TABLE pages (

    tx_wdvcustomer_page_icon varchar(64) NOT NULL default '0',

    tx_wdvcustomer_page_paddingtop int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_page_background int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_page_breadcrumb_show int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_page_breadcrumb_color int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_page_breadcrumb_spacing int(1) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_page_amountnews int(1) DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_news_domain_model_news'
#
CREATE TABLE tx_news_domain_model_news (

   cruser_id int(11) DEFAULT '0' NOT NULL,
   t3ver_oid int(11) DEFAULT '0' NOT NULL,
   t3ver_id int(11) DEFAULT '0' NOT NULL,
   t3ver_wsid int(11) DEFAULT '0' NOT NULL,
   t3ver_label varchar(30) DEFAULT '' NOT NULL,
   t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
   t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
   t3ver_count int(11) DEFAULT '0' NOT NULL,
   t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
   t3ver_move_id int(11) DEFAULT '0' NOT NULL,
   t3_origuid int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_news_canonical_cat int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_multipage_implied_news int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_implied_from_multipage int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_externalurlsammlung_implied_news int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_implied_from_externalurlsammlung int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_rezeptsammlung_implied_news int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_implied_from_rezeptsammlung int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_themenspecial_implied_news int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_implied_from_themenspecial int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_landingpage_implied_news int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_implied_from_landingpage int(11) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_news_time varchar(255) DEFAULT '3:00' NOT NULL,

    tx_wdvcustomer_news_multipagetype varchar(100) NOT NULL DEFAULT '0',
    tx_wdvcustomer_news_show_as varchar(100) NOT NULL DEFAULT '0',

    tx_wdvcustomer_news_recipe_content_elements int(11) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_recipe_cookingtime varchar(255) DEFAULT '0:00' NOT NULL,
    tx_wdvcustomer_news_recipe_difficulty int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_news_recipe_type int(1) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_news_domain_model_news_multipage_mm'
#
CREATE TABLE tx_news_domain_model_news_multipage_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_news_themenspecial_mm'
#
CREATE TABLE tx_news_domain_model_news_themenspecial_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_news_landingpage_mm'
#
CREATE TABLE tx_news_domain_model_news_landingpage_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_news_rezeptsammlung_mm'
#
CREATE TABLE tx_news_domain_model_news_rezeptsammlung_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_news_externalurlsammlung_mm'
#
CREATE TABLE tx_news_domain_model_news_externalurlsammlung_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_news_domain_model_news_multipage_mm'
#
CREATE TABLE tx_news_domain_model_news_multipage_mm (

	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (

    flexform_css_classes varchar(255) NOT NULL default '',

    tx_wdvcustomer_content_breakoutleft int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_content_breakoutright int(1) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_content_paddingtop int(1) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_content_paddingbottom int(1) DEFAULT '0' NOT NULL,

    tx_wdvcustomer_related_news_recipe int(11) DEFAULT '0' NOT NULL,

    flexform_layout varchar(50) NOT NULL default 'default',
    flexform_scrollbutton varchar(50) NOT NULL default 'default',
    flexform_top_space int(11) DEFAULT '0' NOT NULL,
    flexform_bottom_space int(11) DEFAULT '0' NOT NULL,
    flexform_boxtype varchar(50) NOT NULL default 'transparent',
    flexform_fullwidth int(11) DEFAULT '0' NOT NULL,
    flexform_contentwidth int(11) DEFAULT '0' NOT NULL,

    flexform_bgimage int(11) DEFAULT '0' NOT NULL,
    flexform_bgvideo int(11) DEFAULT '0' NOT NULL,

    flexform_layout_gib8 int(11) DEFAULT '0' NOT NULL,
    flexform_boxalign varchar(10) NOT NULL default 'left',
    flexform_boxvalign varchar(10) NOT NULL default 'top',

    flexform_width varchar(50) NOT NULL default 'fullwidth',
    flexform_backgroundcolor varchar(150) NOT NULL default 'white',

    flexform_cssid varchar(255) NOT NULL default '',
    flexform_rotationtime varchar(50) NOT NULL default '1',

    KEY index_newsrecipecontent (tx_wdvcustomer_related_news_recipe)
);

#
# Table structure for table 'tx_powermail_domain_model_field'
#
CREATE TABLE tx_powermail_domain_model_field (

    tx_wdvcustomer_powermail_bootstrap_cols int(2) DEFAULT '6' NOT NULL,
    tx_wdvcustomer_powermail_row_cols varchar(255) DEFAULT 'items_2' NOT NULL,
    tx_wdvcustomer_powermail_radio_horiz int(2) DEFAULT '0' NOT NULL,
    tx_wdvcustomer_powermail_text text NOT NULL,
    tx_wdvcustomer_powermail_image tinyint(4) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_mdnewsauthor_domain_model_newsauthor'
#
CREATE TABLE tx_mdnewsauthor_domain_model_newsauthor (

    tx_wdvcustomer_newsauthor_position2 varchar(255) DEFAULT '' NOT NULL,
);

#
# Table structure for table 'winner_workshops_durchstarter'
#
CREATE TABLE winner_workshops_durchstarter (

	id int(11) NOT NULL AUTO_INCREMENT,
    firstname varchar(255) DEFAULT '' NOT NULL,
    lastname varchar(255) DEFAULT '' NOT NULL,
    birthday date DEFAULT '1970-01-01' NOT NULL,
    workshop varchar(255) DEFAULT '' NOT NULL,
    year varchar(4) DEFAULT '' NOT NULL,

  	KEY id (id),
  	PRIMARY KEY (id),
);
