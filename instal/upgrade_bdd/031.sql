-- On ajoute le champ permettant de savoir de qui provient un mémo du cahier de texte
ALTER TABLE module_cahierdetextes_memo
ADD created_by VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'USER_ENS';

-- On supprime la valeur par défaut
ALTER TABLE module_cahierdetextes_memo
CHANGE created_by created_by VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
