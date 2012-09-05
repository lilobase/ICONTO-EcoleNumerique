<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneDashboardClasse extends enicZone
{
    public function _createContent(&$toReturn)
    {
        //load TPL
        $tpl = new CopixTpl();

        //get the id for current zone
        $idZone = $this->getParam('idZone');

        //check the matrix right for current classe :
        if($this->matrix->classe($idZone)->_right->eleve->voir){
            $annuaireService =& CopixClassesFactory::Create ('annuaire|AnnuaireService');



            $elevesDatas = $annuaireService->getElevesInClasse($idZone);

            $tpl->assign('eleves', $elevesDatas);

            //return the html content
            $toReturn = $tpl->fetch ('zone.dashboard.classe.tpl');
            return true;
        }

        //if the uses have no right : display a default tpl
        $toReturn = $tpl->fetch ('zone.dashboard.noRight.tpl');
        return true;
    }

}
