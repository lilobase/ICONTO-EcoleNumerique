<?php

class IconitoService
{
    public function getInstallationDate($display = "Human")
    {
        switch ($display) {
        case "Human":
            return date ("d/m/Y H:i:s", filemtime(COPIX_LOG_PATH.".installed"));
        case "TS":
            return filemtime(COPIX_LOG_PATH.".installed");
        }
    }

    public function getLastUpdateDate($display = "Human")
    {
        switch ($display) {
        case "Human":
            return date ("d/m/Y H:i:s", filemtime(COPIX_PROJECT_PATH."../.svn"));
        case "TS":
            return filemtime(COPIX_PROJECT_PATH."../.svn");
        }
    }

    public function getSvnRev ()
    {
        if (file_exists($file = COPIX_PROJECT_PATH."../.svn/entries")) {
            $svn = file($file);
        }
        return isset($svn[3]) ? (int) $svn[3]:'Error';
    }

    public function getBytes()
    {
        return round(disk_total_space(COPIX_PROJECT_PATH."/..") / (1024*1024*1024),2);
    }

    public function getNbObject ($object)
    {
        switch($object) {
            case "villes":
                $sql = "SELECT count(*) as nb FROM kernel_bu_ville";
                break;;
            case "agents":
                $sql = "SELECT count(DISTINCT id_per) as nb FROM kernel_bu_personnel_entite WHERE type_ref='VILLE'";
                break;;
            case "ecoles":
                $sql = "SELECT count(*) as nb FROM kernel_bu_ecole";
                break;;
            case "directeurs":
                $sql = "SELECT  count(DISTINCT id_per) as nb FROM kernel_bu_personnel_entite WHERE type_ref='ECOLE' AND role=2";
                break;;
            case "classes":
                $sql = "SELECT count(*) as nb FROM kernel_bu_ecole_classe";
                break;;
            case "enseignants":
                $sql = "SELECT count(DISTINCT id_per) as nb FROM kernel_bu_personnel_entite WHERE type_ref='CLASSE' AND role=1";
                break;;
            case "eleves":
                $sql = "SELECT count(*) as nb FROM kernel_bu_eleve";
                break;;
            case "responsables":
                $sql = "SELECT count(*) as nb FROM kernel_bu_responsable";
                break;;
            case "externes":
                $sql = "SELECT count(*) as nb FROM  kernel_ext_user";
                break;;
            case "blogs":
                $sql = "SELECT count(*) as nb FROM  module_blog";
                break;;
            case "articles":
                $sql = "SELECT count(*) as nb FROM  module_blog_article";
                break;;
            case "categories":
                $sql = "SELECT count(*) as nb FROM  module_blog_articlecategory";
                break;;
            case "commentaires":
                $sql = "SELECT count(*) as nb FROM  module_blog_articlecomment";
                break;;
            case "pages":
                $sql = "SELECT count(*) as nb FROM  module_blog_page";
                break;;
            case "malles":
                $sql = "SELECT count(*) as nb FROM  module_malle_malles";
                break;;
            case "fichiers":
                $sql = "SELECT count(*) as nb FROM  module_malle_files";
                break;;
            case "albums":
                $sql = "SELECT count(*) as nb FROM  module_album_albums";
                break;;
            case "photos":
                $sql = "SELECT count(*) as nb FROM  module_album_photos";
                break;;
            case "minimail_from":
                $sql = "SELECT count(*) as nb FROM  module_minimail_from";
                break;;
            case "minimail_to":
                $sql = "SELECT count(*) as nb FROM  module_minimail_from";
                break;;
            case "agendas":
                $sql = "SELECT count(*) as nb FROM  module_agenda_agenda";
                break;;
            case "evenements":
                $sql = "SELECT count(*) as nb FROM  module_agenda_event";
                break;;
            case "lecons":
                $sql = "SELECT count(*) as nb FROM  module_agenda_lecon";
                break;;
            case "forums":
                $sql = "SELECT count(*) as nb FROM  module_forum_forums";
                break;;
            case "topics":
                $sql = "SELECT count(*) as nb FROM  module_forum_topics";
                break;;
            case "forums_messages":
                $sql = "SELECT count(*) as nb FROM  module_forum_messages";
                break;;
            case "carnets":
                $sql = "SELECT count(*) as nb FROM  module_carnet_topics";
                break;;
            case "carnets_messages":
                $sql = "SELECT count(*) as nb FROM  module_carnet_messages";
                break;;
            case "quiz":
                $sql = "SELECT count(*) as nb FROM  module_quiz_quiz";
                break;;
            case "questions":
                $sql = "SELECT count(*) as nb FROM  module_quiz_questions";
                break;;
            case "reponses":
                $sql = "SELECT count(*) as nb FROM  module_quiz_responses";
                break;;
            case "teleprocedures_type":
                $sql = "SELECT count(*) as nb FROM  module_teleprocedure_type";
                break;;
            case "teleprocedures":
                $sql = "SELECT count(*) as nb FROM  module_teleprocedure";
                break;;
            case "groupe":
                $sql = "SELECT count(*) as nb FROM  module_groupe_groupe";
                break;;
            default:
                return -1;
        }
        $result = _doQuery($sql);
        return($result[0]->nb);
    }
}
