SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET CHARACTER SET 'utf8';


-- --------------------------------------------------------
INSERT INTO `kernel_mod_available` (`node`, `module`) VALUES 
('BU_CLASSE', 'MOD_CLASSEUR'),
('BU_ECOLE', 'MOD_CLASSEUR'),
('BU_VILLE', 'MOD_CLASSEUR'),
('CLUB', 'MOD_CLASSEUR'),
('USER_%', 'MOD_CLASSEUR');

-- -----------------------------------------------------
-- Table `module_classeur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `module_classeur` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(64) NULL,
  `cle` VARCHAR(10) NOT NULL,
  `date_creation` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- -----------------------------------------------------
-- Table `module_classeur_dossier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `module_classeur_dossier` (
  `id` int(11) NOT NULL auto_increment,
  `module_classeur_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL default '0',
  `nom` varchar(64) NOT NULL,
  `nb_dossiers` int(11) NOT NULL default '0',
  `nb_fichiers` int(11) NOT NULL default '0',
  `taille` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL,
  `user_type` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_classeur` (`module_classeur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- -----------------------------------------------------
-- Table `module_classeur_fichier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `module_classeur_fichier` (
  `id` int(11) NOT NULL auto_increment,
  `module_classeur_id` int(11) NOT NULL,
  `module_classeur_dossier_id` int(11) NOT NULL,
  `titre` varchar(64) NOT NULL,
  `commentaire` varchar(255) default NULL,
  `fichier` varchar(128) NOT NULL,
  `taille` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `cle` varchar(10) NOT NULL,
  `date_upload` datetime NOT NULL,
  `user_type` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_dossier` (`module_classeur_dossier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- 29/06/11 - Ajout des champs "_files_type" pour le module "cahierdetextes"
--

ALTER TABLE `module_cahierdetextes_memo2files` ADD `module_files_type` VARCHAR( 64 ) NOT NULL AFTER `module_cahierdetextes_memo_id`;
ALTER TABLE `module_cahierdetextes_travail2files` ADD `module_files_type` VARCHAR( 64 ) NOT NULL AFTER `module_cahierdetextes_travail_id`;

--
-- 29/06/11 - Ajout du champ "public" pour les albums publics du classeur
--
ALTER TABLE `module_classeur_dossier` ADD `public` TINYINT( 4 ) NULL DEFAULT '0' AFTER `user_id`;
ALTER TABLE `module_classeur` ADD `public` TINYINT( 4 ) NULL DEFAULT '0' AFTER `date_creation`;
ALTER TABLE `module_classeur` ADD `date_publication` DATETIME NULL DEFAULT NULL AFTER `date_creation`;
ALTER TABLE `module_classeur_dossier` ADD `date_publication` DATETIME NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `module_classeur_dossier` ADD `cle` VARCHAR( 10 ) NOT NULL AFTER `taille`;