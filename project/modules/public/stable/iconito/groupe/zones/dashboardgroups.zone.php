<?php

/**
 * Pour le dashboard, zone affichant le lien vers l'annuaire des groupes, et le bouton pour creer un groupe
 *
 * @package Iconito
 * @subpackage	Groupe
 */
class ZoneDashboardGroups extends CopixZone
{
    /**
     * Pour le dashboard, zone affichant le lien vers l'annuaire des groupes, et le bouton pour creer un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/06/11
     */
    public function _createContent (&$toReturn)
    {
    $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $tpl = new CopixTpl ();
        //$tpl->assign ('list', $groupes);

        $tpl->assign ('canCreate', ($groupeService->canMakeInGroupe('ADD_GROUP',NULL) ? 1 : 0));

        if( CopixConfig::exists('|can_group_showlist') && CopixConfig::get('|can_group_showlist') ) {
            $tpl->assign ('can_group_showlist', 1);
        } else {
            $tpl->assign ('can_group_showlist', 0);
        }

        $toReturn = $tpl->fetch('dashboardgroups.tpl');
        return true;

    }
}
