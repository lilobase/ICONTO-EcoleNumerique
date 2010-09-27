-- Généré le : Lun 27 Septembre 2010 à 12:17
--
-- Structure de la table `module_getreq`
--

CREATE TABLE IF NOT EXISTS `module_getreq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` text NOT NULL,
  `enfants` text NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);


-- UPDATED : cbeyer

ALTER TABLE `module_blog` ADD `template` VARCHAR( 30 ) NULL DEFAULT NULL COMMENT 'Template a utiliser, si different de blog_main.tpl';
ALTER TABLE `module_blog` ADD INDEX ( `url_blog` );  
DROP TABLE `module_welcome_homes`, `module_welcome_url`;
