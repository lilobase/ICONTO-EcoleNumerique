<?php
/**
 * Actiongroup du module Malle
 * 
 * @package	Iconito
 * @subpackage	Malle
 */

require_once (COPIX_UTILS_PATH.'pclzip.lib.php');
 
class ActionGroupMalle extends CopixActionGroup {

   /**
   * Affichage du détail d'une malle
	 * 
	 * Affiche l'ensemble des fichiers, dossiers et actions possibles de réaliser dans un dossier d'une malle
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/02
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire
	 * @param array $errors (option) Erreurs rencontrées
   */
   function processGetMalle () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest ('id', null);
		
		// Malle perso : si $id non défini ou si $id=0
		if( !$id ) {
			// Récupération des infos de l'utilisateur.
			$userInfo = Kernel::getUserInfo();
			// Création des modules inexistants.
			Kernel::createMissingModules( $userInfo["type"], $userInfo["id"] );
			// Liste des modules activés.
			$modsList = Kernel::getModEnabled( $userInfo["type"], $userInfo["id"] );
			foreach( $modsList AS $modInfo ) {
				// Recherche du module de malle.
				//print_r($modInfo);
				if( $modInfo->module_type == "MOD_MALLE" && $modInfo->module_id) {
					// Sélection de la malle personnelle.
					$urlReturn = CopixUrl::get ('malle||getMalle', array('id'=>$modInfo->module_id));
					return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);	
				}
			}
		}
		
		$folder = $this->getRequest ('folder', 0);
		$errors = $this->getRequest ('errors', array());

		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("READ",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
				//print_r($parent);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		} else {
		
		
			// On récupère les dossiers de ce répertoire
			$folders = $daoFolders->getFoldersInFolder($id, $folder);
			
			// On récupère les fichiers de ce répertoire
			$files = $daoFiles->getFilesInFolder($id, $folder);
			foreach ($files as $k=>$file) {	// Ajout des infos sur le type du fichier
				$mime = $malleService->getTypeInfos ($file->type, $file->fichier);
				$files[$k]->type_text = $mime['type_text'];
				$files[$k]->type_icon = $mime['type_icon'];
			}
			
			$dispMenu = true;
			if (substr($parent['type'],0,5)=='USER_')		{
				$title = CopixI18N::get ('malle|malle.perso'); $dispMenu = false;
			} else
				$title = $parent["nom"];
			
			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', $title);
			if ($dispMenu)
				$tpl->assign ('MENU', '<a href="'.CopixUrl::get (''.$parent["module"].'||go', array("id"=>$parent["id"])).'">'.CopixI18N::get ('malle|malle.backMalle').'</a>');
			
			$can = array(
				'file_download'=>$malleService->canMakeInMalle("FILE_DOWNLOAD",$mondroit),
				'file_upload'=>$malleService->canMakeInMalle("FILE_UPLOAD",$mondroit),
				'folder_create'=>$malleService->canMakeInMalle("FOLDER_CREATE",$mondroit),
				'item_delete'=>$malleService->canMakeInMalle("ITEM_DELETE",$mondroit),
				'item_move'=>$malleService->canMakeInMalle("ITEM_MOVE",$mondroit),
				'item_copy'=>$malleService->canMakeInMalle("ITEM_COPY",$mondroit),
				'item_rename'=>$malleService->canMakeInMalle("ITEM_RENAME",$mondroit),
				'item_downloadZip'=>$malleService->canMakeInMalle("ITEM_DOWNLOAD_ZIP",$mondroit),
			);
			//print_r($can);
			$tplMalle = & new CopixTpl ();
			$tplMalle->assign ('id', $id);
			$tplMalle->assign ('folder', $folder);
			$tplMalle->assign ('folders', $folders);
			$tplMalle->assign ('files', $files);
			$tplMalle->assign ('errors', $errors);
			$tplMalle->assign ('can', $can);
			$tplMalle->assign ('petitpoucet', CopixZone::process ('malle|petitpoucet', array('malle'=>$id, 'folder'=>$folder)));
			$tplMalle->assign ('combofoldersdest', CopixZone::process ('malle|combofolders', array('malle'=>$id, 'folder'=>$folder, 'fieldName'=>'folderDest', 'attribs'=>'style="width:100%;"')));
			$tplMalle->assign ('combofolders', CopixZone::process ('malle|combofolders', array('malle'=>$id, 'folder'=>$folder, 'fieldName'=>'folder', 'attribs'=>'ONCHANGE="this.form.submit();"', 'linesSup'=>array(0=>array('value'=>'', 'libelle'=>CopixI18N::get ('malle|malle.comboDirectAccess'))))));
			$tplMalle->assign ("uploadMaxSize", CopixConfig::get ('malle|uploadMaxSize') );

			//if ($mondroit>=PROFILE_CCV_PUBLISH)	$tplForum->assign ('canPublish', 1);
			//else																$tplForum->assign ('canPublish', 0);

			$result = $tplMalle->fetch('getmalle.tpl');
			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		}
	}


   /**
	 * Affichage d'une malle en popup, permettant de sélectionner un ou plusieurs fichiers à insérer dans une zone de saisie formaté wiki (blog, forum...)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/11/23
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire
	 * @param array $errors (option) Erreurs rencontrées
	 * @param string $field Champ utilisé dans la fenêtre "en-dessous"
	 * @param string $format Format de la zone de saisie (wiki, html, fckeditor...)
   */
   function processGetMallePopup () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest ('id', null);
		$folder = $this->getRequest ('folder', 0);
		$errors = $this->getRequest ('errors', array());
		$field = $this->getRequest ('field', null);
		$format = $this->getRequest ('format', null);
    
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("READ",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
				//print_r($parent);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		} else {
		
		
			// On récupère les dossiers de ce répertoire
			$folders = $daoFolders->getFoldersInFolder($id, $folder);
			
			// On récupère les fichiers de ce répertoire
			$files = $daoFiles->getFilesInFolder($id, $folder);
			foreach ($files as $k=>$file) {	// Ajout des infos sur le type du fichier
				$mime = $malleService->getTypeInfos ($file->type, $file->fichier);
				$files[$k]->type_text = $mime['type_text'];
				$files[$k]->type_icon = $mime['type_icon'];
			}
			
			$dispMenu = true;
			if (substr($parent['type'],0,5)=='USER_')		{
				$title = CopixI18N::get ('malle|malle.perso'); $dispMenu = false;
			} elseif (!$title)
				$title = $parent["nom"];

			$tplMalle = & new CopixTpl ();
			$tplMalle->assign ('TITLE_PAGE', CopixI18N::get ('malle|malle.popup.title'));
			$tplMalle->assign ('LANGUE', PluginI18n::getLang());
			$tplMalle->assign ('id', $id);
			$tplMalle->assign ('folder', $folder);
			$tplMalle->assign ('folders', $folders);
			$tplMalle->assign ('files', $files);
			$tplMalle->assign ('errors', $errors);
			$tplMalle->assign ('field', $field);
			$tplMalle->assign ('format', $format);
			$tplMalle->assign ('petitpoucet', CopixZone::process ('malle|petitpoucet', array('malle'=>$id, 'folder'=>$folder, 'action'=>'getMallePopup', 'field'=>$field, 'format'=>$format)));
			$tplMalle->assign ('combofolders', CopixZone::process ('malle|combofolders', array('malle'=>$id, 'folder'=>$folder, 'fieldName'=>'folder', 'attribs'=>'ONCHANGE="this.form.submit();"', 'linesSup'=>array(0=>array('value'=>'', 'libelle'=>CopixI18N::get ('malle|malle.comboDirectAccess'))))));
			$tplMalle->assign ("uploadMaxSize", CopixConfig::get ('malle|uploadMaxSize') );
			
			//if ($mondroit>=PROFILE_CCV_PUBLISH)	$tplForum->assign ('canPublish', 1);
			//else																$tplForum->assign ('canPublish', 0);
			
			// On déduit le chemin absolu
			$path = $_SERVER['PHP_SELF'];
			$pos = strrpos($path, "/");
			if ($pos !== false) {
				$abspath = substr($path,0,$pos+1);
			}
			$tplMalle->assign ('abspath', $abspath);	
			
			
			$result = $tplMalle->fetch('getmallepopup.tpl');
      echo $result;
			return new CopixActionReturn (COPIX_AR_NONE, 0);
		}
	}




   /**
   * Affichage du formulaire d'upload d'un fichier
	 * 
	 * Affiche l'ensemble des fichiers, dossiers et actions possibles de réaliser dans un dossier d'une malle
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/03
	 * @deprecated Mis de côté dans le desc, on permet en effet l'upload directement depuis l'affichage de la malle
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire destination
	 * @param array $errors (option) Erreurs rencontrées
   */
	function getUploadFile () {
	

		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		
		$id = $this->getRequest ('id', null);
		$folder = $this->getRequest ('folder', 0);
		$errors = $this->getRequest ('errors', array());
		
		$criticErrors = array();	
		
		if ($folder) {	// Upload dans un dossier
			$rFolder = $daoFolders->get($folder);
			//print_r($rFolder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			else {
				//print_r($rMessage);
				$mondroit = $kernelService->getLevel("MOD_MALLE", $rFolder->malle);
				if (!$malleService->canMakeInMalle("FILE_UPLOAD",$mondroit))
					$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			}
		}

		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		} else {
			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('malle|malle.newFile'));
			$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('|getMalle', array("id"=>$id, "folder"=>$folder)).'">'.CopixI18N::get ('malle|malle.backMalle').'</a>');

			$tplForm = & new CopixTpl ();
			$tplForm->assign ('id', $id);
			$tplForm->assign ('folder', $folder);
			$tplForm->assign ("errors", $errors);
			$tplForm->assign ('petitpoucet', CopixZone::process ('malle|petitpoucet', array('malle'=>$id, 'folder'=>$folder)));

			$result = $tplForm->fetch('getuploadfileform.tpl');
			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		}
	}


   /**
   * Soumission du formulaire d'upload d'un fichier
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/03
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire destination
	 * @param string $field (option) Si positionné, cas d'un upload en mode popup
	 * @param string $format (option) Si positionné, cas d'un upload en mode popup
   */
	function doUploadFile () {
	
		// Temporaire...
		ini_set( 'memory_limit', '32M' ); // Pb d'allocation mémoire
		ini_set( 'max_execution_time', '120' ); // Pb de temps de traitement

		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest ('id', null);
		$folder = $this->getRequest ('folder', 0);
		$field = $this->getRequest ('field', null);
		$format = $this->getRequest ('format', null);
		
		$criticErrors = $errors = array();	
		
		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
      $malle_cle = $rFolder->malle_cle;
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
			else
	      $malle_cle = $rMalle->cle;
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("FILE_UPLOAD",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
  
    
		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||getMalle', array('id'=>$id, 'folder'=>$folder))));

  	$urlReturn = ($field && $format) ? CopixUrl::get ('malle||getMallePopup', array('id'=>$id, 'folder'=>$folder, 'field'=>$field, 'format'=>$format)) : CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$folder));
    $processReturn = ($field) ? 'malle|malle::getMallePopup' : 'malle|malle::getMalle';

    if( ! is_uploaded_file( $_FILES['fichier']['tmp_name'] ) ) {
			switch( $_FILES['fichier']['error'] ) {
				case 0: //no error; possible file attack!
					$errors[] = CopixI18N::get ('malle|malle.error.upload_default', $_FILES['fichier']['type']);
					break;
				case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
					$errors[] = CopixI18N::get ('malle|malle.error.upload_toobig', $_FILES['fichier']['type']);
					break;
				case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
					$errors[] = CopixI18N::get ('malle|malle.error.upload_toobig', $_FILES['fichier']['type']);
					break;
				case 3: //uploaded file was only partially uploaded
					$errors[] = CopixI18N::get ('malle|malle.error.upload_partial', $_FILES['fichier']['type']);
					break;
				case 4: //no file was uploaded
					$errors[] = CopixI18N::get ('malle|malle.error.upload_nofile', $_FILES['fichier']['type']);
					break;
				default:
					$errors[] = CopixI18N::get ('malle|malle.error.upload_default', $_FILES['fichier']['type']);
					break;
			}
		}

    if (!$errors && $_FILES['fichier']['name']) {
  		
			// On ne permet pas le dézippage en mode popup
  		if (!$field && !$format && in_array($_FILES['fichier']['type'], array('application/zip', 'application/x-zip-compressed', 'application/forcedownload', 'multipart/x-zip')))
  			return CopixActionGroup::process ('malle|malle::getUploadFileZip', array ('id'=>$id, 'folder'=>$folder, 'file'=>$_FILES['fichier']));
  		
  		// On insère d'abord dans la base
  		$new = CopixDAOFactory::createRecord("malle|malle_files");
  		$new->malle = $id;
  		$new->folder = $folder;
  		$new->nom = $_FILES['fichier']['name'];
  		$new->fichier = Kernel::simpleName($_FILES['fichier']['name']);
  		$new->taille = $_FILES['fichier']['size'];
  		$new->type = $_FILES['fichier']['type'];
  		$new->cle = $malleService->createKey();
  		$new->date_upload = date("Y-m-d H:i:s");
  		$daoFiles->insert ($new);
  		
  		if ($new->id!==NULL) {
  			$name = $new->id."_".$new->fichier;
  			$uploadFrom = $_FILES['fichier']['tmp_name'];
          $uploadTo = realpath('./static/malle').'/'.$id.'_'.$malle_cle.'/'.($name); 
          if (@move_uploaded_file($uploadFrom, $uploadTo)) {
  				$malleService->update_infos_for_folder ($id, $folder);
  			} else {
  				$daoFiles->delete ($new->id);
  				$errors[] = CopixI18N::get ('malle|malle.error.uploadFile');
  			}
        } else {	// Prob d'insertion dans la base
  			$errors[] = CopixI18N::get ('malle|malle.error.uploadFileDB');
  		}
  	}

    if ($errors)
  		return CopixActionGroup::process ($processReturn, array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors, 'field'=>$field));
  	return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);	
	}


   /**
	 * Ajout d'un zip : on détaille son contenu et on demande s'il doit être dézippé ou pas
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/20
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire
	 * @param array $file Toutes les infos du fichier ajouté (issues de $_FILES)
   */
	function processGetUploadFileZip () {

		$id = $this->getRequest('id');
		$folder = $this->getRequest('folder');
		$file = $this->getRequest('file');
		
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = Kernel::getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("FILE_UPLOAD",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
			}
		}
		
		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		
		$name = $file['name'];
		
		$archive = new PclZip($file['tmp_name']);
		
		$files = $archive->listContent();
		//print_r($files);
		//print_r($file);
		
		if (move_uploaded_file($file['tmp_name'], $malleService->getTmpFolder().'/'.$file['name'])) {
			
			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('malle|malle.zip.title'));
			$tpl->assign ('id', $id);
			$tpl->assign ('folder', $folder);
			$tpl->assign ('file', $file);
			$tpl->assign ('files', $files);
			$tpl->assign ('unzip', array('values'=>array(1,0)));
			$tpl->assign ('uploadMaxSize', CopixConfig::get ('malle|uploadMaxSize'));

			$result = $tpl->fetch('getuploadfilezip.tpl');
			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
			
		}
		
		$criticErrors = array(CopixI18N::get ('malle|malle.error.unzipMoveFile'));
		return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||', array('id'=>$id, 'folder'=>$folder))));
		
	}
	


   /**
	 * Ajout effectif d'un zip : on le décompresse ou le copie simplement
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/20
	 * @param string $file Nom du fichier ZIP
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire
	 * @param integer $unzip 1 si le fichier est à dézippé, 0 s'il est juste à uploader comme ça
   */
	function doUploadFileZip () {

		$id = $this->getRequest('id');
		$folder = $this->getRequest('folder');
		$file = $this->getRequest('file');
		$unzip = $this->getRequest('unzip');
		
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
      $malle_cle = $rFolder->malle_cle;
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
      $malle_cle = $rMalle->cle;
		}
		if (!$criticErrors) {
			$mondroit = Kernel::getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("FILE_UPLOAD",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
			}
		}

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));



		$errors = array();
		
		if ($unzip) {	// On le dézippe
		
			$unzipFolder = $malleService->getTmpFolder().'/zip'.mt_rand(1000,9999999);
			if (!@mkdir ($unzipFolder)) {
				$errors[] = CopixI18N::get ('malle|malle.error.unzipTmpFolderCreate');
			} else
				@chmod ($unzipFolder, 0777);
			
			//$unzipFile = $file;
			//print_r("unzipFolder=$unzipFolder<br/>");
			
			if (!$errors) {
				$archive = new PclZip($malleService->getTmpFolder().'/'.$file);
				//print_r($archive);
			
				// On parcourt les fichiers, en supprimant les trop gros fichiers
				$list = $archive->listContent();
				//print_r($list);
				$okIndex = array();
				foreach ($list as $k=>$f) {
					//print_r("k=$k");
					if ($f['size']>CopixConfig::get ('malle|uploadMaxSize')) {
					} else
						$okIndex[] = $k;
				}
			
				$list = $archive->listContent();
				//print_r($list);
				//print_r($okIndex);
			
				//print_r($archive->properties());
				//die("Prop : ");
				//print_r("Error : ".$archive->errorInfo(true));
			
				//die(CopixConfig::get ('malle|uploadMaxSize'));
			}
					
			if (!$errors && $okIndex) {
			
				$extract = $archive->extract(
					PCLZIP_OPT_PATH, $unzipFolder,
					PCLZIP_OPT_BY_INDEX, implode(',',$okIndex), 
					PCLZIP_OPT_REMOVE_ALL_PATH
				);
			
			} else
				$extract = 0;
			//die("extract=$extract<br/>");

			if (!$errors && !$extract == 0)
			{
				
				
				// On crée le dossier
				$new = CopixDAOFactory::createRecord("malle|malle_folders");
				$new->malle = $id;
				$new->parent = $folder;
				$new->nom = $file;
				$new->date_creation = date("Y-m-d H:i:s");
				$new->nb_folders = 0;
				$new->nb_files = 0;
				$new->taille = 0;
				$daoFolders->insert ($new);
				
				if ($new->id!==NULL) {
					$list = $archive->listContent();
					//print_r($list);
					foreach ($list as $f) {
						if ($f['folder']) continue;
						
						$pos = strrpos($f['filename'], '/');
						if ($pos === false)	$name = $f['filename'];
						else								$name = substr($f['filename'],$pos+1);
						
						if (!file_exists($unzipFolder.'/'.$name)) continue;	// Si le fichier d'origine n'existe pas (ne devrait pas arriver)

						// On insère d'abord dans la base
						$newf = CopixDAOFactory::createRecord("malle|malle_files");
						$newf->malle = $id;
						$newf->folder = $new->id;
						$newf->nom = $name;
						$newf->fichier = $name;
						$newf->taille = $f['size'];
						$newf->cle = $malleService->createKey();
						
						if (function_exists('mime_content_type'))
							$newf->type = mime_content_type ($unzipFolder.'/'.$name);
						else {
							$point = strrpos ($name, ".");
							if ($point !== false) {
								$ext = substr($name,$point+1);
								$newf->type = strtolower($ext);
							} else
								$newf->type = '';
						}

						$newf->date_upload = date("Y-m-d H:i:s");
						$daoFiles->insert ($newf);
				
						if ($newf->id!==NULL) {
							$name2 = $newf->id."_".$newf->fichier;
							$uploadFrom = $unzipFolder.'/'.$name;
	      	    $uploadTo = realpath('./static/malle').'/'.$id.'_'.$malle_cle.'/'.($name2); 
							//echo " / uploadFrom=$uploadFrom";
							//echo " / uploadTo=$uploadTo";
		          if (copy($uploadFrom, $uploadTo)) {
								
							} else {
								$daoFiles->delete ($newf->id);
								$errors[] = CopixI18N::get ('malle|malle.error.uploadFile');
								return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
							}
		        } else {	// Prob d'insertion dans la base
							$errors[] = CopixI18N::get ('malle|malle.error.uploadFileDB');
							return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
						}
					} // Fin foreach
					
					$malleService->update_infos_for_folder ($id, $new->id);
		

	      } else {	// Prob d'insertion dans la base
					$errors[] = CopixI18N::get ('malle|malle.error.createFolder');
				}
				$malleService->deleteDir ($unzipFolder); // Suppression du dossier temporaire
			} else {
				$errors[] = CopixI18N::get ('malle|malle.error.unzipFile');
			}

		} else {	// On le copie juste
						
						$name = $file;
						
						$uploadFrom = $malleService->getTmpFolder().'/'.$name;
						
						// On insère d'abord dans la base
						$newf = CopixDAOFactory::createRecord("malle|malle_files");
						$newf->malle = $id;
						$newf->folder = $folder;
						$newf->nom = $name;
						$newf->fichier = $name;
						$newf->taille = filesize ($uploadFrom);
						$newf->type = 'application/zip';
						$newf->date_upload = date("Y-m-d H:i:s");
            $newf->cle = $malleService->createKey();
						$daoFiles->insert ($newf);
				    
						if ($newf->id!==NULL) {
							$name2 = $newf->id."_".$newf->fichier;
							
	      	    $uploadTo = realpath('./static/malle').'/'.$id.'_'.$malle_cle.'/'.($name2); 
		          if (copy($uploadFrom, $uploadTo)) {
								$malleService->update_infos_for_folder ($id, $folder);
							} else {
								$daoFiles->delete ($new->id);
								$errors[] = CopixI18N::get ('malle|malle.error.uploadFile');
								return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
							}
		        } else {	// Prob d'insertion dans la base
							$errors[] = CopixI18N::get ('malle|malle.error.uploadFileDB');
							return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
						}


		}
		
		// On efface le ZIP
		@unlink ($malleService->getTmpFolder().'/'.$file);
		
		if ($errors)
			return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
		else
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$folder)));	

	}





   /**
   * Soumission du formulaire de création d'un dossier
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/02
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire destination
	 * @param sting $new_folder Nom du répertoire à créer
   */
	function doAddFolder () {
	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		
		$id = $this->getRequest ('id', null);
		$folder = $this->getRequest ('folder', 0);
		$new_folder = $this->getRequest ('new_folder', null);
		
		$criticErrors = array();	
		
		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("FOLDER_CREATE",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		} else {
			
			$errors = array();
			
			if (!$new_folder)
				$errors[] = CopixI18N::get ('malle|malle.error.typeFolder');
			
			if (!$errors) {
				// On insère dans la base
				$new = CopixDAOFactory::createRecord("malle|malle_folders");
				$new->malle = $id;
				$new->parent = $folder;
				$new->nom = $new_folder;
				$new->date_creation = date("Y-m-d H:i:s");
				$new->nb_folders = 0;
				$new->nb_files = 0;
				$new->taille = 0;
				$daoFolders->insert ($new);
				
				if ($new->id!==NULL) {
					$malleService->update_infos_for_folder ($id, $folder);
					$urlReturn = CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$folder));
					return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);	
	      } else {	// Prob d'insertion dans la base
					$errors[] = CopixI18N::get ('malle|malle.error.createFolder');
				}
			}

			return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));

		}
	}


   /**
   * Téléchargement (download) d'un fichier d'une malle
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/05
	 * @param integer $id Id de la malle
	 * @param integer $file Id du fichier
   */
	function doDownloadFile () {

		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest ('id', null);
		$file = $this->getRequest ('file', null);
		
		$criticErrors = array();
		
		$rFile = $daoFiles->get($file);
		if (!$rFile)
			$criticErrors[] = CopixI18N::get ('malle|malle.error.noFile');
		else {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $rFile->malle );
			//print_r($mondroit);
			if (!$malleService->canMakeInMalle("FILE_DOWNLOAD",$mondroit))
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
		}

		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		}
		
		$fichier = $rFile->id.'_'.$rFile->fichier;

		$fullFile = realpath('./static/malle').'/'.$rFile->malle.'_'.$rFile->malle_cle.'/'.($fichier);
		$errors = array();
		if (!file_exists($fullFile))
			$errors[] = CopixI18N::get ('malle|malle.error.noFile');
		//print_r($rFile);
		//print_r($errors);
		
		//die();
		
		if ($errors) {
			//$urlReturn = CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$rFile->folder));
			//return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
			return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$rFile->folder, 'errors'=>$errors));
		}
		return new CopixActionReturn (COPIX_AR_DOWNLOAD, $fullFile, '"'.$rFile->nom.'"');
	}


   /**
   * Une action sur une liste de fichiers/dossiers
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/05
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du répertoire
	 * @param array $files Fichier sélectionnés
	 * @param array $folders Dossiers sélectionnés
	 * @param integer $folderDest Id du dossier destination (si copie ou déplacement)
	 * @param string $actionDelete Si l'action est "supprimer"
	 * @param string $actionMove Si l'action est "déplacer"
	 * @param string $actionCopy Si l'action est "copier"
	 * @param string $actionRename Si l'action est "renommer"
	 * @param string $actionDownloadZip Si l'action est "télécharger en zip"
   */
   function doAction () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest ('id', null);
		$folder = $this->getRequest ('folder', 0);
		$files = $this->getRequest ('files', array());
		$folders = $this->getRequest ('folders', array());
		$folderDest = $this->getRequest ('folderDest', 0);

		$actionDelete = $this->getRequest ('actionDelete', null);
		$actionMove = $this->getRequest ('actionMove', null);
		$actionCopy = $this->getRequest ('actionCopy', null);
		$actionRename = $this->getRequest ('actionRename', null);
		$actionDownloadZip = $this->getRequest ('actionDownloadZip', null);
		
		if ($actionRename)
			return CopixActionGroup::process ('malle|malle::getActionRename', array ('id'=>$id, 'folder'=>$folder, 'files'=>$files, 'folders'=>$folders));
		elseif ($actionDownloadZip)
			return CopixActionGroup::process ('malle|malle::doActionDownloadZip', array ('id'=>$id, 'folder'=>$folder, 'files'=>$files, 'folders'=>$folders));

		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if ( ($actionDelete && !$malleService->canMakeInMalle("ITEM_DELETE",$mondroit)) || 
					 ($actionMove   && !$malleService->canMakeInMalle("ITEM_MOVE",$mondroit)) || 
					 ($actionCopy   && !$malleService->canMakeInMalle("ITEM_COPY",$mondroit)) )
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
		
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		} else {

			if ($actionDelete) {	// ================ SUPPRESSION =================
			
				// Parcours des fichiers
				foreach ($files as $item) {
					$oFile = $daoFiles->get ($item);
					if ($oFile && $oFile->malle==$id && $oFile->folder==$folder) {
						$del = $malleService->deleteFile ($oFile);
					}
				}
				
				// Parcours des dossiers
				foreach ($folders as $item) {
					$oFolder = $daoFolders->get ($item);
					if ($oFolder && $oFolder->malle==$id && $oFolder->parent==$folder) {
						$del = $malleService->deleteFolder ($oFolder);
					}
				}
			}
			
			elseif ($actionMove) {	// ================ Déplacer =================
				
				// Parcours des fichiers
				foreach ($files as $item) {
					$oFile = $daoFiles->get ($item);
					if ($folder!=$folderDest && $oFile && $oFile->malle==$id && $oFile->folder==$folder) {
						// On regarde si ce fichier n'est pas déjà présent dans le répertoire destination
						$deja = $daoFiles->isFichierInFolder($oFile->fichier, $folderDest);
						if (!$deja) {
							$oFile->folder = $folderDest;
							$daoFiles->update ($oFile);
						} else {
							$errors[] = CopixI18N::get ('malle|malle.error.moveFileAlready', array('file'=>$oFile->nom));
						}
					}
				}

				// Parcours des dossiers
				foreach ($folders as $item) {
					$oFolder = $daoFolders->get ($item);
					if ($folder!=$folderDest && $oFolder && $oFolder->malle==$id && $oFolder->parent==$folder) {
						//print_r($oFolder);
						// On vérifie que la destination n'est pas "sous" la source
						$under = $malleService->isFolderUnderFolder ($folderDest, $item);
						if (!$under) {	// OK
							// On vérifie qu'un dossier de même nom n'existe pas déjà dans la destination
							$deja = $daoFolders->isFolderInFolder($oFolder->nom, $folderDest);
							if (!$deja) {
								$oFolder->parent = $folderDest;
								$daoFolders->update ($oFolder);
							} else
								$errors[] = CopixI18N::get ('malle|malle.error.moveFolderAlready', array('folder'=>$oFolder->nom));
						} else {
							$errors[] = CopixI18N::get ('malle|malle.error.moveFolderUnder', array('folder'=>$oFolder->nom));
						}
					}
				}
			}
			
			elseif ($actionCopy) {	// ================ Copier =================
				
				// Parcours des fichiers
				foreach ($files as $item) {
					$oFile = $daoFiles->get ($item);
					if ($folder!=$folderDest && $oFile && $oFile->malle==$id && $oFile->folder==$folder) {
						// On regarde si ce fichier n'est pas déjà présent dans le répertoire destination
						$deja = $daoFiles->isFichierInFolder($oFile->fichier, $folderDest);
						//print_r($deja);
						if (!$deja) {
							$malleService->copyFile ($oFile, $folderDest);
						} else {
							$errors[] = CopixI18N::get ('malle|malle.error.moveFileAlready', array('file'=>$oFile->nom));
						}
					}
				}
				
				// Parcours des dossiers
				foreach ($folders as $item) {
					$oFolder = $daoFolders->get ($item);
					if ($folder!=$folderDest && $oFolder && $oFolder->malle==$id && $oFolder->parent==$folder) {
						//print_r($oFolder);
						// On vérifie que la destination n'est pas "sous" la source
						$under = $malleService->isFolderUnderFolder ($folderDest, $item);
						if (!$under) {
							// On vérifie qu'un dossier de même nom n'existe pas déjà dans la destination
							$deja = $daoFolders->isFolderInFolder($oFolder->nom, $folderDest);
							if (!$deja) {
								$copy = $malleService->copyFolder ($oFolder, $folderDest);
								if ($copy)
									$malleService->update_infos_for_folder ($id, $copy);
							} else
								$errors[] = CopixI18N::get ('malle|malle.error.moveFolderAlready', array('folder'=>$oFolder->nom));
						} else {
							$errors[] = CopixI18N::get ('malle|malle.error.moveFolderUnder', array('folder'=>$oFolder->nom));
						}
					}
				}
				//die();
			}

			if ($files || $folders) {
				$malleService->update_infos_for_folder ($id, $folder);
				if ($folderDest)
					$malleService->update_infos_for_folder ($id, $folderDest);
			} else
				$errors[] = CopixI18N::get ('malle|malle.error.noSelection');
			if ( ($actionCopy || $actionMove) && $folder==$folderDest)
				$errors[] = CopixI18N::get ('malle|malle.error.noSelection');
			//print_r($errors);
			
			if ($errors)
				return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
			else {
				$urlReturn = CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$folder));
				return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
			}
		}
	}


   /**
   * Affichage de la liste des fichiers/dossiers à renommer
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/03
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du dossier
	 * @param array $files Liste des fichiers à renommer
	 * @param array $folders Liste des dossiers à renommer
   */
   function getActionRename () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest('id', null);
		$folder = $this->getRequest('folder', null);
		$files = $this->getRequest('files', array());
		$folders = $this->getRequest('folders', array());
		
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if ( !$malleService->canMakeInMalle("ITEM_RENAME",$mondroit) )
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				//$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		}
		
			$listFiles = array();
			foreach ($files as $f) {
				$r = $daoFiles->get($f);
				$mime = $malleService->getTypeInfos ($r->type, $r->fichier);
				$r->type_text = $mime['type_text'];
				$r->type_icon = $mime['type_icon'];
				$listFiles[] = $r;
			}
			$listFolders = array();
			foreach ($folders as $f) {
				$r = $daoFolders->get($f);
				$listFolders[] = $r;
			}

			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('malle|malle.btn.rename'));
			
			$tplList = & new CopixTpl ();
			$tplList->assign ('id', $id);
			$tplList->assign ('folder', $folder);
			$tplList->assign ('folders', $listFolders);
			$tplList->assign ('files', $listFiles);
			$result = $tplList->fetch('getactionrename.tpl');

			$tpl->assign ('MAIN', $result);
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);



	}




   /**
   * Renommage effectif de fichiers/dossiers
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/03
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du dossier
	 * @param array $newFiles Liste des nouveaux noms de fichiers
	 * @param array $newFolders Liste des nouveaux noms de dossiers
   */
   function processDoActionRename () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest('id', null);
		$folder = $this->getRequest('folder', null);
		$newFiles = $this->getRequest('newFiles', array());
		$newFolders = $this->getRequest('newFolders', array());
		
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if ( !$malleService->canMakeInMalle("ITEM_RENAME",$mondroit) )
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				//$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		}
		
		foreach ($newFiles as $k=>$name) {
			$r = $daoFiles->get($k);
			if ($r && $r->malle==$id && $r->folder==$folder) {
				//print_r ($r);
				$r->nom = $name;
				$daoFiles->update ($r);
			}
		}
		foreach ($newFolders as $k=>$name) {
			$r = $daoFolders->get($k);
			if ($r && $r->malle==$id && $r->parent==$folder) {
				//print_r ($r);
				$r->nom = $name;
				$daoFolders->update ($r);
			}
		}


		$urlReturn = CopixUrl::get ('malle||getMalle', array('id'=>$id, 'folder'=>$folder));
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
/*
		if ($errors) {
				//return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
		} else {
				
		}
*/










	}




   /**
   * Téléchargement (download) de plusieurs fichiers en un seul fichier ZIP
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/09
	 * @param integer $id Id de la malle
	 * @param integer $folder Id du dossier
	 * @param array $files Liste des fichiers sélectionnés
	 * @param array $folders Liste des dossiers sélectionnés
   */
   function processDoActionDownloadZip () {
	 	
		$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
	 	$daoMalles = CopixDAOFactory::create("malle|malle_malles");
	 	$daoFolders = CopixDAOFactory::create("malle|malle_folders");
	 	$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		$id = $this->getRequest('id', null);
		$folder = $this->getRequest('folder', null);
		$files = $this->getRequest('files', array());
		$folders = $this->getRequest('folders', array());
		
		$criticErrors = array();

		if ($folder) {
			$rFolder = $daoFolders->get($folder);
			if (!$rFolder)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
			elseif ($rFolder->malle != $id)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noFolder');
      $malle_cle = $rFolder->malle_cle;
		} else {
			$rMalle = $daoMalles->get($id);
			if (!$rMalle)
				$criticErrors[] = CopixI18N::get ('malle|malle.error.noMalle');
      $malle_cle = $rMalle->cle;
		}
		if (!$criticErrors) {
			$mondroit = $kernelService->getLevel( "MOD_MALLE", $id );
			//print_r($mondroit);
			if ( !$malleService->canMakeInMalle("ITEM_DOWNLOAD_ZIP",$mondroit) )
				$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			else {
				//$parent = $kernelService->getModParentInfo( "MOD_MALLE", $id);
			}
		}
		
		if ($criticErrors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('malle||')));
		}
		
		$errors = array();
		
		$zipFile = 'archive'.mt_rand(100000,9999999).'.zip';
		
		if (file_exists($malleService->getTmpFolder().'/'.$zipFile))
			unlink ($malleService->getTmpFolder().'/'.$zipFile);

		$archive = new PclZip($malleService->getTmpFolder().'/'.$zipFile);
		
		//print_r($files);
		
		$zipFiles = array();
		foreach ($files as $k) {
			$r = $daoFiles->get($k);
			if ($r && $r->malle==$id && $r->folder==$folder) {
				//print_r($r);
				$zipFiles[] = $r->id.'_'.$r->fichier;
			}
		}
		
		$zipFiles2 = array();
		foreach ($folders as $k) {
			$r = $daoFolders->get($k);
			if ($r && $r->malle==$id && $r->parent==$folder) {
				//print_r($r);
				$malleService->getFilesInFolder ($id, $k, $zipFiles2);
			}
		}
		//print_r($zipFiles2);
		
		
		if ($zipFiles) {	// Fichiers
			foreach ($zipFiles as $f) {
				//print_r($f);
				$fullFile = realpath('./static/malle').'/'.$id.'_'.$malle_cle.'/'.$f;
				//print_r($fullFile);
				//$add = $archive->add($fullFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_ATT_FILE_NEW_SHORT_NAME, 'toto.txt');
				$add = $archive->add (array(
					PCLZIP_ATT_FILE_NAME => $fullFile,
					//PCLZIP_ATT_FILE_NEW_FULL_NAME => '818_aom.jpg',
					//PCLZIP_ATT_FILE_NEW_SHORT_NAME => '818_aom.jpg',
					//PCLZIP_ATT_FILE_NEW_SHORT_NAME => $fullFile2,
				), PCLZIP_OPT_REMOVE_ALL_PATH);

				if ($add == 0) { // , 
    			$errors[] = $archive->errorInfo(true);
  			}
			}
		}
				

		if ($zipFiles2) {	// Dossiers
			//print_r($zipFiles2);
			//print_r($fullFile);
			foreach ($zipFiles2 as $f) {
				$fullFile = realpath('./static/malle').'/'.$id.'_'.$malle_cle.'/'.$f['id'].'_'.($f['fichier']);
				//print_r($fullFile);
				
				if ($archive->add($fullFile, PCLZIP_OPT_ADD_PATH, $f['folder_path'], PCLZIP_OPT_REMOVE_ALL_PATH) == 0) { // , 
    			$errors[] = $archive->errorInfo(true);
  			}
			}
		}


		//print_r($archive);
		$list = $archive->listContent();
		//print_r($list);
		
		if ($errors) {
			//print_r($errors);
			return CopixActionGroup::process ('malle|malle::getMalle', array ('id'=>$id, 'folder'=>$folder, 'errors'=>$errors));
		} else {
			//return new CopixActionReturn (COPIX_AR_NONE, 0);
			return new CopixActionReturn (COPIX_AR_DOWNLOAD, $malleService->getTmpFolder().'/'.$zipFile, $zipFile);
		}
	}

}



?>
