ALTER TABLE  `module_classeur` CHANGE  `titre`  `titre` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE  `module_classeur_dossier` CHANGE  `nom`  `nom` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `module_classeur_fichier` CHANGE  `titre`  `titre` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
