<?php
/**
* @package   petiteenfance
* @subpackage gestion
* @version   $Id$
* @author   Frederic Mossmann <fmossmann@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

class ActionGroupStructures extends CopixActionGroup {

	/**
	 * Verifie les droits
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2009/08/12
	 */
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
	}


	/**
	 * Liste des structures
	 * 
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2009/08/12
	 */
	public function processDefault () {
		// return _arRedirect (_url ('instruction|dossiers|'));
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Structures";
		
		// Menu
		$ppo->MENU = CopixZone::process (
			'kernel|menu', array (
				'level_0' => 'gestion',
				'level_1' => 'structures'
			)
		);
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('gestion||'), 'Gestion');
 		$cBc->addItem (null, 'Structures');
		
 		$criteres = _daoSp ()->orderBy ('nom');
		$ppo->structures = _ioDAO ('kernel|structure')->findBy ($criteres);

		// echo "<pre>"; print_r($ppo->structures_types); die();
		
		return _arPPO ($ppo, 'structures-default.tpl');
		
	}
	
	/**
	 * Modification d'une structure
	 * 
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2009/08/12
	 * @param integer $id Id de la structure
	 */
	public function processModifier () {
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Structure / Modification";
		
		// Menu
		$ppo->MENU = CopixZone::process (
			'kernel|menu', array (
				'level_0' => 'gestion',
				'level_1' => 'structures'
			)
		);
		
		// Fil d'ariane
		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
		$cBc->addItem (CopixUrl::get ('gestion||'), 'Gestion');
		$cBc->addItem (CopixUrl::get ('gestion|structures|'), 'Structures');
		$cBc->addItem (null, 'Modification');

		$pId = CopixRequest::getInt('id',0);
		$submit = CopixRequest::getInt('submit');
		
		if($pId) {
			$structureDAO = _ioDAO ('kernel|structure');
			if (!$rStructure = $structureDAO->get ($pId)) {
				$pId = null;
			} else {
				$ppo->rForm = $rStructure;
			}
		} else {
			$ppo->rForm = _record ('kernel|structure');
		}
 			
 		
		
		if ($submit) {
			$record = _record ('kernel|structure');
			$this->_parametersToObject ('structure', $record);
			$check = _ioDAO('kernel|structure')->check($record);
			
			// echo "<pre>"; print_r($check); die();
			
			if (!count($check))
				unset($check);
			
			if (!is_array($check)) {
				if ($record->id) { // Mise a jour
					_ioDAO ('kernel|structure')->update($record);
					
				} else { // Ajout 
					_ioDAO ('kernel|structure')->insert($record);
					if (!$record->id)
						$check[] = 'Probl&egrave;me de cr&eacute;ation du structure';
				}
				
				return _arRedirect (_url ('gestion|structures|'));
				
			}
			$ppo->errors = $check;
			$ppo->rForm = $record;
		}
		
		
		// echo "<pre>"; print_r($ppo->structures_types); die();
		
		return _arPPO ($ppo, 'structures-form.tpl');
		
	}

	
	/**
	 * Horaires d'ouverture d'une structure
	 * 
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2009/08/12
	 * @param integer $id Id de la structure
	 */
	public function processOuverture () {
		$cStructures = _class ('gestion|Structures');
		
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Structure / Ouverture";
		
		// Menu
		$ppo->MENU = CopixZone::process (
			'kernel|menu', array (
				'level_0' => 'gestion',
				'level_1' => 'structures'
			)
		);
		
		// Fil d'ariane
		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
		$cBc->addItem (CopixUrl::get ('gestion||'), 'Gestion');
		$cBc->addItem (CopixUrl::get ('gestion|structures|'), 'Structures');
		$cBc->addItem (null, 'Ouverture');

		CopixHTMLHeader::addCSSLink (_resource ('styles/gestion.css'));
		
		
		$pId = CopixRequest::getInt('id',0);
		if(!$pId) return _arRedirect (_url ('gestion|structures|'));
		$ppo->structure_id = $pId;
		
		$submit = CopixRequest::getInt('submit');
		
		if($submit) {
			if(isset($_POST['jours']) && is_array($_POST['jours']) ) foreach( $_POST['jours'] AS $jour=>$flag ) {
				$cStructures->modifyHoraire($_POST['do'], $pId, $jour, $_POST['debut_heure'], $_POST['debut_minute'], $_POST['fin_heure'], $_POST['fin_minute'] );
			}
			
			return _arRedirect (_url ('gestion|structures|ouverture', array('id'=>$pId) ));
		}
		
		
		
	
		$ppo->MAIN = CopixZone::process ( 'gestion|structures_calendrier', array ( 'id'=>$pId, 'display'=>'horaires' ) );
		
		// echo "<pre>"; print_r($ppo->structures_types); die();
		
		$ppo->combo_heures = array(); // array( -1 => "--" );
		for( $cpt=0 ; $cpt<24 ; $cpt++ ) $ppo->combo_heures[$cpt] = $cpt;
		$ppo->combo_minutes = array(); // array( -1 => "--" );
		for( $cpt=0 ; $cpt<60 ; $cpt+=5 ) $ppo->combo_minutes[$cpt] = str_pad($cpt, 2, "0", STR_PAD_LEFT);
		$ppo->combo_do = array( 'add' => 'Ajouter l\'horaire', 'del' => 'Retirer l\'horaire' );
		
		return _arPPO ($ppo, 'structures-horaires.tpl');
		
	}
	
	private function _parametersToObject ($table, &$record) {
		switch ($table) {
			case 'structure' :
				$record->id = CopixRequest::getInt('id',0);
				$record->nom = CopixRequest::get('nom');
				$record->type = CopixRequest::get('type');
				$record->adresse = CopixRequest::get('adresse','');
				$record->cp = CopixRequest::get('cp','');
				$record->ville = CopixRequest::get('ville','');
				$record->tel1 = CopixRequest::get('tel1','');
				$record->tel2 = CopixRequest::get('tel2','');
				break;
		}
	}
	

}

?>