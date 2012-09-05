<?php

/**
 * Fonctions diverses du module Teleprocedures
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */
class TeleproceduresService
{
    const DROIT_RESPONSABLE = 30;
    const DROIT_LECTEUR = 20;

    public function droitName ($droit)
    {
        switch ($droit) {
            case TeleproceduresService::DROIT_RESPONSABLE : $nom = CopixI18N::get ('teleprocedures.droit.responsable'); break;
            case TeleproceduresService::DROIT_LECTEUR : $nom = CopixI18N::get ('teleprocedures.droit.lecteur'); break;
            default : $nom = $droit; break;
        }
        return $nom;
    }

    /**
     * Renvoie l'ecole sur laquelle on travaille selon la session utilisateur, uniquement pour les directeurs
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/23
     * @return integer Id de l'ecole, ou NULL si aucune (anormal)
     */
    public function getTelepEcole ()
    {
    $myNode = CopixSession::get ('myNode');
        return ($myNode && $myNode['type']=='BU_ECOLE') ? $myNode['id'] : null;
    }


    /**
     * Determine si l'usager peut afficher un type de teleprocedure (de ville ou d'ecole)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param string $action Indication de l'action a faire
     * @param integer $droit Droit de l'usager dans le module (vient de kernel*)
     * @param array $params Parametres precisant des infos : [idinter]
     * @return boolean True s'il a le droit, false sinon
     */
    public function canMakeInTelep ($action, $droit, $params=array())
    {
        $can = false;
        switch ($action) {
            case "ADD_INTERVENTION" :	// Ajouter une intervention
                $can = ($droit >= PROFILE_CCV_READ && TeleproceduresService::getTelepProfil()=='DIRECTEUR');
                break;
            case "VIEW" :	// Accueil du module
            case "ADD_COMMENT" :	// Ajout commentaire
                $can = ($droit >= PROFILE_CCV_READ);
                break;
            case "CHECK_VISIBLE" : // Ecrire des notes internes
            case "VIEW_DELAI" : // Voir delai d'action
            case "VIEW_COMBO_ECOLES" : // Filtrer par ecole (combo)
                $can = ($droit >= PROFILE_CCV_READ && TeleproceduresService::getTelepProfil()!='DIRECTEUR');
                break;
            case "DELEGUE" : // Deleguer
            case "CHANGE_STATUT" : // Changer le statut
            case "SEND_MAILS" : // Changer le statut
                $droitInTelep = TeleproceduresService::getDroit($params['idinter']);
                $can = ($droit >= PROFILE_CCV_READ && $droitInTelep >= TeleproceduresService::DROIT_RESPONSABLE);
                break;
            case "ADMIN" :	// Admin des types
            case "ADMIN_BLOG" :	// Admin du blog
                $can = ($droit >= PROFILE_CCV_ADMIN);
                break;
            case "VIEW_BLOG" :	// Vue du blog
                //$can = ($droit >= PROFILE_CCV_READ && TeleproceduresService::getTelepProfil()=='DIRECTEUR');
                $can = ($droit >= PROFILE_CCV_READ);
                break;
        }
        return $can;
    }

    /**
     * Le profil de l'usager courante dans les teleprocedures : directeur, agent de ville ou root ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/19
     * @return string Le type de profil : DIRECTEUR / AGENT_VILLE / ROOT_VILLE
     */
    public function getTelepProfil ()
    {
    $res = '';
        $session = Kernel::getSessionBU();
        switch ($session['type']) {
            case 'USER_ENS': $res = 'DIRECTEUR'; break;
            case 'USER_ADM':
        if (CopixConfig::exists('teleprocedures|USER_ADM_as_USER_ENS') && CopixConfig::get('teleprocedures|USER_ADM_as_USER_ENS'))
          $res = 'DIRECTEUR';
        break;
            case 'USER_VIL': $res = 'AGENT_VILLE'; break;
            case 'ROOT_VIL': $res = 'ROOT_VILLE'; break; // TODO
        }
        return $res;
    }

    /**
     * Enregistrement des droits des usagers dans un type. On part d'un tableau comprenant les infos des usagers, on efface les droits dans la base pour les reenregistrer
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/20
     * @param string $pTable Table concernee ("intervention" ou "type")
     * @param integer $pId Id du type ou de l'intervention
     * @param string $pField Champ concerne (responsables ou lecteurs)
     * @param array $pArComptes Comptes
     * @return null
     */
    public function saveDroits ($pTable, $pId, $pField, $pArUsers)
    {
        $droit = null;

        if ($pField == 'responsables') $droit = TeleproceduresService::DROIT_RESPONSABLE;
        if ($pField == 'lecteurs') $droit = TeleproceduresService::DROIT_LECTEUR;

        if ($pTable == 'type') {
            $daoDroit = 'teleprocedures|type_droit';
            $sqlDelete = 'DELETE FROM module_teleprocedure_type_droit WHERE idtype='.$pId.' AND droit='.$droit;
            $droitField = 'idtype';
        } elseif ($pTable == 'intervention') {
            $daoDroit = 'teleprocedures|intervention_droit';
            $sqlDelete = 'DELETE FROM module_teleprocedure_intervention_droit WHERE idinter='.$pId.' AND droit='.$droit;
            $droitField = 'idinter';
        }

        _doQuery($sqlDelete);
        $daoTypeDroit = _dao($daoDroit);
        foreach ($pArUsers as $user) {
            $newDroit = _record($daoDroit);
            $newDroit->$droitField = $pId;
            $newDroit->user_type = $user['type'];
            $newDroit->user_id = $user['id'];
            $newDroit->droit = $droit;
            $daoTypeDroit->insert ($newDroit);
            //print_r($newDroit);
        }
        //return $res;
    }

    /**
     * Pour une intervention, recupere les droits specifiques a son type et lui les applique. Appellee uniquement juste apres l'ajout d'une nouvelle intervention.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/20
     * @param object $pInter Recordset de l'intervention creee
     * @return array Tableau avec les droits
     */
    public function copyDroitFromTypeToInter ($pInter)
    {
        // Les droits sur le type, a duppliquer
        $DAOtypeDroit = & _dao ('teleprocedures|type_droit');
        $DAOinterventionDroit = & _dao ('teleprocedures|intervention_droit');
        $return = array();
        $list = $DAOtypeDroit->findForIdType ($pInter->idtype);
        foreach ($list as $droit) {
            $itvDroit = _record('teleprocedures|intervention_droit');
            $itvDroit->idinter = $pInter->idinter;
            $itvDroit->user_type = $droit->user_type;
            $itvDroit->user_id = $droit->user_id;
            $itvDroit->droit = $droit->droit;
            $DAOinterventionDroit->insert ($itvDroit);
            $return[] = $itvDroit;
        }
        return $return;
    }






    /**
     * Le droit de l'usager courant sur une intervention precise. Regarde en session, sinon va chercher dans la base et met en session
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/21
     * @param integer $idinter Id de l'intervention
     * @return integer Son droit
     */
    public function getDroit ($idinter)
    {
        $user_type = _currentUser()->getExtra('type');
        $user_id = _currentUser()->getExtra('id');

        $cache_id = $user_type.'-'.$user_id.'-'.$idinter;
        $cache_type = 'telepdroit';

        if (!CopixCache::exists ($cache_id, $cache_type)) {
            $getDroit = 0;
            $DAOinterventionDroit = _dao ('teleprocedures|intervention_droit');
            if ($droit = $DAOinterventionDroit->findForIdinterAndUser ($idinter, $user_type, $user_id)) {
                if(isset($droit->droit)) $getDroit = $droit->droit;
            }
            CopixCache::write ($cache_id, $getDroit, $cache_type);
        } else // En cache
            $getDroit = CopixCache::read ($cache_id, $cache_type);
        return $getDroit;
    }



    /**
     * Envoi de messages prives aux responsables d'une intervention
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/22
     * @param object $rIntervention Recordset de l'intervention
     * @param array $arDroits Tableau avec les droits attribues a l'intervention
     * @return null
     */
    public function alertResponsables ($rIntervention, $arDroits)
    {
        $minimailService = & CopixClassesFactory::Create ('minimail|minimailService');
        $titre = $rIntervention->objet;
        $auteur = $rIntervention->iduser;

        $ecoleInfos = Kernel::getNodeInfo('BU_ECOLE',$rIntervention->idetabliss,false);

        foreach ($arDroits as $droit) {
            if ($droit->droit == TeleproceduresService::DROIT_RESPONSABLE) {
                $userInfo = Kernel::getUserInfo ($droit->user_type, $droit->user_id);
                if ($userInfo && $userInfo["user_id"]) {
                    $dest = array($userInfo["user_id"]=>1);
                    $ecole = $ecoleInfos['nom'];
                    $url = CopixUrl::get('teleprocedures||fiche', array("id"=>$rIntervention->idinter));
                    $message = CopixI18N::get ('teleprocedures|teleprocedures.message.alert', array(1=>$ecole, 2=>$url, 3=>$url));
                    $message = str_replace("\\n", "\n", $message);
                    $minimailService->sendMinimail ($titre, $message, $auteur, $dest, 'dokuwiki');
                }
            }
        }
    }

    /**
     * Envoi de messages prives aux responsables d'une intervention lorsqu'on les change. Envoie un message aux nouveaux responsables
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/22
     * @param object $rIntervention Recordset de l'intervention
     * @param string $responsables Liste des nouveaux responsables (logins separes par virgules)
     * @return null
     */
    public function alertChangeResponsables ($rIntervention, $responsables)
    {
        //print_r($rIntervention);
        //print_r($responsables);
        $minimailService = & CopixClassesFactory::Create ('minimail|minimailService');
        $titre = $rIntervention->objet;
        $auteur = $rIntervention->iduser;

        $ecoleInfos = Kernel::getNodeInfo('BU_ECOLE',$rIntervention->idetabliss,false);

        $arOld = $rIntervention->responsables;
        $arOld = str_replace(array(" "), "", $arOld);
        $arOld = str_replace(array(",",";"), ",", $arOld);
        $arOld = explode (",", $arOld);

        $arNew = $responsables;
        $arNew = str_replace(array(" "), "", $arNew);
        $arNew = str_replace(array(",",";"), ",", $arNew);
        $arNew = explode (",", $arNew);

        //print_r($arOld);
        //print_r($arNew);

        foreach ($arNew as $login) {
            if (!in_array($login,$arOld)) {
                $userInfo = Kernel::getUserInfo ('LOGIN', $login);
                if ($userInfo && $userInfo["user_id"]) {
                    $dest = array($userInfo["user_id"]=>1);
                    $ecole = $ecoleInfos['nom'];
                    $url = CopixUrl::get('teleprocedures||fiche', array("id"=>$rIntervention->idinter));
                    $message = CopixI18N::get ('teleprocedures|teleprocedures.message.alertChangeResp', array(1=>$ecole, 2=>$url, 3=>$url));
                    $message = str_replace("\\n", "\n", $message);
                    $minimailService->sendMinimail ($titre, $message, $auteur, $dest, 'dokuwiki');
                }
            }
        }
    }

    /**
     * Verifie si le blog des teleprocedures d'une ville existe. Si non, le cree. Renvoie son id.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/23
     * @param object $rTelep Recordset de la teleprocedure
     * @return object Recordset du blog
     */
    public function checkIfBlogExists ($rTelep)
    {
        $modEnabled = Kernel::getModEnabled('MOD_TELEPROCEDURES', $rTelep->id);
        $return = null;
        $exists = false;
        foreach ($modEnabled as $mod) {
            if ($mod->module_type == 'MOD_BLOG')
                $exists = $mod->module_id;
        }
        if (!$exists) { // Il faut creer le blog
            $modname = 'blog';
            $file  = & CopixSelectorFactory::create($modname."|".$modname);
            $filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
            if (is_readable($filePath)){
                $modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
                if( method_exists( $modservice, "create" ) ) {
                    $title = $rTelep->titre.' - '.CopixI18N::get ('teleprocedures|teleprocedures.moduleDescription');
                    $modid = $modservice->create(array('title'=>$title, 'is_public'=>0));
                    if( $modid != null ) {
                        Kernel::registerModule('MOD_BLOG', $modid, 'MOD_TELEPROCEDURES', $rTelep->id);

                        // Si le blog est cree, on cree aussi la malle et l'album
                        $modname = 'album';
                        $file  = & CopixSelectorFactory::create($modname."|".$modname);
                        $filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
                        if (is_readable($filePath)){
                            $modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
                            if( method_exists( $modservice, "create" ) ) {
                                $title = $rTelep->titre.' - '.CopixI18N::get ('teleprocedures|teleprocedures.moduleDescription');
                                $mod2id = $modservice->create(array('title'=>$title));
                                if( $mod2id != null ) {
                                    Kernel::registerModule('MOD_ALBUM', $mod2id, 'MOD_TELEPROCEDURES', $rTelep->id);
                                }
                            }
                        }
                        $modname = 'malle';
                        $file  = & CopixSelectorFactory::create($modname."|".$modname);
                        $filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
                        if (is_readable($filePath)){
                            $modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
                            if( method_exists( $modservice, "create" ) ) {
                                $title = $rTelep->titre.' - '.CopixI18N::get ('teleprocedures|teleprocedures.moduleDescription');
                                $mod2id = $modservice->create(array('title'=>$title));
                                if( $mod2id != null ) {
                                    Kernel::registerModule('MOD_MALLE', $mod2id, 'MOD_TELEPROCEDURES', $rTelep->id);
                                }
                            }
                        }
                    }
                }
            }
        } else
            $modid = $exists;
        if ($modid) {
            $daoBlog = & _dao ('blog|blog');
            $return = $daoBlog->get($modid);
        }
        return $return;
    }

    /**
     * Enregistre la date de passage d'un utilisateur dans une intervention
     *
     * Cette fonction de "tracking" permet ensuite d'afficher, pour un utilisateur, les interventions dans lesquelles de nouveaux messages ont été écrits depuis sa dernière lecture, et de le renvoyer au premier message non lu.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/03/03
     * @param integer $id_intervention Id de l'intervention
     * @param integer $user Id de l'utilisateur
     */
    public function userReadIntervention ($id_intervention, $user)
    {
        $daoTracking = _dao("teleprocedures|tracking");
        $visite = $daoTracking->get($id_intervention, $user);
        //print_r($visite);
        if ($visite) {	// Il a déjà visité ce topic
            $visite->last_visite = date("Y-m-d H:i:s");
            $daoTracking->update($visite);
        } else {	// 1e visite !
            $newVisite = _record("teleprocedures|tracking");
            $newVisite->intervention = $id_intervention;
            $newVisite->utilisateur = $user;
            $newVisite->last_visite = date("Y-m-d H:i:s");
            $daoTracking->insert ($newVisite);
        }
    }

}


