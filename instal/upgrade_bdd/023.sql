ALTER TABLE `module_cahierdetextes_travail`  ADD `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `module_cahierdetextes_memo`     ADD `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `module_cahierdetextes_travail` ADD `a_rendre` TINYINT NOT NULL DEFAULT '0' AFTER `description`,
ADD `module_classeur_dossier_id` INT( 11 ) NULL DEFAULT NULL AFTER `a_rendre`;
ALTER TABLE `module_cahierdetextes_travail2eleve` ADD `rendu_le` DATETIME NULL AFTER `kernel_bu_eleve_idEleve`;
