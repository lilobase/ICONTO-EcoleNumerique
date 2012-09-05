<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: kernelalbum.class.php,v 1.9 2009-01-23 17:34:11 cbeyer Exp $
* @author   Fr�d�ric Mossmann
* @copyright 2005 CDC-TIC
* @link      http://www.cdc-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class KernelAlbum
{
    /* Cr�e un album photos
         Renvoie son ID ou NULL si erreur
 * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
    */
    public function create ($infos=array())
    {
        $return = NULL;
        $cle = substr( md5(microtime()), 0, 10 );
        $dao = _dao("album|album");
        $new = _record("album|album");
        $new->album_nom = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
        $new->album_prefs = "aa";
        $new->album_date = date("Y-m-d H:i:s");
        $new->album_cle = $cle;
        $new->album_public = 0;
        $dao->insert ($new);
        if ($new->album_id!==NULL) {

            // $path2data = realpath("../data");
            $path2data = realpath("static");
            $folder = $path2data.'/album/'.$new->album_id."_".$cle;

            $mkdir = mkdir ($folder, 0777);
            if ($mkdir) {
                chmod ($folder, 0777);
                $return = $new->album_id;
            }
        }
        return $return;
    }

    /*
        Renvoie diff�rentes infos chiffr�es d'un album photos
    */
    public function getStats ($id_album)
    {
        $dao = _dao("album|album");
        $res = array();
        $infos = $dao->getNbPhotosInAlbum($id_album);
        $res['nbPhotos'] = array ('name'=>CopixI18N::get ('album|album.stats.nbPhotos', array($infos[0]->nb)));
        return $res;
    }

    public function delete ($id_album)
    {
        $albumService = & CopixClassesFactory::Create ('album|album');
        $albumService->delAlbum( $id_album );
        return true;
    }

    /*
    Publication distante (autre module).
    id de l'album + donn�es -> infos sur la nouvelle donn�es dans l'album
    */
    public function publish ($id, $image)
    {
        // $image['file']  -> nom de fichier
        // $image['title'] -> titre (ou nom � d�faut)
        // $image['body']  -> commentaire
        // $image['data']  -> donn�es

        if( !isset($image['file']) || trim($image['file'])==''
        ||  !isset($image['data']) ||      $image['data'] =='' ) {
            return false;
        }

        $album_dao = _dao("album|album");
        $album = $album_dao->get($id);
        if( $album==null ) {
            return false;
        }

        $ext='';
        switch( strtolower(strrchr($image['file'], ".")) ) {
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
            $album_service = & CopixClassesFactory::Create ('album|album');

            $photo_dao = & _dao("album|photo");
            $nouvelle_photo = _record("album|photo");
            $nouvelle_photo->photo_album = $album->album_id;
            $nouvelle_photo->photo_dossier = 0;
            if( trim($image['title']) != '' )
                $nouvelle_photo->photo_nom = $image['title'];
            else
                $nouvelle_photo->photo_nom = $image['file'];
            $nouvelle_photo->photo_comment = '';
            $nouvelle_photo->photo_date = date("Y-m-d H:i:s");
            $nouvelle_photo->photo_ext = $ext;
            $nouvelle_photo->photo_cle = $album_service->createKey();
            $photo_dao->insert( $nouvelle_photo );
            if( $nouvelle_photo->photo_id ) {
                $path2data = realpath("static");
                $path2album = $path2data."/album/".$album->album_id."_".$album->album_cle;
                $photofile = $path2album."/".$nouvelle_photo->photo_id."_".$nouvelle_photo->photo_cle.'.'.$ext;
                $file = fopen( $photofile, 'w' );
                fwrite( $file, $image['data'] );
                fclose( $file );
            }

            $ok = $album_service->createThumbnails(
                $album->album_id.'_'.$album->album_cle ,
                $nouvelle_photo->photo_id.'_'.$nouvelle_photo->photo_cle ,
                $ext );

            if( $ok ) {
                $results = array(
                    'title'     => $nouvelle_photo->photo_nom,
                    'album_id'  => $album->album_id,
                    'album_key' => $album->album_cle,
                    'photo_id'  => $nouvelle_photo->photo_id,
                    'photo_key' => $nouvelle_photo->photo_cle,
                    'photo_ext' => $ext,
                );
            } else {
                $photo_dao->delete( $nouvelle_photo->photo_id );
            }

            return $results;
        }

        return false;

    }

    public function getStatsRoot()
    {
        $res = array();
        /*
         * Nombre d'albums
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_album_albums';
        $a = _doQuery($sql);
        $res['nbAlbums'] = array('name' => CopixI18N::get('album|album.stats.nbAlbums', array($a[0]->nb)));

        /*
         * Nombre de dossiers
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_album_dossiers';
        $a = _doQuery($sql);
        $res['nbDossiers'] = array('name' => CopixI18N::get('album|album.stats.nbDossiers', array($a[0]->nb)));

        /*
         * Nombre de photos
         */
        $sql = '
            SELECT COUNT(id) AS nb
            FROM module_album_photos';
        $a = _doQuery($sql);
        $res['nbPhotos'] = array('name' => CopixI18N::get('album|album.stats.nbPhotos', array($a[0]->nb)));

        return $res;
    }

}

