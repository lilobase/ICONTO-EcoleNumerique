-- PACKAGE

ALTER TABLE `module_blog_articlecomment` CHANGE `authorid_bacc` `authorid_bacc` INT( 11 ) NULL DEFAULT NULL;

-- UPDATED : cbeyer

ALTER TABLE `module_minimail_from` ADD `is_forwarded` TINYINT NULL DEFAULT NULL; 
ALTER TABLE `module_minimail_to` ADD `is_forwarded` TINYINT NULL DEFAULT NULL AFTER `is_replied`; 


CREATE TABLE IF NOT EXISTS `module_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_tags_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(11) unsigned NOT NULL,
  `id_tag` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`,`id_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `module_getreq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` text NOT NULL,
  `enfants` text NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

--

DROP TABLE `module_welcome_homes`, `module_welcome_url`;
