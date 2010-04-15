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
