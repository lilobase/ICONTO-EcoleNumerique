-- 

ALTER TABLE `kernel_bu_eleve`
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `archived`  `archived` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI',
CHANGE  `deleted`  `deleted` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI';

ALTER TABLE `kernel_bu_personnel`
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `archived`  `archived` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI',
CHANGE  `deleted`  `deleted` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI';

ALTER TABLE `kernel_bu_responsable`
CHANGE  `pays`  `pays` INT( 11 ) NULL DEFAULT  '1',
CHANGE  `diffusionAdresse`  `diffusionAdresse` TINYINT( 4 ) NULL DEFAULT  '1' COMMENT  '0 = NON, 1 = OUI',
CHANGE  `updated_at`  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL ,
CHANGE  `archived`  `archived` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI',
CHANGE  `deleted`  `deleted` TINYINT( 4 ) NULL DEFAULT  '0' COMMENT  '0 = NON, 1 = OUI';

ALTER TABLE  `kernel_bu_eleve` CHANGE  `updated_at`  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;


ALTER TABLE  `kernel_bu_personnel` CHANGE  `updated_at`  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE  `kernel_bu_responsable` CHANGE  `updated_at`  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

ALTER TABLE  `kernel_bu_responsables` CHANGE  `redevable`  `redevable` TINYINT( 4 ) NULL DEFAULT  '0',
CHANGE  `responsable`  `responsable` TINYINT( 4 ) NULL DEFAULT  '0',
CHANGE  `domicile`  `domicile` INT( 11 ) NULL DEFAULT  '0',
CHANGE  `updated_at`  `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE  `updated_by`  `updated_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `created_at`  `created_at` TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00',
CHANGE  `created_by`  `created_by` VARCHAR( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
