-- Généré le : Mer 08 Septembre 2010 à 15:15
-- Auteur: PNL
-- Valeur de base manquantes (constantes VS, Quiz, Premier groupe de ville requis pour la gestion des comptes


-- Possibilite d'ajouter un quiz dans les groupes

INSERT INTO `kernel_mod_available` (`node`, `module`) VALUES
('CLUB', 'MOD_QUIZ');

-- Premier groupe de villes

INSERT INTO  `en2010a`.`kernel_bu_groupe_villes` (
`id_grv` ,
`nom_groupe` ,
`date_creation`
)
VALUES (
NULL ,  'Les villes',  '2010-09-01 00:00:00'
);

-- Cycles

INSERT INTO `kernel_bu_cycle` VALUES(1, 'Maternelle');
INSERT INTO `kernel_bu_cycle` VALUES(2, 'Cycle 2');
INSERT INTO `kernel_bu_cycle` VALUES(3, 'Cycle 3');

-- Types de classe

INSERT INTO `kernel_bu_classe_type` VALUES(11, 'Ordinaire');
INSERT INTO `kernel_bu_classe_type` VALUES(12, 'CLAD');
INSERT INTO `kernel_bu_classe_type` VALUES(13, 'CLIS');
INSERT INTO `kernel_bu_classe_type` VALUES(31, 'CLIN');
INSERT INTO `kernel_bu_classe_type` VALUES(24, 'Groupe d''enseignement');
INSERT INTO `kernel_bu_classe_type` VALUES(32, 'Regroupement d''adaptation');
INSERT INTO `kernel_bu_classe_type` VALUES(33, 'Autre');

-- Niveaux

INSERT INTO `kernel_bu_classe_niveau` VALUES(1, 'Toute petite section', 1, 'TPS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(2, 'Petite section', 1, 'PS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(3, 'Moyenne section', 1, 'MS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(4, 'Grande section', 1, 'GS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(5, 'Cours préparatoire', 2, 'CP');
INSERT INTO `kernel_bu_classe_niveau` VALUES(6, 'Cours élémentaire 1er année', 2, 'CE1');
INSERT INTO `kernel_bu_classe_niveau` VALUES(7, 'Cours élémentaire 2ème année', 3, 'CE2');
INSERT INTO `kernel_bu_classe_niveau` VALUES(8, 'Cours moyen 1er année', 3, 'CM1');
INSERT INTO `kernel_bu_classe_niveau` VALUES(9, 'Cours moyen 2ème année', 3, 'CM2');



-- Généré le : Lun 06 Septembre 2010 à 09:54
-- Version du serveur: 5.1.44
-- Version de PHP: 5.2.13

-- Structure de la table `module_admindash`
--

CREATE TABLE `module_admindash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `picture` varchar(255) DEFAULT NULL,
  `id_zone` int(11) unsigned NOT NULL,
  `type_zone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_zone` (`id_zone`),
  KEY `type_zone` (`type_zone`)
) ENGINE=MyISAM  DEFAULT CHARSET=UTF8;


CREATE TABLE `module_contacts` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL,
  `email` varchar(255) default NULL COMMENT 'Email du destinataire. Si plusieurs, separer par des virgules',
  `date_creation` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `module_contacts_messages` (
  `id` int(11) NOT NULL auto_increment,
  `contact` int(11) NOT NULL,
  `from_nom` varchar(150) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `from_login` varchar(32) default NULL,
  `from_user_id` int(11) default NULL,
  `to_email` varchar(255) default NULL,
  `type` int(11) NOT NULL,
  `message` text NOT NULL,
  `ip` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `contact` (`contact`),
  KEY `type` (`type`),
  KEY `from_user_id` (`from_user_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `module_contacts_types` (
  `id` int(11) NOT NULL auto_increment,
  `contact` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `is_default` tinyint(4) default NULL,
  `ordre` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `contact` (`contact`),
  KEY `ordre` (`ordre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- INSERT INTO `module_contacts_types` (`id`, `contact`, `nom`, `is_default`, `ordre`) VALUES (1, 1, 'Anomalie', NULL, 1), (2, 1, 'Suggestion', NULL, 2), (3, 1, 'Comment faire ?', NULL, 3), (4, 1, 'Autre', 1, 4);





-- Généré le : Jeu 05 Août 2010 à 17:54
-- Version du serveur: 5.1.44
-- Version de PHP: 5.2.13
--
-- Structure de la table `module_mailext`
--

CREATE TABLE `module_mailext` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `protocol` varchar(255) NOT NULL,
  `server` varchar(255) NOT NULL,
  `port` int(2) unsigned NOT NULL,
  `ssl` tinyint(2) unsigned NOT NULL,
  `tls` tinyint(2) unsigned NOT NULL,
  `login` varchar(150) NOT NULL,
  `pass` varchar(150) NOT NULL,
  `imap_path` varchar(150) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `webmail_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- Généré le : Lun 12 Juillet 2010 à 12:13
-- Version du serveur: 5.1.44
-- Version de PHP: 5.2.13
-- Structure de la table `module_quiz_choices`
-- 

DROP TABLE IF EXISTS `module_quiz_choices`;
CREATE TABLE `module_quiz_choices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_question` int(11) unsigned NOT NULL,
  `content` text,
  `correct` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_groupes`
--

DROP TABLE IF EXISTS `module_quiz_groupes`;
CREATE TABLE `module_quiz_groupes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_questions`
--

DROP TABLE IF EXISTS `module_quiz_questions`;
CREATE TABLE `module_quiz_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `id_quiz` int(11) unsigned NOT NULL,
  `content` text,
  `order` int(5) unsigned NOT NULL,
  `opt_type` varchar(75) NOT NULL DEFAULT 'choice',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_quiz`
--

DROP TABLE IF EXISTS `module_quiz_quiz`;
CREATE TABLE `module_quiz_quiz` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `id_owner` int(4) unsigned NOT NULL,
  `date_start` int(5) unsigned NOT NULL DEFAULT '0',
  `date_end` int(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL,
  `description` text,
  `help` text,
  `pic` varchar(255) DEFAULT NULL,
  `opt_save` varchar(75) NOT NULL,
  `opt_show_results` varchar(75) NOT NULL,
  `lock` tinyint(4) NOT NULL DEFAULT '0',
  `gr_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gr_id` (`gr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_quiz_responses`
--

DROP TABLE IF EXISTS `module_quiz_responses`;
CREATE TABLE `module_quiz_responses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `id_choice` int(11) unsigned NOT NULL,
  `id_question` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `txt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- Serveur: localhost
-- Généré le : Ven 09 Juillet 2010 à 16:57
-- Version du serveur: 5.1.44
-- Version de PHP: 5.2.13
-- --------------------------------------------------------

--
-- Structure de la table `module_charte_chartes`
--

CREATE TABLE `module_charte_chartes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(75) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `file_id` int(11) unsigned DEFAULT NULL,
  `active` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_type` (`user_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_charte_users_validation`
--

CREATE TABLE `module_charte_users_validation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `charte_id` int(11) unsigned NOT NULL,
  `user_type` varchar(75) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

