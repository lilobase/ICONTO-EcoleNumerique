<?php

class StatsBlog
{
    /*
        Renvoie le nom d'un objet dont l'ID a été enregistré dans les stats/logs
    */
    public function getObjet ($action, $id_objet)
    {
        if (!$id_objet)
            return;
        switch ($action) {
            case "showArticle" :
                $sql = 'SELECT id_bact AS id, name_bact AS name FROM module_blog_article WHERE id_bact='.$id_objet;
                break;
            case "showPage" :
                $sql = 'SELECT id_bpge AS id, name_bpge AS name FROM module_blog_page WHERE id_bpge='.$id_objet;
                break;
        }
        if ($sql) {
            $first = _doQuery($sql);
            if(count($first)) return $first[0];
            else              return false;
        }
    }



}

