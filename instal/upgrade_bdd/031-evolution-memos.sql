-- On ajoute le champ permettant de savoir de qui provient un mémo du cahier de texte (par défaut à Enseignant)
ALTER TABLE module_cahierdetextes_memo
ADD created_by_role INTEGER(11) NOT NULL DEFAULT 1;

-- On supprime la valeur par défaut
ALTER TABLE module_cahierdetextes_memo
CHANGE created_by_role created_by_role INTEGER(11) NOT NULL;
