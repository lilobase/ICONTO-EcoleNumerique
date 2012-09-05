<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: editphotos.zone.php,v 1.2 2007-12-07 16:20:07 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @link      http://www.iconito.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneEditPhotos extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $pAlbumId = $this->getParam('album_id');
        $pDossierId = $this->getParam('dossier_id');

        // $album_dao = _dao("album");
        $dossier_dao = _dao("dossier");
        $photo_dao = _dao("photo");


        if( $pDossierId > 0 ) {
            $dossier = $dossier_dao->get($pDossierId);
        } else {
            $dossier->dossier_id = 0;
            $dossier->dossier_album = $this->getParam('album_id');
            $dossier->dossier_parent = -1;
            $dossier->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
            $dossier->dossier_comment = "";
            $dossier->album_id = $pAlbumId;
        }
        if( $dossier->dossier_album != $pAlbumId ) return false;

        $pictures = $photo_dao->findAllByAlbumAndFolder($pAlbumId,$pDossierId);

        $tpl->assign('album_id',   $pAlbumId );
        $tpl->assign('dossier_id', $pDossierId );

        $tpl->assign('dossier',    $dossier );
        $tpl->assign('pictures',   $pictures );

        $tpl->assign('picture_thumbsize', '_s64' );

        $dossiers_tree = Album::getFoldersTree( $pAlbumId );
        $dossiers_commands = Album::tree2commands( $dossiers_tree );
        $tpl->assign('commands_move', $dossiers_commands );

        $toReturn = $tpl->fetch ('editphotos.tpl');
        return true;
    }
}
