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
		$classeur = intval($this->getParam('classeur'));
		$dossier = intval($this->getParam('dossier'));
		$width = intval($this->getParam('width'));
		$height = intval($this->getParam('height'));
		$legendes = $this->getParam('legendes');
		
		$classeur_dao = _dao('classeur|classeur');
		$nbPhotos = 0;
		if ($rClasseur = $classeur_dao->get($classeur)) {
			$fichier_dao = _dao('classeur|classeurfichier');
			$photolist = $fichier_dao->getParDossier($rClasseur->id, $dossier);
			$nbPhotos = count($photolist);
			if ($nbPhotos > 0) {
				if ($mode == 'dewslider') {
					$tailles = explode(',', CopixConfig::get ('album|thumb_sizes'));
					$trouve = null;
					foreach( $tailles as $taille ) {
						if ($trouve)
							break;
						if (substr($taille,0,1)=='s')
							continue;
						if ($taille>=$width)
							$trouve = $taille;
					}
					$arPhotos = array();
					foreach ($photolist as $photo) {
					  if ($photo->estUneImage()) {
					    $arPhotos[] = $photo;
					  }
					}
					generateDewsliderXml ($rClasseur, $arPhotos, $trouve, $legendes);
				}
			}
		}

		$tpl = new CopixTpl ();
		$tpl->assign ('mode', $mode);
		$tpl->assign ('titre', $titre);
		$tpl->assign ('width', $width);
		$tpl->assign ('height', $height);
		$tpl->assign ('rClasseur', $rClasseur);
		$tpl->assign ('nbPhotos', $nbPhotos);
		
		if ($nbPhotos > 0) {
		    $toReturn = $tpl->fetch('zone_photos.tpl');
		}			
		
		return true;
		
	}
}

function generateDewsliderXml ($rClasseur, $photolist, $trouve, $legendes) {
	
    $folder = 'static/classeur/'.$rClasseur->id.'-'.$rClasseur->cle;
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
			$flush .= '<img src="'.$photo->getLienMiniature($trouve, '').'" title="'.($photo->titre).'" />';
		}
		
		$flush .= '
</album>
		';
		
		$result = $flush;
		fwrite( $file_xml, $result );
		fclose( $file_xml );
	}
}