<?php

class DAORessource_Tags
{
    // Liste des tags joints à un tag donné, avec le nombre de ressources attachées.
    public function getSimilarTags ($tag, $annu=0)
    {
        $tag = addslashes( $tag );

        $critere = '';
        $critere.= 'SELECT * FROM module_ressource_tags WHERE tag=\''.$tag.'\'';
        $ressources_list = _doQuery ($critere);
        $ressources_keys = array();
        foreach( $ressources_list as $ressources_key => $ressources_value ) {
            $ressources_keys[] = $ressources_value->res;
        }

        $critere = '';
        $critere.= 'SELECT tag,count(*) AS nb FROM module_ressource_tags WHERE tag<> \''.$tag.'\' AND res IN ('.implode($ressources_keys,',').') GROUP BY tag ORDER BY nb DESC,tag';
        $tags_list = _doQuery ($critere);
        $tags_keys = array();
        foreach( $tags_list as $tags_key => $tags_value ) {
            // echo '<li>'.$tags_value->tag.' - '.$tags_value->nb.'</li>';
            unset($tmp);
            $tmp->tag = $tags_value->tag;
            $tmp->nb  = $tags_value->nb;
            $tags_keys[] = $tmp;
        }

        return( $tags_keys );

    }

    public function getTagsForRessource ($res, $annu=0)
    {
        $res = addslashes( $res );
        $critere = 'SELECT t_count.tag, count(*) AS nb';
        $critere.= ' FROM module_ressource_tags AS t_res';
        $critere.= ' LEFT JOIN module_ressource_tags AS t_count';
        $critere.= '  ON t_res.tag=t_count.tag';
        $critere.= ' WHERE t_res.res=\''.$res.'\'';

        if( $annu > 0 ) $critere.= ' AND t_res.annu='.$annu;
        if( $annu > 0 ) $critere.= ' AND t_count.annu='.$annu;

        $critere.= ' GROUP BY t_count.tag';
        $critere.= ' ORDER BY nb DESC, t_count.tag;';
        return _doQuery ($critere);
    }


    // Cherche les ressources classées dans une certaine fonction
    public function getAdvancedSearch ($params, $id_annuaire)
    {
        //print_r($params);

        $where = array();

        $critere = 'SELECT DISTINCT(RESS.id), RESS.nom, RESS.description, RESS.submit_date, RESS.valid_date, RESS.url FROM module_ressource_ressources RESS';
        $where[] = 'RESS.id_annu='.$id_annuaire;

        // 0. Mot clé
        if ($params['mot']) {
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
        if ($params['fonctions']) {
            $critere .= ' LEFT JOIN module_ressource_res2fonction R2F ON (R2F.id_ressource=RESS.id)';
            $where[] = 'R2F.id_fonction='.$params['fonctions'];
        }

        // 2. Contenus
        if ($params['contenus']) {
            $critere .= ' LEFT JOIN module_ressource_res2contenu R2C ON (R2C.id_ressource=RESS.id)';
            $where[] = 'R2C.id_contenu='.$params['contenus'];
        }

        // 2. Domaines
        if ($params['domaines']) {
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

class TagResult
{
    public $tag = '';
    public $nb  = 0;

    public function __construct( $local_tag, $local_nb )
    {
        $tag = $local_tag;
        $nb  = $local_nb;
    }
}
