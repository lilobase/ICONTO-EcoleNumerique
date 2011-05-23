SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET CHARACTER SET 'utf8';

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `kernel_mod_available` (`node`, `module`) VALUES
('BU_CLASSE', 'MOD_CAHIERDETEXTES'),
('USER_ELE', 'MOD_CAHIERDETEXTES');

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_domaine`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_domaine` (
  `id` int(11) NOT NULL auto_increment,
  `kernel_bu_ecole_classe_id` int(11) NOT NULL,
  `nom` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_classe` (`kernel_bu_ecole_classe_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_memo`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_memo` (
  `id` int(11) NOT NULL auto_increment,
  `kernel_bu_ecole_classe_id` int(11) NOT NULL,
  `date_creation` varchar(14) NOT NULL,
  `date_validite` varchar(14) default NULL,
  `message` text NOT NULL,
  `avec_signature` tinyint(1) NOT NULL default '0',
  `date_max_signature` varchar(14) default NULL,
  `supprime` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fk_classe` (`kernel_bu_ecole_classe_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_memo2eleve`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_memo2eleve` (
  `module_cahierdetextes_memo_id` int(11) NOT NULL,
  `kernel_bu_eleve_idEleve` int(11) NOT NULL,
  `signe_le` varchar(14) default NULL,
  `commentaire` varchar(255) default NULL,
  PRIMARY KEY  (`module_cahierdetextes_memo_id`,`kernel_bu_eleve_idEleve`),
  KEY `fk_memo` (`module_cahierdetextes_memo_id`),
  KEY `fk_eleve` (`kernel_bu_eleve_idEleve`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_memo2files`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_memo2files` (
  `module_cahierdetextes_memo_id` int(11) NOT NULL,
  `module_malle_files_id` int(11) NOT NULL,
  PRIMARY KEY  (`module_cahierdetextes_memo_id`,`module_malle_files_id`),
  KEY `fk_memo` (`module_cahierdetextes_memo_id`),
  KEY `fk_files` (`module_malle_files_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_travail`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_travail` (
  `id` int(11) NOT NULL auto_increment,
  `module_cahierdetextes_domaine_id` int(11) NOT NULL,
  `a_faire` tinyint(1) NOT NULL default '0',
  `date_creation` varchar(14) NOT NULL,
  `date_realisation` varchar(14) default NULL,
  `description` text NOT NULL,
  `supprime` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fk_domaine` (`module_cahierdetextes_domaine_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_travail2eleve`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_travail2eleve` (
  `module_cahierdetextes_travail_id` int(11) NOT NULL,
  `kernel_bu_eleve_idEleve` int(11) NOT NULL,
  PRIMARY KEY  (`module_cahierdetextes_travail_id`,`kernel_bu_eleve_idEleve`),
  KEY `fk_travail` (`module_cahierdetextes_travail_id`),
  KEY `fk_eleve` (`kernel_bu_eleve_idEleve`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_cahierdetextes_travail2files`
--

CREATE TABLE IF NOT EXISTS `module_cahierdetextes_travail2files` (
  `module_cahierdetextes_travail_id` int(11) NOT NULL,
  `module_malle_files_id` int(11) NOT NULL,
  PRIMARY KEY  (`module_cahierdetextes_travail_id`,`module_malle_files_id`),
  KEY `fk_travail` (`module_cahierdetextes_travail_id`),
  KEY `fk_files` (`module_malle_files_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
