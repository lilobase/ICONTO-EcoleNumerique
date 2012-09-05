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

_classInclude('album|album');
require_once (COPIX_UTILS_PATH.'pclzip.lib.php');

/**
 * handle the photo gallery
 */
class ActionGroupAlbum extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }


    /**
     * Affiche la liste des albums
     *
     * Cette page n'est plus utilis�e depuis que l'album est li� � un noeud.
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @deprecated 2006
     */
    public function getList ()
    {
        $tpl = new CopixTpl ();

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.main'));
        $tpl->assign ('MAIN', CopixZone::process ('album|albumlist'));

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
    public function getAlbum ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        CopixHTMLHeader::addJSLink (_resource("js/iconito/module_malle.js"));

        $tpl = new CopixTpl ();
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        if( !(_request("album_id") )   ||
            trim(_request("album_id"))==""   ||
            ! preg_match ("/^[0-9]+$/", _request("album_id")) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
                        'back'=>CopixUrl::get('||')));
        }

        $album_id = _request("album_id");

        if( _request("dossier_id") && preg_match("/^[0-9]+$/", _request("dossier_id")) ) $dossier_id = _request("dossier_id");
        else $dossier_id = 0;

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_READ ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }


        $tplAlbum = new CopixTpl ();

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

        $key = $album->album_id.'_'.$album->album_cle;
        CopixSession::delete ('modules|album|vignettes|'.$key);
        CopixSession::delete ('modules|album|vignettes|nb-'.$key);

        $errors = Album::checkThumbnails( $album_id, $key );
        if( $errors > 0 ) {
            //die (".".$errors);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|vignettes', array('album'=>$album_id,'dossier'=>$dossier_id,'key'=>$key) ) );
        }



        $parent = $kernel_service->getModParentInfo( "MOD_ALBUM", $album_id);
        $album->parent = $parent;

        $tplAlbum->assign ("album", $album);
        $tplAlbum->assign ("dossier", $dossier);

        $dossiermenu = array();
        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
            $dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.newfolder.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_new\');', 'type'=>'add' );
            if( $dossier_id > 0 ) {
                $dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.rename.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_rename\');', 'type'=>'update' );
                $dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.move.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_move\');', 'type'=>'next' );
                $dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.delete.menu'), 'url'=>'#', 'onclick'=>'openbox(\'folder_delete\');', 'type'=>'delete' );
            }
            $dossiermenu[] = array('txt'=>CopixI18N::get ('album.folder.action.movephotos.menu'), 'url'=>CopixUrl::get ('album|default|editphotos', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) ), 'type'=>'next' );
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

        // Debut petit poucet
        $petit_poucet_array = array();
        $dossier_walk = clone $dossier;
        if($dossier->dossier_id>0) array_unshift( $petit_poucet_array, clone $dossier_walk );
        // _dump($dossier_walk);
        while( $dossier_walk->dossier_parent > 0 ) {
            $dossier_walk = $dossier_dao->get($dossier_walk->dossier_parent);
            array_unshift( $petit_poucet_array, clone $dossier_walk );
        }

        $dossier_walk->dossier_id = 0;
        $dossier_walk->dossier_parent = -1;
        $dossier_walk->dossier_nom = 'Racine';
        array_unshift( $petit_poucet_array, clone $dossier_walk );

        $tplAlbum->assign ('petit_poucet', $petit_poucet_array );
        // Fin petit poucet

        $result = $tplAlbum->fetch("album.tpl");

        $tpl->assign ('MAIN', $result);

        $album_nom = (isset($album->parent["nom"])) ? $album->parent["nom"] : CopixI18N::get('album.moduleDescription');
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
                    'target' => '_blank',
                    'size' => 110,
                    'type' => 'read'
                );
                if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
                    $menu[] = array(
                        'txt' => CopixI18N::get ('album|album.menu.deletefolder'),
                        'url' => CopixUrl::get ('album||depublier', array("album_id"=>$album->album_id,"dossier_id"=>$dossier->dossier_id)),
                        'size' => 140,
                        'type' => 'delete'
                    );
                }
            }
            if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
                $menu[] = array(
                    'txt' => CopixI18N::get ('album|album.menu.publishfolder'),
                    'url' => CopixUrl::get ('album||publier', array("album_id"=>$album->album_id,"dossier_id"=>$dossier_id)),
                    'size' => 100
                );
            }
        }
        // endif( simpleviewer )

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) >= PROFILE_CCV_PUBLISH ) {
            $menu[] = array(
                'txt' => CopixI18N::get ('album|album.menu.addzip'),
                'url' => CopixUrl::get ('album||addzip', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id)),
                'behavior' => 'fancybox',
                'size'=>140,
                'type' => 'addfile'
            );
            $menu[] = array(
                'txt' => CopixI18N::get ('album|album.menu.addphoto'), // 'Ajouter une photo',
                'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id)),
                'behavior' => 'fancybox',
                'size'=>100,
                'type' => 'addfile'
            );
        }

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
    public function getDelAlbum ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        $tpl = new CopixTpl ();

        if( Kernel::getLevel( "MOD_ALBUM", _request("album_id") ) < PROFILE_CCV_ADMIN ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $service = & CopixClassesFactory::Create ('Album');
        $photo = $service->delAlbum( _request("album_id") );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Effacement d'un album.
     *
     * Non utilis�, depuis que l'album est li� � un noeud (effacement via une classe).
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function doDelAlbum ()
    {
        if( Kernel::getLevel( "MOD_ALBUM", _request("album_id") ) < PROFILE_CCV_ADMIN ) {
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
    public function getPhoto ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        $tpl = new CopixTpl ();

        if( !(_request("photo_id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
        }

        $photo_id = _request("photo_id");

        $tplAlbum = new CopixTpl ();


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
            'url' => CopixUrl::get ('album||album', array("album_id"=>$photo->album_id, "dossier_id"=>$photo->photo_dossier)),
            'size' => 95
        );
        if( Kernel::getLevel( "MOD_ALBUM", $photo->album_id ) >= PROFILE_CCV_PUBLISH ) {
            $menu[] = array(
                'txt' => CopixI18N::get ('album|album.menu.addphoto'), // 'Ajouter une photo',
                'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$photo->album_id, "dossier_id"=>$photo->photo_dossier)),
                'behavior' => 'fancybox',
                'size'=>100,
                'type' => 'addfile'
            );
            $menu[] = array(
                'txt' => CopixI18N::get ('album.menu.delphoto'),
                'url' => CopixUrl::get ('album||delphoto', array("photo_id"=>$photo->photo_id)),
                'size'=>105,
                'type' => 'delete'
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
    public function getAddPhoto ()
    {
        $ppo = new CopixPPO();

        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        $tpl = new CopixTpl ();

        if( !(_request("album_id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
        }

        $ppo->display_mode = _request("mode",'normal');
        $ppo->popup_field  = _request("popup_field",'');
        $ppo->popup_format = _request("popup_format",'');

        $album_id = _request("album_id");

        if( _request("dossier_id") && preg_match("/^[0-9]+$/", _request("dossier_id")) ) $dossier_id = _request("dossier_id");
        else $dossier_id = 0;

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $tplAddPhoto = new CopixTpl ();

        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get($album_id);

        // $tplAddPhoto->assign ("album", $album);
        $ppo->album = $album;

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

        // $tplAddPhoto->assign ("dossier", $dossier);
        $ppo->dossier = $dossier;

        // $tplAddPhoto->assign ("file_size_photo", CopixConfig::get ('album|file_size_photo') );
        $ppo->file_size_photo = CopixConfig::get ('album|file_size_photo');

        // $result = $tplAddPhoto->fetch("addphoto.tpl");
        return _arPPO ($ppo, array ('template'=>'addphoto.tpl', 'mainTemplate'=>'main|main_fancy.php'));

        $tpl->assign ('MAIN', $result);
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.menu.addphoto'));

        $menu = array();
        $menu[] = array(
            'txt' => CopixI18N::get ('album.menu.addzip'),
            'url' => CopixUrl::get ('album||addzip', array("album_id"=>$album->album_id, "dossier_id"=>$dossier->dossier_id))
        );
        $menu[] = array(
            'txt' => CopixI18N::get ('album.menu.gotoalbum'),
            'url' => CopixUrl::get ('album||album', array("album_id"=>$album->album_id, "dossier_id"=>$dossier->dossier_id)),
            'size' => 95
        );
        $tpl->assign ('MENU', $menu );

        // TODO : return _arPPO ($ppo, array ('template'=>'addphoto.tpl', 'mainTemplate'=>'main|main_fancy.php'));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Ajout une photo dans un album.
     *
     * Apr�s v�rification des droits et de l'image, engeristrement dans la base et copie du fichier dans le r�pertoire.
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function doAddPhoto ()
    {
        // A faire dans le .htaccess
        // @ini_set( 'memory_limit', '64M' ); // Pb d'allocation m�moire
        @ini_set( 'max_execution_time', '120' ); // Pd de temps de traitement

        $ppo = new CopixPPO();
        $ppo->album_id     = _request("album_id");
        $ppo->display_mode = _request("mode",'normal');
        $ppo->popup_field  = _request("popup_field",'');
        $ppo->popup_format = _request("popup_format",'');

        if( !(_request("album_id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
        }

        if( Kernel::getLevel( "MOD_ALBUM", _request("album_id") ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ( 'album|default|album', array('album_id'=>_request("album_id")) )));
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

            if($ppo->display_mode=='popup') {
                $ppo->message = $message;
                $ppo->back = CopixUrl::get ('album|default|getpopup', array(
                            'album_id'=>$ppo->album_id,
                            'mode'=>$ppo->display_mode,
                            'field'=>$ppo->popup_field,
                            'format'=>$ppo->popup_format
                ) );
                return _arPPO ($ppo, array ('template'=>'popup_error.tpl', 'mainTemplate'=>'main|main_fancy.php'));
            } else {
                return CopixActionGroup::process ('genericTools|Messages::getError',
                    array (
                        'message'=>$message,
                        'back'=> CopixUrl::get( 'album|default|album',
                                                array('album_id'=>_request("album_id"))
                        )
                    )
                );
            }
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
                return CopixActionGroup::process ('album|album::doAddZip');
                break;
            default:
                $photofile = null;
                if($ppo->display_mode=='popup') {
                    $ppo->message = CopixI18N::get ('album|album.error.filetype', $_FILES['fichier']['type']);
                    $ppo->back = CopixUrl::get ('album|default|getpopup', array('album_id'=>_request("album_id"), 'mode'=>$ppo->display_mode, 'field'=>$ppo->popup_field, 'format'=>$ppo->popup_format));
                    return _arPPO ($ppo, array ('template'=>'popup_error.tpl', 'mainTemplate'=>'main|main_fancy.php'));
                } else {
                    return CopixActionGroup::process ('genericTools|Messages::getError',
                        array ('message'=>CopixI18N::get ('album|album.error.filetype', $_FILES['fichier']['type']),
                        'back'=> CopixUrl::get ('album|default|album', array('album_id'=>_request("album_id")))));
                }
        }

        $album_dao = & CopixDAOFactory::create("album");
        $album = $album_dao->get( _request("album_id") );

        $photo_dao = & CopixDAOFactory::create("photo");
        $nouvelle_photo = CopixDAOFactory::createRecord("photo");
        $nouvelle_photo->photo_album = _request("album_id");
        $nouvelle_photo->photo_dossier = _request("dossier_id");
        if( trim(_request("titre")) != '' )
            $nouvelle_photo->photo_nom = _request("titre");
        else
            $nouvelle_photo->photo_nom = $_FILES['fichier']['name'];
        $nouvelle_photo->photo_comment = _request("commentaire");
        $nouvelle_photo->photo_date = date("Y-m-d H:i:s");
        $nouvelle_photo->photo_ext = $ext;
        $nouvelle_photo->photo_cle = Album::createKey();

        $photo_dao->insert( $nouvelle_photo );

        $path2data = realpath("static");
        $path2album = $path2data."/album/"._request("album_id")."_".$album->album_cle;
        $photofile = $path2album."/".$nouvelle_photo->photo_id."_".$nouvelle_photo->photo_cle;

        if( $ext ) $photofile.='.'.$ext;
        if( $photofile != null ) move_uploaded_file ( $_FILES['fichier']['tmp_name'], $photofile );

        if($ppo->display_mode=='popup') {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|getpopup', array('album_id'=>_request("album_id"),'dossier_id'=>_request("dossier_id"), 'field'=>$ppo->popup_field, 'format'=>$ppo->popup_format) ));
        }

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>_request("album_id"),'dossier_id'=>_request("dossier_id")) ));
    }

    /**
     * Formulaire d'effacement de photo
     *
     * Demande confirmation avant effacement d'une photo.
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function getDelPhoto ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        return CopixActionGroup::process ('genericTools|Messages::getConfirm',
            array (
                'title'=>CopixI18N::get ('album.confirm.delphoto'),
                'message'=>CopixI18N::get ('album.confirm.delphotomsg'),
                'confirm'=>CopixUrl::get('album||dodelphoto', array('photo_id'=>_request("photo_id"))),
                'cancel'=>CopixUrl::get('album||photo', array('photo_id'=>_request("photo_id"))),
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
    public function doDelPhoto ()
    {
        $service = & CopixClassesFactory::Create ('Album');

        $photo = $service->getPhoto( _request("photo_id") );

        if( Kernel::getLevel( "MOD_ALBUM", $photo->album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $service->delPhoto( _request("photo_id") );

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
    public function getFile ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        if( !(_request("album_id") ) ) die();
        $album_id = _request("album_id");
        if( !(_request("photo_id") ) ) die();
        $photo_id = _request("photo_id");
        if( (_request("size") ) ) {
            if( preg_match("/^s([0-9]+)$/", _request("size"), $regs) ) {
                $size = $regs[1];
                $mode = "square";
            } else {
                $size = _request("size");
                $mode = "normal";
            }
        } else $size = 0;

        $album_dao = & CopixDAOFactory::create("album");
        $album = $album_dao->get( _request("album_id") );
        $photo_dao = & CopixDAOFactory::create("photo");
        $photo = $photo_dao->get( _request("photo_id") );

        $path2data = realpath("static");
        $path2album = $path2data."/album/".$album_id."_".$album->album_cle;

        $files = array();
        if ($handle = opendir($path2album)) {
            while (false !== ($file = readdir($handle)))
                if( ereg("([0-9]+)(_?[0-9a-f]{10})?\.([a-zA-Z0-9]+)", $file, $regs) ) { // A modifier (obliger la cl� MD5)
                    if( $regs[1] == $photo_id ) {
                        $pathfile = $path2album."/".$file;

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
    public function go ()
    {
        if( (_request("id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>_request("id")) ));
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
    public function popup ()
    {
        if( (_request("id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|getpopup', array('album_id'=>_request("id"), 'dossier_id'=>0) ));
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
    public function getPopup ()
    {
        $tpl = new CopixTpl ();
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        if( !(_request("album_id") )   ||
            trim(_request("album_id"))==""   ||
            ! preg_match ("/^[0-9]+$/", _request("album_id")) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
                        'back'=>CopixUrl::get('||')));
        }

        $album_id = _request("album_id");


        if( (_request("dossier_id")===null || !preg_match ("/^[0-9]+$/", _request("dossier_id")))) {

                    //correction de bug : 0 &&
            if( $get = _sessionGet ('modules|album|vignettes|lastfolder|'.$album_id) ) {
                $dossier_id = $get;
            } else {
                $dossier_id = 0;
            }
        } else {
            $dossier_id = _request("dossier_id");
            _sessionSet ('modules|album|vignettes|lastfolder|'.$album_id, $dossier_id);
        }
        $format = _request("format");


        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_READ ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $tplAlbum = new CopixTpl ();

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
        $tplAlbum->assign ('field', _request("field") );
        $tplAlbum->assign ('format', $format );
        $tplAlbum->assign ('album_thumbsize', "_s128" );
        $result = $tplAlbum->fetch("popup_album.tpl");

        $tpl->assign ('PHOTOS', $result);

        $album_nom = (isset($album->parent["nom"])) ? $album->parent["nom"] : CopixI18N::get('album.moduleDescription');
        $tpl->assign ('TITLE_PAGE', $album_nom);
        $tpl->assign ('field', _request("field") );
        $tpl->assign ('format', $format );
        $tpl->assign ('album_id', $album_id);
        $tpl->assign ('dossier_id', $dossier_id);

        $result = $tpl->fetch('album|popup.tpl');

        $ppo = new CopixPPO ();
        $ppo->result = $result;
        $ppo->TITLE_PAGE = CopixI18N::get ('album.title.album', array($album_nom) );

        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album_popup.css"));

        return _arPPO ($ppo, array ('template'=>'popup_ppo.tpl', 'mainTemplate'=>'default|main_popup.php'));

    }

    /**
     * Cr�ation des vignettes manquantes
     *
     * Si des vignettes sont � cr�er, cette page va cr�er une vignette et se recharger pour cr�er les suivantes.
     * Ceci permet d'afficher l'avanc� de la g�n�ration, et �viter les timeout.
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function vignettes ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        if( !(_request("album") ) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array (	'message'=>CopixI18N::get ('album.error.noalbum'),
                    'back'=>CopixUrl::get('||')));
        }

        if( !_sessionGet ('modules|album|vignettes|'._request("key")) ) {

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>_request("album"),'dossier_id'=>_request("dossier")) ));
            /*
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array (	'message'=>CopixI18N::get ('album.error.nothumbscreate'),
                    'back'=>CopixUrl::get('album||album',array('album_id'=>_request("album")))));
            */
        }

        $finish = false;
        $tpl = new CopixTpl ();

        $vignettes = _sessionGet ('modules|album|vignettes|'._request("key"));
        //print_r($vignettes);
        $image = array_shift( $vignettes );
        _sessionSet ('modules|album|vignettes|'._request("key"), $vignettes);
        if( $image == NULL ) {
            $finish = true;
            $tpl->assign ('url_album', _request("album"));
            $tpl->assign ('url_dossier', _request("dossier"));
            $tpl->assign ('url_key', _request("key"));
            $tpl->assign ('finish', $finish);
            $tpl->assign ('message', CopixI18N::get ('album.message.thumbs_end') );

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('album|default|album', array('album_id'=>_request("album"),'dossier_id'=>_request("dossier")) ));
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

        $pAlbum = _request("album");
        $pDossier = _request("dossier");
        $pKey = _request("key");

        $nb = _sessionGet ('modules|album|vignettes|nb-'.$pKey);
        $vignettes = _sessionGet ('modules|album|vignettes|'.$pKey);

        $tpl->assign (
            'message', CopixI18N::get (
                'album.message.thumbs_create',
                array(
                    ($nb - sizeof($vignettes)),
                    $nb
                )
            )
        );

        $tpl->assign ('bar_max', $nb );
        $tpl->assign ('bar_value', $nb - sizeof($vignettes) );

        $result = $tpl->fetch('vignettes.tpl');

        $ppo = new CopixPPO ();
        $ppo->result = $result;
        $ppo->TITLE_PAGE = CopixI18N::get ('album|album.moduleDescription');
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));

        if ($finish)
            CopixHTMLHeader::addOthers ('<meta HTTP-EQUIV="REFRESH" content="3; url='.CopixUrl::get ('album||album', array('album_id'=>$pAlbum, 'dossier'=>$pDossier)).'"');
        else
            CopixHTMLHeader::addOthers ('<meta HTTP-EQUIV="REFRESH" content="0; url='.CopixUrl::get ('album||vignettes', array('album'=>$pAlbum, 'dossier'=>$pDossier, 'key'=>$pKey)).'"');

        $ppo->CAN_CLOSE = false;
        return _arPPO ($ppo, array ('template'=>'vignettes_ppo.tpl', 'mainTemplate'=>'default|main_popup.php'));

        //return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, "album|vignettes.tpl" );
    }

    /**
     * Publication de l'album
     *
     * Copie les fichiers image et vignette, et g�n�re le fichier XML pour SimpleViewer
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function doPublier()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        if( !(_request("album_id") )   ||
            trim(_request("album_id"))==""   ||
            ! preg_match ("/^[0-9]+$/", _request("album_id")) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
                        'back'=>CopixUrl::get('||')));
        }

        $album_id = _request("album_id");

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get($album_id);

        if( _request("dossier_id") && preg_match ("/^[0-9]+$/", _request("dossier_id")) ) $dossier_id = _request("dossier_id");
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

    $publ_size = (CopixConfig::exists ('album|photo_publ_size')) ? CopixConfig::get ('album|photo_publ_size') : 640;

        $file_xml = fopen( $path2public.'/imageData.xml', 'w' );
        $tplXml = new CopixTpl ();
        $tplXml->assign ("publ_size", $publ_size);
        $tplXml->assign ("album_id", $album_id);
        $tplXml->assign ("album_key", $album->album_cle);
        $tplXml->assign ("album_titre", $parent['nom']);
        $tplXml->assign ("dossier_id", $dossier_id);
        if(isset($dossier->dossier_cle)) $tplXml->assign ("dossier_key", $dossier->dossier_cle);
        if(isset($dossier->dossier_nom)) $tplXml->assign ("dossier_titre", $dossier->dossier_nom);

        $photo_dao = CopixDAOFactory::create("photo");
        // $photolist = $photo_dao->findAllByAlbum($album_id);
        $photolist = $photo_dao->findAllByAlbumAndFolder($album_id,$dossier_id);
        $tplXml->assign ("photolist", $photolist);

        if(!is_dir($path2public.'/images/')) mkdir( $path2public.'/images/', 0775 );
        if(!is_dir($path2public.'/thumbs/')) mkdir( $path2public.'/thumbs/', 0775 );
        @chmod( $path2public.'/images/', 0775 );
        @chmod( $path2public.'/thumbs/', 0775 );

        foreach( $photolist AS $photo ) {
            Album::createThumbnail( $photo->album_id.'_'.$photo->album_cle, $photo->photo_id.'_'.$photo->photo_cle, $photo->photo_ext, "s64", false, 'jpg' );
            Album::createThumbnail( $photo->album_id.'_'.$photo->album_cle, $photo->photo_id.'_'.$photo->photo_cle, $photo->photo_ext, $publ_size, false, 'jpg' );
            copy( $path2album.'/'.$photo->photo_id.'_'.$photo->photo_cle.'_'.$publ_size.'.jpg',
                  $path2public.'/images/'.$photo->photo_id.'_'.$photo->photo_cle.'.jpg' );
            copy( $path2album.'/'.$photo->photo_id.'_'.$photo->photo_cle.'_s64.jpg',
                  $path2public.'/thumbs/'.$photo->photo_id.'_'.$photo->photo_cle.'.jpg' );
        }

        $result = $tplXml->fetch("simpleviewer_xml.tpl");
        fwrite( $file_xml, $result );
        fclose( $file_xml );

        $file_html = fopen( $path2public.'/index.html', 'w' );
        $tplHtml = new CopixTpl ();
        $tplHtml->assign ("album_id", $album_id);
        $tplHtml->assign ("album_key", $album->album_cle);
        $tplHtml->assign ("album_titre", $parent['nom']);
        $tplHtml->assign ("dossier_id", $dossier_id);
        if(isset($dossier->dossier_cle)) $tplHtml->assign ("dossier_key", $dossier->dossier_cle);
        if(isset($dossier->dossier_nom)) $tplHtml->assign ("dossier_nom", $dossier->dossier_nom);
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
    public function doDepublier()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        if( !(_request("album_id") )   ||
            trim(_request("album_id"))==""   ||
            ! preg_match ("/^[0-9]+$/", _request("album_id")) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
                        'back'=>CopixUrl::get('||')));
        }

        $album_id = _request("album_id");

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get($album_id);

        if( _request("dossier_id") && preg_match ("/^[0-9]+$/", _request("dossier_id")) ) $dossier_id = _request("dossier_id");
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
    public function getAddZip()
    {
        $ppo = new CopixPPO();

        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        $tpl = new CopixTpl ();

        if( !(_request("album_id") ) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
        }

        $album_id = _request("album_id");

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        if( _request("dossier_id") && preg_match ("/^[0-9]+$/", _request("dossier_id")) ) $dossier_id = _request("dossier_id");
        else $dossier_id = 0;

        $tplAddPhoto = new CopixTpl ();
        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get($album_id);

        // $tplAddPhoto->assign ("album", $album);
        $ppo->album = $album;

        // $tplAddPhoto->assign ("dossier_id", $dossier_id);
        $ppo->dossier_id = $dossier_id;

        // $tplAddPhoto->assign ("file_size_zip", CopixConfig::get ('album|file_size_zip') );
        $ppo->file_size_zip = CopixConfig::get ('album|file_size_zip');

        // $result = $tplAddPhoto->fetch("addzip.tpl");
        return _arPPO ($ppo, array ('template'=>'addzip.tpl', 'mainTemplate'=>'main|main_fancy.php'));

        $tpl->assign ('MAIN', $result);
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.addzip'));

        $menu = array();
        $menu[] = array(
            'txt' => CopixI18N::get ('album.menu.addphoto'),
            'url' => CopixUrl::get ('album||addphoto', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id))
        );
        $menu[] = array(
            'txt' => CopixI18N::get ('album.menu.gotoalbum'),
            'url' => CopixUrl::get ('album||album', array("album_id"=>$album->album_id, "dossier_id"=>$dossier_id)),
            'size' => 95
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
    public function processDoAddZip()
    {
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
                                            array('album_id'=>_request("album_id"), 'dossier_id'=>_request("dossier_id"))
                    )
                )
            );
        }

        $archive = new PclZip($_FILES['fichier']['tmp_name']);
        $list = $archive->listContent();

        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get(_request('album_id'));


        if (!is_array($list)) {

            return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('album|default|album', array('album_id'=>$album->album_id, 'dossier_id'=>_request("dossier_id")) ));
        }


        foreach ($list as $k=>$f) {
            if ($f['size']<=CopixConfig::get ('album|file_size_photo'))
                $okIndex[] = $k;
        }

        umask( 0 );
        $tmpFolder = tempnam ('XXXXX','PclZip_'._request('album_id').'_');
        unlink( $tmpFolder ); mkdir( $tmpFolder );


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
                $nouvelle_photo->photo_dossier = _request("dossier_id");
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
            CopixUrl::get ('album|default|album', array('album_id'=>$album->album_id, 'dossier_id'=>_request("dossier_id")) ));
    }

    public function doFolder()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        if( !(_request("album_id") )   ||
            trim(_request("album_id"))==""   ||
            ! preg_match ("/^[0-9]+$/", _request("album_id")) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.noalbumnumber'),
                        'back'=>CopixUrl::get('||')));
        }

        $album_id = _request("album_id");

        if( Kernel::getLevel( "MOD_ALBUM", $album_id ) < PROFILE_CCV_PUBLISH ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get ('||')));
        }

        $album_dao = CopixDAOFactory::create("album");
        $album = $album_dao->get($album_id);

        $dossier_id = CopixRequest::getInt ('dossier_id', -1);

        if( $dossier_id == -1 ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.nofoldernumber'),
                        'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id) )));
        }

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

        if( !(_request("subaction") ) ) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array (	'message'=>CopixI18N::get ('album.error.badsubaction'),
                        'back'=>CopixUrl::get('album||album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) )));
        }

        switch( _request("subaction") ) {

            case 'new':
                $dossier_new = CopixDAOFactory::createRecord("dossier");
                $dossier_new->dossier_album = $album_id;
                $dossier_new->dossier_parent = $dossier_id;
                $dossier_new->dossier_nom = _request("folder_new", "Nouveau dossier");
                $dossier_new->dossier_comment = '';
                $dossier_new->dossier_date = date("Y-m-d H:i:s");
                $dossier_new->dossier_cle = substr( md5(microtime()), 0, 10 );
                $dossier_new->dossier_public = '0';

                $dossier_dao->insert( $dossier_new );

                return new CopixActionReturn (COPIX_AR_REDIRECT,
                    CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_new->dossier_id) ));

                break;

            case 'rename':
                $dossier->dossier_nom = _request("folder_rename");
                $dossier_dao->update( $dossier );

                return new CopixActionReturn (COPIX_AR_REDIRECT,
                    CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) ));

                break;

            case 'move':
                $dossier->dossier_parent = _request("folder_move");
                $dossier_dao->update( $dossier );

                return new CopixActionReturn (COPIX_AR_REDIRECT,
                    CopixUrl::get ('album|default|album', array('album_id'=>$album_id,'dossier_id'=>$dossier_id) ));

                break;

            case 'delete':
                // action=dofolder subaction=delete album_id=1& dossier_id=4 dossier_todo=moveparent

                Album::delFolder( _request("album_id"), _request("dossier_id"), _request("dossier_todo") );

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

    public function getEditPhotos()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_album.css"));
        $tpl = new CopixTpl ();

        $menu = array();
        $menu[] = array(
            'txt' => CopixI18N::get ('album.menu.gotoalbum'),
            'url' => CopixUrl::get ('album||album', array("album_id"=>_request("album_id"), "dossier_id"=>_request("dossier_id"))),
            'size' => 95
        );
        $tpl->assign ('MENU', $menu );

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('album.title.editphotos'));
        $tpl->assign ('MAIN', CopixZone::process ('album|editphotos', array(
            'album_id'=>_request("album_id"),
            'dossier_id'=>_request("dossier_id"),
        ) ));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function doEditPhotos()
    {
        $photo_dao = CopixDAOFactory::create("photo");
        $pictures = $photo_dao->findAllByAlbumAndFolder(_request('album'),_request('dossier'));

        if( count($pictures) ) {
            foreach( $pictures as $picture ) {
                if( _request('photo_'.$picture->photo_id) ) {
                    $picture_modif = $photo_dao->get($picture->photo_id);
                    $picture_modif->photo_dossier = _request('folder_move');
                    $photo_dao->update( $picture_modif );
                }
            }
        }
        return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('album|default|album', array('album_id'=>_request('album'),'dossier_id'=>_request('dossier')) ));
    }

}
