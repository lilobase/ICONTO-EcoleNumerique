
-- SIRET --
ALTER TABLE `kernel_bu_ecole` ADD `siret` VARCHAR( 255 ) NULL AFTER `RNE` ;

--
-- Structure de la table `module_account`
--

CREATE TABLE `module_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_account` int(11) unsigned NOT NULL,
  `id_school` int(11) unsigned NOT NULL,
  `id_director` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_account` (`id_account`,`id_school`,`id_director`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `module_account_class`
--

CREATE TABLE `module_account_class` (
  `id_account` int(11) unsigned NOT NULL,
  `id_class_SUB` int(11) unsigned NOT NULL,
  `id_class_EN` int(11) unsigned NOT NULL,
  `creation_date` date NOT NULL,
  `validity_date` date NOT NULL,
  KEY `id_account` (`id_account`,`id_class_SUB`,`id_class_EN`)
);