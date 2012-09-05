<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: album.class.php,v 1.21 2008-10-20 08:04:43 fmossmann Exp $
* @author   Fr�d�ric Mossmann
* @copyright 2005 CDC-TIC
* @link      http://www.cdc-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class Album
{
    /**
     * getPhoto
     *
     * Retourne informations d'une photo en fonction de son identifiant
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @param integer $photo_id Id de la photo
     * @return object Informations sur la photo
     */
    public function getPhoto( $photo_id )
    {
        $photo_dao = _dao("photo");
        $photo = $photo_dao->get($photo_id);
        if( $photo === false ) {
            return false;
        } else {
            return( $photo );
        }
    }

    /**
     * getNbPhotos
     *
     * Retourne le nombre de photos d'un album
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @param integer $album_id Id de l'album
     * @return integer Nombre de photos
     */
    public function getNbPhotos( $album_id, $dossier_id )
    {
        $photo_dao = _dao("photo");
        $photo = $photo_dao->findAllByAlbumAndFolder($album_id, $dossier_id);
        return( count($photo) );
    }

    /**
     * getPrevPhoto
     *
     * Retourne l'identifiant de la photo pr�c�dente
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $album_id Id de l'album
     * @param integer $photo_id Id de la photo
     * @return integer Identifiant de la photo pr�c�dente
     */
    public function getPrevPhoto($album_id, $dossier_id, $photo_id)
    {
        $dao = _dao("photo");
        $photo = $dao->getPrevPhoto($album_id, $dossier_id, $photo_id);
        if (count($photo) > 0) {
            return( $photo[0]->photo_id );
        } else {
            return( 0 );
        }
    }

    /**
     * getNextPhoto
     *
     * Retourne l'identifiant de la photo suivante
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $album_id Id de l'album
     * @param integer $photo_id Id de la photo
     * @return integer Identifiant de la photo suivante
     */
    public function getNextPhoto($album_id, $dossier_id, $photo_id)
    {
        $dao = _dao("photo");
        $photo = $dao->getNextPhoto($album_id, $dossier_id, $photo_id);
        if (count($photo) > 0) {
            return( $photo[0]->photo_id );
        } else {
            return( 0 );
        }
    }

    /**
     * createKey
     *
     * Retourne une cl� hexa de 10 caract�res al�atoires
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     * @return string Cl� hexa de 10 caract�res al�atoires
     */
    public function createKey()
    {
        return substr( md5(microtime()), 0, 10 );
    }

    /**
     * createThumbnails
     *
     * Demande la cr�ation de toutes les tailles de vignette d'une image
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param string $album Nom de la photo et cl�, s�par�s par '_'
     * @param string $file Nom de l'album et cl�, s�par�s par '_'
     * @param string $ext Extension de la photo (jpg, gif, etc.)
     */
    public function createThumbnails( $album, $file, $ext )
    {
        $ok = true;
        $tailles = explode(",",CopixConfig::get ('album|thumb_sizes'));
        foreach( $tailles AS $taille ) {
            $ok &= Album::createThumbnail( $album, $file, $ext, $taille );
        }

        return $ok;
    }

    /**
     * createThumbnail
     *
     * G�n�re la vignette d'une image en fonction d'une taille donn�e
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param string $album Nom de la photo et cl�, s�par�s par '_'
     * @param string $file Nom de l'album et cl�, s�par�s par '_'
     * @param string $ext Extension de la photo (jpg, gif, etc.)
     * @param string $taille Taille de la vignette � cr�er (avec 's' pour un format carr�)
     * @param boolean $force Force la cr�ation de la vignette, m�me si elle existe d�j�
     */
    public function createThumbnail( $album, $file, $ext, $taille="s128", $force=false, $toext='' )
    {
        $memoryLimit = ini_get("memory_limit");
        switch( substr($memoryLimit,-1) ) {
            case 'K':
                $memoryLimit = 1024*(int)substr($memoryLimit,0,-1);
                break;
            case 'M':
                $memoryLimit = 1024*1024*(int)substr($memoryLimit,0,-1);
                break;
            case 'G':
                $memoryLimit = 1024*1024*1024*(int)substr($memoryLimit,0,-1);
                break;
        }

        // A faire dans le .htaccess
        // @ini_set( 'memory_limit', '64M' );
        @ini_set( 'max_execution_time', '120' );

        $path2data = realpath("static");
        $pathfolder = $path2data.'/album/'.$album;
        $pathfile = $pathfolder.'/'.$file.'.'.$ext;

        if( $toext!='' ) { // Changement de format
            $savedfile = $pathfolder.'/'.$file.'_'.$taille.'.'.$toext;
            if( file_exists($savedfile) && !$force ) return true;
        } else { // Conservation du format
            $savedfile = $pathfolder.'/'.$file.'_'.$taille.'.'.$ext;
            if( file_exists($savedfile) && !$force ) return true;
        }

        // D�codage de la taille ('s' pour carr�e et nombre de pixels)
        if( ereg("^s([0-9]+)$", $taille, $regs) ) {
            $size = $regs[1];
            $mode = "square";
        } else {
            $size = $taille;
            $mode = "normal";
        }

        // R�cup�ration des infos de l'image (sinon erreur)
        $file_info = getimagesize($pathfile);
        if( $file_info == false ) {
            //echo "Erreur : ".$pathfile;
            return false;
        }
        list($width, $height, $type, $attr) = $file_info;

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
            $square_thumbsize = $size;
        }
        $ratio = max($width,$height) / $size;

        // Doit-on r�duite l'image ?
        if( $ratio > 1 ) {
            $new_width  = round($width/$ratio);
            $new_height = round($height/$ratio);
        } else {
            $new_width  = $width;
            $new_height = $height;
        }

        // M�moire d�j� utilis�e par PHP et Iconito
        $memoryUsed = memory_get_usage();

        // M�moire pr�vue pour ouvrir le fichier original
        if(!isset($file_info['channels'])) $file_info['channels']=4; // Normalement 3, mais au pire avec l'AlphaChannel, on prend 4.
        if(!isset($file_info['bits']))     $file_info['bits'    ]=8; // 8 bits.
        $memoryNeeded = round(($file_info[0] * $file_info[1] * $file_info['bits'] * $file_info['channels'] / 8 + Pow(2, 16)) * 1.65);

        // M�moire pr�vue pour ouvrir la vignette (en pr�voyant un peu large)
        $memoryNeeded += round((1024 * 1024 * 8 * 4 / 8 + Pow(2, 16)) * 1.65);

        // Est-ce qu'il reste assez de m�moire pour allouer l'image originale et la vignette ?
        if( ($memoryLimit-($memoryUsed+$memoryNeeded)) <0 ) {
            /*
            echo "<li>memoryLimit=".$memoryLimit."</li>";
            echo "<li>memoryUsed=".$memoryUsed."</li>";
            echo "<li>memoryNeeded=".$memoryNeeded."</li>";
            echo "<li>Free=".($memoryLimit-($memoryUsed+$memoryNeeded))."</li>";
            */
            return false;
        }

        // Ouverture du fichier en fonction du format
        switch( $ext ) {
            case "gif":
                $image = @imagecreatefromgif($pathfile);
                break;
            case "jpg":
                $image = @imagecreatefromjpeg($pathfile);
                break;
            case "png":
                $image = @imagecreatefrompng ($pathfile);
                break;
        }

        // Si l'image n'est pas ouverte (malgr� les tests), errur.
        if (!$image) {
            return false;
            /* Exemple d'image blanche d'erreur.
            $image = imagecreate(200, 30); // Cr�ation d'une image blanche
            $bgc = imagecolorallocate($image, 255, 255, 255);
            $tc  = imagecolorallocate($image, 128, 128, 128);
            imagefilledrectangle($image, 0, 0, 200, 30, $bgc);
            imagestring($image, 1, 10, 10, "Erreur !", $tc);
            */
        }

        // echo "<li>Usage Apres ouverture: ".memory_get_usage()."</li>";

        if( $mode == "square" ) {
            // Mode carr�...
            $image_p = imagecreatetruecolor($square_thumbsize, $square_thumbsize);
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);

            // $white = imagecolorallocate($image_p, 255, 255, 255);
            // imagefill($image_p, 0, 0, $white);

            imagecopyresampled($image_p, $image, 0, 0,
                $square_x, $square_y,
                $square_thumbsize, $square_thumbsize,
                $square_size, $square_size);
        } else {
            // Mode standard...
            $image_p = imagecreatetruecolor($new_width, $new_height);
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);

            // $white = imagecolorallocate($image_p, 255, 255, 255);
            // imagefill($image_p, 0, 0, $white);

            imagecopyresampled($image_p, $image, 0, 0,
                0, 0,
                $new_width, $new_height,
                $width, $height);
        }

        if( $toext!='' ) $ext=$toext;

        // Enregistrement de l'image au format souhait�
        switch( $ext ) {
            case "gif":
                imagegif ( $image_p, $savedfile );
                break;
            case "jpg":
                imagejpeg ( $image_p, $savedfile );
                break;
            case "png":
                imagepng ( $image_p, $savedfile );
                break;
        }

        if($image_p) imagedestroy($image_p);
        imagedestroy($image);
        return true;
    }


    /**
     * checkThumbnails
     *
     * Demande la v�rification des vignettes pour toutes les images d'un album
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param string $album Nom de la photo et cl�, s�par�s par '_'
     */
    public function checkThumbnails( $album, $key )
    {
        $failed=0;

        _sessionSet ('modules|album|vignettes|nb-'.$key, 0);

        $album_dao = _dao("photo");
        $photolist = $album_dao->findAllByAlbum($album);

        foreach( $photolist AS $photothumb ) {

            $failed += Album::checkThumbnail(
                $photothumb->album_id.'_'.$photothumb->album_cle,
                $photothumb->photo_id.'_'.$photothumb->photo_cle,
                $photothumb->photo_ext,
                $key );
        }

        return $failed;
    }


    /**
     * checkThumbnail
     *
     * V�rifie la pr�sence des vignettes d'une image
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param string $album Nom de la photo et cl�, s�par�s par '_'
     * @param string $file Nom de l'album et cl�, s�par�s par '_'
     * @param string $ext Extension de la photo (jpg, gif, etc.)
     */
    public function checkThumbnail( $album, $file, $ext, $key )
    {
        $failed=0;
        $path2data = realpath("static");
        $pathfolder = $path2data.'/album/'.$album;



        $tailles = explode(",",CopixConfig::get ('album|thumb_sizes'));
        foreach( $tailles AS $taille ) {
            $savedfile = $pathfolder.'/'.$file.'_'.$taille.'.'.$ext;
            //print_r($savedfile);
            if( !file_exists($savedfile) ) {
                $failed++;

                if (!$get = _sessionGet ('modules|album|vignettes|'.$key)) {
                    $get = array();
                }

                $get[] = array(
                    'album'=>$album,
                    'photo'=>$file,
                    'taille'=>$taille,
                    'ext'=>$ext,
                );
                _sessionSet ('modules|album|vignettes|'.$key, $get);

                $nb = _sessionGet ('modules|album|vignettes|nb-'.$key);
                _sessionSet ('modules|album|vignettes|nb-'.$key, $nb+1);
            }
        }

        return $failed;
    }

    /**
     * delPhoto
     *
     * Efface une photo
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $photo_id Id de la photo
     */
    public function delPhoto( $photo_id )
    {
        $photo_dao = _dao("album|photo");

        $photo = $photo_dao->get($photo_id);
        if( !$photo ) return false;

        $path2data = realpath("static");
        $pathfolder = $path2data.'/album/'.$photo->album_id."_".$photo->album_cle;
        $pathfile = $pathfolder.'/'.$photo->photo_id.'_'.$photo->photo_cle.'.'.$photo->photo_ext;


        if(!$dh = @opendir($pathfolder)) return;
        while (($obj = readdir($dh))) {
            // Recherche des fichiers li�s � l'image (vignettes)
            if( preg_match( '/'.$photo->photo_id.'_'.$photo->photo_cle.'(_s?[0-9]+)?\.'.$photo->photo_ext.'/', $obj ) ) {
                unlink($pathfolder.'/'.$obj);
            }
        }

        // if(file_exists($pathfile)) unlink($pathfile);
        $photo_dao->delete($photo_id);
    }

    /**
     * delAlbum
     *
     * Efface un album
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $album_id Id de l'album
     */
    public function delAlbum( $album_id )
    {
        $photo_dao = _dao("album|photo");
    _classInclude('malle|malleservice');

        $photos = $photo_dao->findAllByAlbum($album_id);
        foreach( $photos AS $photo ) {
            Album::delPhoto( $photo->photo_id );
        }

        $album_dao = _dao("album|album");
        $album = $album_dao->get($album_id);
        if( $album ) {
            $path2data = realpath("static");
            $pathfolder = $path2data.'/album/'.$album->album_id."_".$album->album_cle;

      MalleService::deleteDir ($pathfolder);

            $album_dao->delete($album_id);
        }

        Kernel::unregisterModule( "MOD_ALBUM", $album_id );
    }

    /**
     * getFoldersTree
     *
     * Retourne l'arbre des dossiers d'un album
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $album_id Id de l'album
      * @param integer $parent_id Id du parent (facultatif)
     */
    public function getFoldersTree( $album_id, $dossier_id=-1 )
    {
        // Liste des dossiers d'un album en vrac
        $dossiers_dao = _dao("dossier");

        $list2 = array();
        $dossiers_list = $dossiers_dao->findAllByAlbum($album_id);
        foreach ($dossiers_list as $r)
            $list2[] = $r;

        $r = new CopixPPO();
        $r->dossier_id = 0;
        $r->dossier_album = $album_id;
        $r->dossier_parent = -1;
        $r->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
        $r->dossier_comment = "";
        $r->album_id = $album_id;
        $list1 = array($r);

        $dossiers_list = array_merge( $list1, $list2 );

        // Liste des photos d'un album en vrac
        $photos_dao = _dao("photo");
        $photos_list = $photos_dao->findAllByAlbum($album_id);

        // Tri des photos par dossiers
        $photos_folder = array();
        reset( $photos_list );
        foreach( $photos_list AS $key => $photo ) {
            $photos_folder[$photo->photo_dossier][] = $photo;
        }

        // Pour chaque dossier, copie des photos de ce dossier
        reset( $dossiers_list );
        foreach( $dossiers_list AS $key => $dossier ) {
            $dossiers_list[$key]->photos = (isset($photos_folder[$dossier->dossier_id])) ? $photos_folder[$dossier->dossier_id] : array();
        }

        // Transformation de la liste brute en arbre
        $dossiers_tree = Album::getFolderTreeFromList( $dossiers_list, $dossier_id );
                //kernel::MyDebug($dossiers_tree);
        return( $dossiers_tree );
    }

    /**
     * getFolderTreeFromList (priv�e)
     *
     * Fonction priv�e de construction de l'arbre des dossiers d'un album
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
     */
    public function getFolderTreeFromList( $dossiers_list, $parent=-1 )
    {
        $dossiers_tree = array();
        foreach( $dossiers_list AS $key => $dossier ) {
            if( $dossier->dossier_parent == $parent ) {

                $dossiers_childs = Album::getFolderTreeFromList( $dossiers_list, $dossier->dossier_id );

                if( count($dossiers_childs) ) {
                    $dossier->childs = $dossiers_childs;
                }

                $dossiers_tree[] = $dossier;

            }
        }

            return $dossiers_tree;
    }

    /**
     * delFolder
     *
     * Efface un dossier
     *
     * @author Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
      * @param integer $album_id Id de l'album
      * @param integer $dossier_id Id du dossier
      * @param string $mode Mode d'effacement (conservation des photos ou non)
     * 	 */
    public function delFolder( $album_id, $dossier_id, $mode="moveparent" )
    {
        $album_dao = _dao("album");
        $dossier_dao = _dao("dossier");
        $photo_dao = _dao("photo");

        if( $dossier_id <= 0 ) return false;
        $dossier = $dossier_dao->get($dossier_id);
        if( $dossier->dossier_album != $album_id ) return false;

        $folders = Album::getFoldersTree( $album_id, $dossier_id );
        $pictures = $photo_dao->findAllByAlbumAndFolder($album_id,$dossier_id);

        if( _request('dossier_todo')=='moveparent' ) {

            // D�placement des dossiers...
            foreach( $folders as $folder_tmp ) {
                $dossier_modif = $dossier_dao->get($folder_tmp->dossier_id);
                $dossier_modif->dossier_parent = $dossier->dossier_parent;
                $dossier_dao->update( $dossier_modif );
            }

            // D�placement des photos...
            foreach( $pictures as $picture_tmp ) {
                $picture_modif = $photo_dao->get($picture_tmp->photo_id);
                $picture_modif->photo_dossier = $dossier->dossier_parent;
                $photo_dao->update( $picture_modif );
            }

            // Suppression du dossier vide...
            $dossier_dao->delete( $dossier_id );

        } elseif( _request('dossier_todo')=='deleteall' ) {
//			echo "<li><tt>BEGIN Delete (album=".$album_id."/dossier=".$dossier_id.")</tt></li>";
//			echo "<ul>";

            // D�placement des dossiers...
            foreach( $folders as $folder_tmp ) {
                $dossier_modif = $dossier_dao->get($folder_tmp->dossier_id);
                Album::delFolder( $album_id, $dossier_modif->dossier_id, $mode );
            }

            // D�placement des photos...
            foreach( $pictures as $picture_tmp ) {
                $picture_modif = $photo_dao->get($picture_tmp->photo_id);
//				echo "<li><tt>Delete photo (album=".$album_id."/dossier=".$dossier_id."/photo=".$picture_modif->photo_id.")</tt></li>";
                Album::delPhoto( $picture_modif->photo_id );
            }

            // Suppression du dossier vide...
//			echo "<li><tt>Delete dossier (album=".$album_id."/dossier=".$dossier_id.")</tt></li>";
            $dossier_dao->delete( $dossier_id );

//			echo "</ul>";
//			echo "<li><tt>END   Delete (album=".$album_id."/dossier=".$dossier_id.")</tt></li>";
        } else {

        }

        return true;
    }

    public function tree2commands( $dossiers_tree )
    {
        $cmds = array();
        //var_dump($dossiers_tree);
        if( count($dossiers_tree) ) {
            // $cmds[] = array( 'type'=>'open' );

            foreach( $dossiers_tree as $key=>$val ) {
                $childs = (isset($val->childs)) ? $val->childs : array();
                unset( $val->childs );
                $cmds[] = array(
                    'type'=>'folder',
                    'data'=>$val
                );

                $childs_cmds = Album::tree2commands( $childs );
                if( count($childs_cmds) ) {
                    $cmds[] = array( 'type'=>'open' );
                    $cmds = array_merge( $cmds, $childs_cmds );
                    $cmds[] = array( 'type'=>'close' );
                }
            }
            // $cmds[] = array( 'type'=>'close' );
        }
        //var_dump($cmds);
        return $cmds;
    }

    public function tree2move( $dossiers_tree, $dossier_courant )
    {
        foreach( $dossiers_tree as $key => $val ) {
            /*
            if( $dossiers_tree[$key]->dossier_id == $dossier_courant || $dossier_interdit) {
                $dossiers_tree[$key]->move_destination = 0;
                $dossier_interdit = true;
            } else {
                $dossiers_tree[$key]->move_destination = 1;
            }

            if( isset( $dossiers_tree[$key]->childs ) ) {
                $dossiers_tree[$key]->childs = $this->tree2move( $dossiers_tree[$key]->childs, $dossier_courant, $dossier_interdit );
            }
            */
            if( $dossiers_tree[$key]->dossier_id == $dossier_courant ) {
                unset( $dossiers_tree[$key] );
            } else {
                if( isset( $dossiers_tree[$key]->childs ) ) {
                    $dossiers_tree[$key]->childs = Album::tree2move( $dossiers_tree[$key]->childs, $dossier_courant );
                }
            }
        }
        return( $dossiers_tree );
    }

}

