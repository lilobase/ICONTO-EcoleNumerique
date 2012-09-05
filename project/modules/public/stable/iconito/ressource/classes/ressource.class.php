<?php

class Ressource
{
    public function checkRight( $type, $id, $mini )
    {
        switch( $type ) {
            case 'ANNU':
                $annuaire_dao = CopixDAOFactory::create("ressource_annuaires");
                $annuaire = $annuaire_dao->get($id);
                if( !$annuaire ) return false;
                $id_annu = $id;
                break;
            case 'RES':
                $ressource_dao = CopixDAOFactory::create("ressource_ressources");
                $ressource = $ressource_dao->get($id);
                if( !$ressource ) return false;
                $id_annu = $ressource->ressources_id_annu;
                break;
            default:
                return false;
        }

        // Test : if( ! Ressource::checkRight( "RES", $id, PROFILE_CCV_SHOW ) )

        if( Kernel::getLevel( "MOD_RESSOURCE", $id_annu ) >= $mini ) return true;
        return false;
    }


    public function searchRessources( $params, $annu=-1 )
    {
        // Découpage du pattern
        $testpattern=str_replace(array(" ","%20"), "%20", $params["text"]);
        $temp = split ("%20", $testpattern);

        // Conditions de recherche
        $conditions = _daoSp ();

        // Texte
        $conditions->startGroup("AND");
        foreach ($temp as $word) {
            if ($word != "") {
                $conditions->startGroup("OR");
                $conditions->addCondition("ressources_nom", " like ", "%".$word."%");
                $conditions->addCondition("ressources_url", " like ", "%".$word."%");
                $conditions->addCondition("ressources_description", " like ", "%".$word."%");
                $conditions->addCondition("ressources_mots", " like ", "%".$word."%");
                $conditions->addCondition("ressources_auteur", " like ", "%".$word."%");
                $conditions->endGroup();
            }
        }
        $conditions->endGroup();

        // Conditions de base
        /*
        $conditions->addCondition("ressources_id_annu", "=", $annu);
        */

        // Récupération de la liste des ressources
        $ressources_dao = CopixDAOFactory::create("ressource_ressources");
        return $ressources_dao->findBy($conditions);
    }

    public function searchAdvancedRessources( $params, $annu=-1 )
    {
        $ressources_dao = CopixDAOFactory::create("ressource_annuaires");
        $list = $ressources_dao->getAdvancedSearch($params, $annu);
        //print_r($list);
        return $list;
    }

    public function motcles2tags( $motcle )
    {
        $tags = array();
        // $motcle = strtolower( $motcle );
        $mots_list = preg_split("/[\s,;:\/\-]+/", $motcle);

        foreach ($mots_list as $key=>$mot) {
            if( trim($mot) == '' ) continue;
            // $tags_old[$key]['name'] = $mot;
            // $tags_old[$key]['nb'] = count(Ressource::tag2ressources( $mot ));

            $mot_lower = strtolower($mot);
            $tags[$mot_lower]['name'] = $mot;
            $tags[$mot_lower]['nb'] = count(Ressource::tag2ressources( $mot ));
            // $tags[$mot]['res'] = Ressource::tag2ressources( $mot );
        }

        return( $tags );
    }

    public function tag2ressources( $tag, $annu=0 )
    {
        $tags_dao = CopixDAOFactory::create("ressource_tags");
        if( $annu > 0 )
            $ressources_list = $tags_dao->getTagAnnu($tag, $annu);
        else
            $ressources_list = $tags_dao->getTag($tag);

        return $ressources_list;
    }

    public function alltags( $annu=0 )
    {
        $conditions = _daoSp ();

        if( $annu > 0 ) $conditions->addCondition("ressources_id_annu", "=", "$annu");
        $ressources_dao = CopixDAOFactory::create("ressource_ressources");
        $ressources_list = $ressources_dao->findBy($conditions);

        $tags_all = array();
        foreach( $ressources_list AS $res_key=>$res_val ) {
            $tags = Ressource::motcles2tags( $res_val->ressources_mots );
            $tags_all = array_merge( $tags_all, $tags );
        }

        return( $tags_all );
    }

    public function othertags( $tag, $annu=0 ) { // Todo: annu
        $ressources_list = Ressource::tag2ressources( $tag, $annu );

        $tags_all = array();
        foreach( $ressources_list AS $res_key=>$res_val ) {
            $tags = Ressource::motcles2tags( $res_val->ressources_mots );
            $tags_all = array_merge( $tags_all, $tags );
        }

        return( $tags_all );
    }

    public function similarTags( $tag, $annu=0 )
    {
        $tags_dao = CopixDAOFactory::create("ressource_tags");
        $tags = $tags_dao->getSimilarTags($tag, $annu);
        return $tags;
    }

    public function savetags( $res_id )
    {
        $ressource_dao = CopixDAOFactory::create("ressource_ressources");
        $ressource = $ressource_dao->get($res_id);

        $tags_dao = CopixDAOFactory::create("ressource_tags");
        $tags_dao->delRessource($res_id);

        $tags = Ressource::motcles2tags( $ressource->ressources_mots );
        $restag = CopixDAOFactory::createRecord("ressource_ressources");
        foreach( $tags AS $tag_key=>$tag_val ) {
            $restag->annu = $ressource->ressources_id_annu;
            $restag->res  = $res_id;
            $restag->tag  = $tag_key;
            @ $tags_dao->insert( $restag );
        }
    }



}

