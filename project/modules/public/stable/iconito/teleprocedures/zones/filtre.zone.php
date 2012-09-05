<?php

/**
 * Filtrage des teleprocedures
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

class ZoneFiltre extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

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
            $tpl->assign ('comboEcolesLinesSup', array(0=>array('value'=>'', 'libelle'=>'---'), 1=>array('value'=>'', 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllEcoles'))));
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
