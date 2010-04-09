CREATE TABLE IF NOT EXISTS `module_regroupements_grvilles2villes` (
      `id_groupe` int(11) NOT NULL,
      `id_ville` int(11) NOT NULL,
      `updated_at` datetime NOT NULL,
      `updated_by` varchar(50) NOT NULL,
      PRIMARY KEY  (`id_groupe`,`id_ville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
