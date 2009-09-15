DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `author_cmt` varchar(50) NOT NULL default '',
  `date_cmt` varchar(8) NOT NULL default '',
  `id_cmt` varchar(255) NOT NULL default '',
  `textformat_cmt` varchar(50) NOT NULL default 'text',
  `type_cmt` varchar(255) NOT NULL default '',
  `content_cmt` text NOT NULL,
  `position_cmt` int(11) NOT NULL default '0',
  `title_cmt` varchar(255) default NULL
) TYPE=MyISAM;
