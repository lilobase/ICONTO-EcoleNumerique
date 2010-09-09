-- 
-- Structure de la table `copixconfig`
-- 

DROP TABLE IF EXISTS `copixconfig`;
CREATE TABLE `copixconfig` (
  `id_ccfg` varchar(255) NOT NULL default '',
  `module_ccfg` varchar(255) NOT NULL default '',
  `value_ccfg` varchar(255) default NULL,
  PRIMARY KEY  (`id_ccfg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `copixlog`
-- 

DROP TABLE IF EXISTS `copixlog`;
CREATE TABLE `copixlog` (
  `date` varchar(255) NOT NULL default '',
  `profile` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `level` varchar(255) NOT NULL default '',
  `user` varchar(255) default NULL,
  `classname` varchar(255) default NULL,
  `functionname` varchar(255) default NULL,
  `line` varchar(5) default NULL,
  `file` varchar(255) default NULL,
  `type` varchar(50) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `copixmodule`
-- 

DROP TABLE IF EXISTS `copixmodule`;
CREATE TABLE `copixmodule` (
  `name_cpm` varchar(255) NOT NULL default '',
  `path_cpm` varchar(255) NOT NULL default '',
  `version_cpm` varchar(255) default NULL,
  PRIMARY KEY  (`name_cpm`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `dbgroup`
-- 

DROP TABLE IF EXISTS `dbgroup`;
CREATE TABLE `dbgroup` (
  `id_dbgroup` int(11) NOT NULL auto_increment,
  `caption_dbgroup` varchar(255) NOT NULL,
  `description_dbgroup` text,
  `superadmin_dbgroup` tinyint(4) NOT NULL,
  `public_dbgroup` tinyint(4) NOT NULL,
  `registered_dbgroup` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_dbgroup`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `dbgroup_users`
-- 

DROP TABLE IF EXISTS `dbgroup_users`;
CREATE TABLE `dbgroup_users` (
  `id_dbgroup` int(11) NOT NULL,
  `userhandler_dbgroup` varchar(255) NOT NULL,
  `user_dbgroup` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `dbuser`
-- 

DROP TABLE IF EXISTS `dbuser`;
CREATE TABLE `dbuser` (
  `id_dbuser` int(11) NOT NULL auto_increment,
  `login_dbuser` varchar(32) NOT NULL,
  `password_dbuser` varchar(32) NOT NULL,
  `email_dbuser` varchar(255) NOT NULL,
  `enabled_dbuser` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_dbuser`),
  UNIQUE KEY `login` (`login_dbuser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `dynamiccredentials`
-- 

DROP TABLE IF EXISTS `dynamiccredentials`;
CREATE TABLE `dynamiccredentials` (
  `id_dc` int(11) NOT NULL auto_increment,
  `name_dc` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_dc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `dynamiccredentialsgroups`
-- 

DROP TABLE IF EXISTS `dynamiccredentialsgroups`;
CREATE TABLE `dynamiccredentialsgroups` (
  `id_dcg` int(11) NOT NULL auto_increment,
  `id_dc` int(11) NOT NULL,
  `id_dcv` int(11) default NULL,
  `handler_group` varchar(255) default NULL,
  `id_group` varchar(255) default NULL,
  PRIMARY KEY  (`id_dcg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `dynamiccredentialsvalues`
-- 

DROP TABLE IF EXISTS `dynamiccredentialsvalues`;
CREATE TABLE `dynamiccredentialsvalues` (
  `id_dcv` int(11) NOT NULL auto_increment,
  `value_dcv` varchar(255) NOT NULL,
  `id_dc` int(11) NOT NULL,
  `level_dcv` int(11) default NULL,
  PRIMARY KEY  (`id_dcv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_animateurs`
-- 

DROP TABLE IF EXISTS `kernel_animateurs`;
CREATE TABLE `kernel_animateurs` (
  `user_type` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `can_connect` tinyint(4) NOT NULL,
  `can_tableaubord` tinyint(4) NOT NULL,
  `can_comptes` tinyint(4) NOT NULL,
  `is_visibleannuaire` tinyint(4) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY  (`user_type`,`user_id`),
  KEY `is_visibleannuaire` (`is_visibleannuaire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_animateurs2regroupements`
-- 

DROP TABLE IF EXISTS `kernel_animateurs2regroupements`;
CREATE TABLE `kernel_animateurs2regroupements` (
  `user_type` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `regroupement_type` ENUM('villes', 'ecoles') NOT NULL,
  `regroupement_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_type`,`user_id`,`regroupement_type`, `regroupement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_annee_scolaire`
-- 

DROP TABLE IF EXISTS `kernel_bu_annee_scolaire`;
CREATE TABLE `kernel_bu_annee_scolaire` (
  `id_as` int(11) NOT NULL default '0',
  `annee_scolaire` varchar(10) NOT NULL default '',
  `dateDebut` date NOT NULL default '0000-00-00',
  `dateFin` date NOT NULL default '0000-00-00',
  `current` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_as`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_auth`
-- 

DROP TABLE IF EXISTS `kernel_bu_auth`;
CREATE TABLE `kernel_bu_auth` (
  `node_type` varchar(15) NOT NULL,
  `node_id` int(11) NOT NULL,
  `service` varchar(15) NOT NULL,
  `login` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_civilite`
-- 

DROP TABLE IF EXISTS `kernel_bu_civilite`;
CREATE TABLE `kernel_bu_civilite` (
  `id_civ` tinyint(4) NOT NULL auto_increment,
  `civilite` varchar(15) NOT NULL default '',
  `civ_court` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id_civ`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_classe_niveau`
-- 

DROP TABLE IF EXISTS `kernel_bu_classe_niveau`;
CREATE TABLE `kernel_bu_classe_niveau` (
  `id_n` tinyint(4) NOT NULL auto_increment,
  `niveau` varchar(30) NOT NULL default '',
  `id_cycle` tinyint(4) NOT NULL default '0',
  `niveau_court` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`id_n`),
  KEY `id_cycle` (`id_cycle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_classe_type`
-- 

DROP TABLE IF EXISTS `kernel_bu_classe_type`;
CREATE TABLE `kernel_bu_classe_type` (
  `id_tycla` tinyint(4) NOT NULL auto_increment,
  `type_classe` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_tycla`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_cycle`
-- 

DROP TABLE IF EXISTS `kernel_bu_cycle`;
CREATE TABLE `kernel_bu_cycle` (
  `id_c` tinyint(4) NOT NULL auto_increment,
  `cycle` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id_c`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_ecole`
-- 

DROP TABLE IF EXISTS `kernel_bu_ecole`;
CREATE TABLE `kernel_bu_ecole` (
  `numero` int(11) NOT NULL auto_increment,
  `RNE` varchar(10) NOT NULL default '',
  `code_ecole_vaccination` varchar(15) NOT NULL default '',
  `type` varchar(25) NOT NULL default '',
  `nom` varchar(50) NOT NULL default '',
  `num_rue` varchar(5) NOT NULL default '',
  `num_seq` varchar(10) default NULL,
  `adresse1` varchar(100) NOT NULL default '',
  `adresse2` varchar(100) NOT NULL default '',
  `code_postal` varchar(8) NOT NULL default '',
  `commune` varchar(100) NOT NULL default '',
  `tel` varchar(15) NOT NULL default '',
  `web` varchar(80) default NULL,
  `mail` varchar(80) default NULL,
  `num_intranet` int(11) default NULL,
  `numordre` int(11) NOT NULL default '0',
  `num_plan_interactif` int(11) NOT NULL default '0',
  `id_ville` int(11) NOT NULL default '0',
  PRIMARY KEY  (`numero`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_ecole_classe`
-- 

DROP TABLE IF EXISTS `kernel_bu_ecole_classe`;
CREATE TABLE `kernel_bu_ecole_classe` (
  `id` int(11) NOT NULL auto_increment,
  `ecole` int(11) NOT NULL default '0',
  `nom` varchar(50) NOT NULL default '',
  `annee_scol` int(11) NOT NULL default '2006',
  `is_validee` tinyint(1) NOT NULL default '0',
  `is_supprimee` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ecole` (`ecole`),
  KEY `is_validee` (`is_validee`),
  KEY `is_supprimee` (`is_supprimee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_ecole_classe_niveau`
-- 

DROP TABLE IF EXISTS `kernel_bu_ecole_classe_niveau`;
CREATE TABLE `kernel_bu_ecole_classe_niveau` (
  `classe` int(11) NOT NULL default '0',
  `niveau` int(11) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  PRIMARY KEY  (`classe`,`niveau`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve`;
CREATE TABLE `kernel_bu_eleve` (
  `idEleve` int(11) NOT NULL auto_increment,
  `numero` varchar(22) NOT NULL default '',
  `INE` varchar(20) default NULL,
  `nom` varchar(50) NOT NULL default '',
  `nom_jf` varchar(50) default NULL,
  `prenom1` varchar(50) NOT NULL default '',
  `prenom2` varchar(50) default NULL,
  `prenom3` varchar(50) default NULL,
  `civilite` varchar(15) NOT NULL default '',
  `id_sexe` int(11) NOT NULL default '0',
  `pays_nais` varchar(50) default NULL,
  `nationalite` int(11) default NULL,
  `dep_nais` varchar(10) default NULL,
  `com_nais` varchar(100) default NULL,
  `date_nais` date default NULL,
  `annee_france` int(11) default NULL,
  `num_rue` varchar(5) NOT NULL default '',
  `num_seq` varchar(10) default NULL,
  `adresse1` varchar(100) NOT NULL default '',
  `adresse2` varchar(100) default NULL,
  `code_postal` varchar(8) NOT NULL default '',
  `commune` varchar(100) NOT NULL default '',
  `id_ville` int(11) NOT NULL default '0',
  `pays` int(11) NOT NULL default '1',
  `hors_scol` tinyint(4) NOT NULL default '0',
  `id_directeur` varchar(10) default NULL,
  `observations` varchar(255) default NULL,
  `flag` int(11) NOT NULL default '0',
  `adresse_tmp` varchar(150) default NULL,
  `date_tmp` datetime default NULL,
  `ele_last_update` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`idEleve`),
  KEY `id_directeur` (`id_directeur`),
  KEY `civilite` (`civilite`),
  KEY `id_sexe` (`id_sexe`),
  KEY `pays_nais` (`pays_nais`),
  KEY `nationalite` (`nationalite`),
  KEY `date_nais` (`date_nais`),
  KEY `adresse1` (`adresse1`),
  KEY `commune` (`commune`),
  KEY `flag` (`flag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_admission`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_admission`;
CREATE TABLE `kernel_bu_eleve_admission` (
  `numero` int(11) NOT NULL auto_increment,
  `eleve` int(11) NOT NULL default '0',
  `etablissement` int(11) NOT NULL default '0',
  `annee_scol` int(11) NOT NULL default '0',
  `id_niveau` int(11) NOT NULL default '0',
  `etat_eleve` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `date_effet` date NOT NULL default '0000-00-00',
  `code_radiation` int(11) NOT NULL default '0',
  `previsionnel` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`numero`),
  KEY `eleve` (`eleve`),
  KEY `etablissement` (`etablissement`),
  KEY `annee_scol` (`annee_scol`),
  KEY `etat_eleve` (`etat_eleve`),
  KEY `code_radiation` (`code_radiation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_affectation`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_affectation`;
CREATE TABLE `kernel_bu_eleve_affectation` (
  `id` int(11) NOT NULL auto_increment,
  `eleve` int(11) NOT NULL default '0',
  `annee_scol` int(11) NOT NULL default '0',
  `classe` int(11) NOT NULL default '0',
  `niveau` int(11) NOT NULL default '0',
  `dateDebut` date NOT NULL default '0000-00-00',
  `current` tinyint(4) NOT NULL default '1',
  `previsionnel_cl` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `eleve` (`eleve`),
  KEY `annee_scol` (`annee_scol`),
  KEY `classe` (`classe`),
  KEY `niveau` (`niveau`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_classe_mvt`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_classe_mvt`;
CREATE TABLE `kernel_bu_eleve_classe_mvt` (
  `id_eleve` int(11) NOT NULL default '0',
  `id_ecole_ini` int(11) NOT NULL default '0',
  `id_classe_ini` int(11) NOT NULL default '0',
  `id_niveau_ini` tinyint(4) NOT NULL default '0',
  `id_classe_next` int(11) default NULL,
  `id_niveau_next` tinyint(4) default NULL,
  `code_radiation` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_etat`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_etat`;
CREATE TABLE `kernel_bu_eleve_etat` (
  `id_ee` int(11) NOT NULL auto_increment,
  `lib_etat` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id_ee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_inscription`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_inscription`;
CREATE TABLE `kernel_bu_eleve_inscription` (
  `numero` int(11) NOT NULL auto_increment,
  `eleve` int(11) NOT NULL default '0',
  `annee_scol` int(11) NOT NULL default '0',
  `date_preinscript` date NOT NULL default '0000-00-00',
  `date_effet_preinscript` date NOT NULL default '0000-00-00',
  `date_inscript` date NOT NULL default '0000-00-00',
  `date_effet_inscript` date NOT NULL default '0000-00-00',
  `etablissement` int(11) default '0',
  `etablissement_refus` int(11) NOT NULL default '0',
  `id_niveau` tinyint(4) NOT NULL default '0',
  `id_typ_cla` tinyint(4) NOT NULL default '11',
  `vaccins_aj` tinyint(4) NOT NULL default '0',
  `attente` tinyint(4) NOT NULL default '0',
  `date_attente` date default NULL,
  `derogation_dem` tinyint(4) NOT NULL default '0',
  `derogation_accept` tinyint(4) default NULL,
  `derogation_date_dem` date default NULL,
  `derogation_date_accept` date default NULL,
  `derogation_maire` tinyint(4) default NULL,
  `derogation_commune` varchar(50) default NULL,
  `temporaire` tinyint(4) NOT NULL default '0',
  `date_vac_limit` date default NULL,
  `current_inscr` int(11) NOT NULL default '1',
  PRIMARY KEY  (`numero`),
  KEY `eleve` (`eleve`),
  KEY `annee_scol` (`annee_scol`),
  KEY `etablissement` (`etablissement`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_typ_cla` (`id_typ_cla`),
  KEY `vaccins_aj` (`vaccins_aj`),
  KEY `temporaire` (`temporaire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_periscolaire`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_periscolaire`;
CREATE TABLE `kernel_bu_eleve_periscolaire` (
  `id_ps` int(11) NOT NULL auto_increment,
  `eleve` int(11) NOT NULL default '0',
  `etablissement` int(11) NOT NULL default '0',
  `annee_scol` int(11) NOT NULL default '0',
  `garderie_matin` tinyint(4) NOT NULL default '0',
  `garderie_soir` tinyint(4) NOT NULL default '0',
  `etude_surv` tinyint(4) NOT NULL default '0',
  `resto_scol` tinyint(4) NOT NULL default '0',
  `transp_scol` tinyint(4) NOT NULL default '0',
  `allergies_alim` tinyint(4) NOT NULL default '0',
  `allergies_autres` tinyint(4) NOT NULL default '0',
  `a_regimes` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_ps`),
  KEY `eleve` (`eleve`),
  KEY `etablissement` (`etablissement`),
  KEY `annee_scol` (`annee_scol`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_eleve_vaccins`
-- 

DROP TABLE IF EXISTS `kernel_bu_eleve_vaccins`;
CREATE TABLE `kernel_bu_eleve_vaccins` (
  `eleve` int(11) NOT NULL default '0',
  `bcg_contre_indic` date NOT NULL default '0000-00-00',
  `bcg_injection1` date NOT NULL default '0000-00-00',
  `bcg_injection2` date NOT NULL default '0000-00-00',
  `bcg_injection3` date NOT NULL default '0000-00-00',
  `diphterie_contre_indic` date NOT NULL default '0000-00-00',
  `diphterie_type` char(4) NOT NULL default '',
  `diphterie_date` date NOT NULL default '0000-00-00',
  `diphterie_nature` tinyint(4) NOT NULL default '0',
  `diphterie_next` date NOT NULL default '0000-00-00',
  `tetanos_contre_indic` date NOT NULL default '0000-00-00',
  `tetanos_type` char(4) NOT NULL default '',
  `tetanos_date` date NOT NULL default '0000-00-00',
  `tetanos_next` date NOT NULL default '0000-00-00',
  `polio_contre_indic` date NOT NULL default '0000-00-00',
  `polio_type` char(4) NOT NULL default '',
  `polio_date` date NOT NULL default '0000-00-00',
  `polio_next` date NOT NULL default '0000-00-00',
  `coqueluche_contre_indic` date NOT NULL default '0000-00-00',
  `coqueluche_type` char(4) NOT NULL default '',
  `coqueluche_date` date NOT NULL default '0000-00-00',
  `coqueluche_nature` tinyint(4) NOT NULL default '0',
  `coqueluche_next` date NOT NULL default '0000-00-00',
  `haemophilus_contre_indic` date NOT NULL default '0000-00-00',
  `haemophilus_type` char(4) NOT NULL default '',
  `haemophilus_date` date NOT NULL default '0000-00-00',
  `haemophilus_next` date NOT NULL default '0000-00-00',
  `rougeole_inj1` date NOT NULL default '0000-00-00',
  `rougeole_inj2` date NOT NULL default '0000-00-00',
  `oreillons_inj1` date NOT NULL default '0000-00-00',
  `oreillons_inj2` date NOT NULL default '0000-00-00',
  `rubeole_inj1` date NOT NULL default '0000-00-00',
  `rubeole_inj2` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`eleve`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_geo_departements`
-- 

DROP TABLE IF EXISTS `kernel_bu_geo_departements`;
CREATE TABLE `kernel_bu_geo_departements` (
  `C_Reg` char(2) NOT NULL default '',
  `C_Dpt` char(3) NOT NULL default '',
  `N_Dpt_min` varchar(50) NOT NULL default '',
  `N_Dpt_maj` varchar(50) NOT NULL default '',
  `C_Chef_lieu` varchar(50) NOT NULL default '',
  `N_Chef_lieu_min` varchar(50) NOT NULL default '',
  `N_Chef_lieu_maj` varchar(50) NOT NULL default '',
  `Arr` tinyint(4) NOT NULL default '0',
  `Cant` int(11) NOT NULL default '0',
  `Com` int(11) NOT NULL default '0',
  `Pop90` int(11) NOT NULL default '0',
  `Pop99` int(11) NOT NULL default '0',
  PRIMARY KEY  (`C_Dpt`),
  KEY `C_Dpt` (`C_Dpt`),
  KEY `C_Reg` (`C_Reg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_groupe_villes`
-- 

DROP TABLE IF EXISTS `kernel_bu_groupe_villes`;
CREATE TABLE `kernel_bu_groupe_villes` (
  `id_grv` int(11) NOT NULL auto_increment,
  `nom_groupe` varchar(150) NOT NULL default '',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_grv`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_lien_parental`
-- 

DROP TABLE IF EXISTS `kernel_bu_lien_parental`;
CREATE TABLE `kernel_bu_lien_parental` (
  `id_pa` tinyint(4) NOT NULL auto_increment,
  `parente` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_pa`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_nationalite`
-- 

DROP TABLE IF EXISTS `kernel_bu_nationalite`;
CREATE TABLE `kernel_bu_nationalite` (
  `id` int(11) NOT NULL auto_increment,
  `nationalite` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `nationalite` (`nationalite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_organisme`
-- 

DROP TABLE IF EXISTS `kernel_bu_organisme`;
CREATE TABLE `kernel_bu_organisme` (
  `numero` int(11) NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `num_rue` varchar(5) NOT NULL default '',
  `num_seq` varchar(10) default NULL,
  `adresse1` varchar(100) NOT NULL default '',
  `adresse2` varchar(100) NOT NULL default '',
  `code_postal` varchar(8) NOT NULL default '',
  `commune` varchar(100) NOT NULL default '',
  `tel` varchar(15) NOT NULL default '',
  `web` varchar(80) default NULL,
  PRIMARY KEY  (`numero`),
  KEY `commune` (`commune`),
  KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_pays`
-- 

DROP TABLE IF EXISTS `kernel_bu_pays`;
CREATE TABLE `kernel_bu_pays` (
  `id` int(11) NOT NULL auto_increment,
  `pays` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `nationalite` (`pays`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_pcs`
-- 

DROP TABLE IF EXISTS `kernel_bu_pcs`;
CREATE TABLE `kernel_bu_pcs` (
  `id_p` int(11) NOT NULL default '0',
  `pcs` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`id_p`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_personnel`
-- 

DROP TABLE IF EXISTS `kernel_bu_personnel`;
CREATE TABLE `kernel_bu_personnel` (
  `numero` int(11) NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `nom_jf` varchar(50) default NULL,
  `prenom1` varchar(50) NOT NULL default '',
  `civilite` varchar(15) NOT NULL default '',
  `id_sexe` int(11) NOT NULL default '0',
  `date_nais` date default NULL,
  `cle_privee` varchar(128) default NULL,
  `profession` varchar(80) default NULL,
  `tel_dom` varchar(15) default NULL,
  `tel_gsm` varchar(15) default NULL,
  `tel_pro` varchar(15) default NULL,
  `mel` varchar(80) default NULL,
  `num_rue` varchar(5) NOT NULL default '',
  `num_seq` varchar(10) default NULL,
  `adresse1` varchar(100) NOT NULL default '',
  `adresse2` varchar(100) default NULL,
  `code_postal` varchar(8) NOT NULL default '',
  `commune` varchar(100) NOT NULL default '',
  `id_ville` int(11) NOT NULL default '0',
  `pays` int(11) NOT NULL default '1',
  `challenge` varchar(128) default NULL,
  `dateChallenge` int(11) default NULL,
  PRIMARY KEY  (`numero`),
  KEY `nom` (`nom`),
  KEY `id_sexe` (`id_sexe`),
  KEY `adresse1` (`adresse1`),
  KEY `commune` (`commune`),
  KEY `pays` (`pays`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_personnel_entite`
-- 

DROP TABLE IF EXISTS `kernel_bu_personnel_entite`;
CREATE TABLE `kernel_bu_personnel_entite` (
  `id_per` int(11) NOT NULL default '0',
  `reference` int(11) NOT NULL default '0',
  `type_ref` char(6) NOT NULL default '',
  `role` int(11) default NULL,
  PRIMARY KEY  (`id_per`,`reference`,`type_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_personnel_role`
-- 

DROP TABLE IF EXISTS `kernel_bu_personnel_role`;
CREATE TABLE `kernel_bu_personnel_role` (
  `id_role` int(11) NOT NULL auto_increment,
  `nom_role` varchar(80) NOT NULL default '',
  `nom_role_pluriel` varchar(80) NOT NULL default '',
  `perimetre` varchar(30) NOT NULL default '',
  `priorite` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id_role`),
  KEY `nom_role` (`nom_role`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_radiation`
-- 

DROP TABLE IF EXISTS `kernel_bu_radiation`;
CREATE TABLE `kernel_bu_radiation` (
  `id` int(11) NOT NULL default '0',
  `libelle` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_responsable`
-- 

DROP TABLE IF EXISTS `kernel_bu_responsable`;
CREATE TABLE `kernel_bu_responsable` (
  `numero` int(11) NOT NULL auto_increment,
  `nom` varchar(50) NOT NULL default '',
  `nom_jf` varchar(50) default NULL,
  `prenom1` varchar(50) NOT NULL default '',
  `civilite` varchar(15) NOT NULL default '',
  `id_sexe` int(11) NOT NULL default '0',
  `id_pcs` int(11) default NULL,
  `profession` varchar(80) default NULL,
  `id_fam` int(11) default NULL,
  `tel_dom` varchar(15) default NULL,
  `tel_gsm` varchar(15) default NULL,
  `tel_pro` varchar(15) default NULL,
  `mel` varchar(80) default NULL,
  `num_rue` varchar(5) NOT NULL default '',
  `num_seq` varchar(10) default NULL,
  `adresse1` varchar(100) NOT NULL default '',
  `adresse2` varchar(100) default NULL,
  `code_postal` varchar(8) NOT NULL default '',
  `commune` varchar(100) NOT NULL default '',
  `id_ville` int(11) NOT NULL default '0',
  PRIMARY KEY  (`numero`),
  KEY `nom` (`nom`),
  KEY `id_sexe` (`id_sexe`),
  KEY `id_pcs` (`id_pcs`),
  KEY `id_fam` (`id_fam`),
  KEY `adresse1` (`adresse1`),
  KEY `commune` (`commune`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_responsables`
-- 

DROP TABLE IF EXISTS `kernel_bu_responsables`;
CREATE TABLE `kernel_bu_responsables` (
  `id_rel` int(11) NOT NULL auto_increment,
  `id_beneficiaire` int(11) NOT NULL default '0',
  `type_beneficiaire` varchar(12) NOT NULL default 'eleve',
  `id_responsable` int(11) NOT NULL default '0',
  `type` varchar(12) NOT NULL default '',
  `auth_parentale` tinyint(4) NOT NULL default '0',
  `id_par` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id_rel`),
  KEY `id_beneficiaire` (`id_beneficiaire`),
  KEY `id_responsable` (`id_responsable`),
  KEY `auth_parentale` (`auth_parentale`),
  KEY `id_par` (`id_par`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_rue`
-- 

DROP TABLE IF EXISTS `kernel_bu_rue`;
CREATE TABLE `kernel_bu_rue` (
  `id_voie` int(11) NOT NULL auto_increment,
  `type_voie` varchar(20) NOT NULL default '',
  `lien_voie` varchar(50) default NULL,
  `nom_voie` varchar(100) NOT NULL default '',
  `code_postal` varchar(10) NOT NULL default '',
  `commune` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id_voie`),
  KEY `commune` (`commune`),
  KEY `nom_voie` (`nom_voie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_scolarite`
-- 

DROP TABLE IF EXISTS `kernel_bu_scolarite`;
CREATE TABLE `kernel_bu_scolarite` (
  `idEleve` int(11) NOT NULL default '0',
  `b2i` tinyint(4) NOT NULL default '0',
  `comp_b2i` tinyint(4) NOT NULL default '0',
  `aper` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`idEleve`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_sexe`
-- 

DROP TABLE IF EXISTS `kernel_bu_sexe`;
CREATE TABLE `kernel_bu_sexe` (
  `id_s` tinyint(4) NOT NULL auto_increment,
  `sexe` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id_s`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_situation_familiale`
-- 

DROP TABLE IF EXISTS `kernel_bu_situation_familiale`;
CREATE TABLE `kernel_bu_situation_familiale` (
  `id_sf` tinyint(4) NOT NULL auto_increment,
  `situation` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id_sf`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_utilisateurs_bu`
-- 

DROP TABLE IF EXISTS `kernel_bu_utilisateurs_bu`;
CREATE TABLE `kernel_bu_utilisateurs_bu` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `nom` varchar(50) NOT NULL default '',
  `prenom` varchar(50) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `profil` int(11) NOT NULL default '0',
  `code_utilisateur` varchar(10) default NULL,
  `connexion_nb` int(11) NOT NULL default '0',
  `connexion_first` datetime NOT NULL default '0000-00-00 00:00:00',
  `connexion_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `profil` (`profil`),
  KEY `login_2` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_utilisateurs_bu_entite`
-- 

DROP TABLE IF EXISTS `kernel_bu_utilisateurs_bu_entite`;
CREATE TABLE `kernel_bu_utilisateurs_bu_entite` (
  `utilisateur` int(11) NOT NULL default '0',
  `reference` int(11) NOT NULL default '0',
  `type_ref` char(6) NOT NULL default '',
  PRIMARY KEY  (`utilisateur`,`reference`,`type_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_utilisateurs_bu_profils`
-- 

DROP TABLE IF EXISTS `kernel_bu_utilisateurs_bu_profils`;
CREATE TABLE `kernel_bu_utilisateurs_bu_profils` (
  `id` int(11) NOT NULL auto_increment,
  `profil_nom` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_bu_ville`
-- 

DROP TABLE IF EXISTS `kernel_bu_ville`;
CREATE TABLE `kernel_bu_ville` (
  `id_vi` int(11) NOT NULL auto_increment,
  `nom` varchar(150) NOT NULL default '',
  `canon` varchar(80) NOT NULL default '',
  `id_grville` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_vi`),
  UNIQUE KEY `canon` (`canon`),
  KEY `nom` (`nom`,`id_grville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_conf_uservisibility`
-- 

DROP TABLE IF EXISTS `kernel_conf_uservisibility`;
CREATE TABLE `kernel_conf_uservisibility` (
  `src` enum('USER_ELE','USER_RES','USER_ENS','USER_ADM','USER_VIL','USER_EXT') NOT NULL,
  `dst` enum('USER_ELE','USER_RES','USER_ENS','USER_ADM','USER_VIL','USER_EXT') NOT NULL,
  `visibility` enum('FULL','BU_GRVILLE','BU_VILLE','BU_ECOLE','BU_CLASSE','CLUB','NONE') NOT NULL,
  PRIMARY KEY  (`src`,`dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_ext_user`
-- 

DROP TABLE IF EXISTS `kernel_ext_user`;
CREATE TABLE `kernel_ext_user` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `prenom` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_limits_urls`
-- 

DROP TABLE IF EXISTS `kernel_limits_urls`;
CREATE TABLE `kernel_limits_urls` (
  `id` mediumint(9) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `theme` varchar(50) default NULL,
  `id_blog` int(11) default NULL,
  `ville` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_link_bu2user`
-- 

DROP TABLE IF EXISTS `kernel_link_bu2user`;
CREATE TABLE `kernel_link_bu2user` (
  `user_id` int(11) NOT NULL default '0',
  `bu_type` varchar(10) NOT NULL default '',
  `bu_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`bu_type`,`bu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_link_groupe2node`
-- 

DROP TABLE IF EXISTS `kernel_link_groupe2node`;
CREATE TABLE `kernel_link_groupe2node` (
  `groupe_id` int(11) NOT NULL default '0',
  `node_type` varchar(15) NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`groupe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_link_user2node`
-- 

DROP TABLE IF EXISTS `kernel_link_user2node`;
CREATE TABLE `kernel_link_user2node` (
  `user_type` varchar(15) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `node_type` varchar(15) NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  `droit` int(11) NOT NULL default '0',
  `debut` varchar(8) default NULL,
  `fin` varchar(8) default NULL,
  PRIMARY KEY  (`user_type`,`user_id`,`node_type`,`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_mod_available`
-- 

DROP TABLE IF EXISTS `kernel_mod_available`;
CREATE TABLE `kernel_mod_available` (
  `node` varchar(15) NOT NULL default '',
  `module` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`node`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_mod_enabled`
-- 

DROP TABLE IF EXISTS `kernel_mod_enabled`;
CREATE TABLE `kernel_mod_enabled` (
  `node_type` varchar(20) NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  `module_type` varchar(20) NOT NULL default '',
  `module_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`node_type`,`node_id`,`module_type`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_sso_challenges`
-- 

DROP TABLE IF EXISTS `kernel_sso_challenges`;
CREATE TABLE `kernel_sso_challenges` (
  `id_sso` mediumint(8) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `challenge` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_sso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `kernel_sso_users`
-- 

DROP TABLE IF EXISTS `kernel_sso_users`;
CREATE TABLE `kernel_sso_users` (
  `id_sso` mediumint(8) unsigned NOT NULL auto_increment,
  `login` varchar(50) NOT NULL,
  `cle_privee` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_sso`),
  KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_agenda_agenda`
-- 

DROP TABLE IF EXISTS `module_agenda_agenda`;
CREATE TABLE `module_agenda_agenda` (
  `id_agenda` int(11) NOT NULL auto_increment,
  `title_agenda` varchar(255) NOT NULL default '',
  `desc_agenda` varchar(255) default '',
  `type_agenda` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id_agenda`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_agenda_event`
-- 

DROP TABLE IF EXISTS `module_agenda_event`;
CREATE TABLE `module_agenda_event` (
  `id_event` int(11) NOT NULL auto_increment,
  `id_agenda` int(11) default '0',
  `title_event` varchar(100) NOT NULL default '',
  `desc_event` text,
  `place_event` varchar(100) default NULL,
  `datedeb_event` varchar(8) NOT NULL default '',
  `heuredeb_event` varchar(5) default '',
  `datefin_event` varchar(8) NOT NULL default '',
  `heurefin_event` varchar(5) default NULL,
  `alldaylong_event` int(1) NOT NULL default '0',
  `everyday_event` int(1) NOT NULL default '0',
  `everyweek_event` int(1) NOT NULL default '0',
  `everymonth_event` int(1) NOT NULL default '0',
  `everyyear_event` int(1) NOT NULL default '0',
  `endrepeatdate_event` varchar(8) default '',
  PRIMARY KEY  (`id_event`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_agenda_lecon`
-- 

DROP TABLE IF EXISTS `module_agenda_lecon`;
CREATE TABLE `module_agenda_lecon` (
  `id_lecon` int(11) NOT NULL auto_increment,
  `id_agenda` int(11) default '0',
  `desc_lecon` text,
  `date_lecon` varchar(8) default '',
  PRIMARY KEY  (`id_lecon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_album_albums`
-- 

DROP TABLE IF EXISTS `module_album_albums`;
CREATE TABLE `module_album_albums` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `prefs` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `cle` varchar(10) NOT NULL default '',
  `public` tinyint(4) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_album_dossiers`
-- 

DROP TABLE IF EXISTS `module_album_dossiers`;
CREATE TABLE `module_album_dossiers` (
  `id` int(11) NOT NULL auto_increment,
  `id_album` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL default '0',
  `nom` varchar(255) NOT NULL,
  `commentaire` text NOT NULL,
  `date` datetime NOT NULL,
  `cle` varchar(10) NOT NULL,
  `public` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_album_photos`
-- 

DROP TABLE IF EXISTS `module_album_photos`;
CREATE TABLE `module_album_photos` (
  `id` int(11) NOT NULL auto_increment,
  `id_album` int(11) NOT NULL default '0',
  `id_dossier` int(11) NOT NULL default '0',
  `nom` varchar(255) NOT NULL default '',
  `commentaire` text,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ext` varchar(5) NOT NULL default '',
  `cle` varchar(10) NOT NULL default '',
  `public` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  KEY `id_album` (`id_album`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog`
-- 

DROP TABLE IF EXISTS `module_blog`;
CREATE TABLE `module_blog` (
  `id_blog` bigint(20) NOT NULL auto_increment,
  `name_blog` varchar(100) NOT NULL default '',
  `id_ctpt` bigint(20) NOT NULL default '0',
  `logo_blog` varchar(100) default NULL,
  `url_blog` varchar(100) default NULL,
  `style_blog_file` tinyint(4) NOT NULL default '0',
  `is_public` tinyint(4) NOT NULL default '1',
  `has_comments_activated` tinyint(1) NOT NULL default '0',
  `type_moderation_comments` varchar(5) NOT NULL default 'POST',
  `default_format_articles` varchar(10) NOT NULL default 'wiki',
  PRIMARY KEY  (`id_blog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_article`
-- 

DROP TABLE IF EXISTS `module_blog_article`;
CREATE TABLE `module_blog_article` (
  `id_bact` bigint(20) NOT NULL auto_increment,
  `id_blog` bigint(20) NOT NULL default '0',
  `name_bact` varchar(100) NOT NULL default '',
  `sumary_bact` text,
  `sumary_html_bact` text,
  `content_bact` text,
  `content_html_bact` text,
  `format_bact` varchar(10) NOT NULL default 'wiki',
  `author_bact` int(11) NOT NULL default '0',
  `date_bact` varchar(8) NOT NULL default '',
  `time_bact` varchar(5) NOT NULL default '',
  `url_bact` varchar(100) NOT NULL default '',
  `sticky_bact` int(1) NOT NULL default '0',
  `is_online` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_bact`),
  KEY `is_online` (`is_online`),
  KEY `id_blog` (`id_blog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_article_blogarticlecategory`
-- 

DROP TABLE IF EXISTS `module_blog_article_blogarticlecategory`;
CREATE TABLE `module_blog_article_blogarticlecategory` (
  `id_bact` bigint(20) NOT NULL default '0',
  `id_bacg` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id_bact`,`id_bacg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_articlecategory`
-- 

DROP TABLE IF EXISTS `module_blog_articlecategory`;
CREATE TABLE `module_blog_articlecategory` (
  `id_bacg` bigint(20) NOT NULL auto_increment,
  `id_blog` bigint(20) NOT NULL default '0',
  `order_bacg` bigint(10) NOT NULL default '0',
  `name_bacg` varchar(100) NOT NULL default '',
  `url_bacg` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_bacg`),
  KEY `id_blog` (`id_blog`),
  KEY `order_bacg` (`order_bacg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_articlecomment`
-- 

DROP TABLE IF EXISTS `module_blog_articlecomment`;
CREATE TABLE `module_blog_articlecomment` (
  `id_bacc` bigint(20) NOT NULL auto_increment,
  `id_bact` bigint(20) NOT NULL default '0',
  `authorid_bacc` int(11) NOT NULL default '0',
  `authorname_bacc` varchar(50) NOT NULL default '',
  `authoremail_bacc` varchar(50) default NULL,
  `authorweb_bacc` varchar(100) default NULL,
  `authorip_bacc` varchar(15) NOT NULL default '',
  `date_bacc` varchar(8) NOT NULL default '',
  `time_bacc` varchar(8) NOT NULL default '',
  `content_bacc` text NOT NULL,
  `is_online` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id_bacc`),
  KEY `authorid_bacc` (`authorid_bacc`),
  KEY `is_online` (`is_online`),
  KEY `id_bact` (`id_bact`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_fluxrss`
-- 

DROP TABLE IF EXISTS `module_blog_fluxrss`;
CREATE TABLE `module_blog_fluxrss` (
  `id_bfrs` bigint(20) NOT NULL auto_increment,
  `id_blog` bigint(20) NOT NULL default '0',
  `name_bfrs` varchar(255) NOT NULL default '',
  `order_bfrs` bigint(10) NOT NULL default '0',
  `url_bfrs` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_bfrs`),
  KEY `id_blog` (`id_blog`),
  KEY `order_bfrs` (`order_bfrs`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_functions`
-- 

DROP TABLE IF EXISTS `module_blog_functions`;
CREATE TABLE `module_blog_functions` (
  `id_blog` bigint(20) NOT NULL default '0',
  `article_bfct` int(1) NOT NULL default '0',
  `archive_bfct` int(1) NOT NULL default '0',
  `find_bfct` int(1) NOT NULL default '0',
  `link_bfct` int(1) NOT NULL default '0',
  `rss_bfct` int(1) NOT NULL default '0',
  `photo_bfct` int(1) NOT NULL default '0',
  `option_bfct` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id_blog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_link`
-- 

DROP TABLE IF EXISTS `module_blog_link`;
CREATE TABLE `module_blog_link` (
  `id_blnk` bigint(20) NOT NULL auto_increment,
  `id_blog` bigint(20) NOT NULL default '0',
  `order_blnk` bigint(10) NOT NULL default '0',
  `name_blnk` varchar(100) NOT NULL default '',
  `url_blnk` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_blnk`),
  KEY `id_blog` (`id_blog`),
  KEY `order_blnk` (`order_blnk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_blog_page`
-- 

DROP TABLE IF EXISTS `module_blog_page`;
CREATE TABLE `module_blog_page` (
  `id_bpge` bigint(20) NOT NULL auto_increment,
  `id_blog` bigint(20) NOT NULL default '0',
  `name_bpge` varchar(100) NOT NULL default '',
  `content_bpge` text,
  `content_html_bpge` text,
  `format_bpge` varchar(10) NOT NULL default 'wiki',
  `author_bpge` int(11) NOT NULL default '0',
  `date_bpge` varchar(8) NOT NULL default '',
  `url_bpge` varchar(100) NOT NULL default '',
  `order_bpge` bigint(10) NOT NULL default '0',
  `is_online` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_bpge`),
  KEY `id_blog` (`id_blog`),
  KEY `order_bpge` (`order_bpge`),
  KEY `is_online` (`is_online`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_carnet_messages`
-- 

DROP TABLE IF EXISTS `module_carnet_messages`;
CREATE TABLE `module_carnet_messages` (
  `id` int(11) NOT NULL auto_increment,
  `topic` int(11) NOT NULL default '0',
  `eleve` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `format` varchar(10) NOT NULL,
  `auteur` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `topic` (`topic`),
  KEY `eleve` (`eleve`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_carnet_topics`
-- 

DROP TABLE IF EXISTS `module_carnet_topics`;
CREATE TABLE `module_carnet_topics` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(150) NOT NULL default '',
  `message` text NOT NULL,
  `format` varchar(10) NOT NULL,
  `createur` int(11) NOT NULL default '0',
  `classe` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `createur` (`createur`),
  KEY `classe` (`classe`),
  KEY `date_creation` (`date_creation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_carnet_topics_to`
-- 

DROP TABLE IF EXISTS `module_carnet_topics_to`;
CREATE TABLE `module_carnet_topics_to` (
  `topic` int(11) NOT NULL default '0',
  `eleve` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topic`,`eleve`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_carnet_tracking`
-- 

DROP TABLE IF EXISTS `module_carnet_tracking`;
CREATE TABLE `module_carnet_tracking` (
  `topic` int(11) NOT NULL default '0',
  `utilisateur` int(11) NOT NULL default '0',
  `eleve` int(11) NOT NULL default '0',
  `last_visite` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`topic`,`utilisateur`,`eleve`),
  KEY `last_visite` (`last_visite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_fiches_ecoles`
-- 

DROP TABLE IF EXISTS `module_fiches_ecoles`;
CREATE TABLE `module_fiches_ecoles` (
  `id` int(11) NOT NULL,
  `horaires` text,
  `zone_ville_titre` varchar(200) default NULL,
  `zone_ville_texte` text,
  `zone1_titre` varchar(200) default NULL,
  `zone1_texte` text,
  `zone2_titre` varchar(200) default NULL,
  `zone2_texte` text,
  `zone3_titre` varchar(200) default NULL,
  `zone3_texte` text,
  `zone4_titre` varchar(200) default NULL,
  `zone4_texte` text,
  `photo` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_forum_forums`
-- 

DROP TABLE IF EXISTS `module_forum_forums`;
CREATE TABLE `module_forum_forums` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL default '',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `date_creation` (`date_creation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_forum_messages`
-- 

DROP TABLE IF EXISTS `module_forum_messages`;
CREATE TABLE `module_forum_messages` (
  `id` int(11) NOT NULL auto_increment,
  `topic` int(11) NOT NULL default '0',
  `forum` int(11) NOT NULL default '0',
  `auteur` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `format` varchar(10) NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` tinyint(4) NOT NULL default '1',
  `nb_alertes` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `topic` (`topic`),
  KEY `date` (`date`),
  KEY `status` (`status`),
  KEY `nb_alertes` (`nb_alertes`),
  KEY `auteur` (`auteur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_forum_topics`
-- 

DROP TABLE IF EXISTS `module_forum_topics`;
CREATE TABLE `module_forum_topics` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(150) NOT NULL default '',
  `forum` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `createur` int(11) NOT NULL default '0',
  `nb_messages` int(11) NOT NULL default '0',
  `nb_lectures` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '1',
  `last_msg_id` int(11) default NULL,
  `last_msg_auteur` int(11) default NULL,
  `last_msg_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `forum` (`forum`),
  KEY `date_creation` (`date_creation`),
  KEY `status` (`status`),
  KEY `last_msg_date` (`last_msg_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_forum_tracking`
-- 

DROP TABLE IF EXISTS `module_forum_tracking`;
CREATE TABLE `module_forum_tracking` (
  `topic` int(11) NOT NULL default '0',
  `utilisateur` int(11) NOT NULL default '0',
  `last_visite` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`topic`,`utilisateur`),
  KEY `last_visite` (`last_visite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_groupe_groupe`
-- 

DROP TABLE IF EXISTS `module_groupe_groupe`;
CREATE TABLE `module_groupe_groupe` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `is_open` tinyint(4) NOT NULL default '0',
  `createur` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_regroupements_grvilles`
-- 

DROP TABLE IF EXISTS `module_regroupements_grvilles`;
CREATE TABLE `module_regroupements_grvilles` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_regroupements_grvilles2villes`
-- 

DROP TABLE IF EXISTS `module_regroupements_grvilles2villes`;
CREATE TABLE `module_regroupements_grvilles2villes` (
      `id_groupe` int(11) NOT NULL,
      `id_ville` int(11) NOT NULL,
      `updated_at` datetime NOT NULL,
      `updated_by` varchar(50) NOT NULL,
      PRIMARY KEY  (`id_groupe`,`id_ville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_grvilles_gr2ville`
-- 

DROP TABLE IF EXISTS `module_grvilles_gr2ville`;
CREATE TABLE `module_grvilles_gr2ville` (
  `id_groupe` int(11) NOT NULL,
  `id_ville` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY  (`id_groupe`,`id_ville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_regroupements_grecoles`
--

DROP TABLE IF EXISTS `module_regroupements_grecoles`;
CREATE TABLE module_regroupements_grecoles (
	id          int(11)      NOT NULL AUTO_INCREMENT,
	nom         varchar(255) NOT NULL,
	updated_at  datetime     NOT NULL,
	updated_by  varchar(49)  NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MYISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `module_regroupements_grecoles2ecoles`
--

DROP TABLE IF EXISTS `module_regroupements_grecoles2ecoles`;
CREATE TABLE module_regroupements_grecoles2ecoles (
	id_groupe   int(11) NOT NULL,
	id_ecole    int(11) NOT NULL,
	updated_at  datetime NOT NULL,
	updated_by  varchar(50) NOT NULL,
	PRIMARY KEY (id_groupe,id_ecole)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_liste_listes`
-- 

DROP TABLE IF EXISTS `module_liste_listes`;
CREATE TABLE `module_liste_listes` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL default '',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `date_creation` (`date_creation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_liste_messages`
-- 

DROP TABLE IF EXISTS `module_liste_messages`;
CREATE TABLE `module_liste_messages` (
  `id` int(11) NOT NULL auto_increment,
  `liste` int(11) NOT NULL default '0',
  `titre` varchar(150) NOT NULL default '',
  `message` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `auteur` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `liste` (`liste`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_logs_logs`
-- 

DROP TABLE IF EXISTS `module_logs_logs`;
CREATE TABLE `module_logs_logs` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` varchar(20) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `message` varchar(255) default NULL,
  `mod_name` varchar(30) NOT NULL default '',
  `mod_action` varchar(40) NOT NULL default '',
  `user_id` int(11) default '0',
  `user_login` varchar(30) default NULL,
  `user_ip` varchar(15) NOT NULL default '',
  `node_type` varchar(15) default NULL,
  `node_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_magicmail`
-- 

DROP TABLE IF EXISTS `module_magicmail`;
CREATE TABLE `module_magicmail` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `domain` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_malle_files`
-- 

DROP TABLE IF EXISTS `module_malle_files`;
CREATE TABLE `module_malle_files` (
  `id` int(11) NOT NULL auto_increment,
  `malle` int(11) NOT NULL default '0',
  `folder` int(11) NOT NULL default '0',
  `nom` varchar(200) NOT NULL default '',
  `fichier` varchar(200) NOT NULL default '',
  `taille` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `cle` varchar(10) NOT NULL,
  `date_upload` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `malle` (`malle`),
  KEY `folder` (`folder`),
  KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_malle_folders`
-- 

DROP TABLE IF EXISTS `module_malle_folders`;
CREATE TABLE `module_malle_folders` (
  `id` int(11) NOT NULL auto_increment,
  `malle` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `nom` varchar(200) NOT NULL default '',
  `nb_folders` int(11) NOT NULL default '0',
  `nb_files` int(11) NOT NULL default '0',
  `taille` int(11) NOT NULL default '0',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `malle` (`malle`),
  KEY `parent` (`parent`),
  KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_malle_malles`
-- 

DROP TABLE IF EXISTS `module_malle_malles`;
CREATE TABLE `module_malle_malles` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL default '',
  `date_creation` datetime NOT NULL default '0000-00-00 00:00:00',
  `cle` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `date_creation` (`date_creation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_minimail_from`
-- 

DROP TABLE IF EXISTS `module_minimail_from`;
CREATE TABLE `module_minimail_from` (
  `id` int(11) NOT NULL auto_increment,
  `from_id` int(11) NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `message` text NOT NULL,
  `format` varchar(10) NOT NULL,
  `date_send` datetime NOT NULL default '0000-00-00 00:00:00',
  `attachment1` varchar(100) default NULL,
  `attachment2` varchar(100) default NULL,
  `attachment3` varchar(100) default NULL,
  `is_deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `from_id` (`from_id`),
  KEY `date_send` (`date_send`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_minimail_to`
-- 

DROP TABLE IF EXISTS `module_minimail_to`;
CREATE TABLE `module_minimail_to` (
  `id` int(11) NOT NULL auto_increment,
  `id_message` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `date_read` datetime NOT NULL default '0000-00-00 00:00:00',
  `is_read` tinyint(4) NOT NULL default '0',
  `is_replied` tinyint(4) NOT NULL default '0',
  `is_deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_message` (`id_message`),
  KEY `to_id` (`to_id`),
  KEY `is_read` (`is_read`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_prefs_preferences`
-- 

DROP TABLE IF EXISTS `module_prefs_preferences`;
CREATE TABLE `module_prefs_preferences` (
  `user` int(11) NOT NULL default '0',
  `module` varchar(20) NOT NULL default '',
  `code` varchar(30) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`user`,`module`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_annuaires`
-- 

DROP TABLE IF EXISTS `module_ressource_annuaires`;
CREATE TABLE `module_ressource_annuaires` (
  `id` int(11) NOT NULL auto_increment,
  `date_crea` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_contenus`
-- 

DROP TABLE IF EXISTS `module_ressource_contenus`;
CREATE TABLE `module_ressource_contenus` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_domaines`
-- 

DROP TABLE IF EXISTS `module_ressource_domaines`;
CREATE TABLE `module_ressource_domaines` (
  `id` int(11) NOT NULL auto_increment,
  `id_niveau` int(11) NOT NULL default '0',
  `nom` varchar(255) NOT NULL default '',
  `parent` int(11) NOT NULL default '0',
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_fonctions`
-- 

DROP TABLE IF EXISTS `module_ressource_fonctions`;
CREATE TABLE `module_ressource_fonctions` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_licences`
-- 

DROP TABLE IF EXISTS `module_ressource_licences`;
CREATE TABLE `module_ressource_licences` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_niveaux`
-- 

DROP TABLE IF EXISTS `module_ressource_niveaux`;
CREATE TABLE `module_ressource_niveaux` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `age_min` int(11) default NULL,
  `age_max` int(11) default NULL,
  `ordre` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_res2contenu`
-- 

DROP TABLE IF EXISTS `module_ressource_res2contenu`;
CREATE TABLE `module_ressource_res2contenu` (
  `id_ressource` int(11) NOT NULL default '0',
  `id_contenu` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_ressource`,`id_contenu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_res2domaine`
-- 

DROP TABLE IF EXISTS `module_ressource_res2domaine`;
CREATE TABLE `module_ressource_res2domaine` (
  `id_ressource` int(11) NOT NULL default '0',
  `id_domaine` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_ressource`,`id_domaine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_res2fonction`
-- 

DROP TABLE IF EXISTS `module_ressource_res2fonction`;
CREATE TABLE `module_ressource_res2fonction` (
  `id_ressource` int(11) NOT NULL default '0',
  `id_fonction` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_ressource`,`id_fonction`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_ressources`
-- 

DROP TABLE IF EXISTS `module_ressource_ressources`;
CREATE TABLE `module_ressource_ressources` (
  `id` int(11) NOT NULL auto_increment,
  `id_annu` int(11) NOT NULL default '0',
  `nom` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `mots` varchar(255) NOT NULL default '',
  `auteur` varchar(255) NOT NULL default '',
  `submit_user` varchar(255) NOT NULL default '',
  `submit_date` datetime default '0000-00-00 00:00:00',
  `valid_user` int(11) default '0',
  `valid_date` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `id_annu` (`id_annu`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=977 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_ressource_tags`
-- 

DROP TABLE IF EXISTS `module_ressource_tags`;
CREATE TABLE `module_ressource_tags` (
  `annu` int(11) NOT NULL default '0',
  `res` int(11) NOT NULL default '0',
  `tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`res`,`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_stats_logs`
-- 

DROP TABLE IF EXISTS `module_stats_logs`;
CREATE TABLE `module_stats_logs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `profil` varchar(10) default NULL,
  `date` datetime NOT NULL,
  `module_type` varchar(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `objet_a` int(11) default NULL,
  `objet_b` int(11) default NULL,
  `parent_type` varchar(20) default NULL,
  `parent_id` int(11) default NULL,
  `action` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `module_type` (`module_type`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure`
-- 

DROP TABLE IF EXISTS `module_teleprocedure`;
CREATE TABLE `module_teleprocedure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_infosupp`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_infosupp`;
CREATE TABLE `module_teleprocedure_infosupp` (
  `idinfo` int(11) NOT NULL auto_increment,
  `idinter` int(11) NOT NULL default '0',
  `iduser` int(11) NOT NULL default '0',
  `dateinfo` datetime NOT NULL default '0000-00-00 00:00:00',
  `info_message` text,
  `info_commentaire` text,
  PRIMARY KEY  (`idinfo`),
  KEY `idinter` (`idinter`),
  KEY `iduser` (`iduser`),
  KEY `dateinfo` (`dateinfo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_intervention`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_intervention`;
CREATE TABLE `module_teleprocedure_intervention` (
  `idinter` int(11) NOT NULL auto_increment,
  `iduser` int(11) NOT NULL default '0',
  `dateinter` date NOT NULL default '0000-00-00',
  `idetabliss` int(11) NOT NULL default '0',
  `objet` text NOT NULL,
  `idtype` int(11) NOT NULL default '0',
  `idstatu` int(11) NOT NULL default '0',
  `datederniere` datetime NOT NULL,
  `detail` text NOT NULL,
  `responsables` text NOT NULL,
  `lecteurs` text,
  `mail_from` varchar(255) default NULL,
  `mail_to` text,
  `mail_cc` text,
  `mail_message` text,
  `infosup` text NOT NULL,
  PRIMARY KEY  (`idinter`),
  KEY `datederniere` (`datederniere`),
  KEY `idetabliss` (`idetabliss`),
  KEY `idtype` (`idtype`),
  KEY `idstatu` (`idstatu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_intervention_droit`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_intervention_droit`;
CREATE TABLE `module_teleprocedure_intervention_droit` (
  `idinter` int(11) NOT NULL,
  `user_type` varchar(15) NOT NULL,
  `user_id` int(11) NOT NULL,
  `droit` int(11) NOT NULL,
  PRIMARY KEY  (`idinter`,`user_type`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_statu`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_statu`;
CREATE TABLE `module_teleprocedure_statu` (
  `idstat` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  PRIMARY KEY  (`idstat`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_tracking`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_tracking`;
CREATE TABLE `module_teleprocedure_tracking` (
  `intervention` int(11) NOT NULL default '0',
  `utilisateur` int(11) NOT NULL default '0',
  `last_visite` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`intervention`,`utilisateur`),
  KEY `last_visite` (`last_visite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_type`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_type`;
CREATE TABLE `module_teleprocedure_type` (
  `idtype` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `is_online` tinyint(3) unsigned NOT NULL default '1',
  `teleprocedure` int(10) unsigned NOT NULL,
  `format` varchar(10) NOT NULL default 'fckeditor',
  `texte_defaut` text NOT NULL,
  `responsables` text NOT NULL,
  `lecteurs` text,
  `mail_from` varchar(255) default NULL,
  `mail_to` text,
  `mail_cc` text,
  `mail_message` text,
  PRIMARY KEY  (`idtype`),
  KEY `teleprocedure` (`teleprocedure`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_teleprocedure_type_droit`
-- 

DROP TABLE IF EXISTS `module_teleprocedure_type_droit`;
CREATE TABLE `module_teleprocedure_type_droit` (
  `idtype` int(11) NOT NULL,
  `user_type` varchar(15) NOT NULL,
  `user_id` int(11) NOT NULL,
  `droit` int(11) NOT NULL,
  PRIMARY KEY  (`idtype`,`user_type`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_visioscopia`
-- 

DROP TABLE IF EXISTS `module_visioscopia`;
CREATE TABLE `module_visioscopia` (
  `id` int(11) NOT NULL auto_increment,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_visioscopia_config`
-- 

DROP TABLE IF EXISTS `module_visioscopia_config`;
CREATE TABLE `module_visioscopia_config` (
  `id` int(11) NOT NULL auto_increment,
  `conf_id` varchar(30) NOT NULL,
  `conf_active` tinyint(4) NOT NULL default '0',
  `conf_msg` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_welcome_homes`
-- 

DROP TABLE IF EXISTS `module_welcome_homes`;
CREATE TABLE `module_welcome_homes` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_welcome_url`
-- 

DROP TABLE IF EXISTS `module_welcome_url`;
CREATE TABLE `module_welcome_url` (
  `url` varchar(255) NOT NULL default '',
  `node_type` varchar(10) NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `modulecredentials`
-- 

DROP TABLE IF EXISTS `modulecredentials`;
CREATE TABLE `modulecredentials` (
  `id_mc` int(11) NOT NULL auto_increment,
  `module_mc` varchar(255) default NULL,
  `name_mc` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_mc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `modulecredentialsgroups`
-- 

DROP TABLE IF EXISTS `modulecredentialsgroups`;
CREATE TABLE `modulecredentialsgroups` (
  `id_mcg` int(11) NOT NULL auto_increment,
  `id_mc` int(11) NOT NULL,
  `id_mcv` int(11) default NULL,
  `handler_group` varchar(255) default NULL,
  `id_group` varchar(255) default NULL,
  PRIMARY KEY  (`id_mcg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `modulecredentialsoverpass`
-- 

DROP TABLE IF EXISTS `modulecredentialsoverpass`;
CREATE TABLE `modulecredentialsoverpass` (
  `id_mco` int(11) NOT NULL auto_increment,
  `id_mc` int(11) default NULL,
  `overpass_id_mc` int(11) default NULL,
  `overpath_id_mc` int(11) default NULL,
  PRIMARY KEY  (`id_mco`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `modulecredentialsvalues`
-- 

DROP TABLE IF EXISTS `modulecredentialsvalues`;
CREATE TABLE `modulecredentialsvalues` (
  `id_mcv` int(11) NOT NULL auto_increment,
  `value_mcv` varchar(255) NOT NULL,
  `id_mc` int(11) NOT NULL,
  `level_mcv` int(11) default NULL,
  PRIMARY KEY  (`id_mcv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `version`
-- 

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `version` varchar(10) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

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

-- --------------------------------------------------------

--
-- Structure de la table `module_charte_chartes`
--

DROP TABLE IF EXISTS `module_charte_chartes`;
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

DROP TABLE IF EXISTS `module_charte_users_validation`;
CREATE TABLE `module_charte_users_validation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `charte_id` int(11) unsigned NOT NULL,
  `user_type` varchar(75) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

-- 
-- Structure de la table `module_mailext`
-- 

DROP TABLE IF EXISTS `module_mailext`;
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

-- --------------------------------------------------------

-- 
-- Structure de la table `module_admindash`
-- 

DROP TABLE IF EXISTS `module_admindash`;
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

-- --------------------------------------------------------

-- 
-- Structure de la table `module_contacts`
-- 

DROP TABLE IF EXISTS `module_contacts`;
CREATE TABLE `module_contacts` (
  `id` int(11) NOT NULL auto_increment,
  `titre` varchar(100) NOT NULL,
  `email` varchar(255) default NULL COMMENT 'Email du destinataire. Si plusieurs, separer par des virgules',
  `date_creation` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Structure de la table `module_contacts_messages`
-- 

DROP TABLE IF EXISTS `module_contacts_messages`;
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

-- --------------------------------------------------------

-- 
-- Structure de la table `module_contacts_types`
-- 

DROP TABLE IF EXISTS `module_contacts_types`;
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



