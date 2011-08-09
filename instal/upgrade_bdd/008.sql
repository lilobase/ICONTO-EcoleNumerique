SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET CHARACTER SET 'utf8';

CREATE TABLE IF NOT EXISTS `kernel_conf_user` (
  `path` varchar(255) NOT NULL,
  `id_dbuser` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`path`,`id_dbuser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;