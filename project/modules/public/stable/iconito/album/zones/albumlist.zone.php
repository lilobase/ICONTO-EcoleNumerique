<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: albumlist.zone.php,v 1.2 2007-06-15 15:32:43 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @link      http://www.iconito.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneAlbumList extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		/*
		$service = & CopixClassesFactory::Create ('Album');

	 	$album_dao = CopixDAOFactory::create("album");
		$albumlist = $album_dao->listAlbums();
		
		foreach( $albumlist as $key => $albumitem ) {
// echo 'modules|album|'.$albumitem->album_id." = ".CopixUserProfile::valueOf ('modules|album|'.$albumitem->album_id, 'droit')."<br />";
			$albumlist[$key]->nb_photos = $service->getNbPhotos($albumitem->album_id);
			$albumlist[$key]->droit_lire = CopixUserProfile::CanDo('modules|album|'.$albumitem->album_id,"lire");
			$albumlist[$key]->droit_publier = CopixUserProfile::CanDo('modules|album|'.$albumitem->album_id,"publier");
			$albumlist[$key]->droit_moderer = CopixUserProfile::CanDo('modules|album|'.$albumitem->album_id,"moderer");
			$albumlist[$key]->droit_administrer = CopixUserProfile::CanDo('modules|album|'.$albumitem->album_id,"administrer");
		}
		*/
		$tpl->assign('albumlist', $albumlist );
		
      // retour de la fonction :
      $toReturn = $tpl->fetch ('albumlist.tpl');
      return true;
   }
}
?>
