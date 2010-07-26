
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

