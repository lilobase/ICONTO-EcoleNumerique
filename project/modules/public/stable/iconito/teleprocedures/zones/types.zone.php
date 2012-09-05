<?php
/**
 * Types des teleprocedures
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

class ZoneTypes extends CopixZone
{
    /**
     * Affiche la liste des types de procedures, dans une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/14
     * @param object $rTelep Recordset du module
     * @param boolean $admin Si on est en mode administration
     * @param boolean $canInsert Si l'usager peut demarrer une demande
     */

    public function _createContent (&$toReturn)
    {
      $tpl = new CopixTpl ();

        $rTelep = $this->getParam('rTelep');
        $admin = $this->getParam('admin');
        $canInsert = $this->getParam('canInsert');

        if (!$admin && !$canInsert)
            return true;

        $DAOtype = & _dao ('type');

        if ($admin)
            $list = $DAOtype->findForTeleprocedureAdmin ($rTelep->id);
        else
            $list = $DAOtype->findForTeleprocedure ($rTelep->id);

      $tpl->assign ('rTelep', $rTelep);
      $tpl->assign ('admin', $admin);
      $tpl->assign ('list', $list);
      $tpl->assign ('canInsert', $canInsert);

    $toReturn = $tpl->fetch ('types-zone.tpl');
        return true;

    }
}
