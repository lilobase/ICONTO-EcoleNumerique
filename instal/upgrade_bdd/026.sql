DELETE FROM module_admindash WHERE content IS NULL AND picture IS NULL;

ALTER TABLE  `module_admindash` ADD  `social_stream` VARCHAR( 255 ) NULL;
ALTER TABLE `module_admindash` DROP `id`;
ALTER TABLE module_admindash DROP INDEX id_zone;
ALTER TABLE module_admindash DROP INDEX type_zone;
ALTER TABLE  `module_admindash` ADD PRIMARY KEY (  `id_zone` ,  `type_zone` ) ;
