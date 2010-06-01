-- -------------------------------------------------------



-- UPGRADED : FMossmann - Local/Trunk

--
-- creation table de matrice des droits
--
-- Ajout le 19/05/2010 par Arnaud LEMAIRE

--
-- Structure de la table `module_rightmatrix`
--

DROP TABLE IF EXISTS `module_rightmatrix`;
CREATE TABLE IF NOT EXISTS `module_rightmatrix` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_type_in` varchar(75) NOT NULL,
  `user_type_out` varchar(75) NOT NULL,
  `right` varchar(5) NOT NULL,
  `node_type` varchar(75) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_type_in` (`user_type_in`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Contenu de la table `module_rightmatrix`
--

INSERT INTO `module_rightmatrix` (`id`, `user_type_in`, `user_type_out`, `right`, `node_type`) VALUES
(14, 'USER_ENS', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(13, 'USER_ENS', 'USER_DIR', 'COMM', 'BU_VILLE'),
(12, 'USER_ENS', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(6, 'USER_ENS', 'USER_RES', 'VOIR', 'BU_ECOLE'),
(7, 'USER_ENS', 'USER_RES', 'COMM', 'BU_CLASSE'),
(8, 'USER_ENS', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(9, 'USER_ENS', 'USER_ELE', 'COMM', 'BU_CLASSE'),
(10, 'USER_ENS', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(11, 'USER_ENS', 'USER_ENS', 'COMM', 'BU_VILLE'),
(16, 'USER_DIR', 'USER_VIL', 'COMM', 'BU_VILLE'),
(17, 'USER_DIR', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(18, 'USER_DIR', 'USER_DIR', 'COMM', 'BU_VILLE'),
(19, 'USER_DIR', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(20, 'USER_DIR', 'USER_ENS', 'COMM', 'BU_VILLE'),
(21, 'USER_DIR', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(22, 'USER_DIR', 'USER_ELE', 'COMM', 'BU_ECOLE'),
(23, 'USER_DIR', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(24, 'USER_DIR', 'USER_RES', 'COMM', 'BU_CLASSE'),
(25, 'USER_DIR', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(26, 'USER_VIL', 'USER_VIL', 'COMM', 'BU_VILLE'),
(27, 'USER_VIL', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(28, 'USER_VIL', 'USER_DIR', 'COMM', 'BU_VILLE'),
(29, 'USER_VIL', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(30, 'USER_VIL', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(31, 'USER_VIL', 'USER_ELE', 'VOIR', 'BU_VILLE'),
(32, 'USER_VIL', 'USER_RES', 'VOIR', 'BU_VILLE'),
(33, 'USER_ELE', 'USER_DIR', 'COMM', 'BU_ECOLE'),
(34, 'USER_ELE', 'USER_DIR', 'VOIR', 'BU_ECOLE'),
(35, 'USER_ELE', 'USER_ENS', 'VOIR', 'BU_ECOLE'),
(36, 'USER_ELE', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(37, 'USER_ELE', 'USER_ENS', 'COMM', 'BU_CLASSE'),
(38, 'USER_ELE', 'USER_ELE', 'COMM', 'BU_CLASSE'),
(39, 'USER_ELE', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(40, 'USER_RES', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(41, 'USER_RES', 'USER_DIR', 'COMM', 'BU_ECOLE'),
(42, 'USER_RES', 'USER_DIR', 'VOIR', 'BU_ECOLE'),
(43, 'USER_RES', 'USER_ENS', 'VOIR', 'BU_ECOLE'),
(44, 'USER_RES', 'USER_ENS', 'COMM', 'BU_CLASSE'),
(45, 'USER_RES', 'USER_ELE', 'VOIR', 'BU_CLASSE'),
(46, 'USER_RES', 'USER_RES', 'COMM', 'BU_CLASSE'),
(47, 'USER_RES', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(48, 'USER_VIL', 'USER_EXT', 'VOIR', 'ROOT'),
(49, 'USER_VIL', 'USER_EXT', 'COMM', 'ROOT'),
(50, 'USER_DIR', 'USER_EXT', 'VOIR', 'ROOT'),
(51, 'USER_DIR', 'USER_EXT', 'COMM', 'ROOT'),
(52, 'USER_ENS', 'USER_EXT', 'VOIR', 'ROOT'),
(53, 'USER_ENS', 'USER_EXT', 'COMM', 'ROOT');


-- -------------------------------------------------------

ALTER TABLE `kernel_bu_ecole`
CHANGE `RNE` `RNE` VARCHAR(10) NULL DEFAULT NULL,
CHANGE `code_ecole_vaccination` `code_ecole_vaccination` VARCHAR(15) NULL DEFAULT NULL,
CHANGE `type` `type` VARCHAR(32) NULL DEFAULT 'Primaire',
CHANGE `nom` `nom` VARCHAR(50) NOT NULL,
CHANGE `num_rue` `num_rue` VARCHAR(5) NULL DEFAULT NULL,
CHANGE `adresse1` `adresse1` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `adresse2` `adresse2` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `code_postal` `code_postal` VARCHAR(8) NULL DEFAULT NULL,
CHANGE `commune` `commune` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `tel` `tel` VARCHAR(15) NULL DEFAULT NULL,
CHANGE `numordre` `numordre` INT(11) NULL DEFAULT '0',
CHANGE `num_plan_interactif` `num_plan_interactif` INT(11) NULL DEFAULT '0',
CHANGE `id_ville` `id_ville` INT(11) NULL DEFAULT '0';

ALTER TABLE `kernel_bu_eleve`
CHANGE `numero` `numero` VARCHAR(22) NULL DEFAULT NULL,
CHANGE `nom` `nom` VARCHAR(50) NOT NULL,
CHANGE `prenom1` `prenom1` VARCHAR(50) NOT NULL,
CHANGE `civilite` `civilite` VARCHAR(15) NULL DEFAULT NULL,
CHANGE `id_sexe` `id_sexe` INT(11) NULL DEFAULT '0',
CHANGE `num_rue` `num_rue` VARCHAR(5) NULL DEFAULT NULL,
CHANGE `adresse1` `adresse1` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `code_postal` `code_postal` VARCHAR(8) NULL DEFAULT NULL,
CHANGE `commune` `commune` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `pays` `pays` INT(11) NULL DEFAULT '1',
CHANGE `id_ville` `id_ville` int(11) NULL default '0',
CHANGE `hors_scol` `hors_scol` tinyint(4) NULL default '0';

ALTER TABLE `kernel_bu_personnel`
CHANGE `nom` `nom` VARCHAR(50) NOT NULL,
CHANGE `prenom1` `prenom1` VARCHAR(50) NULL DEFAULT NULL, 
CHANGE `civilite` `civilite` VARCHAR(15) NULL DEFAULT NULL, 
CHANGE `id_sexe` `id_sexe` INT(11) NULL DEFAULT '0', 
CHANGE `cle_privee` `cle_privee` VARCHAR(128) NULL DEFAULT NULL,  
CHANGE `num_rue` `num_rue` VARCHAR(5) NULL DEFAULT NULL, 
CHANGE `adresse1` `adresse1` VARCHAR(100) NULL DEFAULT NULL, 
CHANGE `code_postal` `code_postal` VARCHAR(8) NULL DEFAULT NULL,
CHANGE `commune` `commune` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `id_ville` `id_ville` int(11) NULL default '0',
CHANGE `pays` `pays` int(11) NULL default '1';

ALTER TABLE `kernel_bu_responsable`
CHANGE `nom` `nom` VARCHAR(50) NOT NULL, 
CHANGE `prenom1` `prenom1` VARCHAR(50) NOT NULL, 
CHANGE `civilite` `civilite` VARCHAR(15) NULL DEFAULT NULL, 
CHANGE `id_sexe` `id_sexe` INT(11) NULL DEFAULT '0', 
CHANGE `num_rue` `num_rue` VARCHAR(5) NULL DEFAULT NULL, 
CHANGE `adresse1` `adresse1` VARCHAR(100) NULL DEFAULT NULL, 
CHANGE `code_postal` `code_postal` VARCHAR(8) NULL DEFAULT NULL,
CHANGE `commune` `commune` VARCHAR(100) NULL DEFAULT NULL,
CHANGE `id_ville` `id_ville` int(11) NULL default '0';

INSERT INTO `dbgroup` (`id_dbgroup`, `caption_dbgroup`, `description_dbgroup`, `superadmin_dbgroup`, `public_dbgroup`, `registered_dbgroup`) VALUES
(3, 'cities_group_agent', NULL, 0, 0, 0),
(4, 'city_agent', NULL, 0, 0, 0),
(5, 'administration_staff', NULL, 0, 0, 0),
(6, 'principal', NULL, 0, 0, 0),
(7, 'teacher', NULL, 0, 0, 0),
(8, 'cities_group_animator', NULL, 0, 0, 0),
(9, 'schools_group_animator', NULL, 0, 0, 0);

--
-- Contenu de la table `modulecredentials`
--

INSERT INTO `modulecredentials` (`id_mc`, `module_mc`, `name_mc`) VALUES
(1, 'gestionautonome', 'cities_group'),
(2, 'gestionautonome', 'city'),
(3, 'gestionautonome', 'school'),
(4, 'gestionautonome', 'classroom'),
(5, 'gestionautonome', 'cities_group_agent'),
(6, 'gestionautonome', 'city_agent'),
(7, 'gestionautonome', 'administration_staff'),
(8, 'gestionautonome', 'principal'),
(9, 'gestionautonome', 'teacher'),
(10, 'gestionautonome', 'student'),
(11, 'gestionautonome', 'person_in_charge'),
(12, 'gestionautonome', 'access');

--
-- Contenu de la table `modulecredentialsgroups`
--

INSERT INTO `modulecredentialsgroups` (`id_mcg`, `id_mc`, `id_mcv`, `handler_group`, `id_group`) VALUES
(1, 1, 3, 'auth|dbgrouphandler', '3'),
(2, 2, 6, 'auth|dbgrouphandler', '3'),
(3, 3, 9, 'auth|dbgrouphandler', '3'),
(4, 5, 15, 'auth|dbgrouphandler', '3'),
(5, 6, 18, 'auth|dbgrouphandler', '3'),
(6, 7, 21, 'auth|dbgrouphandler', '3'),
(7, 8, 24, 'auth|dbgrouphandler', '3'),
(8, 9, 27, 'auth|dbgrouphandler', '3'),
(9, 10, 30, 'auth|dbgrouphandler', '3'),
(10, 11, 33, 'auth|dbgrouphandler', '3'),
(11, 12, NULL, 'auth|dbgrouphandler', '3'),
(12, 3, 9, 'auth|dbgrouphandler', '4'),
(13, 4, 12, 'auth|dbgrouphandler', '4'),
(23, 9, 27, 'auth|dbgrouphandler', '6'),
(22, 4, 12, 'auth|dbgrouphandler', '6'),
(16, 7, 21, 'auth|dbgrouphandler', '4'),
(17, 8, 24, 'auth|dbgrouphandler', '4'),
(18, 9, 27, 'auth|dbgrouphandler', '4'),
(19, 10, 30, 'auth|dbgrouphandler', '4'),
(20, 11, 33, 'auth|dbgrouphandler', '4'),
(21, 12, NULL, 'auth|dbgrouphandler', '4'),
(24, 10, 30, 'auth|dbgrouphandler', '6'),
(25, 11, 33, 'auth|dbgrouphandler', '6'),
(26, 12, NULL, 'auth|dbgrouphandler', '6'),
(32, 11, 32, 'auth|dbgrouphandler', '7'),
(33, 3, 8, 'auth|dbgrouphandler', '8'),
(29, 12, NULL, 'auth|dbgrouphandler', '7'),
(30, 4, 12, 'auth|dbgrouphandler', '3'),
(31, 10, 29, 'auth|dbgrouphandler', '7'),
(34, 4, 12, 'auth|dbgrouphandler', '8'),
(35, 7, 21, 'auth|dbgrouphandler', '8'),
(36, 8, 24, 'auth|dbgrouphandler', '8'),
(37, 9, 27, 'auth|dbgrouphandler', '8'),
(38, 10, 30, 'auth|dbgrouphandler', '8'),
(39, 11, 33, 'auth|dbgrouphandler', '8'),
(40, 12, NULL, 'auth|dbgrouphandler', '8'),
(41, 4, 11, 'auth|dbgrouphandler', '9'),
(42, 9, 27, 'auth|dbgrouphandler', '9'),
(43, 10, 30, 'auth|dbgrouphandler', '9'),
(44, 11, 33, 'auth|dbgrouphandler', '9'),
(45, 12, NULL, 'auth|dbgrouphandler', '9');

--
-- Contenu de la table `modulecredentialsvalues`
--

INSERT INTO `modulecredentialsvalues` (`id_mcv`, `value_mcv`, `id_mc`, `level_mcv`) VALUES
(1, 'create', 1, 1),
(2, 'update', 1, 2),
(3, 'delete', 1, 3),
(4, 'create', 2, 1),
(5, 'update', 2, 2),
(6, 'delete', 2, 3),
(7, 'create', 3, 1),
(8, 'update', 3, 2),
(9, 'delete', 3, 3),
(10, 'create', 4, 1),
(11, 'update', 4, 2),
(12, 'delete', 4, 3),
(13, 'create', 5, 1),
(14, 'update', 5, 2),
(15, 'delete', 5, 3),
(16, 'create', 6, 1),
(17, 'update', 6, 2),
(18, 'delete', 6, 3),
(19, 'create', 7, 1),
(20, 'update', 7, 2),
(21, 'delete', 7, 3),
(22, 'create', 8, 1),
(23, 'update', 8, 2),
(24, 'delete', 8, 3),
(25, 'create', 9, 1),
(26, 'update', 9, 2),
(27, 'delete', 9, 3),
(28, 'create', 10, 1),
(29, 'update', 10, 2),
(30, 'delete', 10, 3),
(31, 'create', 11, 1),
(32, 'update', 11, 2),
(33, 'delete', 11, 3);

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_choices`
--

CREATE TABLE IF NOT EXISTS `module_regroupements_grvilles2villes` (
      `id_groupe` int(11) NOT NULL,
      `id_ville` int(11) NOT NULL,
      `updated_at` datetime NOT NULL,
      `updated_by` varchar(50) NOT NULL,
      PRIMARY KEY  (`id_groupe`,`id_ville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Base de données: `module_quiz`
-- Ajout le 15/04/2010 par Arnaud LEMAIRE

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_choices`
--

CREATE TABLE IF NOT EXISTS `module_quiz_choices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_question` int(11) unsigned NOT NULL,
  `content_txt` varchar(1024) DEFAULT NULL,
  `content_pic` varchar(512) DEFAULT NULL,
  `correct` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_questions`
--

CREATE TABLE IF NOT EXISTS `module_quiz_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_quiz` int(11) unsigned NOT NULL,
  `content_txt` varchar(255) DEFAULT NULL,
  `content_pic` varchar(255) DEFAULT NULL,
  `order` int(5) unsigned NOT NULL,
  `opt_type` varchar(75) NOT NULL DEFAULT 'choice',
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_quiz`
--

CREATE TABLE IF NOT EXISTS `module_quiz_quiz` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `id_author` int(4) unsigned NOT NULL,
  `date_start` int(5) unsigned NOT NULL DEFAULT '0',
  `date_end` int(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL,
  `description` text,
  `pic` varchar(255) DEFAULT NULL,
  `opt_save` varchar(75) NOT NULL,
  `opt_show_results` varchar(75) NOT NULL,
  `lock` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_responses`
--

CREATE TABLE IF NOT EXISTS `module_quiz_responses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `id_choice` int(11) unsigned NOT NULL,
  `id_question` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `txt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

--
-- Structure de la table `kernel_animateurs`
--

CREATE TABLE `kernel_animateurs` (
  `user_type` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `can_connect` tinyint(4) NOT NULL,
  `can_tableaubord` tinyint(4) NOT NULL,
  `can_comptes` tinyint(4) NOT NULL,
  `is_visibleannuaire` tinyint(4) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY (`user_type`,`user_id`),
  KEY `is_visibleannuaire` (`is_visibleannuaire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `kernel_animateurs2regroupements`
--

CREATE TABLE `kernel_animateurs2regroupements` (
  `user_type` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `regroupement_type` enum('villes','ecoles') NOT NULL,
  `regroupement_id` int(11) NOT NULL,
  PRIMARY KEY (`user_type`,`user_id`,`regroupement_type`,`regroupement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

