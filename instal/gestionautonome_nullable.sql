-- 

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
