<?php
/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneDashboardEcole extends enicZone
{
    public function _createContent(&$toReturn)
    {
        //load TPL
        $tpl = new CopixTpl();

        //get the id for current zone (force int)
        $idZone = $this->getParam('idZone')*1;
        $catalog = $this->getParam('catalog');

        $enseignants = array();
        $oEns        = array();

        //check the matrix right for current classe :
        if($this->matrix->ecole($idZone)->_right->enseignant->voir){
            $annuaireService =& CopixClassesFactory::Create ('annuaire|AnnuaireService');

            //get the children's node
            $childNodeDatas = Kernel::getNodeChilds('BU_ECOLE', $idZone, false, array('skip_user' => true));

            //if the child is a CLASSE get the enseignant
            foreach($childNodeDatas as $child)
                if($child['type'] == 'BU_CLASSE')
                    $enseignants[] = $annuaireService->getEnseignantInClasse($child['id']);

            /*
             * delete the multiple ereg
             */
            $uniqueEnsId = array();
            foreach($enseignants as $enseignant){
                foreach($enseignant as $ens){
                    //check if the ens is already ereg
                    if(in_array($ens['id'], $uniqueEnsId)){
                        continue;
                    }else{
                        $oEns[] = $ens;
                        $uniqueEnsId[] = $ens['id'];
                    }
                }
            }

            /*
             * get the school's picture
             */
            $pic = $this->model->query('SELECT photo FROM module_fiches_ecoles WHERE id = '.$idZone)->toString();

            $tpl->assign('ens', $oEns);
            $tpl->assign('pic', $pic);
            $tpl->assign('idZone', $idZone);
            $tpl->assign('catalog', $catalog);

            //return the html content
            $toReturn = $tpl->fetch ('zone.dashboard.ecole.tpl');
            return true;
        }

        //if the uses have no right : display a default tpl
        $toReturn = $tpl->fetch ('zone.dashboard.noRight.tpl');
        return true;
    }

}
