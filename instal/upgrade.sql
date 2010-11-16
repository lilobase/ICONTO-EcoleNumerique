-- Généré le : Mar 16 Novembre 2010 à 14:50
-- Version du serveur: 5.1.44
-- Base de données: `ecole_numerique`
-- --------------------------------------------------------
--
-- Structure de la table `module_tags`
CREATE TABLE IF NOT EXISTS `module_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
--
-- Structure de la table `module_tags_groups`
CREATE TABLE IF NOT EXISTS `module_tags_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` int(11) unsigned NOT NULL,
  `id_tag` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`,`id_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


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
