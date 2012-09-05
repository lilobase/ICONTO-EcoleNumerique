<?php

/**
 * Actions pouvant etre faites sur une teleprocedure
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

class ZoneFicheActionsDroits extends CopixZone
{
    /**
     * Les droits dans une procedure. On considere que la verif des droits a ete faite avant
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/03/06
     * @param object $rFiche Recordset de la procedure
     */

    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $rFiche = $this->getParam('rFiche');
        $errors = $this->getParam('errors');

      $tpl->assign ('rFiche', $rFiche);
        $tpl->assign ('errors', $errors);

        $tpl->assign ('linkpopup_responsables', CopixZone::process ('annuaire|linkpopup', array('field'=>'responsables', 'profil'=>'USER_VIL')));
        $tpl->assign ('linkpopup_lecteurs', CopixZone::process ('annuaire|linkpopup', array('field'=>'lecteurs', 'profil'=>'USER_VIL')));

    $toReturn = $tpl->fetch ('fiche-actions-droits-zone.tpl');
        return true;

    }
}
