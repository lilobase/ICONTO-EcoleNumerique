<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id$
 * @author      Frederic Mmossmann <fmossmann@cap-tic.fr>
 * @copyright   2011 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|cacheservices');
_classInclude('sysutils|admin');

class ActionGroupMigration_Classeur extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }

    public function processDefault ()
    {
        ini_set('max_execution_time', 0);

        $classeurservice = & CopixClassesFactory::Create ('classeur|classeurservice');


        /* DEBUG
        if (!Admin::canAdmin())
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
        */

        $tpl = new CopixTpl ();
        // $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sysutils|admin.menu.cache'));
        // $tpl->assign ('MENU', Admin::getMenu('cache'));

        ///////////////
        //// ALBUM ////
        ///////////////
        $sql = "
            SELECT
                A.id AS album_id, A.nom AS album_nom, A.prefs AS album_prefs, A.date AS album_date, A.cle AS album_cle, A.public AS album_public
            FROM module_album_albums A
            ORDER BY A.id
        ";
        $album_list = _doQuery( $sql );
        $album_tree = array();
        foreach( $album_list AS $album_item ) {
            if( !isset($album_tree[$album_item->album_id])) {
                $album_tree[$album_item->album_id] = new CopixPPO();
                $album_tree[$album_item->album_id]->info = $album_item;
                $parent = Kernel::getModParent( "MOD_ALBUM", $album_item->album_id );

                if(!$parent) {
                    unset($album_tree[$album_item->album_id]);
                    continue;
                }

                $album_tree[$album_item->album_id]->parent = $parent[0];
                $album_tree[$album_item->album_id]->dossier = array();
                $album_tree[$album_item->album_id]->photo = array();
            }
            $root = $this->albumScanFolder( $album_item->album_id, 0 );
            $album_tree[$album_item->album_id]->dossier = $root->dossier;
            $album_tree[$album_item->album_id]->photo   = $root->photo;

        }


        // module_malle_malles : id 	titre 	date_creation 	cle
        // module_malle_folders : id malle parent nom nb_folders nb_files taille date_creation
        // module_malle_files : id 	malle 	folder 	nom 	fichier 	taille 	type 	cle 	date_upload

        ///////////////
        //// MALLE ////
        ///////////////
        $sql = "
            SELECT
                id, titre, date_creation, cle
            FROM module_malle_malles
            ORDER BY id
        ";
        $malle_list = _doQuery( $sql );
        $malle_tree = array();
        foreach( $malle_list AS $malle_item ) {
            if( !isset($malle_tree[$malle_item->id])) {
                $malle_tree[$malle_item->id] = new CopixPPO();
                $malle_tree[$malle_item->id]->info = $malle_item;

                $parent = Kernel::getModParent( "MOD_MALLE", $malle_item->id );

                if(!$parent) {
                    unset($malle_tree[$malle_item->id]);
                    continue;
                }

                $malle_tree[$malle_item->id]->parent = $parent[0];

                $user_parents = Kernel::getNodeParents( $parent[0]->node_type, $parent[0]->node_id );
                $malle_tree[$malle_item->id]->user_parents = $user_parents;

                $malle_tree[$malle_item->id]->dossier = array();
                $malle_tree[$malle_item->id]->docs = array();
            }
            $root = $this->malleScanFolder( $malle_item->id, 0 );
            $malle_tree[$malle_item->id]->dossier = $root->dossier;
            $malle_tree[$malle_item->id]->docs   = $root->docs;

        }



        /////////////////////////////////////
        /////////////////////////////////////

        // kernel_mod_enabled : node_type 	node_id 	module_type 	module_id
        // module_classeur : id 	titre 	cle 	date_creation 	date_publication 	public
        // module_classeur_dossier : id 	module_classeur_id 	parent_id 	nom 	nb_dossiers 	nb_fichiers 	taille 	cle 	date_creation 	user_type 	user_id 	date_publication 	public
        // module_classeur_fichier : id 	module_classeur_id 	module_classeur_dossier_id 	titre 	commentaire 	fichier 	taille 	type 	cle 	date_upload 	user_type 	user_id

        // IMPORT ALBUMS
        if(1) foreach( $album_tree AS $album_item ) {

            if(!isset( $album_item->parent->node_type )) continue;

            //// RECHERCHE D'UN CLASSEUR EXISTANT
            $sql = "
                SELECT module_id
                FROM kernel_mod_enabled
                WHERE node_type=:node_type AND node_id=:node_id AND module_type=:module_type
            ";
            $param = array(
                'node_type'   => $album_item->parent->node_type,
                'node_id'     => $album_item->parent->node_id,
                'module_type' => 'MOD_CLASSEUR'
            );
            $mod_classeur = _doQuery( $sql, $param );
            if( !isset($mod_classeur[0]) ) {
                $node_infos = $this->getNodeInfos($album_item->parent->node_type, $album_item->parent->node_id);

                $file     = & CopixSelectorFactory::create("classeur|classeur");
                $filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
                $modservice = & CopixClassesFactory::Create ('classeur|kernelclasseur');
                if( method_exists( $modservice, "create" ) ) {
                    $modid = $modservice->create(array('title'=>$node_infos->nom, 'subtitle'=>'', 'node_type'=>$album_item->parent->node_type, 'node_id'=>$album_item->parent->node_id));
                    if( $modid != null ) {
                        Kernel::registerModule( 'MOD_CLASSEUR', $modid, $album_item->parent->node_type, $album_item->parent->node_id );
                    }
                }
                $classeur_id = $modid;
            } else {
                $classeur_id = $mod_classeur[0]->module_id;
            }

            $classeurDAO  = _ioDAO('classeur|classeur');
            $classeur = $classeurDAO->get($classeur_id);
            $new_dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
            if (!file_exists($new_dir)) {
                mkdir($new_dir, 0755, true);
            }


            //// CREATION D'UN DOSSIER D'IMPORT
            $dossierDAO = _ioDAO('classeur|classeurdossier');
            $dossier = _record ('classeur|classeurdossier');

            $dossier->classeur_id    = $classeur_id;
            $dossier->parent_id      = 0;
            $dossier->nom            = "Import Album ".$album_item->info->album_id;
            $dossier->cle            = $album_item->info->album_cle;
            $dossier->date_creation  = $album_item->info->album_date;
            $dossier->user_type      = "";
            $dossier->user_id        = "";
            $dossier->nb_dossiers    = 0;
            $dossier->nb_fichiers    = 0;
            $dossier->taille         = 0;
            $dossier->casier         = 0;

            $dossierDAO->insert ($dossier);

            // $dossier->id

            $this->albumImport( $album_item, $dossier );




            $classeurservice->updateFolderInfosWithDescendants($dossier);
        }


        // IMPORT MALLES
        if(1) foreach( $malle_tree AS $malle_item ) {

            if(!isset( $malle_item->parent->node_type )) continue;

            //// RECHERCHE D'UN CLASSEUR EXISTANT
            $sql = "
                SELECT module_id
                FROM kernel_mod_enabled
                WHERE node_type=:node_type AND node_id=:node_id AND module_type=:module_type
            ";
            $param = array(
                'node_type'   => $malle_item->parent->node_type,
                'node_id'     => $malle_item->parent->node_id,
                'module_type' => 'MOD_CLASSEUR'
            );
            $mod_classeur = _doQuery( $sql, $param );
            if( !isset($mod_classeur[0]) ) {
                $node_infos = $this->getNodeInfos($malle_item->parent->node_type, $malle_item->parent->node_id);

                $file     = & CopixSelectorFactory::create("classeur|classeur");
                $filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
                $modservice = & CopixClassesFactory::Create ('classeur|kernelclasseur');
                if( method_exists( $modservice, "create" ) ) {
                    $modid = $modservice->create(array('title'=>$node_infos->nom, 'subtitle'=>'', 'node_type'=>$malle_item->parent->node_type, 'node_id'=>$malle_item->parent->node_id));
                    if( $modid != null ) {
                        Kernel::registerModule( 'MOD_CLASSEUR', $modid, $malle_item->parent->node_type, $malle_item->parent->node_id );
                    }
                }
                $classeur_id = $modid;
            } else {
                $classeur_id = $mod_classeur[0]->module_id;
            }

            $classeurDAO  = _ioDAO('classeur|classeur');
            $classeur = $classeurDAO->get($classeur_id);
            $new_dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
            if (!file_exists($new_dir)) {
                mkdir($new_dir, 0755, true);
            }


            //// CREATION D'UN DOSSIER D'IMPORT
            $dossierDAO = _ioDAO('classeur|classeurdossier');
            $dossier = _record ('classeur|classeurdossier');

            $dossier->classeur_id    = $classeur_id;
            $dossier->parent_id      = 0;
            $dossier->nom            = "Import documents ".$malle_item->info->id;
            $dossier->cle            = $malle_item->info->cle;
            $dossier->date_creation  = $malle_item->info->date_creation;
            $dossier->user_type      = "";
            $dossier->user_id        = "";
            $dossier->nb_dossiers    = 0;
            $dossier->nb_fichiers    = 0;
            $dossier->taille         = 0;

            $dossierDAO->insert ($dossier);

            // $dossier->id

            $this->malleImport( $malle_item, $dossier );




            $classeurservice->updateFolderInfosWithDescendants($dossier);
        }

        $sql = "
            UPDATE kernel_mod_available
            SET node = CONCAT(node, '_OFF')
            WHERE module = 'MOD_MALLE' OR module = 'MOD_ALBUM'";
        _doQuery($sql);

        $sql = "
            UPDATE kernel_mod_enabled
            SET node_type = CONCAT(node_type, '_OFF')
            WHERE module_type = 'MOD_MALLE' OR module_type = 'MOD_ALBUM' AND node_type != 'MOD_TELEPROCEDURES'";
        _doQuery($sql);

        echo "<h1>Fin</h1>";
        echo "<pre>"; print_r( $album_tree ); print_r( $malle_tree );
        die();


    }

    private function albumScanFolder( $album_id, $folder_id )
    {
        $return = new CopixPPO();

        $sql = "
            SELECT
                D.id AS dossier_id, D.id_album AS dossier_id_album, D.id_parent AS dossier_id_parent, D.nom AS dossier_nom, D.commentaire AS dossier_commentaire, D.date AS dossier_date, D.cle AS dossier_cle, D.public AS dossier_public
            FROM module_album_dossiers D

            WHERE D.id_album=:id_album AND D.id_parent=:id_dossier
            ORDER BY D.id
        ";
        $params = array( ':id_album'=>$album_id, ':id_dossier'=>$folder_id );
        $dossier_list = _doQuery( $sql, $params );

        $return->dossier = array();
        foreach( $dossier_list AS $dossier_item ) {
            $return->dossier[$dossier_item->dossier_id] = new CopixPPO();
            $return->dossier[$dossier_item->dossier_id]->info = $dossier_item;
            $childs = $this->albumScanFolder( $dossier_item->dossier_id_album, $dossier_item->dossier_id );
            $return->dossier[$dossier_item->dossier_id]->dossier = $childs->dossier;
            $return->dossier[$dossier_item->dossier_id]->photo   = $childs->photo;

        }

        $sql = "
            SELECT
                P.id AS photo_id, P.id AS photo_id, P.id_album AS photo_id_album, P.id_dossier AS photo_id_dossier, P.nom AS photo_nom, P.commentaire AS photo_commentaire, P.date AS photo_date, P.ext AS photo_ext, P.cle AS photo_cle, P.public AS photo_public, A.cle AS album_cle
            FROM module_album_photos P
            JOIN module_album_albums A ON A.id=P.id_album
            WHERE P.id_album=:id_album AND P.id_dossier=:id_dossier
            ORDER BY P.id
        ";
        $params = array( ':id_album'=>$album_id, ':id_dossier'=>$folder_id );
        $photo_list = _doQuery( $sql, $params );

        $return->photo = array();
        foreach( $photo_list AS $photo_item ) {
            $return->photo[$photo_item->photo_id] = $photo_item;
        }

        return $return;
    }

    private function malleScanFolder( $malle_id, $folder_id )
    {
        $return = new CopixPPO();

        // module_malle_folders : id malle parent nom nb_folders nb_files taille date_creation
        $sql = "
            SELECT
                id, malle, parent, nom, nb_folders, nb_files, taille, date_creation
            FROM module_malle_folders

            WHERE malle=:id_malle AND parent=:id_dossier
            ORDER BY id
        ";
        $params = array( ':id_malle'=>$malle_id, ':id_dossier'=>$folder_id );
        $dossier_list = _doQuery( $sql, $params );

        $return->dossier = array();
        foreach( $dossier_list AS $dossier_item ) {
            $return->dossier[$dossier_item->id] = new CopixPPO();
            $return->dossier[$dossier_item->id]->info = $dossier_item;
            $childs = $this->malleScanFolder( $dossier_item->malle, $dossier_item->id );
            $return->dossier[$dossier_item->id]->dossier = $childs->dossier;
            $return->dossier[$dossier_item->id]->docs   = $childs->docs;

        }

        // module_malle_files : id 	malle 	folder 	nom 	fichier 	taille 	type 	cle 	date_upload
        $sql = "
            SELECT
                F.id, F.malle, F.folder, F.nom, F.fichier, F.taille, F.type, F.cle, F.date_upload, M.cle AS malle_cle
            FROM module_malle_files F
            JOIN module_malle_malles M ON M.id=F.malle
            WHERE malle=:id_malle AND folder=:id_dossier
            ORDER BY id
        ";
        $params = array( ':id_malle'=>$malle_id, ':id_dossier'=>$folder_id );
        $docs_list = _doQuery( $sql, $params );

        $return->docs = array();
        foreach( $docs_list AS $docs_item ) {
            $return->docs[$docs_item->id] = $docs_item;
        }

        return $return;
    }

    private function albumImport( $album_dossier, $classeur_dossier )
    {
        $tailles = explode(",",CopixConfig::get ('album|thumb_sizes'));

        $classeurDAO = _ioDAO('classeur|classeur');
        $classeur = $classeurDAO->get($classeur_dossier->classeur_id);
        // print_r($classeur); die();


        if( count($album_dossier->dossier) ) {
            // Import dossiers
            foreach( $album_dossier->dossier AS $album_dossier_item ) {

                $dossierDAO = _ioDAO('classeur|classeurdossier');
                $dossier = _record ('classeur|classeurdossier');

                $dossier->classeur_id    = $classeur_dossier->classeur_id;
                $dossier->parent_id      = $classeur_dossier->id;
                $dossier->nom            = $album_dossier_item->info->dossier_nom;
                $dossier->cle            = $album_dossier_item->info->dossier_cle;
                $dossier->date_creation  = $album_dossier_item->info->dossier_date;
                $dossier->user_type      = "";
                $dossier->user_id        = "";
                $dossier->nb_dossiers    = 0;
                $dossier->nb_fichiers    = 0;
                $dossier->taille         = 0;

                $dossierDAO->insert ($dossier);


                $this->albumImport( $album_dossier_item, $dossier );
            }
        }
        if( count($album_dossier->photo) ) {
            foreach( $album_dossier->photo AS $album_photo_item ) {

                $old_file = realpath('./static/album').'/'.$album_photo_item->photo_id_album.'_'.$album_photo_item->album_cle.'/'.$album_photo_item->photo_id.'_'.$album_photo_item->photo_cle.'.'.$album_photo_item->photo_ext;

                $fichierDAO = _ioDAO('classeur|classeurfichier');
                $fichier = _record ('classeur|classeurfichier');

                $fichier->classeur_id    = $classeur_dossier->classeur_id;
                $fichier->dossier_id     = $classeur_dossier->id;
                $fichier->titre          = $album_photo_item->photo_nom;
                $fichier->commentaire    = $album_photo_item->photo_commentaire;
                $fichier->cle            = $album_photo_item->photo_cle;
                $fichier->date_upload    = $album_photo_item->photo_date;
                $fichier->user_type      = "";
                $fichier->user_id        = "";

                $fichier->taille         = filesize($old_file);
                $fichier->type           = strtoupper($album_photo_item->photo_ext);
                $fichier->fichier        = $album_photo_item->photo_nom.'.'.$album_photo_item->photo_ext; // 'image-'.$album_photo_item->photo_id.".".$album_photo_item->photo_ext;

                $fichierDAO->insert ($fichier);

                $new_file = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$fichier->id.'-'.$album_photo_item->photo_cle.'.'.$album_photo_item->photo_ext;
                if (file_exists($old_file))
                    copy( $old_file, $new_file );

                foreach( $tailles AS $taille ) {
                    $old_file = realpath('./static/album').'/'.$album_photo_item->photo_id_album.'_'.$album_photo_item->album_cle.'/'.$album_photo_item->photo_id.'_'.$album_photo_item->photo_cle.'_'.$taille.'.'.$album_photo_item->photo_ext;
                    $new_file = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$fichier->id.'-'.$album_photo_item->photo_cle.'_'.$taille.'.'.$album_photo_item->photo_ext;
                    if (file_exists($old_file))
                        copy( $old_file, $new_file );
                    // echo "<li>".$old_file." &raquo; ".$new_file;

                }
                // echo "<li>".$old_file." &raquo; ".$new_file;



                /*
                ./album/2_313fc27fdf/26_88ac2e9434_480.jpg
                ./album/3_5ac946b20b
                ./classeur/4-1d691e55c5
                ./classeur/4-1d691e55c5/1-81338881fe.pdf

                $filepath   = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$file->id.'-'.$file->cle.$extension;

                */

            }
        }
    }

    private function malleImport( $malle_dossier, $classeur_dossier )
    {
        $classeurDAO = _ioDAO('classeur|classeur');
        $classeur = $classeurDAO->get($classeur_dossier->classeur_id);


        if( count($malle_dossier->dossier) ) {
            // Import dossiers
            foreach( $malle_dossier->dossier AS $malle_dossier_item ) {
                // print_r($malle_dossier_item); die();

                $dossierDAO = _ioDAO('classeur|classeurdossier');
                $dossier = _record ('classeur|classeurdossier');

                $dossier->classeur_id    = $classeur_dossier->classeur_id;
                $dossier->parent_id      = $classeur_dossier->id;
                $dossier->nom            = $malle_dossier_item->info->nom;
                $dossier->cle            = substr( md5(microtime()), 0, 10 );
                $dossier->date_creation  = $malle_dossier_item->info->date_creation;
                $dossier->user_type      = "";
                $dossier->user_id        = "";
                $dossier->nb_dossiers    = 0;
                $dossier->nb_fichiers    = 0;
                $dossier->taille         = 0;

                $dossierDAO->insert ($dossier);


                $this->malleImport( $malle_dossier_item, $dossier );
            }
        }
        if( count($malle_dossier->docs) ) {
            foreach( $malle_dossier->docs AS $malle_item ) {
                // print_r($malle_item); die();

                $old_file = realpath('./static/malle').'/'.$malle_item->malle.'_'.$malle_item->malle_cle.'/'.$malle_item->id.'_'.$malle_item->fichier;

                $fichierDAO = _ioDAO('classeur|classeurfichier');
                $fichier = _record ('classeur|classeurfichier');

                $fichier->classeur_id    = $classeur_dossier->classeur_id;
                $fichier->dossier_id     = $classeur_dossier->id;
                $fichier->titre          = $malle_item->nom;
                $fichier->commentaire    = '';
                $fichier->cle            = $malle_item->cle;
                $fichier->date_upload    = $malle_item->date_upload;
                $fichier->user_type      = "";
                $fichier->user_id        = "";

                $fichier->taille         = filesize($old_file);
                $fichier->type           = end(explode(".", $malle_item->fichier));
                $fichier->fichier        = $malle_item->fichier;

                $fichierDAO->insert ($fichier);

                $new_file = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$fichier->id.'-'.$malle_item->cle.'.'.end(explode(".", $malle_item->fichier));
                if (file_exists($old_file))
                    copy( $old_file, $new_file );

                // echo "<li>".$old_file." &raquo; ".$new_file;



                /*
                ./album/2_313fc27fdf/26_88ac2e9434_480.jpg
                ./album/3_5ac946b20b
                ./classeur/4-1d691e55c5
                ./classeur/4-1d691e55c5/1-81338881fe.pdf

                $filepath   = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$file->id.'-'.$file->cle.$extension;

                */

            }
        }
    }

    private function getNodeInfos ($node_type, $node_id)
    {
        switch($node_type) {
            case "BU_CLASSE":
                $sql = "SELECT nom FROM kernel_bu_ecole_classe WHERE id = :node_id";
                break;
            case "BU_ECOLE":
                $sql = "SELECT nom FROM kernel_bu_ecole WHERE numero = :node_id";
                break;
            case "BU_VILLE":
                $sql = "SELECT nom FROM kernel_bu_ville WHERE id_vi = :node_id";
                break;
            case "CLUB":
                $sql = "SELECT titre as nom FROM module_groupe_groupe WHERE id = :node_id";
                break;
            default:
                $retour = new CopixPPO();
                $retour->nom = 'Classeur';
                return $retour;
        }
        $param = array(
            'node_id'     => $node_id,
        );
        $node_info = _doQuery( $sql, $param );

        return $node_info[0];
    }

}
