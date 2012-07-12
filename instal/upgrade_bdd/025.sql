CREATE TABLE kernel_notifications_lastvisit (
	id int(11) NOT NULL AUTO_INCREMENT,
	user_id int(11) NOT NULL,
	`date` datetime NOT NULL,
	node_type varchar(20) DEFAULT NULL,
	node_id int(11) DEFAULT NULL,
	module_type varchar(20) DEFAULT NULL,
	module_id varchar(20) DEFAULT NULL,
	last_check datetime DEFAULT NULL,
	last_number int(11) DEFAULT NULL,
	last_message varchar(255) DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE module_logs_logs
	ADD module_type VARCHAR(20) NULL,
	ADD module_id   VARCHAR(20) NULL;
	
ALTER TABLE `module_cahierdetextes_travail`  ADD `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `module_cahierdetextes_memo`     ADD `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
