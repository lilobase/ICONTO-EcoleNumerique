<?php
/**
 * Assistance - Classes
 *
 * @package	Iconito
 * @subpackage  Assistance
 * @version     $Id: assistance.class.php,v 1.1 2009-09-30 10:06:20 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */


class Assistance
{
    public function getAssistanceUsers()
    {
        $animateur_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
        $animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");

        $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        $ien2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_ien2regroupements");

        $grvilles_gr2ville_dao = & CopixDAOFactory::create("regroupements|grvilles_gr2ville");
        $grecoles_gr2ecole_dao = & CopixDAOFactory::create("regroupements|grecoles_gr2ecole");
        $prefs_dao = & CopixDAOFactory::create("prefs|prefs");

        $ecoles_dao = & CopixDAOFactory::create("kernel|kernel_tree_eco");
        $personnels_dao = & CopixDAOFactory::create("kernel|kernel_bu_personnel");

        $animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        $ien = $ien_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        // echo "<pre>"; print_r($animateur); die("</pre>");


        $regroupements_list = $animateurs2regroupements_dao->findByUser(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        $regroupements_list_ien = $ien2regroupements_dao->findByUser(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        // echo "<pre>"; print_r($regroupements_list_ien); die("</pre>");

        $users_animateur=array();
        $users_ien=array();

        // Pour chaque regroupement - ANIMATEURS
        foreach($regroupements_list AS $regroupement_item) {

            // Si c'est un groupe de villes...
            if($regroupement_item->regroupement_type=='villes') {
                // Pour toutes les villes du grvilles
                $villes = $grvilles_gr2ville_dao->findByGroupe($regroupement_item->regroupement_id);
                foreach( $villes AS $ville ) {

                    // Si on n'a jamais traité la ville (qui peut être dans plusieurs regroupements)
                    if(!isset($users_animateur[$ville->id_ville])) {
                        $users_animateur[$ville->id_ville] = array();

                        // On cherche les ecoles de la ville (format DAO)
                        $ecoles = $ecoles_dao->getByVille($ville->id_ville);
                        // On traite la sortie du DAO pour avoir un array propre
                        foreach( $ecoles AS $ecole ) {
                            $users_animateur[$ville->id_ville][$ecole->eco_numero] = $ecole;

                            $tmp_personnels = $personnels_dao->getPersonnelInEcole($ecole->eco_numero);
                            $users_animateur[$ville->id_ville][$ecole->eco_numero]->personnels = array();
                            foreach( $tmp_personnels AS $tmp_personnel ) {
                                $users_animateur[$ville->id_ville][$ecole->eco_numero]->personnels[$tmp_personnel->id_copix] = $tmp_personnel;
                            }
                        }

                        // echo "<pre>"; print_r($users_animateur[$ville->id_ville]); echo("</pre>");
                    }
                }
            }

            // Si c'est un groupe d'ecoles...
            if($regroupement_item->regroupement_type=='ecoles') {
                $ecoles = $grecoles_gr2ecole_dao->findByGroupe($regroupement_item->regroupement_id);
                // echo "<pre>"; print_r($ecoles); echo("</pre>");

                foreach( $ecoles AS $ecole ) {
                    $ecole_info = $ecoles_dao->get($ecole->id_ecole);
                    $tmp_personnels = $personnels_dao->getPersonnelInEcole($ecole->id_ecole);
                    $ecole_info->personnels = array();
                    foreach( $tmp_personnels AS $tmp_personnel ) {
                        $ecole_info->personnels[$tmp_personnel->id_copix] = $tmp_personnel;
                    }

                    // echo "<pre>"; print_r($ecole_info); echo("</pre>");

                    if(!isset($users_animateur[$ecole_info->vil_id_vi])) $users_animateur[$ecole_info->vil_id_vi] = array();
                    $users_animateur[$ecole_info->vil_id_vi][$ecole_info->eco_numero] = $ecole_info;
                    // echo "<pre>"; print_r($users_animateur); die("</pre>");

                }
            }
        }

        // Pour chaque regroupement - IEN
        foreach($regroupements_list_ien AS $regroupement_item) {

            // Si c'est un groupe de villes...
            if($regroupement_item->regroupement_type=='villes') {
                // Pour toutes les villes du grvilles
                $villes = $grvilles_gr2ville_dao->findByGroupe($regroupement_item->regroupement_id);
                foreach( $villes AS $ville ) {

                    // Si on n'a jamais traité la ville (qui peut être dans plusieurs regroupements)
                    if(!isset($users_ien[$ville->id_ville])) {
                        $users_ien[$ville->id_ville] = array();

                        // On cherche les ecoles de la ville (format DAO)
                        $ecoles = $ecoles_dao->getByVille($ville->id_ville);
                        // On traite la sortie du DAO pour avoir un array propre
                        foreach( $ecoles AS $ecole ) {
                            $users_ien[$ville->id_ville][$ecole->eco_numero] = $ecole;

                            $tmp_personnels = $personnels_dao->getPersonnelInEcole($ecole->eco_numero);
                            $users_ien[$ville->id_ville][$ecole->eco_numero]->personnels = array();
                            foreach( $tmp_personnels AS $tmp_personnel ) {
                                $users_ien[$ville->id_ville][$ecole->eco_numero]->personnels[$tmp_personnel->id_copix] = $tmp_personnel;
                            }
                        }

                        // echo "<pre>"; print_r($users_ien[$ville->id_ville]); echo("</pre>");
                    }
                }
            }

            // Si c'est un groupe d'ecoles...
            if($regroupement_item->regroupement_type=='ecoles') {
                $ecoles = $grecoles_gr2ecole_dao->findByGroupe($regroupement_item->regroupement_id);
                // echo "<pre>"; print_r($ecoles); echo("</pre>");

                foreach( $ecoles AS $ecole ) {
                    $ecole_info = $ecoles_dao->get($ecole->id_ecole);
                    $tmp_personnels = $personnels_dao->getPersonnelInEcole($ecole->id_ecole);
                    $ecole_info->personnels = array();
                    foreach( $tmp_personnels AS $tmp_personnel ) {
                        $ecole_info->personnels[$tmp_personnel->id_copix] = $tmp_personnel;
                    }

                    // echo "<pre>"; print_r($ecole_info); echo("</pre>");

                    if(!isset($users_ien[$ecole_info->vil_id_vi])) $users_ien[$ecole_info->vil_id_vi] = array();
                    $users_ien[$ecole_info->vil_id_vi][$ecole_info->eco_numero] = $ecole_info;
                    // echo "<pre>"; print_r($users_ien); die("</pre>");

                }
            }
        }
        // echo "<pre>"; print_r($users_ien); die("</pre>");

        $default_assistance = CopixConfig::exists('|conf_assistance_default')?CopixConfig::get('|conf_assistance_default'):0;
        $default_assistance_ien = CopixConfig::exists('|conf_assistance_ien_default')?CopixConfig::get('|conf_assistance_ien_default'):0;

        foreach($users_animateur AS $ville_id => $ville) foreach($ville AS $ecole_id => $ecole) foreach($ecole->personnels AS $personnel_id => $personnel) {
            $assistance = $prefs_dao->get( $personnel->id_copix, 'prefs', 'assistance' );
            // $assistance_ien = $prefs_dao->get( $personnel->id_copix, 'prefs', 'assistance_ien' );

            if( $assistance === false ) $user_assistance = $default_assistance;
            elseif( $assistance->prefs_value == "1" ) $user_assistance = 1;
            else $user_assistance = 0;

            // if( $assistance_ien === false ) $user_assistance_ien = $default_assistance_ien;
            // elseif( $assistance_ien->prefs_value == "1" ) $user_assistance_ien = 1;
            // else $user_assistance_ien = 0;

            $users_animateur[$ville_id][$ecole_id]->personnels[$personnel_id]->assistance = $user_assistance; // $user_assistance_ien
        }
        foreach($users_ien AS $ville_id => $ville) foreach($ville AS $ecole_id => $ecole) foreach($ecole->personnels AS $personnel_id => $personnel) {
            $assistance_ien = $prefs_dao->get( $personnel->id_copix, 'prefs', 'assistance_ien' );

            if( $assistance_ien === false ) $user_assistance_ien = $default_assistance_ien;
            elseif( $assistance_ien->prefs_value == "1" ) $user_assistance_ien = 1;
            else $user_assistance_ien = 0;

            $users_ien[$ville_id][$ecole_id]->personnels[$personnel_id]->assistance = $user_assistance_ien;
        }

        // Cumul des droits
        $users = array();

        foreach( array( $users_animateur, $users_ien ) AS $users_a_tester )
        foreach( $users_a_tester AS $ville => $users_animateur_ville ) {
            if( !isset($users[$ville])) $users[$ville] = $users_animateur_ville;
            else {
                foreach( $users_animateur_ville AS $ecole => $users_animateur_ville_ecole ) {
                    if( !isset($users[$ville][$ecole])) $users[$ville][$ecole] = $users_animateur_ville_ecole;
                    else {
                        foreach( $users_animateur_ville_ecole->personnels AS $personnel => $users_animateur_ville_ecole_personnel ) {
                            if(!isset($users[$ville][$ecole]->personnels[$users_animateur_ville_ecole_personnel->id_copix])) {
                                $users[$ville][$ecole]->personnels[$users_animateur_ville_ecole_personnel->id_copix] = $users_animateur_ville_ecole_personnel;
                            } else {
                                $users[$ville][$ecole]->personnels[$users_animateur_ville_ecole_personnel->id_copix]->assistance = max(
                                    $users[$ville][$ecole]->personnels[$users_animateur_ville_ecole_personnel->id_copix]->assistance,
                                    $users_animateur_ville_ecole_personnel->assistance
                                );
                            }
                        }
                    }
                }
            }
        }

        // echo "<h1>Animateurs</h1><pre>"; print_r($users_animateur); echo "</pre><hr/><h1>IEN</h1><pre>"; print_r($users_ien); echo "</pre><hr/><h1>Resultat</h1><pre>"; print_r($users); die("</pre>");

        return $users;
    }

}

