<?php
/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneDashboardVille extends enicZone
{
    public function _createContent(&$toReturn)
    {
        //load TPL
        $tpl = new CopixTpl();

        //get the id for current zone
        $idZone = $this->getParam('idZone');

        $annuaireService =& CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $ecolesDatas = $annuaireService->getEcolesInVille($idZone);

        $tpl->assign('ecoles', $ecolesDatas);

        //return the html content
        $toReturn = $tpl->fetch ('zone.dashboard.ville.tpl');
        return true;
    }

}
