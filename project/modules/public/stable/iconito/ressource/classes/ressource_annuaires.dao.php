<?php



class DAORessource_Annuaires
{
    // Le nb de ressources d'un annuaire de ressources
    public function getNbRessourcesInAnnuaire ($id_annuaire)
    {
        $critere = 'SELECT COUNT(RES.id) AS nb FROM module_ressource_ressources RES WHERE RES.id_annu='.$id_annuaire.'';
        return _doQuery($critere);
    }


    // Cherche les ressources classées dans une certaine fonction
    public function getAdvancedSearch ($params, $id_annuaire)
    {
        //print_r($params);

        $where = array();

        $critere = 'SELECT DISTINCT(RESS.id), RESS.nom, RESS.description, RESS.submit_date, RESS.valid_date, RESS.url FROM module_ressource_ressources RESS';
        $where[] = 'RESS.id_annu='.$id_annuaire;

        // 0. Mot clé
        if (isset($params['mot']) && $params['mot']) {
            $testpattern=str_replace(array(" ","%20"), "%20", $params['mot']);
            $temp = split ("%20", $testpattern);
            foreach ($temp as $word) {
                if ($word != "") {
                    $critere .= '';
                    $where[] = ' RESS.nom LIKE \'%'.addslashes($word).'%\' OR RESS.url LIKE \'%'.addslashes($word).'%\' OR RESS.description LIKE \'%'.addslashes($word).'%\' OR RESS.mots LIKE \'%'.addslashes($word).'%\' OR RESS.auteur LIKE \'%'.addslashes($word).'%\'';
                }
            }
        }


        // 1. Fonctions
        if (isset($params['fonctions'])) {
            $critere .= ' LEFT JOIN module_ressource_res2fonction R2F ON (R2F.id_ressource=RESS.id)';
            $where[] = 'R2F.id_fonction='.$params['fonctions'];
        }

        // 2. Contenus
        if (isset($params['contenus'])) {
            $critere .= ' LEFT JOIN module_ressource_res2contenu R2C ON (R2C.id_ressource=RESS.id)';
            $where[] = 'R2C.id_contenu='.$params['contenus'];
        }

        // 2. Domaines
        if (isset($params['domaines'])) {
            foreach ($params['domaines'] as $k=>$domaine) {
                if (strstr($domaine,",") !== false) {
                    $domaines = substr($domaine,0,strlen($domaine)-1);
                    if ($domaines) {
                        $critere .= ' LEFT JOIN module_ressource_res2domaine R2D'.$k.' ON (R2D'.$k.'.id_ressource=RESS.id)';
                        $where[] = 'R2D'.$k.'.id_domaine IN ('.$domaines.')';
                    }
                } else {
                    $critere .= ' LEFT JOIN module_ressource_res2domaine R2D'.$k.' ON (R2D'.$k.'.id_ressource=RESS.id)';
                    $where[] = 'R2D'.$k.'.id_domaine='.$domaine;
                }
            }
        }

        $critere .= ' WHERE 1';
        foreach($where as $key => $value)
            $critere .= ' AND ('.$value.')';
        $critere .= ' ORDER BY nom ASC, id ASC';
        //print_r($critere);
        //print_r($where);

        return _doQuery ($critere);
    }

    // Cherche les ressources classées dans un certaine contenu
    public function getSearchRessourcesInContenu ($id_contenu, $id_annuaire)
    {
        $critere = 'SELECT RESS.id, RESS.nom, RESS.description FROM module_ressource_ressources RESS, module_ressource_res2contenu R2C WHERE R2C.id_ressource=RESS.id AND R2C.id_contenu='.$id_contenu.' AND RESS.id_annu='.$id_annuaire.' ORDER BY nom ASC, id ASC';
        return _doQuery ($critere);
    }


}




