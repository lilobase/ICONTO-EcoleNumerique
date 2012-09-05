<?php

/**
 * Actions pouvant etre faites sur une teleprocedure
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

_classInclude('teleprocedures|teleproceduresservice');

class ZoneFicheActions extends CopixZone
{
    /**
     * Actions d'une procedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/21
     * @param object $rFiche Recordset de la procedure
     */

    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $rFiche = $this->getParam('rFiche');
        $mondroit = $this->getParam('mondroit');

        $canChangeStatut = TeleproceduresService::canMakeInTelep('CHANGE_STATUT', $mondroit, array('idinter'=>$rFiche->idinter));
        $canDelegue = TeleproceduresService::canMakeInTelep('DELEGUE', $mondroit, array('idinter'=>$rFiche->idinter));
        $canSendMails = TeleproceduresService::canMakeInTelep('SEND_MAILS', $mondroit, array('idinter'=>$rFiche->idinter));


        $daoStat = & _dao ('statu');
    $tpl->assign ('arStat', $daoStat->findAll ());

      $tpl->assign ('rFiche', $rFiche);
        $tpl->assign ('canChangeStatut', $canChangeStatut);
        $tpl->assign ('canDelegue', $canDelegue);
        $tpl->assign ('canSendMails', $canSendMails);

        $tpl->assign ('mailEnabled', (CopixConfig::get('|mailEnabled')));

        $tpl->assign ('linkpopup_responsables', CopixZone::process ('annuaire|linkpopup', array('field'=>'responsables', 'profil'=>'USER_VIL')));

    $toReturn = $tpl->fetch ('fiche-actions-zone.tpl');
        return true;

    }
}
