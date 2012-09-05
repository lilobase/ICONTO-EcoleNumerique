<?php

/**
 * Zone qui affiche les groupes de l'utilisateur courant
 *
 * @package Iconito
 * @subpackage	Groupe
 */
class ZoneMyGroupes extends CopixZone
{
    /**
     * Affiche la liste des groupes de l'utilisateur courant
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/23
     * @param string $kw Mot-clé pour la recherche (option)
     */
    public function _createContent (&$toReturn)
    {
        $where = $this->getParam('where','home');

        $dao = _dao("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $groupesAll = $dao->getListAll();

        //print_r($groupesAll);

        // Parcours de chaque groupe
        $groupes = array();

        foreach ($groupesAll as $k=>$gr) {

      if(CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance')) && $gr->id==$groupeAssistance) {
            continue;
      }

            $mondroit = $kernel_service->getLevel( "CLUB", $groupesAll[$k]->id);
            //print_r($mondroit."-".PROFILE_CCV_READ);
            // Affichage sur la page d'accueil limité aux groupes dont on est admin.
            // if( $where=='home' && $mondroit<70 ) continue;

            //print_r($mondroit);
            if ($groupeService->canMakeInGroupe('VIEW_HOME', $mondroit)) {
                $groupesAll[$k]->mondroit = $mondroit;
                $groupesAll[$k]->mondroitnom = $groupeService->getRightName($mondroit);
                $userInfo = $kernel_service->getUserInfo("ID", $groupesAll[$k]->createur);
                $groupesAll[$k]->createur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
                $groupesAll[$k]->createur_infos = $userInfo;
                $members = $groupeService->getNbMembersInGroupe($groupesAll[$k]->id);
                $groupesAll[$k]->inscrits = $members['inscrits'];
                $groupesAll[$k]->canViewHome = 1;
                $blog = $groupeService->getGroupeBlog($groupesAll[$k]->id);
                if ($blog && ($blog->is_public || $groupeService->canMakeInGroupe('VIEW_HOME', $mondroit)))
                    $groupesAll[$k]->blog = $blog;
                $groupesAll[$k]->canAdmin = $groupeService->canMakeInGroupe('ADMIN', $mondroit);
                $groupes[] = $groupesAll[$k];
                //print_r($groupesAll[$k]);
            }
        }
        //$groupes = array_reverse ($groupes, true);
        //print_r($groupes);

        //print_r($where);

        $tpl = new CopixTpl ();
        $tpl->assign ('list', $groupes);

        if ($where == 'groupes')
            $toReturn = $tpl->fetch('zonemygroupes.tpl');
        elseif ($where == 'home') {

            if( CopixConfig::exists('|conf_ModConcerto') && CopixConfig::get('|conf_ModConcerto') && _currentUser()->getExtra('type')=='USER_RES') {
                $new_module = null;
                $sql = 'SELECT id,login,password FROM kernel_bu_auth WHERE node_type=\'responsable\' AND node_id='._currentUser()->getExtra('id').' AND service=\'concerto\'';
                $concerto = _doQuery($sql);
                if( $concerto ) {
                    $new_module = _record("kernel|kernel_mod_enabled");
                    $new_module->node_type = _currentUser()->getExtra('type');
                    $new_module->node_id = _currentUser()->getExtra('id');
                    $new_module->module_type = "MOD_CONCERTO";
                    $new_module->module_id = 0;
                    $modules[] = $new_module;
                }
                $tpl->assign ('concerto', $new_module);
                $tpl->assign ('concerto_data', $concerto);
            }

            $toReturn = $tpl->fetch('zonemygroupes_home.tpl');
        }
        return true;

    }
}
