 RENAME TABLE module_grvilles          TO module_regroupements_grvilles;
 RENAME TABLE module_grvilles_gr2ville TO module_regroupements_grvilles2villes;
 
CREATE TABLE module_regroupements_grecoles (
	id          int(11)      NOT NULL AUTO_INCREMENT,
	nom         varchar(255) NOT NULL,
	updated_at  datetime     NOT NULL,
	updated_by  varchar(50)  NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MYISAM DEFAULT CHARSET=latin1;

CREATE TABLE module_regroupements_grecoles2ecoles (
	id_groupe   int(11) NOT NULL,
	id_ecole    int(11) NOT NULL,
	updated_at  datetime NOT NULL,
	updated_by  varchar(50) NOT NULL,
	PRIMARY KEY (id_groupe,id_ecole)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
