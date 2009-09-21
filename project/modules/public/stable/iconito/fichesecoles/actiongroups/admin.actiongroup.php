<?php
/**
 * Actiongroup du module Fichesecoles - Back office
 * 
 * @package	Iconito
 * @subpackage fichesecole
 */

_classInclude('fichesecoles|fichesecolesservice');

class ActionGroupAdmin extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
		CopixTpl::setTheme(Kernel::getTheme());
	}

	/**
   * Formulaire de modification des infos d'une ecole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param integer $id Id de l'ecole
   */
	 function form () {

		$id = $this->getRequest('id', null);
		$save = $this->getRequest('save', null);

		$nbZones = 4;
		
		$horaires = $this->getRequest('horaires', null);
		for ($i=1 ; $i<=$nbZones ; $i++) {
			$field = 'zone'.$i.'_titre';
			$$field = $this->getRequest($field, null);
			$field = 'zone'.$i.'_texte';
			$$field = $this->getRequest($field, null);
		}
		
		$photoMaxWidth = CopixConfig::get ('fichesecoles|photoMaxWidth');
		
		$criticErrors = $errors = array();
		
		$ecoleDAO = CopixDAOFactory::create('kernel|kernel_bu_ecole');
		$ficheDAO = CopixDAOFactory::create("fiches_ecoles");
		
		if (!$rEcole = $ecoleDAO->get($id))
			$criticErrors[] = CopixI18N::get ('fichesecoles.error.param');
		elseif (!FichesEcolesService::canMakeInFicheEcole($id,'MODIFY'))
			$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));


		//$tplForm->assign ('',$errors);
		//var_dump($rEcole);
		
		
		$canModifyVille = FichesEcolesService::canMakeInFicheEcole($id,'MODIFY_VILLE');
		
		$rFiche = $ficheDAO->get($id);

		$rForm = CopixDAOFactory::createRecord('fiches_ecoles');

		if ($save == 1) {

			$rForm->id = $id;
			$rForm->photo = $rFiche->photo;
			
			if (is_uploaded_file($_FILES['photoFile']['tmp_name'])) {
				if ($size = @getimagesize ($_FILES['photoFile']['tmp_name'])) {
					$formats = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
					if (!in_array($size[2],$formats))
						$errors[] = CopixI18N::get ('fichesecoles.error.photo.badformat');
					else {
						$file = COPIX_VAR_PATH.CopixConfig::get ('fichesecoles|photoPath').$rFiche->photo;
						if (file_exists($file)) {
							@unlink($file);
						}
						$fileName = $rForm->id."_".$_FILES['photoFile']['name'];
						$file = COPIX_VAR_PATH.CopixConfig::get ('fichesecoles|photoPath').$fileName;
						if (@move_uploaded_file ($_FILES['photoFile']['tmp_name'], $file)) {
							$this->_resizeImage ($file, $photoMaxWidth);
							$rForm->photo = $fileName;
						}
					}
				} else {
					$errors[] = CopixI18N::get ('fichesecoles.error.photo.badformat');
				}
			} else {
				switch( $_FILES['photoFile']['error'] ) {
					case 0: //no error; possible file attack!
						$errors[] = CopixI18N::get ('malle|malle.error.upload_default', $_FILES['photoFile']['type']);
						break;
					case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
						$errors[] = CopixI18N::get ('malle|malle.error.upload_toobig', $_FILES['photoFile']['type']);
						break;
					case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
						$errors[] = CopixI18N::get ('malle|malle.error.upload_toobig', $_FILES['photoFile']['type']);
						break;
					case 3: //uploaded file was only partially uploaded
						$errors[] = CopixI18N::get ('malle|malle.error.upload_partial', $_FILES['photoFile']['type']);
						break;
					case 4: //no file was uploaded
						//$errors[] = CopixI18N::get ('malle|malle.error.upload_nofile', $_FILES['fichier']['type']);
						break;
					default:
						$errors[] = CopixI18N::get ('malle|malle.error.upload_default', $_FILES['fichier']['type']);
						break;
				}
			}

			$rForm->horaires = $horaires;
			for ($i=1 ; $i<=$nbZones ; $i++) {
				$field = 'zone'.$i.'_titre';
				$rForm->$field = $$field;
				$field = 'zone'.$i.'_texte';
				$rForm->$field = $$field;
			}
			if ($canModifyVille) {
				$rForm->zone_ville_titre = $this->getRequest('zone_ville_titre', null);
				$rForm->zone_ville_texte = $this->getRequest('zone_ville_texte', null);
			} else {
				if (!$rFiche) { // Pas encore de fiche
					$ville = FichesEcolesService::getZoneVille($rEcole);
					$rForm->zone_ville_titre = $ville['zone_ville_titre'];
					$rForm->zone_ville_texte = $ville['zone_ville_texte'];
				} else {
					$rForm->zone_ville_titre = $rFiche->zone_ville_titre;
					$rForm->zone_ville_texte = $rFiche->zone_ville_texte;
				}
			}

			if (!count($errors)) {
				
				if ($rFiche)
					$ficheDAO->update ($rForm);
				else
					$ficheDAO->insert ($rForm);
				
				if ($canModifyVille)
					FichesEcolesService::propageZoneVille($rEcole, $rForm);
					
				return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('default|fiche', array('id'=>$id)));
			}
		} else {
			
			$rForm = $rFiche;

			if ($canModifyVille && !$rForm->zone_ville_titre && !$rForm->zone_ville_texte) {
				$ville = FichesEcolesService::getZoneVille($rEcole);
				$rForm->zone_ville_titre = $ville['zone_ville_titre'];
				$rForm->zone_ville_texte = $ville['zone_ville_texte'];
			}
		}

		$tplForm = & new CopixTpl ();
		$tplForm->assign ('rEcole',$rEcole);
		$tplForm->assign ('rForm',$rForm);
		$tplForm->assign ('form_horaires', CopixZone::process ('kernel|edition', array('field'=>'horaires', 'format'=>'htmlnl2br', 'content'=>$rForm->horaires, 'height'=>100, 'width'=>290)));
		$tplForm->assign ('form_zone1_texte', CopixZone::process ('kernel|edition', array('field'=>'zone1_texte', 'format'=>'htmlnl2br', 'content'=>$rForm->zone1_texte, 'height'=>80, 'width'=>380)));
		$tplForm->assign ('form_zone2_texte', CopixZone::process ('kernel|edition', array('field'=>'zone2_texte', 'format'=>'htmlnl2br', 'content'=>$rForm->zone2_texte, 'height'=>80, 'width'=>380)));
		$tplForm->assign ('form_zone3_texte', CopixZone::process ('kernel|edition', array('field'=>'zone3_texte', 'format'=>'htmlnl2br', 'content'=>$rForm->zone3_texte, 'height'=>80, 'width'=>380)));
		$tplForm->assign ('form_zone4_texte', CopixZone::process ('kernel|edition', array('field'=>'zone4_texte', 'format'=>'htmlnl2br', 'content'=>$rForm->zone4_texte, 'height'=>80, 'width'=>380)));
		if ($canModifyVille)
			$tplForm->assign ('form_zone_ville_texte', CopixZone::process ('kernel|edition', array('field'=>'zone_ville_texte', 'format'=>'htmlnl2br', 'content'=>$rForm->zone_ville_texte, 'height'=>80, 'width'=>780)));

		$tplForm->assign ('photoMaxWidth', $photoMaxWidth);
		$tplForm->assign ('errors',$errors);
		$tplForm->assign ('canModifyVille',$canModifyVille);

		
		
		$main = $tplForm->fetch('form.tpl');
		$title = $rEcole->nom;
		if ($rEcole->type) $title .= ' ('.$rEcole->type.')';
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $title);
		$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('|fiche', array('id'=>$id)).'">'.CopixI18N::get ('kernel|kernel.back').'</a>');
		$tpl->assign ("MAIN", $main);
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

	}

	/**
   * Redimensionne, si besoin est, une image. L'image est ecrasee par la nouvelle, reduite.
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/09
	 * @param string $file Chemin complet de l'image
	 * @param integer $maxWidth Largeur maximale autorisee
   */
	function _resizeImage ($file, $maxWidth) {
		if ($size = getimagesize ($file)) {
			//$type = exif_imagetype ($file);
			$type = $size[2];
			//var_dump($size);
			$width = $size[0];
			$height = $size[1];
			if ($width > $maxWidth) {
				$ratio = $maxWidth/$width;
				//var_dump($ratio);
				$newWidth = $width*$ratio;
				$newHeight = $height*$ratio;
				
				$newImage = imagecreatetruecolor($newWidth, $newHeight);
				
				switch ($type) {
					case IMAGETYPE_JPEG :
						$image = imagecreatefromjpeg($file); break;
					case IMAGETYPE_PNG :
						$image = imagecreatefrompng($file); break;
					case IMAGETYPE_GIF :
						$image = imagecreatefromgif($file); break;
				}
				
				
				imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
				
				switch ($type) {
					case IMAGETYPE_JPEG :
						imagejpeg($newImage, $file, 100); break;
					case IMAGETYPE_PNG :
						imagepng($newImage, $file, 100); break;
					case IMAGETYPE_GIF :
						imagegif($newImage, $file, 100); break;
				}
			}
		}
	}

}


?>
