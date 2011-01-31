
--  UPDATED : cbeyer

ALTER TABLE `module_fiches_ecoles` ADD `doc1_titre` VARCHAR( 200 ) NULL DEFAULT NULL ,
ADD `doc1_fichier` VARCHAR( 200 ) NULL DEFAULT NULL;

--

INSERT INTO `module_rightmatrix` VALUES('', 'USER_ATI', 'USER_ENS', 'VOIR', 'BU_GRVILLE');
INSERT INTO `module_rightmatrix` VALUES('', 'USER_ATI', 'USER_ENS', 'COMM', 'BU_GRVILLE');
INSERT INTO `module_rightmatrix` VALUES('', 'USER_ATI', 'USER_DIR', 'VOIR', 'BU_GRVILLE');
INSERT INTO `module_rightmatrix` VALUES('', 'USER_ATI', 'USER_DIR', 'COMM', 'BU_GRVILLE');

--

INSERT INTO `dbgroup` (`id_dbgroup`, `caption_dbgroup`, `description_dbgroup`, `superadmin_dbgroup`, `public_dbgroup`, `registered_dbgroup`) VALUES
(10, 'teacher_school', NULL, 0, 0, 0);

INSERT INTO `modulecredentialsgroups` ( `id_mc`, `id_mcv`, `handler_group`, `id_group`) VALUES
(10, 29, 'auth|dbgrouphandler', '10'),
(11, 32, 'auth|dbgrouphandler', '10'),
(12, NULL, 'auth|dbgrouphandler', '10');

--

DELETE FROM `module_stats_logs` WHERE `module_type`='MOD_BLOG' AND `action`='showArticle' AND `objet_a` IS NULL;
DELETE FROM `module_stats_logs` WHERE `module_type`='MOD_BLOG' AND `action`='showPage' AND `objet_a` IS NULL;
DELETE FROM `module_stats_logs` WHERE `module_type`='MOD_MINIMAIL' AND `action`='readMinimail' AND `objet_a` IS NULL;
DELETE FROM `module_stats_logs` WHERE `module_type`='MOD_MINIMAIL' AND `action`='sendMinimail' AND `objet_a` IS NULL;

-- PACKAGE

INSERT INTO  `kernel_mod_available` ( `node` , `module` ) VALUES ( 'BU_CLASSE',  'MOD_QUIZ');

ALTER TABLE `module_blog_articlecomment` CHANGE `authorid_bacc` `authorid_bacc` INT( 11 ) NULL DEFAULT NULL;

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
