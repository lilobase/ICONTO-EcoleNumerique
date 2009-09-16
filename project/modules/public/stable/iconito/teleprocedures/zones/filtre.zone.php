<?php

/**
 * Filtrage des teleprocedures
 * 
 * @package Iconito
 * @subpackage Teleprocedures
 */

class ZoneFiltre extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$motcle= $this->getParam('motcle');
		$clos = $this->getParam('clos');
		$type = $this->getParam('type');
		$ecole= $this->getParam('ecole');

		$rTelep = $this->getParam('rTelep');
		$admin = $this->getParam('admin');
		$mondroit = $this->getParam('mondroit');
		
		$daoType = & _dao ('teleprocedures|type');
    $tpl->assign ('arTypes', $daoType->findForTeleprocedure ($rTelep->id));
	
		//print_r($rTelep);
		
		$canViewComboEcoles = TeleproceduresService::canMakeInTelep('VIEW_COMBO_ECOLES',$mondroit);
		
		if ($canViewComboEcoles) {
			$tpl->assign ('comboEcoles', CopixZone::process ('annuaire|comboecolesinville', array('ville'=>$rTelep->parent['id'], 'value'=>$ecole, 'fieldName'=>'ecole', 'attribs'=>'class="form"', 'linesSup'=>array(0=>array('value'=>'', 'libelle'=>'---'), 1=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllEcoles'))))));

			//$tplListe->assign ('comboEcoles', CopixZone::process('filtre',array('rTelep'=>$rTelep, 'motcle'=>$motcle, 'clos'=>$clos, 'type'=>$type, 'admin'=>true, 'mondroit'=>$mondroit)));
		}
		
		
		$tpl->assign ('rTelep', $rTelep);
	  $tpl->assign ('admin', $admin);
	  $tpl->assign ('motcle', $motcle);
	  $tpl->assign ('clos', $clos);
	  $tpl->assign ('type', $type);
	  $tpl->assign ('ecole', $ecole);
		$tpl->assign ('canViewComboEcoles', $canViewComboEcoles);

    $toReturn = $tpl->fetch ('filtre-zone.tpl');
		return true;
	 
	}
}
?>
