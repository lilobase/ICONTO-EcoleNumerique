<?php

/**
 * Fiche d'une teleprocedure
 * 
 * @package Iconito
 * @subpackage Teleprocedures
 */

require_once (COPIX_MODULE_PATH.'annuaire/'.COPIX_CLASSES_DIR.'annuaireservice.class.php');
require_once (COPIX_MODULE_PATH.'teleprocedures/'.COPIX_CLASSES_DIR.'teleproceduresservice.class.php');

class ZoneFiche extends CopixZone {


	/**
	 * Detail d'une procedure
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/01/30
	 * @param object $rFiche Recordset de la procedure
	 */

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$rFiche = $this->params['rFiche'];
		$errors = $this->params['errors'];
		$ok = $this->params['ok'];
		$print = $this->params['print'];

		//var_dump($rFiche);
		
		//$dbWidget = & CopixDBFactory::getDbWidget ();
		//$daoType = & CopixDAOFactory::create ('type');
    //$tpl->assign ('arTypes', $daoType->findAll ());
		$arResponsables = explode(",",$rFiche->responsables);
		$rFiche->tabResponsables = $arResponsables;
		$mondroit = $this->params['mondroit'];
		
		
		$rEcole = Kernel::getNodeInfo ('BU_ECOLE', $rFiche->idetabliss, false);
		//var_dump($rEcole);
		$rFiche->ecole_nom = $rEcole['ALL']->eco_nom;
		$rFiche->ecole_type = $rEcole['ALL']->eco_type;
		$rFiche->ecole_tel = $rEcole['ALL']->eco_tel;
		
		$rFiche->ecole_dir = AnnuaireService::getDirecteurInEcole ($rFiche->idetabliss);
		
		$daoType = & CopixDAOFactory::create ('teleprocedures|type');
    if ($tmp = $daoType->get ($rFiche->idtype))
			$rFiche->idtype_nom = $tmp->nom;
		$daoStatu = & CopixDAOFactory::create ('teleprocedures|statu');
    if ($tmp = $daoStatu->get ($rFiche->idstatu))
			$rFiche->idstatu_nom = $tmp->nom;
		
		$canDelegue = TeleproceduresService::canMakeInTelep('DELEGUE', $mondroit, array('idinter'=>$rFiche->idinter));
		$tpl->assign ('canDelegue', $canDelegue);
		$tpl->assign ('canViewDelai', TeleproceduresService::canMakeInTelep('VIEW_DELAI',$mondroit));

	  $tpl->assign ('rFiche', $rFiche);
	  $tpl->assign ('errors', $errors);
	  $tpl->assign ('ok', $ok);
		
		$tplFiche = $tpl->fetch ('fiche-zone.tpl');
		
		$toReturn = $tplFiche;
		if ($print) {
			$tplMain = & new CopixTpl ();
			$tplMain->assign ('TITLE_PAGE', $rFiche->objet);
			$tplMain->assign ('MAIN', $tplFiche);
			$toReturn = $tplMain->fetch ('|main_print.tpl');
		}
    
		return true;
	 
	}
}
?>
