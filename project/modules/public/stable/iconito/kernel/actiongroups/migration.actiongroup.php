<?php
/**
 * Kernel Migration - ActionGroup
 *
 * Migrations de donnees
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id$
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

_classInclude('album|album');

class ActionGroupMigration extends CopixActionGroup
{
    private $connection_name_old = 'iconito_migration_old';
    private $connection_name_new = 'iconito_migration_new';
    private $path_old = '/Users/fmossmann/Documents/Eclipse/EN2010 ALPI/www/static_old';
    private $path_new = '/Users/fmossmann/Documents/Eclipse/EN2010 ALPI/www/static';

    private $connection_old;
    private $connection_new;
    private $bdname_old;
    private $bdname_new;


    public function __construct()
    {
        $this->connection_old = CopixDB::getConnection ($this->connection_name_old)->getProfile ()->getConnectionStringParts ();
        $this->connection_new = CopixDB::getConnection ($this->connection_name_new)->getProfile ()->getConnectionStringParts ();

        $this->bdname_old = $connection_old['dbname'];
        $this->bdname_new = $connection_new['dbname'];
    }

    public function processAlpi_blogalbum()
    {
        $ppo = new CopixPPO();

        /*
        $connection_old = CopixDB::getConnection ('iconito_migration_old')->getProfile ()->getConnectionStringParts ();
        $connection_new = CopixDB::getConnection ('iconito_migration_new')->getProfile ()->getConnectionStringParts ();

        $path_old = '';
        $path_new = '';

        $bdname_old = $connection_old['dbname'];
        $bdname_new = $connection_new['dbname'];
        */

        $ppo->a_migrer = _doQuery ('
            SELECT
                PER.numero AS per_id, PER.nom AS per_nom, PER.prenom1 AS per_prenom,
                OLD_CLA.id AS cla_id, OLD_CLA.nom AS cla_nom,
                OLD_ECO.numero AS eco_id, OLD_ECO.nom AS eco_nom,
                OLD_MOD.module_type AS from_mod_type, OLD_MOD.module_id AS from_mod_id,
                NEW_MOD.module_id AS to_malle_id
            FROM '.$this->bdname_new.'.kernel_bu_personnel PER
            JOIN '.$this->bdname_old.'.kernel_bu_personnel_entite OLD_ENT ON PER.numero=OLD_ENT.id_per AND OLD_ENT.type_ref="CLASSE"
            JOIN '.$this->bdname_old.'.kernel_bu_ecole_classe OLD_CLA ON OLD_ENT.reference=OLD_CLA.id
            JOIN '.$this->bdname_old.'.kernel_bu_ecole OLD_ECO ON OLD_CLA.ecole=OLD_ECO.numero
            JOIN '.$this->bdname_old.'.kernel_mod_enabled OLD_MOD ON OLD_ENT.reference=OLD_MOD.node_id AND OLD_MOD.node_type="BU_CLASSE" AND (OLD_MOD.module_type="MOD_MALLE" OR OLD_MOD.module_type="MOD_ALBUM")
            JOIN '.$this->bdname_new.'.kernel_mod_enabled NEW_MOD ON PER.numero=NEW_MOD.node_id AND NEW_MOD.node_type="USER_ENS" AND NEW_MOD.module_type="MOD_MALLE"

            -- DEBUG
            -- WHERE OLD_MOD.module_type="MOD_MALLE" AND NEW_MOD.module_id=52

            ORDER BY eco_nom, cla_nom, per_nom, per_prenom
            ');
        // node_type  node_id  module_type  module_id
        // USER_RES   8451     MOD_MALLE    853

        // kernel_bu_personnel : numero 	nom 	nom_jf 	prenom1 	civilite 	id_sexe
        // kernel_bu_personnel_entite : id_per 	reference 	type_ref 	role
        // kernel_bu_ecole_classe : id 	ecole 	nom 	annee_scol 	is_validee 	is_supprimee
        // kernel_bu_ecole : numero 	RNE 	code_ecole_vaccination 	type 	nom
        // kernel_mod_enabled : node_type 	node_id 	module_type 	module_id

        // _dump($ppo->a_migrer); die();


        foreach( $ppo->a_migrer AS $a_migrer_key => $a_migrer_val ) {
            // Rechercher malle archive nom classe
            // // Si absente : creer
            switch( $a_migrer_val->from_mod_type ) {
                case 'MOD_ALBUM':
                    $ppo->a_migrer[$a_migrer_key]->data = new CopixPPO();
                    $ppo->a_migrer[$a_migrer_key]->data->files = $this->getAlbumFiles($a_migrer_val->from_mod_id);
                    $ppo->a_migrer[$a_migrer_key]->data->folders = $this->getAlbumFolders($a_migrer_val->from_mod_id);
                    break;
                case 'MOD_MALLE':
                    $ppo->a_migrer[$a_migrer_key]->data = new CopixPPO();
                    $ppo->a_migrer[$a_migrer_key]->data->files = $this->getMalleFiles($a_migrer_val->from_mod_id);
                    $ppo->a_migrer[$a_migrer_key]->data->folders = $this->getMalleFolders($a_migrer_val->from_mod_id);
                    break;
            }
        }


        if(_request("go",0)) $this->doMigration($ppo->a_migrer);

        return _arPPO($ppo, 'migration_alpi_blogalbum.tpl');
    }

    private function getAlbumFiles($album, $folder=0)
    {
        $return = array();

        $photo_dao = _dao('album|photo',$this->connection_name_old);
        $photos = $photo_dao->findAllByAlbumAndFolder($album, $folder);
        foreach( $photos AS $photo ) {
            $return[] = $photo;
        }
        return( $return );
    }

    private function getAlbumFolders($album, $folder=0)
    {
        $return = array();

        $dossiers_dao = _dao('album|dossier',$this->connection_name_old);
        $dossiers = $dossiers_dao->findAllByAlbum($album);
        foreach( $dossiers AS $dossier ) {
            if($dossier->dossier_parent==$folder) {
                $dossier->files = $this->getAlbumFiles($album, $dossier->dossier_id);
                $dossier->folders = $this->getAlbumFolders($album, $dossier->dossier_id);
                $return[] = $dossier;
            }
        }

        return( $return );
    }

    private function getMalleFiles($malle, $folder=0)
    {
        $return = array();

        $files_dao = _dao('malle|malle_files',$this->connection_name_old);
        $files = $files_dao->getFilesInFolder($malle, $folder);
        foreach( $files AS $file ) {
            $return[] = $file;
        }
        return( $return );
    }

    private function getMalleFolders($malle, $folder=0)
    {
        $return = array();


        $dossiers_dao = _dao('malle|malle_folders',$this->connection_name_old);
        $dossiers = $dossiers_dao->getFoldersInFolder($malle, $folder);
        foreach( $dossiers AS $dossier ) {
            $dossier->files = $this->getMalleFiles($malle, $dossier->id);
            $dossier->folders = $this->getMalleFolders($malle, $dossier->id);
            $return[] = $dossier;
        }

        return( $return );
    }

    private function doMigration($datas)
    {
        $annee_scolaire_dao = _ioDAO ('kernel|kernel_bu_annee_scolaire',$this->connection_name_old);

        $album_photo_dao = _dao('album|photo',$this->connection_name_old);
        $album_dossiers_dao = _dao('album|dossier',$this->connection_name_old);

        $malle_files_dao = _dao('malle|malle_files',$this->connection_name_old);
        $malle_dossiers_dao = _dao('malle|malle_folders',$this->connection_name_old);

        $destmalle_malle_dao = _dao('malle|malle_malles',$this->connection_name_new);
        $destmalle_files_dao = _dao('malle|malle_files',$this->connection_name_new);
        $destmalle_dossiers_dao = _dao('malle|malle_folders',$this->connection_name_new);

        $currentGrade = $annee_scolaire_dao->getCurrent ();
        if(count($currentGrade)) $archive_name_main = "Archive ".($currentGrade->anneeScolaire);
        else                     $archive_name_main = "Archive";

        foreach( $datas AS $data ) {
            $archive_name = $archive_name_main." : ".$data->cla_nom;

            $malleinfos = $destmalle_malle_dao->get($data->to_malle_id);

            if(!is_dir($this->path_new."/malle/".$malleinfos->id."_".$malleinfos->cle)) {
                mkdir($this->path_new."/malle/".$malleinfos->id."_".$malleinfos->cle);
            }

            if( 0==count($data->data->files) && 0==count($data->data->folders) ) continue;


            // Recherche du dossier d'archive
            $destfolders = $destmalle_dossiers_dao->getFoldersInFolder($data->to_malle_id, 0);
            $destfolder_found = false;
            foreach( $destfolders AS $destfolder ) {
                if($destfolder->nom==$archive_name) { $destfolder_found = true; break; }
            }

            // Si le dossier d'archive n'existe pas, création...
            if(!$destfolder_found) {
                $destfolder = _record('malle|malle_folders',$this->connection_name_new);
                $destfolder->malle = $data->to_malle_id;
                $destfolder->malle_cle = $malleinfos->cle;
                $destfolder->parent = 0;
                $destfolder->nom = $archive_name;
                $destfolder->nb_folders = 0;
                $destfolder->nb_files = 0;
                $destfolder->taille = 0;
                $destfolder->date_creation = date("Y-m-d H:i:s");

                _dao('malle|malle_folders',$this->connection_name_new)->insert($destfolder);
            }

            // Création du sous-dossier Album ou Malle
            $destsubfolders = _record('malle|malle_folders',$this->connection_name_new);
            $destsubfolders->malle = $data->to_malle_id;
            $destsubfolders->malle_cle = $malleinfos->cle;
            $destsubfolders->parent = $destfolder->id;
            $destsubfolders->nb_folders = 0;
            $destsubfolders->nb_files = 0;
            $destsubfolders->taille = 0;
            $destsubfolders->date_creation = date("Y-m-d H:i:s");

            switch( $data->from_mod_type ) {
                case 'MOD_ALBUM':
                    $destsubfolders->nom = "Album";
                    _dao('malle|malle_folders',$this->connection_name_new)->insert($destsubfolders);
                    $this->doExportAlbum($destsubfolders, $data->data);
                    break;
                case 'MOD_MALLE':
                    $destsubfolders->nom = "Malle";
                    _dao('malle|malle_folders',$this->connection_name_new)->insert($destsubfolders);
                    $this->doExportMalle($destsubfolders, $data->data);
                    break;
            }

            // _dump($data);

            $malleService = & CopixClassesFactory::Create ('malle|malleService');
            $malleService->update_infos_for_folder_desc($data->to_malle_id, 0);
            // Malle Service : update_infos_for_folder
            // die('stop'.$data->from_mod_type);
        }
        // $malle_dossiers_dao

        // _dump($data);

        // Check Malle's folder
    }

    private function doExportAlbum($destfolder, $data)
    {
        foreach( $data->files AS $file ) {
            $file_from = $this->path_old."/album/".$file->album_id."_".$file->album_cle."/".$file->photo_id."_".$file->photo_cle.".".$file->photo_ext;

            $destfile = _record('malle|malle_files',$this->connection_name_new);
            $destfile->malle = $destfolder->malle;
            $destfile->malle_cle = $destfolder->malle_cle;
            $destfile->folder = $destfolder->id;
            $destfile->nom = $file->photo_nom;
            $destfile->fichier = "archive_album_".$file->photo_id."_".$file->photo_cle.".".$file->photo_ext;
            $destfile->taille = filesize($file_from);
            $destfile->type = CopixMIMETypes::getFromExtension ($file->photo_ext);
            $destfile->cle = substr( md5(microtime()), 0, 10 );
            $destfile->date_upload = $destfolder->date_creation;
            _dao('malle|malle_files',$this->connection_name_new)->insert($destfile);

            $file_dest = $this->path_new."/malle/".$destfolder->malle."_".$destfolder->malle_cle."/".$destfile->id."_archive_album_".$file->photo_id."_".$file->photo_cle.".".$file->photo_ext;
            copy( $file_from, $file_dest );
        }

        foreach( $data->folders AS $folder ) {
            $destsubfolders = _record('malle|malle_folders',$this->connection_name_new);
            $destsubfolders->malle = $destfolder->malle;
            $destsubfolders->malle_cle = $destfolder->malle_cle;
            $destsubfolders->parent = $destfolder->id;
            $destsubfolders->nb_folders = 0;
            $destsubfolders->nb_files = 0;
            $destsubfolders->taille = 0;
            $destsubfolders->date_creation = date("Y-m-d H:i:s");
            $destsubfolders->nom = $folder->dossier_nom;
            _dao('malle|malle_folders',$this->connection_name_new)->insert($destsubfolders);

            $this->doExportAlbum($destsubfolders, $folder);
        }
    }

    private function doExportMalle($destfolder, $data)
    {
        $malleService = & CopixClassesFactory::Create ('malle|malleService');

        foreach( $data->files AS $file ) {
            $file_from = $this->path_old."/malle/".$file->malle."_".$file->malle_cle."/".$file->id."_".$file->fichier;

            $destfile = _record('malle|malle_files',$this->connection_name_new);
            $destfile->malle = $destfolder->malle;
            $destfile->malle_cle = $destfolder->malle_cle;
            $destfile->folder = $destfolder->id;
            $destfile->nom = stripslashes($file->nom);
            $destfile->fichier = "archive_malle_".$file->fichier;
            $destfile->taille = filesize($file_from);
            $destfile->type = $malleService->getMimeType($file_from);
            $destfile->cle = substr( md5(microtime()), 0, 10 );
            $destfile->date_upload = $destfolder->date_creation;
            _dao('malle|malle_files',$this->connection_name_new)->insert($destfile);

            $file_dest = $this->path_new."/malle/".$destfolder->malle."_".$destfolder->malle_cle."/".$destfile->id."_archive_malle_".$file->fichier;
            copy( $file_from, $file_dest );
        }

        foreach( $data->folders AS $folder ) {
            $destsubfolders = _record('malle|malle_folders',$this->connection_name_new);
            $destsubfolders->malle = $destfolder->malle;
            $destsubfolders->malle_cle = $destfolder->malle_cle;
            $destsubfolders->parent = $destfolder->id;
            $destsubfolders->nb_folders = 0;
            $destsubfolders->nb_files = 0;
            $destsubfolders->taille = 0;
            $destsubfolders->date_creation = date("Y-m-d H:i:s");
            $destsubfolders->nom = $folder->nom;
            _dao('malle|malle_folders',$this->connection_name_new)->insert($destsubfolders);

            $this->doExportMalle($destsubfolders, $folder);
        }
    }
}

