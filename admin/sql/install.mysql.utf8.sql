CREATE TABLE IF NOT EXISTS `#__sermondistributor_preacher` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`description` TEXT NOT NULL,
	`email` VARCHAR(255) NOT NULL DEFAULT '',
	`icon` CHAR(64) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`website` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_name` (`name`),
	KEY `idx_alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_sermon` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`auto_sermons` TEXT NOT NULL,
	`build` TINYINT(1) NOT NULL DEFAULT 0,
	`catid` INT(11) NOT NULL DEFAULT 0,
	`description` TEXT NOT NULL,
	`icon` CHAR(64) NOT NULL DEFAULT '',
	`link_type` TINYINT(1) NOT NULL DEFAULT 1,
	`local_files` TEXT NOT NULL,
	`manual_files` TEXT NOT NULL,
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`not_required` INT(1) NOT NULL DEFAULT 0,
	`preacher` INT(11) NOT NULL DEFAULT 0,
	`scripture` VARCHAR(255) NOT NULL DEFAULT '',
	`series` INT(11) NOT NULL DEFAULT 0,
	`short_description` VARCHAR(255) NOT NULL DEFAULT '',
	`source` TINYINT(1) NOT NULL DEFAULT 0,
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_name` (`name`),
	KEY `idx_preacher` (`preacher`),
	KEY `idx_series` (`series`),
	KEY `idx_catid` (`catid`),
	KEY `idx_link_type` (`link_type`),
	KEY `idx_alias` (`alias`),
	KEY `idx_build` (`build`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_series` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`description` TEXT NOT NULL,
	`icon` CHAR(64) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`scripture` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_name` (`name`),
	KEY `idx_alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_statistic` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`counter` INT(64) NOT NULL DEFAULT 0,
	`filename` VARCHAR(255) NOT NULL DEFAULT '',
	`preacher` INT(11) NOT NULL DEFAULT 0,
	`series` INT(11) NOT NULL DEFAULT 0,
	`sermon` INT(11) NOT NULL DEFAULT 0,
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_filename` (`filename`),
	KEY `idx_sermon` (`sermon`),
	KEY `idx_preacher` (`preacher`),
	KEY `idx_series` (`series`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_external_source` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`build` TINYINT(1) NOT NULL DEFAULT 0,
	`description` VARCHAR(255) NOT NULL DEFAULT '',
	`dropboxoptions` TINYINT(1) NOT NULL DEFAULT 1,
	`externalsources` INT(11) NOT NULL DEFAULT 0,
	`filetypes` TEXT NOT NULL,
	`folder` TEXT NOT NULL,
	`not_required` INT(1) NOT NULL DEFAULT 0,
	`oauthtoken` TEXT NOT NULL,
	`permissiontype` VARCHAR(64) NOT NULL DEFAULT '',
	`sharedurl` TEXT NOT NULL,
	`update_method` TINYINT NOT NULL DEFAULT 0,
	`update_timer` INT(1) NOT NULL DEFAULT 0,
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_description` (`description`),
	KEY `idx_externalsources` (`externalsources`),
	KEY `idx_update_method` (`update_method`),
	KEY `idx_build` (`build`),
	KEY `idx_dropboxoptions` (`dropboxoptions`),
	KEY `idx_permissiontype` (`permissiontype`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_local_listing` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`build` TINYINT(1) NOT NULL DEFAULT 0,
	`external_source` INT(11) NOT NULL DEFAULT 0,
	`key` VARCHAR(255) NOT NULL DEFAULT '',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`size` INT(50) NOT NULL DEFAULT 0,
	`url` TEXT NOT NULL,
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_name` (`name`),
	KEY `idx_build` (`build`),
	KEY `idx_key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sermondistributor_help_document` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`admin_view` CHAR(255) NOT NULL DEFAULT '',
	`alias` CHAR(64) NOT NULL DEFAULT '',
	`article` TINYINT(1) NOT NULL DEFAULT 0,
	`content` TEXT NOT NULL,
	`groups` TEXT NOT NULL,
	`location` TINYINT(1) NOT NULL DEFAULT 0,
	`not_required` INT(1) NOT NULL DEFAULT 0,
	`site_view` CHAR(255) NOT NULL DEFAULT '',
	`target` TINYINT(1) NOT NULL DEFAULT 0,
	`title` CHAR(64) NOT NULL DEFAULT '',
	`type` TINYINT(1) NOT NULL DEFAULT 0,
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`params` text NOT NULL DEFAULT '',
	`published` TINYINT(3) NOT NULL DEFAULT 1,
	`created_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`modified_by` INT(10) unsigned NOT NULL DEFAULT 0,
	`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` int(11) unsigned NOT NULL DEFAULT 0,
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`version` INT(10) unsigned NOT NULL DEFAULT 1,
	`hits` INT(10) unsigned NOT NULL DEFAULT 0,
	`access` INT(10) unsigned NOT NULL DEFAULT 0,
	`ordering` INT(11) NOT NULL DEFAULT 0,
	`metakey` TEXT NOT NULL DEFAULT '',
	`metadesc` TEXT NOT NULL DEFAULT '',
	`metadata` TEXT NOT NULL DEFAULT '',
	PRIMARY KEY  (`id`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`),
	KEY `idx_title` (`title`),
	KEY `idx_type` (`type`),
	KEY `idx_location` (`location`),
	KEY `idx_target` (`target`),
	KEY `idx_alias` (`alias`),
	KEY `idx_article` (`article`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;



--
-- Always insure this column rules is large enough for all the access control values.
--
ALTER TABLE `#__assets` CHANGE `rules` `rules` MEDIUMTEXT NOT NULL COMMENT 'JSON encoded access control.';

--
-- Always insure this column name is large enough for long component and view names.
--
ALTER TABLE `#__assets` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The unique name for the asset.';
