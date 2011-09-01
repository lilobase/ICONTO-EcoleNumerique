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

class ActionGroupMigration_Classeur extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
	}

	function processDefault () {
		/* DEBUG
		if (!Admin::canAdmin())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
		*/
		
		$tpl = & new CopixTpl ();
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
				$malle_tree[$malle_item->id]->dossier = array();
				$malle_tree[$malle_item->id]->docs = array();
			}
			$root = $this->malleScanFolder( $malle_item->id, 0 );
			$malle_tree[$malle_item->id]->dossier = $root->dossier;
			$malle_tree[$malle_item->id]->docs   = $root->docs;
			
		}
		
		
		
		echo "<pre>"; print_r( $malle_tree ); die();
		
		$tplCache = & new CopixTpl();
		$tplCache->assign ('info', CopixZone::process('sysutils|cacheStatus'));
		
		$tpl->assign ('MAIN', $tplCache->fetch('sysutils|cache.info.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

	private function albumScanFolder( $album_id, $folder_id ) {
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
				P.id AS photo_id, P.id AS photo_id, P.id_album AS photo_id_album, P.id_dossier AS photo_id_dossier, P.nom AS photo_nom, P.commentaire AS photo_commentaire, P.date AS photo_date, P.ext AS photo_ext, P.cle AS photo_cle, P.public AS photo_public
			FROM module_album_photos P
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
	
	private function malleScanFolder( $malle_id, $folder_id ) {
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
				id, malle, folder, nom, fichier, taille, type, cle, date_upload
			FROM module_malle_files
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
	
}
?>
