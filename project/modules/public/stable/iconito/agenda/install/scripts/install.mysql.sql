DROP TABLE IF EXISTS `module_agenda_agenda`;
CREATE TABLE `module_agenda_agenda` (
  `id_agenda` int(11) NOT NULL auto_increment,
  `title_agenda` varchar(255) NOT NULL default '',
  `desc_agenda` varchar(255) NULL default '',
  `type_agenda` int(2) NOT NULL default '',
  PRIMARY KEY  (id_agenda)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `module_agenda_event`;
CREATE TABLE `module_agenda_event` (
  `id_event` int(11) NOT NULL auto_increment,
  `id_agenda` int(11) NULL default '',
  `title_event` varchar(100) NOT NULL default '',
  `desc_event` text NULL default '',
  `place_event` varchar(100),
  `datedeb_event` varchar(8) NOT NULL default '',
  `heuredeb_event` varchar(5) NULL default '',
  `datefin_event` varchar(8) NOT NULL default '',
  `heurefin_event` varchar(5),
  `alldaylong_event` int(1) NOT NULL default '',
  `everyday_event` int(1) NOT NULL default '',
  `everyweek_event` int(1) NOT NULL default '',
  `everymonth_event` int(1) NOT NULL default '',
  `everyyear_event` int(1) NOT NULL default '',
  `endrepeatdate_event` varchar(8) NULL default '',
  PRIMARY KEY  (id_event)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `module_agenda_lecon`;
CREATE TABLE `module_agenda_lecon` (
  `id_lecon` int(11) NOT NULL auto_increment,
  `id_agenda` int(11) NULL default '',
  `desc_lecon` text NULL default '',
  `date_lecon` varchar(8) NULL default'',  
  PRIMARY KEY  (id_lecon)
) TYPE=MyISAM;
