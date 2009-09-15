DROP TABLE IF EXISTS `blogfluxrss`;
CREATE TABLE `blogfluxrss` (
`id_bfrs` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_blog` BIGINT( 20 ) NOT NULL ,
`order_bfrs` BIGINT( 10 ) NOT NULL DEFAULT 0,
`url_bfrs` VARCHAR( 255 ) NOT NULL,
PRIMARY KEY ( `id_bfrs` )
);