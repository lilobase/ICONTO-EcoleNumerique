SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET CHARACTER SET 'utf8';

-- --------------------------------------------------------

--
-- Structure de la table `kernel_i18n_vocabulary_catalog`
--

CREATE TABLE IF NOT EXISTS `kernel_i18n_vocabulary_catalog` (
  `id_vc` int(11) NOT NULL auto_increment,
  `name_vc` varchar(64) default NULL,
  PRIMARY KEY  (`id_vc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `kernel_i18n_vocabulary_word`
--

CREATE TABLE IF NOT EXISTS `kernel_i18n_vocabulary_word` (
  `id_word` int(11) NOT NULL auto_increment,
  `vocabulary_catalog_id` int(11) NOT NULL,
  `key_word` varchar(255) NOT NULL,
  `value_word` varchar(255) NOT NULL,
  `definite_word` varchar(255) default NULL,
  `indefinite_word` varchar(255) default NULL,
  `lang_word` varchar(255) NOT NULL,
  PRIMARY KEY  (`id_word`),
  KEY `fk_vocabulary_catalog` (`vocabulary_catalog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Structure de la table `kernel_i18n_node_vocabularycatalog`
--

CREATE TABLE IF NOT EXISTS `kernel_i18n_node_vocabularycatalog` (
  `node_type` varchar(15) NOT NULL,
  `node_id` int(11) NOT NULL,
  `vocabulary_catalog_id` int(11) NOT NULL,
  PRIMARY KEY  (`node_type`,`node_id`,`vocabulary_catalog_id`),
  KEY `fk_vocabulary_catalog` (`vocabulary_catalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `kernel_i18n_vocabulary_catalog` (`id_vc`, `name_vc`) VALUES
(1, 'academic'),
(2, 'entertainment');

INSERT INTO `kernel_i18n_vocabulary_word` (`vocabulary_catalog_id`, `key_word`, `value_word`, `definite_word`, `indefinite_word`, `lang_word`) VALUES
(1, 'structure_element_staff_person', 'enseignant', 'l''enseignant', 'un enseignant', 'fr'),
(1, 'user_ens', 'enseignant', 'l''enseignant', 'un enseignant', 'fr'),
(1, 'user_ens2', 'enseignante', 'l''enseignante', 'une enseignante', 'fr'),
(1, 'user_adm', 'équipe administrative', 'l''équipe administrative', 'une équipe administrative', 'fr'),
(1, 'user_adm2', 'équipe administrative', 'l''équipe administrative', 'une équipe administrative', 'fr'),
(1, 'user_ele', 'élève', 'l''élève', 'un élève', 'fr'),
(1, 'user_ele2', 'élève', 'l''élève', 'une élève', 'fr'),
(1, 'user_res', 'responsable', 'le responsable', 'un responsable', 'fr'),
(1, 'user_res2', 'responsable', 'la responsable', 'une responsable', 'fr'),
(1, 'user_vil', 'agent de ville', 'l''agent de ville', 'un agent de ville', 'fr'),
(1, 'user_vil2', 'agent de ville', 'l''agent de ville', 'une agent de ville', 'fr'),
(1, 'user_ext', 'intervenant extérieur', 'l''intervenant extérieur', 'un intervenant extérieur', 'fr'),
(1, 'user_ext2', 'intervenante extérieur', 'l''intervenante extérieur', 'une intervenante extérieur', 'fr'),
(2, 'user_ens2', 'responsable d''accueil de loisirs', 'la responsable d''accueil de loisirs', 'une responsable d''accueil de loisirs', 'fr'),
(2, 'user_ens', 'responsable d''accueil de loisirs', 'le responsable d''accueil de loisirs', 'un responsable d''accueil de loisirs', 'fr'),
(1, 'structure', 'école', 'l''école', 'une école', 'fr'),
(2, 'structure', 'accueil de loisirs', 'l''accueil de loisirs', 'un accueil de loisirs', 'fr'),
(1, 'structures', 'écoles', 'les écoles', 'des écoles', 'fr'),
(2, 'structures', 'accueils de loisirs', 'les accueils de loisirs', 'des accueils de loisirs', 'fr'),
(1, 'user_dir', 'directeur', 'les directeurs', 'le directeur', 'fr'),
(1, 'structure_element', 'classe', 'la classe', 'une classe', 'fr'),
(1, 'city', 'ville', 'la ville', 'une ville', 'fr'),
(1, 'citygroup', 'groupe de ville', 'le groupe de ville', 'un groupe de ville', 'fr'),
(1, 'structure_element_Responsables', 'Parents', 'Les parents', 'Des parents', 'fr'),
(1, 'structure_element_person', 'élève', 'l''élève', 'un élève', 'fr'),
(1, 'structure_element_staff_Persons', 'Enseignants', 'Les enseignants', 'Des enseignants', 'fr'),
(1, 'structure_element_Persons', 'Elèves', 'Les élèves', 'Des élèves', 'fr'),
(1, 'another_structure_element', 'autre classe', 'l''autre classe', 'une autre classe', 'fr'),
(1, 'structure_element_administration_staff', 'personnel administratif', 'le personnel administratif', 'un personnel administratif', 'fr'),
(1, 'structure_element_responsable', 'parent', 'le parent', 'un parent', 'fr'),
(1, 'structure_element_persons', 'élèves', 'les élèves', 'des élèves', 'fr'),
(1, 'structure_element_staff_persons', 'enseignants', 'les enseignants', 'des enseignants', 'fr'),
(1, 'structure_element_responsables', 'parents', 'les parents', 'des parents', 'fr'),
(1, 'Structure_element', 'Classe', 'La classe', 'Une classe', 'fr'),
(1, 'Structure', 'Ecole', 'L''école', 'Une école', 'fr');
