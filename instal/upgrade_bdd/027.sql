CREATE TABLE kernel_ien (
  user_type varchar(10) NOT NULL,
  user_id int(11) NOT NULL,
  can_connect tinyint(4) NOT NULL,
  can_tableaubord tinyint(4) NOT NULL,
  can_comptes tinyint(4) NOT NULL,
  is_visibleannuaire tinyint(4) NOT NULL,
  updated_at datetime NOT NULL,
  updated_by varchar(50) NOT NULL,
  PRIMARY KEY (user_type,user_id),
  KEY is_visibleannuaire (is_visibleannuaire)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE kernel_ien2regroupements (
  user_type varchar(10) NOT NULL,
  user_id int(11) NOT NULL,
  regroupement_type enum('villes','ecoles') NOT NULL,
  regroupement_id int(11) NOT NULL,
  PRIMARY KEY (user_type,user_id,regroupement_type,regroupement_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

