#
# Table structure for table 'tx_vigostoerer_domain_model_stoerer'
#
CREATE TABLE tx_vigostoerer_domain_model_stoerer (

  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  title varchar(255) DEFAULT '' NOT NULL,
  layout varchar(255) DEFAULT '' NOT NULL,
  link text,
  content text NOT NULL,
  image int(11) DEFAULT '0' NOT NULL,
  all_pages TINYINT(1) unsigned NOT NULL DEFAULT '0',
  sites VARCHAR(255)       NOT NULL DEFAULT '',

  starttime int(11) unsigned DEFAULT '0' NOT NULL,
  endtime int(11) unsigned DEFAULT '0' NOT NULL,

  sorting int(11) DEFAULT '0' NOT NULL,

  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l10n_parent int(11) DEFAULT '0' NOT NULL,
  l10n_diffsource mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid)
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    tx_vigostoerer_related_stoerer int(11) unsigned DEFAULT '0'
);

#
# Table structure for table 'tx_news_domain_model_news'
#
CREATE TABLE tx_news_domain_model_news (
    tx_vigostoerer_related_stoerer int(11) unsigned DEFAULT '0'
);

#
# Table structure for table 'tx_vigostoerer_pages_stoerer_mm'
#
CREATE TABLE tx_vigostoerer_pages_stoerer_mm (

    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_vigostoerer_news_stoerer_mm'
#
CREATE TABLE tx_vigostoerer_news_stoerer_mm (

    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);