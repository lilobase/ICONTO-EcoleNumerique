<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: album.actiongroup.php,v 1.53 2008-10-20 08:04:43 fmossmann Exp $
* @author   Fr�d�ric Mossmann
* @copyright 2005 CDC-TIC
* @link      http://www.cdc-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


require_once (COPIX_MODULE_PATH.'album/'.COPIX_CLASSES_DIR.'album.class.php');
require_once (COPIX_UTILS_PATH.'pclzip.lib.php');

/**
 * handle the photo gallery
 */
class ActionGroupAlbum extends CopixActionGroup {

	/**
	 * Affiche la liste des albums
	 *
	 * Cette page n'est plus utilis�e depuis que l'album est li� � un noeud.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 * @deprecated 2006
	 */
	function getList () {
		$tpl = & new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.main'));
		$tpl->assign ('MAIN', CopixZone::process ('album|albumlist'));

/*
		$tpl->assign ('MENU', 
			(CopixUserProfile::CanDo("modules|album","publier")?'<li><a href="">Cr�er un album</a></li>':'')
			);
*/

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * Affiche le contenu d'un album
	 *
	 * V�rifie l'existance et les droits d'un album, et affiche les vignettes.
	 * V�rifie �galement la pr�sence de toutes les vignettes, et lance la g�n�ration au cas ou.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getAlbum () {
		$tpl = & new CopixTpl ();
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		if( !isset( $this->vars["album_id"] )   ||
		    trim($this->vars["album_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["album_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
						'back'=>CopixUrl::get('||')));
		}
		
		$album_id = $this->vars["album_id"];
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_READ ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		
		$tplAlbum = & new CopixTpl ();
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		if(!$album) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array (	'message'=>CopixI18N::get ('album.error.noalbum'),
			        'back'=>CopixUrl::get('||')));
		}

		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = "Racine";
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		
		// V�rification des vignettes
		unset($_SESSION['modules']['album']['vignettes'][$album->album_id.'_'.$album->album_cle]);
		unset($_SESSION['modules']['album']['vignettes']['nb-'.$album->album_id.'_'.$album->album_cle]);
		$errors = Album::checkThumbnails( $album_id, $album->album_id.'_'.$album->album_cle );
		if( $errors > 0 ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|vignettes', array('album'=>$album_id,'dossier'=>$dossier_id,'key'=>$album->album_id.'_'.$album->album_cle) ) );
		}
		
		
		
		$parent = $kernel_service->getModParentInfo( "MOD_ALBUM", $album_id);
		$album->parent = $parent;
		
		$tplAlbum->assign ("album", $album);
		$tplAlbum->assign ("dossier", $dossier);
		
		$dossiermenu = array();
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
			$dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.newfolder.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_new\');');
			if( $dossier_id > 0 ) {
				$dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.rename.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_rename\');');
				$dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.move.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_move\');');
				$dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.delete.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_delete\');');
			}
			$dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.movephotos.menu'), 'url'=>CopixUrl::get ('album|default|editphotos', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) )     );
		}
		
		$dossiers_list = Album::getFoldersTree( $album_id );

		$tplAlbum->assign ("dossiers", CopixZone::process (
			'album|dossierstree',
			array(
				'tree'=>$dossiers_list,
				'mode'=>'combo',
				'album_id'=>$album_id,
				'dossier_id'=>$dossier_id,
				'dossier'=>$dossier,
				'dossiermenu'=>$dossiermenu,
			)
		) );

		$photo_dao = CopixDAOFactory::create("photo");
		$photolist = $photo_dao->findAllByAlbumAndFolder($album_id,$dossier_id);
		$tplAlbum->assign ("photolist", $photolist);
		
		$dossiers_childs = Album::getFoldersTree( $album_id, $dossier_id );
		$tplAlbum->assign ("dossierlist", $dossiers_childs);
		
		foreach( $photolist AS $photothumb ) {
			Album::createThumbnails(
				$photothumb->album_id.'_'.$photothumb->album_cle,
				$photothumb->photo_id.'_'.$photothumb->photo_cle,
				$photothumb->photo_ext );
		}
		
		$tplAlbum->assign ('album_thumbsize', "_s128" );
		$tplAlbum->assign ('album_thumbsize_height', "128" );
		$tplAlbum->assign ('album_thumbsize_width', "128" );
		$result = $tplAlbum->fetch("album.tpl");

		$tpl->assign ('MAIN', $result);
		
		$album_nom = $album->parent["nom"];
		if( $dossier_id > 0 ) $album_nom .= " / ".$dossier->dossier_nom;
		$tpl->assign ('TITLE_PAGE', CopixI18N::get('album.title.album', array($album_nom) ));
		
		$menu = array();
		
		$simpleviewer_path = realpath("simpleviewer");
		
		if( file_exists($simpleviewer_path.'/viewer.swf') && file_exists($simpleviewer_path.'/flashobject.js') ) {
			$path2data = realpath("static");
			$path2album = $path2data."/album/".$album_id."_".$album->album_cle;

			$addtopath = '';
			if( $dossier_id > 0 ) $addtopath = '/'.$dossier->dossier_id."_".$dossier->dossier_cle;

			$path2album .= $addtopath;
			if( file_exists($path2album.'/imageData.xml') ) {
				$menu[] = array(
					'txt' => CopixI18N::get ('album|album.menu.viewfolder'),
					'url' => CopixUrl::get ().'static/album/'.$album_id."_".$album->album_cle.$addtopath,
					'target' => '_blank'
				);
				if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
					$menu[] = array(
						'txt' => CopixI18N::get ('album|album.menu.deletefolder'),
						'url' => CopixUrl::get ('album||depublier', array("album_id"=>$album->album_id,"dossier_id"=>$dossier->dossier_id))
					);
				}
			}
			if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
				$menu[] = array(
					'txt' => CopixI18N::get ('album|album.menu.publishfolder'),
					'url' => CopixUrl::get ('album||publier', array("album_id"=>$album->album_id,"dossier_id"=>$dossier_id))
				);
			}
		}
		// endif( simpleviewer )
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
			$menu[] = array(
				'txt' => CopixI18N::get ('album|album.menu.addzip'),
				'url' => CopixUrl::get ('album||addzip', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id))
			);
			$menu[] = array(
				'txt' => CopixI18N::get ('album|album.menu.addphoto'), // 'Ajouter une photo',
				'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id))
			);
		}
		
		$returntoparent = Kernel::menuReturntoParent( "MOD_ALBUM", $album_id );
		if( $returntoparent ) $menu[] = $returntoparent;


		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}


	/**
	 * Confirmation d'effacement
	 *
	 * Demande la confirmation avant l'effacement d'un album.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getDelAlbum () {
		$tpl = & new CopixTpl ();
		
		if( Kernel::getLevel( "MOD_ALBUM", $this->vars["album_id"] ) < PROFILE_CCV_ADMIN ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}

		$service = & CopixClassesFactory::Create ('Album');
		$photo = $service->delAlbum( $this->vars["album_id"] );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * Effacement d'un album.
	 *
	 * Non utilis�, depuis que l'album est li� � un noeud (effacement via une classe).
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doDelAlbum () {
		
		if( Kernel::getLevel( "MOD_ALBUM", $this->vars["album_id"] ) < PROFILE_CCV_ADMIN ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		// TODO
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
	}

	/**
	 * Affichage une photo.
	 *
	 * Affiche la photo d'un album, avec les liens vers les pr�c�dentes et suivantes.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getPhoto () {
		$tpl = & new CopixTpl ();
		
		if( !isset( $this->vars["photo_id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
		}
		
		$photo_id = $this->vars["photo_id"];
		
		$tplAlbum = & new CopixTpl ();
		
		
		$service = & CopixClassesFactory::Create ('Album');
		$photo = $service->getPhoto( $photo_id );
		if( $photo === false ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noimage'),
						'back'=>CopixUrl::get('||')));
		}
		
		if( Kernel::getLevel( "MOD_ALBUM", $photo->album_id ) < PROFILE_CCV_READ ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$photo->prev = $service->getPrevPhoto( $photo->album_id, $photo->photo_dossier, $photo_id );
		$photo->next = $service->getNextPhoto( $photo->album_id, $photo->photo_dossier, $photo_id );
		
		$tplAlbum->assign ("photo", $photo);
		
		$tplAlbum->assign ('photo_size', '_'.CopixConfig::get ('album|photo_size'));
		$result = $tplAlbum->fetch("photo.tpl");
		
		$tpl->assign ('MAIN', $result);
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get('album.title.photo', array(htmlentities($photo->photo_nom)) ));
		
		$menu = array();
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.gotoalbum'),
			'url' => CopixUrl::get ('album||album', array("album_id"=>$photo->album_id, "dossier_id"=>$photo->photo_dossier))
		);
		if( Kernel::getLevel( "MOD_ALBUM", $photo->album_id ) >= PROFILE_CCV_PUBLISH ) {
			$menu[] = array(
				'txt' => CopixI18N::get ('album.menu.addphoto'),
				'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$photo->album_id, "dossier_id"=>$photo->photo_dossier))
			);
			$menu[] = array(
				'txt' => CopixI18N::get ('album.menu.delphoto'),
				'url' => CopixUrl::get ('album||delphoto', array("photo_id"=>$photo->photo_id))
			);
		}
		
		$tpl->assign ('MENU', $menu );

		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}


	/**
	 * Formulaire d'ajout de photo.
	 *
	 * Affiche le formulaire d'upload d'une photo, avec nom et commentaire.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getAddPhoto () {
		$tpl = & new CopixTpl ();
		
		if( !isset( $this->vars["album_id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
		}
		
		$album_id = $this->vars["album_id"];
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;
				
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$tplAddPhoto = & new CopixTpl ();

		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		$tplAddPhoto->assign ("album", $album);

		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = "Racine";
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		$tplAddPhoto->assign ("dossier", $dossier);
		
		$tplAddPhoto->assign ("file_size_photo", CopixConfig::get ('album|file_size_photo') );
		
		$result = $tplAddPhoto->fetch("addphoto.tpl");
		
		$tpl->assign ('MAIN', $result);
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.menu.addphoto'));

		$menu = array();
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.addzip'),
			'url' => CopixUrl::get ('album||addzip', array("album_id"=>$album->album_id, "dossier_id"=>$dossier->dossier_id))
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.gotoalbum'),
			'url' => CopixUrl::get ('album||album', array("album_id"=>$album->album_id, "dossier_id"=>$dossier->dossier_id))
		);
		$tpl->assign ('MENU', $menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * Ajout une photo dans un album.
	 *
	 * Apr�s v�rification des droits et de l'image, engeristrement dans la base et copie du fichier dans le r�pertoire.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doAddPhoto () {
		
		// Temporaire...
		@ini_set( 'memory_limit', '64M' ); // Pb d'allocation m�moire
		@ini_set( 'max_execution_time', '120' ); // Pd de temps de traitement
		
		if( !isset( $this->vars["album_id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
		}
		
		if( Kernel::getLevel( "MOD_ALBUM", $this->vars["album_id"] ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		if( ! is_uploaded_file( $_FILES['fichier']['tmp_name'] ) ) {
			$message = '';
			switch( $_FILES['fichier']['error'] ) {
				case 0: //no error; possible file attack!
					$message = CopixI18N::get ('album|album.error.upload_default', $_FILES['fichier']['type']);
					break;
				case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
					$message = CopixI18N::get ('album|album.error.upload_toobig', $_FILES['fichier']['type']);
					break;
				case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
					$message = CopixI18N::get ('album|album.error.upload_toobig', $_FILES['fichier']['type']);
					break;
				case 3: //uploaded file was only partially uploaded
					$message = CopixI18N::get ('album|album.error.upload_partial', $_FILES['fichier']['type']);
					break;
				case 4: //no file was uploaded
					$message = CopixI18N::get ('album|album.error.upload_nofile', $_FILES['fichier']['type']);
					break;
				default:
					$message = CopixI18N::get ('album|album.error.upload_default', $_FILES['fichier']['type']);
					break;
			}
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (
					'message'=>$message,
					'back'=> CopixUrl::get( 'album|default|album',
					                        array('album_id'=>$this->vars["album_id"])
					)
				)
			);
		}
		
		/* Verif photo ! */
		switch( $_FILES['fichier']['type'] ) {
			case 'image/png':
			case 'image/x-png': // IE
				$ext="png";
				break;
			case 'image/gif':
				$ext="gif";
				break;
			case 'image/jpeg':
			case 'image/pjpeg': // IE
				$ext="jpg";
				break;
			case 'application/zip':
			case 'application/x-zip-compressed':
				return CopixActionGroup::process ('album|album::getUploadFileZip', array ('album'=>$this->vars["album_id"], 'file'=>$_FILES));
				break;
			default:
				$photofile = null;
				return CopixActionGroup::process ('genericTools|Messages::getError',
					array ('message'=>CopixI18N::get ('album|album.error.filetype', $_FILES['fichier']['type']),
					'back'=> CopixUrl::get ('album|default|album', array('album_id'=>$this->vars["album_id"]))));
		}

		$album_dao = & CopixDAOFactory::create("album");
		$album = $album_dao->get( $this->vars["album_id"] );
		
		$photo_dao = & CopixDAOFactory::create("photo");
		$nouvelle_photo = CopixDAOFactory::createRecord("photo");
		$nouvelle_photo->photo_album = $this->vars["album_id"];
		$nouvelle_photo->photo_dossier = $this->vars["dossier_id"];
		if( trim($this->vars["titre"]) != '' )
			$nouvelle_photo->photo_nom = $this->vars["titre"];
		else
			$nouvelle_photo->photo_nom = $_FILES['fichier']['name'];
		$nouvelle_photo->photo_comment = $this->vars["commentaire"];
		$nouvelle_photo->photo_date = date("Y-m-d H:i:s");
		$nouvelle_photo->photo_ext = $ext;
		$nouvelle_photo->photo_cle = Album::createKey();

		$photo_dao->insert( $nouvelle_photo );
		
		$path2data = realpath("static");
		$path2album = $path2data."/album/".$this->vars["album_id"]."_".$album->album_cle;
		$photofile = $path2album."/".$nouvelle_photo->photo_id."_".$nouvelle_photo->photo_cle;
			
		if( $ext ) $photofile.='.'.$ext;
		if( $photofile != null ) move_uploaded_file ( $_FILES['fichier']['tmp_name'], $photofile );

		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$this->vars["album_id"],'dossier_id'=>$this->vars["dossier_id"]) ));
	}

	/**
	 * Formulaire d'effacement de photo
	 *
	 * Demande confirmation avant effacement d'une photo.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getDelPhoto () {
		return CopixActionGroup::process ('genericTools|Messages::getConfirm',
			array (
				'title'=>CopixI18N::get ('album.confirm.delphoto'),
				'message'=>CopixI18N::get ('album.confirm.delphotomsg'),
				'confirm'=>CopixUrl::get('album||dodelphoto', array('photo_id'=>$this->vars["photo_id"])),
				'cancel'=>CopixUrl::get('album||photo', array('photo_id'=>$this->vars["photo_id"])),
			)
		);			
	}

	/**
	 * Effacement de photo
	 *
	 * Proc�dure d'effacement de photo, dans la base et dans les fichiers
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doDelPhoto () {
		
		$service = & CopixClassesFactory::Create ('Album');
		
		$photo = $service->getPhoto( $this->vars["photo_id"] );
		
		if( Kernel::getLevel( "MOD_ALBUM", $photo->album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$service->delPhoto( $this->vars["photo_id"] );
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$photo->album_id, 'dossier_id'=>$photo->dossier_id) ));
	}


	/**
	 * Envoie les donn�es d'un fichier image.
	 *
	 * Cette fonction permet de v�firier les droits d'acc�s et d'envoyer ensuite les donn�es d'une image.
	 * Ceci est remplac� par un acc�s direct aux fichiers, avec un cl� dans le nom de l'album et de l'image.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 * @deprecated 2005
	 */
	function getFile () {
		if( !isset( $this->vars["album_id"] ) ) die();
		$album_id = $this->vars["album_id"];
		if( !isset( $this->vars["photo_id"] ) ) die();
		$photo_id = $this->vars["photo_id"];
		if( isset( $this->vars["size"] ) ) {
			if( ereg("^s([0-9]+)$", $this->vars["size"], $regs) ) {
				$size = $regs[1];
				$mode = "square";
			} else {
				$size = $this->vars["size"];
				$mode = "normal";
			}
		}
		else $size = 0;

		$album_dao = & CopixDAOFactory::create("album");
		$album = $album_dao->get( $this->vars["album_id"] );
		$photo_dao = & CopixDAOFactory::create("photo");
		$photo = $photo_dao->get( $this->vars["photo_id"] );

		$path2data = realpath("static");
		$path2album = $path2data."/album/".$album_id."_".$album->album_cle;
		
		$files = array();
		if ($handle = opendir($path2album)) {
			while (false !== ($file = readdir($handle)))
				if( ereg("([0-9]+)(_?[0-9a-f]{10})?\.([a-zA-Z0-9]+)", $file, $regs) ) { // A modifier (obliger la cl� MD5)
					if( $regs[1] == $photo_id ) {
						$pathfile = $path2album."/".$file;
						
						if (isset($GLOBALS['COPIX']['DEBUG'])){
							$GLOBALS['COPIX']['DEBUG']->addInfo("Image : Chemin=".$pathfile."", 'Album :');
						}

						if( file_exists($paththumbnail) ) {
							die( "Image exise : ".$paththumbnail );
						}

						$image = NULL;
						list($width, $height, $type, $attr) = getimagesize($pathfile);

// Album::createThumbnails( $photo->album_id."_".$photo->album_cle, $photo->photo_id."_".$photo->photo_cle, $photo->photo_ext );

						// SQUARE //
						if( $mode == "square" ) {
							$square_width = $width;
							$square_height = $height;
							if ($square_width>=$square_height) {	// Plus large que haut
								$square_y = 0;
								$square_size = $square_height;
								$square_x = round($square_width-$square_height)/2;
							} else {	// Plus haut que large
								$square_x = 0;
								$square_size = $square_width;
								$square_y = round($square_height-$square_width)/2;
							}
							$square_thumbsize = $size; // 168;
						}

						if( 1|| $size>0 /* && ( max($width,$height) > $size ) */ ) {
							$ratio = max($width,$height) / $size;
							// echo "Trop gros (ratio=".$ratio.")!<br/>";
							
							$new_width  = round($width/$ratio);
							$new_height = round($height/$ratio);
							// echo "Nouvelle taille = ".$new_width."x".$new_height."<br/>";

							switch( $type ) {
								case 1: // GIF
									$image = @imagecreatefromgif($pathfile);
									break;
								case 2: // JPG:
									$image = @imagecreatefromjpeg($pathfile);
									break;
								case 3: // PNG
									$image = @imagecreatefrompng ($pathfile);
									break;
								default:
									// die('Type inconnu : '.$mime);
							}

							if (!$image) { /* V�rification */
								$image = imagecreate(200, 30); /* Cr�ation d'une image blanche */
								$bgc = imagecolorallocate($image, 255, 255, 255);
								$tc  = imagecolorallocate($image, 128, 128, 128);
								imagefilledrectangle($image, 0, 0, 200, 30, $bgc);
								/* Affichage d'un message d'erreur */
								imagestring($image, 1, 10, 10, "Erreur !", $tc);
							}

							if( !$image ) 
								if (isset($GLOBALS['COPIX']['DEBUG'])){
									$GLOBALS['COPIX']['DEBUG']->addInfo("Erreur d'ouverture de l'image (imagecreatefrom...)", 'Album :');
								}
							
							if (isset($GLOBALS['COPIX']['DEBUG'])){
								$GLOBALS['COPIX']['DEBUG']->addInfo("G�n�ration d'image (".$width."x".$height.")->(".$new_width."x".$new_height.")", 'Album :');
							}

							// SQUARE //
							if( $mode == "square" ) {
								$image_p = imagecreatetruecolor($square_thumbsize, $square_thumbsize);
								imagecopyresampled($image_p, $image, 0, 0, $square_x, $square_y, $square_thumbsize, $square_thumbsize, $square_size, $square_size);
							} else {
								$image_p = imagecreatetruecolor($new_width, $new_height);
								imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
							}

							header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
							header ("Content-Type: image/".$regs[2]);
							header ("Content-Disposition: inline;");
							imagejpeg($image_p, null, 100);
							closedir($handle);
							return new CopixActionReturn (COPIX_AR_NONE);
						}

if (isset($GLOBALS['COPIX']['DEBUG'])){
	$GLOBALS['COPIX']['DEBUG']->addInfo("Copie d'image (".$width."x".$height.")", 'Album :');
}

						header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header ("Content-Type: image/".$regs[2]);
						header ("Content-Length: " . filesize($pathfile));
						header ("Content-Disposition: inline; filename=".$photo_id.".".$regs[2]);
						readfile($pathfile);
						closedir($handle);
						return new CopixActionReturn (COPIX_AR_NONE);
					}
				}
			closedir($handle);
		}
		
		return new CopixActionReturn (COPIX_AR_NONE);
	}

	
	/**
	 * Acc�s direct � un album
	 *
	 * Fonction g�n�rique d'acc�s � un album, depuis un noeud.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function go () {
		if( isset( $this->vars["id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$this->vars["id"]) ));
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
	}



	/**
	 * Ouverture de l'album en Popup
	 *
	 * Appel direct � l'album sous forme de popup pour le choix d'images depuis le blog.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function popup () {
		if( isset( $this->vars["id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|getpopup', array('album_id'=>$this->vars["id"], 'dossier_id'=>0) ));
		}
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}



	/**
	 * Affichage de l'album en popup
	 *
	 * Affichage simplifi� de l'album, avec choix de la taille et de la position de l'image.
	 * Retourne le code Wiki pour l'affichage de l'image directement dans le blog.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getPopup () {
		$tpl = & new CopixTpl ();
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		if( !isset( $this->vars["album_id"] )   ||
		    trim($this->vars["album_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["album_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
						'back'=>CopixUrl::get('||')));
		}
		
		$album_id = $this->vars["album_id"];
		
		if( !isset($this->vars["dossier_id"]) || !ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) {
			if( isset($_SESSION['modules']['album']['lastfolder'][$album_id]) ) {
				$dossier_id = $_SESSION['modules']['album']['lastfolder'][$album_id];
			} else {
				$dossier_id = 0;
			}
		} else {
			$dossier_id = $this->vars["dossier_id"];
			$_SESSION['modules']['album']['lastfolder'][$album_id] = $dossier_id;
		}
		$format = $this->vars["format"];
		
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_READ ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}

		$tplAlbum = & new CopixTpl ();
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		if(!$album) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array (	'message'=>CopixI18N::get ('album.error.noalbum'),
			        'back'=>CopixUrl::get('||')));
		}
		
		$parent = $kernel_service->getModParentInfo( "MOD_ALBUM", $album_id);
		$album->parent = $parent;
		
		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = "Racine";
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		
		$tplAlbum->assign ("album", $album);
		$tplAlbum->assign ("dossier", $dossier);
		
		$album_dao = CopixDAOFactory::create("photo");
		// $photolist = $album_dao->findAllByAlbum($album_id);
		$photolist = $album_dao->findAllByAlbumAndFolder($album_id, $dossier_id);
		// Kernel::MyDebug($photolist);
		
		$tplAlbum->assign ("photolist", $photolist);
		
		foreach( $photolist AS $photothumb ) {
			Album::createThumbnails(
				$photothumb->album_id.'_'.$photothumb->album_cle,
				$photothumb->photo_id.'_'.$photothumb->photo_cle,
				$photothumb->photo_ext );
		}
		
		$dossiers_childs = Album::getFoldersTree( $album_id, $dossier_id );
		$tplAlbum->assign ("dossierlist", $dossiers_childs);
		$tplAlbum->assign ('field', $this->vars["field"] );
		$tplAlbum->assign ('format', $format );
		
		$tplAlbum->assign ('album_thumbsize', "_s128" );
		$result = $tplAlbum->fetch("popup_album.tpl");

		$tpl->assign ('PHOTOS', $result);
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.album', array($album->parent["nom"]) ));
		$tpl->assign ('field', $this->vars["field"] );
		$tpl->assign ('format', $format );
		
		// On d�duit le chemin absolu
		$path = $_SERVER['PHP_SELF'];
		$pos = strrpos($path, "/");
		if ($pos !== false) {
			$abspath = substr($path,0,$pos+1);
		}
		$tpl->assign ('abspath', $abspath);	
		
		
		$menu = '';
		$menu.= '<a href="'.CopixUrl::get ('album||addphoto', array("album_id"=>$album->album_id)).'">'.CopixI18N::get ('album.menu.addphoto').'</a>';
		$tpl->assign ('MENU', $menu );
		$tpl->assign ('LANGUE', PluginI18n::getLang());
		
		return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, "album|popup.tpl" );
	}

	/**
	 * Cr�ation des vignettes manquantes
	 *
	 * Si des vignettes sont � cr�er, cette page va cr�er une vignette et se recharger pour cr�er les suivantes.
	 * Ceci permet d'afficher l'avanc� de la g�n�ration, et �viter les timeout.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function vignettes () {
		if( !isset( $this->vars["album"] ) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array (	'message'=>CopixI18N::get ('album.error.noalbum'),
			        'back'=>CopixUrl::get('||')));
		}
		
		if( !isset($_SESSION['modules']['album']['vignettes'][$this->vars["key"]]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array (	'message'=>CopixI18N::get ('album.error.nothumbscreate'),
			        'back'=>CopixUrl::get('album||album',array('album_id'=>$this->vars["album"]))));
		}
		
		
		$tpl = & new CopixTpl ();
		
		$image = array_shift( $_SESSION['modules']['album']['vignettes'][$this->vars["key"]] );
		if( $image == NULL ) {
			$tpl->assign ('url_album', $this->vars["album"]);
			$tpl->assign ('url_dossier', $this->vars["dossier"]);
			$tpl->assign ('url_key', $this->vars["key"]);
			$tpl->assign ('finish', true );
			$tpl->assign ('message', CopixI18N::get ('album.message.thumbs_end') );
			
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$this->vars["album"],'dossier_id'=>$this->vars["dossier"]) ));
			// return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, "album|vignettes.tpl" );
		}
		
		$photo_dao= null;
		$ok = Album::createThumbnail( $image['album'], $image['photo'], $image['ext'], $image['taille'] );
		if( !$ok ) {
			if( ereg( "^([0-9]+)_([0-9a-fA-F]+)$", $image['photo'], $photo_regs ) ) {
				if( $photo_dao==null ) $photo_dao = CopixDAOFactory::create("photo");
				$photo_dao->delete($photo_regs[1]);
			}
		}
		
		$tpl->assign ('url_album', $this->vars["album"]);
		$tpl->assign ('url_dossier', $this->vars["dossier"]);
		$tpl->assign ('url_key', $this->vars["key"]);
		
		$tpl->assign (
			'message', CopixI18N::get (
				'album.message.thumbs_create',
				array(
					($_SESSION['modules']['album']['vignettes']['nb-'.$this->vars["key"]] -
					sizeof($_SESSION['modules']['album']['vignettes'][$this->vars["key"]])),

					$_SESSION['modules']['album']['vignettes']['nb-'.$this->vars["key"]]
				)
			)
		);

		$tpl->assign ('bar_max', $_SESSION['modules']['album']['vignettes']['nb-'.$this->vars["key"]] );
		$tpl->assign ('bar_value', $_SESSION['modules']['album']['vignettes']['nb-'.$this->vars["key"]] - sizeof($_SESSION['modules']['album']['vignettes'][$this->vars["key"]]) );

		return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, "album|vignettes.tpl" );
	}

	/**
	 * Publication de l'album
	 *
	 * Copie les fichiers image et vignette, et g�n�re le fichier XML pour SimpleViewer
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doPublier() {
		
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		if( !isset( $this->vars["album_id"] )   ||
		    trim($this->vars["album_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["album_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
						'back'=>CopixUrl::get('||')));
		}
		
		$album_id = $this->vars["album_id"];
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;

		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		
		$path2data = realpath("static");
		$path2album = $path2data."/album/".$album_id."_".$album->album_cle;
		if( $dossier_id > 0 ) {
			$path2public = $path2album."/".$dossier->dossier_id."_".$dossier->dossier_cle;
			if(!is_dir($path2public)) { mkdir( $path2public, 0775 ); }
			@chmod( $path2public, 0775 );
		} else $path2public = $path2album;
			
		$parent = Kernel::getModParentInfo( "MOD_ALBUM", $album_id );
		
		$file_xml = fopen( $path2public.'/imageData.xml', 'w' );
		$tplXml = & new CopixTpl ();
		$tplXml->assign ("album_id", $album_id);
		$tplXml->assign ("album_key", $album->album_cle);
		$tplXml->assign ("album_titre", $parent['nom']);
		$tplXml->assign ("dossier_id", $dossier_id);
		$tplXml->assign ("dossier_key", $dossier->dossier_cle);
		$tplXml->assign ("dossier_titre", $dossier->dossier_nom);
		
		$photo_dao = CopixDAOFactory::create("photo");
		// $photolist = $photo_dao->findAllByAlbum($album_id);
		$photolist = $photo_dao->findAllByAlbumAndFolder($album_id,$dossier_id);
		$tplXml->assign ("photolist", $photolist);
		
		if(!is_dir($path2public.'/images/')) mkdir( $path2public.'/images/', 0775 );
		if(!is_dir($path2public.'/thumbs/')) mkdir( $path2public.'/thumbs/', 0775 );
		@chmod( $path2public.'/images/', 0775 );
		@chmod( $path2public.'/thumbs/', 0775 );
		
		foreach( $photolist AS $photo ) {
		
			Album::createThumbnail( $photo->album_id.'_'.$photo->album_cle, $photo->photo_id.'_'.$photo->photo_cle, $photo->photo_ext, $taille="s64", false, 'jpg' );
			Album::createThumbnail( $photo->album_id.'_'.$photo->album_cle, $photo->photo_id.'_'.$photo->photo_cle, $photo->photo_ext, $taille="640", false, 'jpg' );
			
			copy( $path2album.'/'.$photo->photo_id.'_'.$photo->photo_cle.'_640.jpg',
			      $path2public.'/images/'.$photo->photo_id.'_'.$photo->photo_cle.'.jpg' );
			copy( $path2album.'/'.$photo->photo_id.'_'.$photo->photo_cle.'_s64.jpg',
			      $path2public.'/thumbs/'.$photo->photo_id.'_'.$photo->photo_cle.'.jpg' );
		}
		
		$result = $tplXml->fetch("simpleviewer_xml.tpl");
		fwrite( $file_xml, $result );
		fclose( $file_xml );
		
		
		$file_html = fopen( $path2public.'/index.html', 'w' );
		$tplHtml = & new CopixTpl ();
		$tplHtml->assign ("album_id", $album_id);
		$tplHtml->assign ("album_key", $album->album_cle);
		$tplHtml->assign ("album_titre", $parent['nom']);
		$tplHtml->assign ("dossier_id", $dossier_id);
		$tplHtml->assign ("dossier_key", $dossier->dossier_cle);
		$tplHtml->assign ("dossier_nom", $dossier->dossier_nom);
		$result = $tplHtml->fetch("simpleviewer_html.tpl");
		fwrite( $file_html, $result );
		fclose( $file_html );
		
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$album_id, 'dossier_id'=>$dossier_id) ));
	}


	/**
	 * Retire les �l�ments de publication.
	 *
	 * Supprime les fichiers pour l'acc�s depuis SimpleViewer
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doDepublier() {
		
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		if( !isset( $this->vars["album_id"] )   ||
		    trim($this->vars["album_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["album_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
						'back'=>CopixUrl::get('||')));
		}
		
		$album_id = $this->vars["album_id"];
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;

		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		
		$path2data = realpath("static");
		$path2album = $path2data."/album/".$album_id."_".$album->album_cle;
		
		$path2public = $path2album;
		if( $dossier_id > 0 ) {
			$path2public = $path2album."/".$dossier->dossier_id."_".$dossier->dossier_cle;
		}
		
		@unlink( $path2public.'/imageData.xml' );
		@unlink( $path2public.'/index.html' );
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>$album_id, 'dossier_id'=>$dossier_id) ));
	}

	/**
	 * Formulaire d'ajout d'images dans un Zip
	 *
	 * Affiche le formulaire de t�l�chargement de fichier zip.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getAddZip() {
		$tpl = & new CopixTpl ();
		
		if( !isset( $this->vars["album_id"] ) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
		}
		
		$album_id = $this->vars["album_id"];
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;
				
		$tplAddPhoto = & new CopixTpl ();
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		$tplAddPhoto->assign ("album", $album);
		$tplAddPhoto->assign ("dossier_id", $dossier_id);
		$tplAddPhoto->assign ("file_size_zip", CopixConfig::get ('album|file_size_zip') );
		
		$result = $tplAddPhoto->fetch("addzip.tpl");
		
		$tpl->assign ('MAIN', $result);
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.addzip'));

		$menu = array();
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.addphoto'),
			'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id))
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.gotoalbum'),
			'url' => CopixUrl::get ('album||album', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id))
		);
		$tpl->assign ('MENU', $menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * Traitement de l'archive ZIP
	 *
	 * D�compresse l'archive, v�rifie et exrait les images, puis lance la g�n�ration des vignettes.
	 *
	 * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function doAddZip() {
		
		if( ! is_uploaded_file( $_FILES['fichier']['tmp_name'] ) ) {
			$message = '';
			switch( $_FILES['fichier']['error'] ) {
				case 0: //no error; possible file attack!
					$message = CopixI18N::get ('album|album.error.upload_default', $_FILES['fichier']['type']);
					break;
				case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
					$message = CopixI18N::get ('album|album.error.upload_toobig', $_FILES['fichier']['type']). '<!-- upload_max_filesize ('.ini_get('upload_max_filesize').') -->';
					break;
				case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
					$message = CopixI18N::get ('album|album.error.upload_toobig', $_FILES['fichier']['type']). '<!-- MAX_FILE_SIZE -->';
					break;
				case 3: //uploaded file was only partially uploaded
					$message = CopixI18N::get ('album|album.error.upload_partial', $_FILES['fichier']['type']);
					break;
				case 4: //no file was uploaded
					$message = CopixI18N::get ('album|album.error.upload_nofile', $_FILES['fichier']['type']);
					break;
				default:
					$message = CopixI18N::get ('album|album.error.upload_default', $_FILES['fichier']['type']);
					break;
			}

			$message .= "<!--\n";
			$message .= print_r( $_FILES, true );
			$message .= print_r( ini_get_all(), true );
			$message .= "-->\n";

			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ( 
					'message'=>$message,
					'back'=> CopixUrl::get( 'album|default|album',
					                        array('album_id'=>$this->vars["album_id"], 'dossier_id'=>$this->vars["dossier_id"])
					)
				)
			);
		}

		$archive = new PclZip($_FILES['fichier']['tmp_name']);
		$list = $archive->listContent();

		foreach ($list as $k=>$f) {
			if ($f['size']<=CopixConfig::get ('album|file_size_photo'))
				$okIndex[] = $k;
		}

		umask( 0 );
		$tmpFolder = tempnam ('XXXXX','PclZip_'.$this->vars['album_id'].'_');
		unlink( $tmpFolder ); mkdir( $tmpFolder );
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($this->vars['album_id']);
		$path2data = realpath("static");
		$path2album = $path2data."/album/".$album->album_id."_".$album->album_cle;

		if ($okIndex) {
			$extract = $archive->extract(
				PCLZIP_OPT_PATH, $tmpFolder,
				PCLZIP_OPT_BY_INDEX, implode(',',$okIndex), 
				PCLZIP_OPT_REMOVE_ALL_PATH
			);
		} else $extract = 0;

		foreach( $okIndex AS $ok_key=>$ok_val ) {
			$filename = $extract[$ok_val]['filename'];
			
			$ext='';
			switch( strtolower(strrchr($filename, ".")) ) {
				case '.jpg':
				case '.jpeg':
				case '.jpe':
					$ext="jpg";
					break;
				case '.gif':
					$ext="gif";
					break;
				case '.png':
					$ext="png";
					break;
				default:
					continue;
					break;
			}

			if( $ext != '' ) {

				$photo_dao = & CopixDAOFactory::create("photo");
				$nouvelle_photo = CopixDAOFactory::createRecord("photo");
				$nouvelle_photo->photo_album = $album->album_id;
				$nouvelle_photo->photo_dossier = $this->vars["dossier_id"];
				$nouvelle_photo->photo_nom = substr( strrchr( $extract[$ok_val]['filename'], "/" ), 1 );
				$nouvelle_photo->photo_comment = '';
				$nouvelle_photo->photo_date = date("Y-m-d H:i:s");
				$nouvelle_photo->photo_ext = $ext;
				$nouvelle_photo->photo_cle = Album::createKey();

				$photo_dao->insert( $nouvelle_photo );

				if( $nouvelle_photo->photo_id ) {
					$path2data = realpath("static");
					$path2album = $path2data."/album/".$album->album_id."_".$album->album_cle;
					$photofile = $path2album."/".$nouvelle_photo->photo_id."_".$nouvelle_photo->photo_cle;
			
					if( $ext ) $photofile.='.'.$ext;
					if( $photofile != null )
						copy( $extract[$ok_val]['filename'], $photofile );
				}
			}
		}
		
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
		$malleService->deleteDir($tmpFolder);
		
		return new CopixActionReturn (COPIX_AR_REDIRECT,
			CopixUrl::get ('album|default|album', array('album_id'=>$album->album_id, 'dossier_id'=>$this->vars["dossier_id"]) ));
	}
	
	function doFolder() {
		
		$kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
		
		if( !isset( $this->vars["album_id"] )   ||
		    trim($this->vars["album_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["album_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
						'back'=>CopixUrl::get('||')));
		}
		
		$album_id = $this->vars["album_id"];
		
		if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get ('||')));
		}
		
		$album_dao = CopixDAOFactory::create("album");
		$album = $album_dao->get($album_id);
		
		
		if( !isset( $this->vars["dossier_id"] )   ||
		    trim($this->vars["dossier_id"])==""   ||
		    ! ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.nofoldernumber'),
						'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id) )));
		}
		
		if( isset($this->vars["dossier_id"]) && ereg ("^[0-9]+$", $this->vars["dossier_id"]) ) $dossier_id = $this->vars["dossier_id"];
		else $dossier_id = 0;
				
		$dossier_dao = CopixDAOFactory::create("dossier");
		if( $dossier_id > 0 ) {
			$dossier = $dossier_dao->get($dossier_id);
			
			if( $dossier->dossier_album != $album_id ) {
				return CopixActionGroup::process ('genericTools|Messages::getError',
					array (	'message'=>CopixI18N::get ('album.error.badfoldernumber'),
							'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id) )));
			}
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $album_id;
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		
		if( !isset( $this->vars["subaction"] ) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array (	'message'=>CopixI18N::get ('album.error.badsubaction'),
						'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) )));
		}
		
		switch( $this->vars["subaction"] ) {

			case 'new':
				$dossier_new = CopixDAOFactory::createRecord("dossier");
				$dossier_new->dossier_album = $album_id;
				$dossier_new->dossier_parent = $dossier_id;
				$dossier_new->dossier_nom = $this->vars["folder_new"];
				$dossier_new->dossier_comment = '';
				$dossier_new->dossier_date = date("Y-m-d H:i:s");
				$dossier_new->dossier_cle = substr( md5(microtime()), 0, 10 );
				$dossier_new->dossier_public = '0';
		
				$dossier_dao->insert( $dossier_new );

				return new CopixActionReturn (COPIX_AR_REDIRECT,
					CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_new->dossier_id) ));
					
				break;
			
			case 'rename':
				$dossier->dossier_nom = $this->vars["folder_rename"];
				$dossier_dao->update( $dossier );

				return new CopixActionReturn (COPIX_AR_REDIRECT,
					CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) ));
				
				break;
			
			case 'move':
				$dossier->dossier_parent = $this->vars["folder_move"];
				$dossier_dao->update( $dossier );

				return new CopixActionReturn (COPIX_AR_REDIRECT,
					CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) ));
				
				break;
			
			case 'delete':
				// action=dofolder subaction=delete album_id=1& dossier_id=4 dossier_todo=moveparent
				
				Album::delFolder( $this->vars["album_id"], $this->vars["dossier_id"], $this->vars["dossier_todo"] );
				
				return new CopixActionReturn (COPIX_AR_REDIRECT,
					CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>max(0,$dossier->dossier_parent)) ));
								
				break;
				
			default:
				return CopixActionGroup::process ('genericTools|Messages::getError',
					array (	'message'=>CopixI18N::get ('album.error.badsubaction'),
							'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) )));
				break;
		}
	}
	
	function getEditPhotos() {
		$tpl = & new CopixTpl ();
		
		$menu = array();
		$menu[] = array(
			'txt' => CopixI18N::get ('album.menu.gotoalbum'),
			'url' => CopixUrl::get ('album||album', array("album_id"=>$this->vars["album_id"], "dossier_id"=>$this->vars["dossier_id"]))
		);
		$tpl->assign ('MENU', $menu );
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.editphotos'));
		$tpl->assign ('MAIN', CopixZone::process ('album|editphotos', array(
			'album_id'=>$this->vars["album_id"],
			'dossier_id'=>$this->vars["dossier_id"],
		) ));

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function doEditPhotos() {
		$photo_dao = CopixDAOFactory::create("photo");
		$pictures = $photo_dao->findAllByAlbumAndFolder($this->vars['album'],$this->vars['dossier']);
		
		if( count($pictures) ) {
			foreach( $pictures as $picture ) {
				if( $this->vars['photo_'.$picture->photo_id] ) {
					$picture_modif = $photo_dao->get($picture->photo_id);
					$picture_modif->photo_dossier = $this->vars['folder_move'];
					$photo_dao->update( $picture_modif );
				}
			}
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT,
			CopixUrl::get ('album|default|album', array('album_id'=>$this->vars['album'],'dossier_id'=>$this->vars['dossier']) ));
	}
	
}
?>