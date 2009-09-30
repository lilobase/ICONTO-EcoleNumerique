<?php

/**
 * Affichage de photos
 * 
 * @package Iconito
 * @subpackage Welcome
 */
class ZonePhotos extends CopixZone {

	/**
	 * Affiche des photos d'un album
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/11/10
	 * @param string $titre Titre a donner a la zone
	 * @param string $mode Mode utilise. Uniquement dewslider pour l'instant
	 * @param integer $album Id de l'album ou on pioche
	 * @param integer $dossier Id du dossier de l'album ou on pioche
	 * @param integer $width Largeur du player, en pixels
	 * @param integer $height Hauteur du player, en pixels
	 * @param boolean $legendes Si on affiche ou pas les legendes de chaque photo
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$titre = $this->getParam('titre');
		$mode = $this->getParam('mode');
		$album = intval($this->getParam('album'));
		$dossier = intval($this->getParam('dossier'));
		$width = intval($this->getParam('width'));
		$height = intval($this->getParam('height'));
		$legendes = $this->getParam('legendes');
		
		$arPhotos = array();
		$album_dao = _dao("album|album");
		$nbPhotos = 0;
		if ($rAlbum = $album_dao->get($album)) {
			//var_dump($rAlbum);
			
			$photo_dao = _dao("album|photo");
			$photolist = $photo_dao->findAllByAlbumAndFolder($album,$dossier);
			//var_dump($photolist);
			$nbPhotos = count($photolist);
			
			if ($nbPhotos) {
				
				if ($mode == 'dewslider') {
				
					$tailles = explode(",",CopixConfig::get ('album|thumb_sizes'));
					$trouve = null;
					foreach( $tailles as $taille ) {
						if ($trouve)
							break;
						if (substr($taille,0,1)=='s')
							continue;
						if ($taille>=$width)
							$trouve = $taille;
					}
					
					foreach ($photolist as $key=>$photo) {
						$photolist[$key]->folder = CopixUrl::getRequestedScriptPath ().'static/album/'.$photo->album_id.'_'.$photo->album_cle;
						$photolist[$key]->file = $photo->photo_id.'_'.$photo->photo_cle.'_'.$trouve.'.'.$photo->photo_ext;
					}
					
					$arPhotos = $photolist;
					generateDewsliderXml ($rAlbum, $photolist, $trouve, $legendes);
				
				}
						
			}
			
		}
		//var_dump($arPhotos);
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('mode', $mode);
		$tpl->assign ('titre', $titre);
		$tpl->assign ('width', $width);
		$tpl->assign ('height', $height);
		$tpl->assign ('rAlbum', $rAlbum);
		$tpl->assign ('nbPhotos', $nbPhotos);
		
		if ($nbPhotos>0)
			$toReturn = $tpl->fetch('zone_photos.tpl');
		
		return true;
		
	}
}


// Voir http://www.alsacreations.fr/dewslider



function generateDewsliderXml ($rAlbum, $photolist, $trouve, $legendes) {
		
		$folder = 'static/album/'.$rAlbum->album_id.'_'.$rAlbum->album_cle;
		if ($file_xml = @fopen( $folder.'/dewslider.xml', 'w' )) {
		
			$showtitles = ($legendes) ? 'yes' : 'no';
			
			$flush = '<?xml version="1.0" ?>
	<album
	showbuttons="yes"
	showtitles="'.$showtitles.'"
	randomstart="yes"
	timer="4"
	aligntitles="bottom"
	alignbuttons="bottom"
	transition="blur"
	speed="10"
	>';
			foreach( $photolist AS $photo ) {
				$flush .= "\n";
				$flush .= '<img src="'.$photo->folder.'/'.$photo->file.'" title="'.($photo->photo_comment).'" />';
			}
			
			$flush .= '
	</album>
			';
			
			$result = $flush;
			fwrite( $file_xml, $result );
			fclose( $file_xml );

		}
}

?>
