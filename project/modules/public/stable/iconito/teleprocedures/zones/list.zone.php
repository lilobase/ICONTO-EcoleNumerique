<?php
/**
 * Filtrage des teleprocedures
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

class ZoneList extends CopixZone
{
    /**
     * Affiche la liste des procedures
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     */

    public function _createContent (&$toReturn)
    {
      $tpl = new CopixTpl ();

        $motcle= $this->getParam('motcle');
        $clos = $this->getParam('clos');
        $type = $this->getParam('type');
        $ecole = $this->getParam('ecole');
        $rTelep = $this->getParam('rTelep');
        $mondroit = $this->getParam('mondroit');

    $daointerv = & _dao ('intervention');

        $sqlSelectPlus = $sqlFromPlus = $sqlWherePlus = '';
        $ville = false;

    $params = array();

        if (TeleproceduresService::getTelepProfil()=='DIRECTEUR') {
            $sqlWherePlus = " AND ITV.idetabliss = :ecole";
      $params[':ecole'] = TeleproceduresService::getTelepEcole();
        } else { // Compte ville : on ne prend que les teleprocedures qui le concerne

            $sqlSelectPlus = ', DR.droit';
            $sqlFromPlus = ', module_teleprocedure_intervention_droit DR';
            $sqlWherePlus = " AND DR.idinter=ITV.idinter AND DR.user_type='"._currentUser()->getExtra('type')."' AND DR.user_id="._currentUser()->getExtra('id');
            $ville = true;
            $tpl->assign ('ville', $ville);
        }

        $user = _currentUser ()->getId();
        $sql = 'SELECT ITV.idinter, ITV.objet, ITV.dateinter, ITV.datederniere, ITV.idstatu, STA.nom AS nomstat, TYP.nom AS nomtype, ECO.nom AS ecole_nom, ECO.type AS ecole_type, TRA.last_visite, TO_DAYS(NOW())-TO_DAYS(ITV.datederniere) AS depuis'.$sqlSelectPlus.' FROM module_teleprocedure_statu STA, module_teleprocedure_type TYP'.$sqlFromPlus.', kernel_bu_ecole ECO, module_teleprocedure_intervention ITV';

        $sql .= ' LEFT JOIN module_teleprocedure_tracking TRA ON (TRA.intervention=ITV.idinter AND TRA.utilisateur='.$user.')';

        $sql .= ' WHERE ITV.idstatu=STA.idstat AND ITV.idtype=TYP.idtype AND ITV.idetabliss=ECO.numero AND TYP.teleprocedure='.$rTelep->id.'';

    if ($motcle && $motcle!='*')
         $sql .= ' AND (ITV.objet like \'%'.addslashes($motcle).'%\' OR ITV.detail like \'%'.addslashes($motcle).'%\')';

    if (!$clos)
            $sql .= ' AND ITV.idstatu != 3';

        if ($type)
            $sql .= ' AND ITV.idtype='.$type;

    if ($ecole)
            $sql .= ' AND ECO.numero='.$ecole;

        $sql .= $sqlWherePlus;

        $sql .= ' ORDER BY dateinter DESC, idinter DESC';
    //echo $sql;
    $liste = _doQuery ($sql, $params);

        if ($ville) {
            foreach ($liste as &$l) {
                $l->droit_nom = TeleproceduresService::droitName($l->droit);
            }
        }

      $tpl->assign ('clos', $clos);
      $tpl->assign ('liste', $liste);
      $tpl->assign ('rTelep', $rTelep);
        $tpl->assign ('canViewDelai', TeleproceduresService::canMakeInTelep('VIEW_DELAI',$mondroit));

    $toReturn = $tpl->fetch ('list-zone.tpl');
        return true;

    }
}
