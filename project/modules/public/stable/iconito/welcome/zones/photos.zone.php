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
		
		$tpl = new CopixTpl ();
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

    // Récupération des paramètres
		$titre = $this->getParam('titre');
		$mode = $this->getParam('mode');
		$classeur = intval($this->getParam('classeur'));
		$album = intval($this->getParam('album'));
		$dossier = intval($this->getParam('dossier'));
		$width = intval($this->getParam('width'));
		$height = intval($this->getParam('height'));
		$legendes = $this->getParam('legendes');
		
		
    // Classeur
    if ($classeur != 0) {
      $classeur_dao = _dao('classeur|classeur');
  		$nbPhotos = 0;
  		if ($rClasseur = $classeur_dao->get($classeur)) {
  			$fichier_dao = _dao('classeur|classeurfichier');
  			$photolist = $fichier_dao->getParDossier($rClasseur->id, $dossier);
  			$nbPhotos = count($photolist);
  			if ($nbPhotos > 0 && $mode == 'dewslider') {
  				$arPhotos = array();
  				foreach ($photolist as $photo) {
  				  if ($photo->estUneImage()) {
  				    $arPhotos[] = $photo;
  				  }
  				}
  				generateClasseurDewsliderXml ($rClasseur, $arPhotos, $width, $legendes);
  				
  				$tpl->assign ('rClasseur', $rClasseur);
  			}
  		}
    }
    elseif ($album != 0) {
      $album_dao = _dao('album|album');
      $nbPhotos = 0;
      if ($rAlbum = $album_dao->get($album)) {
        $photo_dao = _dao('album|photo');
        $photolist = $photo_dao->findAllByAlbumAndFolder($album, $dossier);
        $nbPhotos = count($photolist);
        if ($nbPhotos > 0 && $mode == 'dewslider') {
          foreach ($photolist as $key=>$photo) {
            $photolist[$key]->folder = CopixUrl::getRequestedScriptPath ().'static/album/'.$photo->album_id.'_'.$photo->album_cle;
            $photolist[$key]->file = $photo->photo_id.'_'.$photo->photo_cle.'_'.$width.'.'.$photo->photo_ext;
          }
          
          generateAlbumDewsliderXml ($rAlbum, $photolist, $width, $legendes);
          
          $tpl->assign ('rAlbum', $rAlbum);
        }
      }
		}

		$tpl->assign ('mode', $mode);
		$tpl->assign ('titre', $titre);
		$tpl->assign ('width', $width);
		$tpl->assign ('height', $height);
		$tpl->assign ('nbPhotos', $nbPhotos);
		
		if ($nbPhotos > 0) {
		    $toReturn = $tpl->fetch('zone_photos.tpl');
		}			
		
		return true;
	}
}

function generateAlbumDewsliderXml ($rAlbum, $photolist, $width, $legendes) {
  	
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
</album>';

    $result = $flush;
    fwrite( $file_xml, $result );
    fclose( $file_xml );
  }
}

function generateClasseurDewsliderXml ($rClasseur, $photolist, $width, $legendes) {
	
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
			$flush .= '<img src="'.$photo->getLienMiniature($width, '').'" title="'.($photo->titre).'" />';
		}
		
		$flush .= '
</album>
		';
		
		$result = $flush;
		fwrite( $file_xml, $result );
		fclose( $file_xml );
	}
}