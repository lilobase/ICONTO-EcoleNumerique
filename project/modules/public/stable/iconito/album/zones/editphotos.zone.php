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
class ZoneEditPhotos extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		// $album_dao = _dao("album");
		$dossier_dao = _dao("dossier");
		$photo_dao = _dao("photo");
		
		
		if( $this->getParam('dossier_id') > 0 ) {
			$dossier = $dossier_dao->get($this->getParam('dossier_id'));
		} else {
			$dossier->dossier_id = 0;
			$dossier->dossier_album = $this->getParam('album_id');
			$dossier->dossier_parent = -1;
			$dossier->dossier_nom = CopixI18N::get ('album|album.message.topfolder');
			$dossier->dossier_comment = "";
			$dossier->album_id = $album_id;
		}
		if( $dossier->dossier_album != $this->getParam('album_id') ) return false;
		
		$pictures = $photo_dao->findAllByAlbumAndFolder($this->getParam('album_id'),$this->getParam('dossier_id'));
		
		$tpl->assign('album_id',   $this->getParam('album_id') );
		$tpl->assign('dossier_id', $this->getParam('dossier_id') );
		
		$tpl->assign('dossier',    $dossier );
		$tpl->assign('pictures',   $pictures );
		
		$tpl->assign('picture_thumbsize', '_s64' );
		
		$dossiers_tree = Album::getFoldersTree( $this->getParam('album_id') );
		$dossiers_commands = Album::tree2commands( $dossiers_tree );
		$tpl->assign('commands_move', $dossiers_commands );
		
		$toReturn = $tpl->fetch ('editphotos.tpl');
		return true;
	}
}
?>
