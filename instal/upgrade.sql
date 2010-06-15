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

