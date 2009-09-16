<?php
/**
* @package   petiteenfance
* @subpackage instruction
* @version   $Id: commissions.actiongroup.php 72 2009-08-25 10:10:32Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

_classInclude ("kernel|Tools");

class ActionGroupCommissions extends CopixActionGroup {


	/**
	 * Verifie les droits
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
	}

	/**
	 * Accueil des commissions
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function processDefault () {
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Commissions";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'commissions'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('instruction|commissions|'), 'Commissions');
 		$cBc->addItem (null, 'Liste de commissions');
		
		
		$criteres = _daoSp ()->orderBy ('date');
		$ppo->list = _ioDAO ('kernel|commission')->findBy ($criteres);
		
		
		
		return _arPPO ($ppo, 'commissions.tpl');
	}
	
	
	/**
	 * Ajout d'une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 */
	public function processForm () {
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Commissions";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'commissions'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('commissions|'), 'Commissions');
 		$cBc->addItem (null, 'Ajouter une commission');
		
		if (_request('submit')) {
				
			$record = _record ('kernel|commission');
			
			$this->_parametersToObject ('kernel|commission', $record);

			$check = _ioDAO('kernel|commission')->check($record, false);
			//Tools::print_r2($record);
			if (!is_array($check)) {
				_ioDAO ('kernel|commission')->insert($record);
				if ($record->id) {
					$nb_dossiers = $record->save2Demandes();
					$record->nb_dossiers = $nb_dossiers;
					_ioDAO ('kernel|commission')->update($record, false);
					return _arRedirect (_url ('commissions|details', array('id'=>$record->id)));
				} else {
					$check = array('bdd' => 'Probl&egrave;me');
				}
			}
			
			$ppo->errors = $check;
			$ppo->rForm = $record;
		
		} else { // Arrivee dans le formulaire
			$rCommission = _record ('kernel|commission');
			$rCommission->date = date('d/m/Y');
			$ppo->rForm = $rCommission;
		}
		
		
		return _arPPO ($ppo, 'commission_form.tpl');
	}
	


	/**
	 * Detail d'une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/19
	 * @param integer $id Id de la commission
	 */
	public function processDetails () {
		
		CopixHTMLHeader::addCSSLink (_resource ('styles/commission.css'));
		CopixHTMLHeader::addJSLink (_resource ('js/petiteenfance/commission.js'));
		
		$pId = CopixRequest::getInt('id');
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Commissions";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'commissions'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('commissions|'), 'Commissions');
 		$cBc->addItem (null, 'D&eacute;tail de la commission');
		
	  if ($ppo->rCommission = _ioDAO ('kernel|commission')->get ($pId)) {
			
			if ($ppo->rCommission->est_finie)
				return _arRedirect (_url ('commissions|bilan', array('id'=>$pId)));
				
			$ppo->commission2demande = _record ('kernel|commission2demande');
			
			$ppo->decisions = array(
				'ACCEPTE' => DAORecordCommission2Demande::DECISION_ACCEPTE,
				'AJOURNE' => DAORecordCommission2Demande::DECISION_AJOURNE,
				'REFUSE' => DAORecordCommission2Demande::DECISION_REFUSE,
				'AUCUN' => DAORecordCommission2Demande::DECISION_AUCUN,
				);
			
			$ppo->filtre = new CopixPPO ();
			$ppo->filtre->nom = _request('nom');
			$ppo->filtre->prenom = _request('prenom');
			$ppo->filtre->decision = _request('decision');
			$ppo->filtre->type = _request('type');
			
			$ppo->hasFiltre = ($ppo->filtre->nom || $ppo->filtre->prenom || $ppo->filtre->decision || $ppo->filtre->type);
			
			//Tools::print_r2($ppo->filtre);
			
			$ppo->TITLE_PAGE = "Commission du ".$ppo->rCommission->date_fr;
			//print_r($rCommission);
			
			$ppo->dossiers = _ioDAO ('kernel|commission2demande')->getListeDemandes($ppo->rCommission, $ppo->filtre);
		}
		
		
		return _arPPO ($ppo, 'commission_details.tpl');
	}
	

	/**
	 * Fin d'une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/25
	 * @param integer $id Id de la commission
	 * @param integer $confirm Si on veut mettre fin a la commission
	 */
	public function processEnd () {
		
		CopixHTMLHeader::addCSSLink (_resource ('styles/commission.css'));
		CopixHTMLHeader::addJSLink (_resource ('js/petiteenfance/commission.js'));
		
		$pId = CopixRequest::getInt('id');
		$pConfirm = CopixRequest::getInt('confirm');
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Commissions";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'commissions'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('commissions|'), 'Commissions');
 		$cBc->addItem (null, 'Fin de la commission');
		
	  if ($ppo->rCommission = _ioDAO ('kernel|commission')->get ($pId)) {
			
			if ($pConfirm && !$ppo->rCommission->est_finie) {
				//Tools::print_r2($ppo->rCommission);
				$ppo->rCommission->est_finie = 1;
				_ioDAO ('kernel|commission')->update ($ppo->rCommission, false);
			}
			
			if ($ppo->rCommission->est_finie)
				return _arRedirect (_url ('commissions|bilan', array('id'=>$pId)));
			
			$ppo->TITLE_PAGE = "Commission du ".$ppo->rCommission->date_fr;
			//print_r($rCommission);
			
			$ppo->filtre = new CopixPPO ();
			$ppo->filtre->order = 'decision';
			
			$ppo->dossiers = _ioDAO ('kernel|commission2demande')->getListeDemandes($ppo->rCommission, $ppo->filtre);
			
			$ppo->bilan = array();
			foreach ($ppo->dossiers as $r) {
				if (!isset($ppo->bilan[$r->decision]))
					$ppo->bilan[$r->decision]=array('nb'=>0, 'nom'=>DAORecordCommission2Demande::getDecisionNom($r->decision,'listEndCommission'));
				$ppo->bilan[$r->decision]['nb']++;
			}
		}
		
		
		return _arPPO ($ppo, 'commission_end.tpl');
	}


	/**
	 * Bilan d'une commission (apres la fin), avec acces aux PDF
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/25
	 * @param integer $id Id de la commission
	 */
	public function processBilan () {
		
		CopixHTMLHeader::addCSSLink (_resource ('styles/commission.css'));
		CopixHTMLHeader::addJSLink (_resource ('js/petiteenfance/commission.js'));
		
		$pId = CopixRequest::getInt('id');
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Commissions";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'commissions'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('commissions|'), 'Commissions');
 		$cBc->addItem (null, 'Bilan de la commission');
		
	  if ($ppo->rCommission = _ioDAO ('kernel|commission')->get ($pId)) {
			
			if (!$ppo->rCommission->est_finie)
				return _arRedirect (_url ('commissions|details', array('id'=>$pId)));
				
			$ppo->TITLE_PAGE = "Commission du ".$ppo->rCommission->date_fr;
			//print_r($rCommission);
		}
		
		return _arPPO ($ppo, 'commission_bilan.tpl');
	}


	/**
	 * Enregistrement d'une decision pour un dossier dans une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/20
	 * @param integer $commission Id de la commission
	 * @param integer $demande Id de la demande
	 * @param integer $decision Decision prise
	 * @param array $choix Choix
	 */
	public function processAjaxSetDecision () {
		
		$pCommission = CopixRequest::getInt('commission');
		$pDemande = CopixRequest::getInt('demande');
		$pDecision = CopixRequest::getInt('decision');
		$pMotif = CopixRequest::get('motif');
		$pDate = CopixRequest::get('date');
		$pChoix = CopixRequest::get('choix');
		$pNotes = CopixRequest::get('notes');
		
		$result = $errors = '';
		$TODOENLEVER = null;
		
		if ( ($rCommission = _ioDAO ('kernel|commission')->get ($pCommission))) {
			//if ($rCommission = _ioDAO ('kernel|commission')->get ($pCommission)) {
				//if ($pDemande && $rDemande = _ioDAO('kernel|commission2demande')->getListeDemandes($rCommission, array('demande'=>$pDemande))) {
					//$rDossier = $rDemande[$pDemande];
				if ($rDemande = _ioDAO ('kernel|demande')->getFull ($pDemande, $TODOENLEVER, array('choix'=>true))) {
					
					if ($link = _ioDAO('kernel|commission2demande')->get($pCommission,$pDemande)) {
						//Tools::print_r2($link);
						if ($link->canSetDecision($rCommission)) {
							
							$record = $rDemande;
							$this->_parametersToObject ('kernel|demande', $record);
							$record->date = $record->date_fr;
							$check = _ioDAO('kernel|demande')->check($record, false);
							
							$record2 = $link;
							$this->_parametersToObject ('kernel|commission2demande', $record2);
							//Tools::print_r2($record);
							
							$check2 = _ioDAO('kernel|commission2demande')->check($record2, false);
							
							if (!is_array($check) && !is_array($check2)) {
								
								_ioDAO('kernel|demande')->update($record);
								_ioDAO('kernel|commission2demande')->update($record2);

								// Rechargement
								$filtre = new CopixPPO ();
								$filtre->demande = $pDemande;
								$list = _ioDAO('kernel|commission2demande')->getListeDemandes($rCommission, $filtre);
								$rDemande = $list[$pDemande];
								
								_ioDAO ('kernel|commission')->calculeNbDossiersTraites($pCommission);
								
							} else {
								if (is_array($check) && is_array($check2))
									$errors = array_merge($check, $check2);
								elseif (is_array($check))
									$errors = $check;
								elseif (is_array($check2))
									$errors = $check2;
								//Tools::print_r2($record2);
								$rDemande->date_entree_fr = $record->date_entree;
								$rDemande->decision_motif = $record2->motif;
								$rDemande->decision_date_fr = $record2->date;
							}
								
						} else
							$errors = array("Impossible d'enregistrer la d&eacute;cision");
					}
				
					//Tools::print_r2($rDemande);
					$result .= CopixZone::process ('instruction|commission_dossier', array ('rDossier' => $rDemande,       'rCommission' => $rCommission, 'errors'=>$errors));	

				}

			//}
		}
		
		header('Content-type: text/html; charset=utf-8');
		$ppo = new CopixPpo ();
		$ppo->MAIN = $result;
		return _arDirectPPO ($ppo, 'generictools|blank.tpl');
		
	}



	/**
	 * affichage des infos d'entete d'une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/25
	 * @param integer $commission Id de la commission
	 */
	public function processAjaxGetCommissionInfos () {
		
		$pCommission = CopixRequest::getInt('commission');
		
		if ( ($rCommission = _ioDAO ('kernel|commission')->get ($pCommission))) {
			$result = CopixZone::process ('instruction|commission_infos', array ('rCommission' => $rCommission));	
		}
		
		header('Content-type: text/html; charset=utf-8');
		$ppo = new CopixPpo ();
		$ppo->MAIN = $result;
		return _arDirectPPO ($ppo, 'generictools|blank.tpl');
		
	}




	/**
	 * Transformation des donnees soumises en formulaire en recordset
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/19
	 * @param string $table Nom de la table concerne
	 * @param object $record Recordset qui sera renseigne
	 */
	private function _parametersToObject ($table, &$record) {
		switch ($table) {
			case 'kernel|commission' :
				$record->id = CopixRequest::getInt('id');
				if (!$record->id) {
					$record->date_saisie = CopixDateTime::timeStampToyyyymmddhhiiss (time ());
					$record->auteur = _currentUser()->getLogin();
					$record->est_finie = 0;
				}
				$record->date = CopixRequest::get('date');
				$record->notes = CopixRequest::get('notes');
				break;
			case 'kernel|demande' : // Demande dans une commission
				$record->choix = CopixRequest::get('choix');
				$record->date_entree = CopixRequest::get('date_entree');
				$record->date_decision = CopixRequest::get('date_decision');
				$record->notes = CopixRequest::get('notes');
				break;
			case 'kernel|commission2demande' :
				$record->date = CopixRequest::get('date');
				$record->motif = CopixRequest::get('motif');
				$record->decision = CopixRequest::getInt('decision');
				$record->auteur = _currentUser()->getLogin();
				break;
		}
	}





}

?>
