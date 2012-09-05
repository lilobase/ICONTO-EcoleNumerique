<?php
/**
 * Fonctions diverses du module Comptes
 *
 * @package Iconito
 * @subpackage	Comptes
 */

class ComptesService
{
    /**
     * createLogin
     *
     * Propose un login en fonction des information de l'utilisateur (nom, prénom, rôle, etc.)
     *
     * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
     * @param array $user_infos Tableau des informations de l'utilisateur.
     * @return string Login composé des information disponibles.
     */
    public function createLogin( $user_infos )
    {
        // Caractères pouvant être dans un nom/prenom.
        $interdits = array(" ", "'", "-");

        $nom = $user_infos['nom'];
        $prenom = $user_infos['prenom'];
        $nom_init = $prenom_init = '';

        // Recherche des initiales : la première lettre de chaque entité dans un nom/prenom.
        $separateur_init = implode( '', $interdits );
        $tok = strtok($nom, $separateur_init);
        while ($tok !== false) {
            $nom_init .= $tok{0};
            $tok = strtok($separateur_init);
        }
        $tok = strtok($prenom, $separateur_init);
        while ($tok !== false) {
            $prenom_init .= $tok{0};
            $tok = strtok($separateur_init);
        }

        // Retrait des caractères spéciaux des noms/prénoms.
        $nom       = str_replace($interdits, "", $nom);
        $prenom    = str_replace($interdits, "", $prenom);

        // Simplification (accents, majuscules, etc.)
        $nom         = Kernel::simpleName($nom);
        $nom_init    = Kernel::simpleName($nom_init);
        $prenom      = Kernel::simpleName($prenom);
        $prenom_init = Kernel::simpleName($prenom_init);

        $login_parts = array();
        switch( $user_infos['type'] ) {
            case 'USER_ELE': // Elèves : Prénom et initiale du nom
                if( trim($prenom)   != '' ) $login_parts[] = $prenom;
                // if( trim($nom_init) != '' ) $login_parts[] = $nom_init;
                $login = implode( '', $login_parts );
                break;
            case 'USER_VIL': // Officiels : prénom et nom séparés par un point
                if( trim($prenom) != '' ) $login_parts[] = $prenom;
                if( trim($nom)    != '' ) $login_parts[] = $nom;
                $login = implode( '.', $login_parts );
                break;
            default; // Par défaut : initiale du prénom et nom
                if( trim($prenom_init) != '' ) $login_parts[] = $prenom_init;
                if( trim($nom)         != '' ) $login_parts[] = $nom;
                $login = implode( '', $login_parts );
                break;
        }

        $ext=''; $fusible=1000; // Fusible pour éviter les boucles sans fin.

        $get = _dao('kernel|kernel_copixuser')->getByLogin($login.$ext);
        while( count($get) && $fusible-- ) {
            if( $ext=='' ) $ext=1;
            else $ext++;
            $get = _dao('kernel|kernel_copixuser')->getByLogin($login.$ext);
        }

        return $login.$ext;
    }

    /**
     * createPasswd
     *
     * Propose un mot de passe aléatoire.
     *
     * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
     * @return string Mot de passe aléatoire.
     */
    public function createPasswd()
    {
        $lettres  = 'abcdefghijklmnopqrstuvwxyz';
        $chiffres = '0123456789';
        $passwd = '';

        $passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
        $passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
        $passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
        $passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
        $passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
        $passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
        $passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
        $passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};

        return( $passwd );
    }

    /*
    public function canCreateCompte( $node_type, $node_id )
    {
        return( Kernel::getLevel( $node_type, $node_id ) );
    }
    */








    // Tri les enseignants
    public function order_tab_enseignants ($tab)
    {
        usort ($tab, array("AnnuaireService", "compare_nom"));
        return $tab;
    }

    // Tri les elèves
    public function order_tab_eleves ($tab)
    {
        usort ($tab, array("AnnuaireService", "compare_info_nom"));
        return $tab;
    }

    public function compare_nom($a, $b)
    {
        //print_r($a);
      return strcmp($a["nom"], $b["nom"]);
    }

    public function compare_info_nom ($a, $b)
    {
        //print_r($a);
      return strcmp($a["info"]["nom"], $b["info"]["nom"]);
    }

    public function checkLoginAccess( $login )
    {
            if( trim($login)=='' ) {
            die( 'Pas de login dans l\'URL' );
        }

        $userinfo = Kernel::getUserInfo( 'LOGIN', $login );
        if( 0 == sizeof( $userinfo ) ) {
            die( 'Login inconnu' );
        }

        $level = 0;

        $level = Kernel::getLevel_r( $userinfo['type'], $userinfo['id'] );

        $level = max( $level, Kernel::getLevel_r( 'ROOT','0' ) );


        // Test via le module assistance
        $ok = false;

        $animateur_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
        $animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        if( $animateur && isset($animateur->can_comptes) && $animateur->can_comptes ) {
            $assistance_service = & CopixClassesFactory::Create ('assistance|assistance');
            $user_assistance = $assistance_service->getAssistanceUsers();

            if(!$user_assistance) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('assistance||users', array('error'=>'forbidden') ));
            foreach($user_assistance AS $ville_id => $ville) foreach($ville AS $ecole_id => $ecole) foreach($ecole->personnels AS $personnel_id => $personnel) {
                if( $personnel->login == $login ) {
                    $ok = true;
                    // echo "<pre>"; print_r($personnel); die("</pre>");
                }
            }
            if($ok) $level=70;
        }


        if( $level<70 ) { // A vérifier...
            die( 'Pas le droit !' );
        }

        return( $userinfo );
    }

    public function getGrvillesList()
    {
        $grvilles = array();

        $grvilles_dao  = & CopixDAOFactory::create("regroupements|grvilles");
        $grvilles_list = $grvilles_dao->findAll();
        foreach( $grvilles_list AS $grvilles_item ) {
            $grvilles_item->villes = array();
            $grvilles[$grvilles_item->id] = $grvilles_item;
        }

        $grvilles_gr2ville_dao = & CopixDAOFactory::create("regroupements|grvilles_gr2ville");
        $grvilles_gr2ville_list = $grvilles_gr2ville_dao->findAll();
        foreach( $grvilles_gr2ville_list AS $grvilles_gr2ville_item ) {
            $grvilles[$grvilles_gr2ville_item->id_groupe]->villes[$grvilles_gr2ville_item->id_ville] = $grvilles_gr2ville_item;
        }

        return $grvilles;
    }

    public function getGrecolesList()
    {
        $grecoles = array();

        $grecoles_dao  = & CopixDAOFactory::create("regroupements|grecoles");
        $grecoles_list = $grecoles_dao->findAll();
        foreach( $grecoles_list AS $grecoles_item ) {
            $grecoles_item->ecoles = array();
            $grecoles[$grecoles_item->id] = $grecoles_item;
        }

        $grecoles_gr2ecole_dao = & CopixDAOFactory::create("regroupements|grecoles_gr2ecole");
        $grecoles_gr2ecole_list = $grecoles_gr2ecole_dao->findAll();
        foreach( $grecoles_gr2ecole_list AS $grecoles_gr2ecole_item ) {
            $grecoles[$grecoles_gr2ecole_item->id_groupe]->ecoles[$grecoles_gr2ecole_item->id_ecole] = $grecoles_gr2ecole_item;
        }

        return $grecoles;
    }

}


