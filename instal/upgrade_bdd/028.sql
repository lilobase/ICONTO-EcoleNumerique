ALTER TABLE kernel_link_bu2user ADD INDEX bu ( bu_type, bu_id );
ALTER TABLE kernel_link_bu2user ADD INDEX copix ( user_id );
ALTER TABLE kernel_link_bu2user ADD INDEX bu_id ( bu_id );
ALTER TABLE kernel_bu_ecole_classe_niveau ADD INDEX ( classe );
ALTER TABLE kernel_bu_personnel_entite ADD INDEX ( id_per );
