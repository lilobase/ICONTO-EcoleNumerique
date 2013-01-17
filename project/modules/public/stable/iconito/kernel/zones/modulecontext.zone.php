<?php

/*
  @file 		moduleContext.zone.php
  @desc		Set any module in its dashboard context
  @version 	1.0.0b
  @date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
  @author 	S.HOLTZ <sholtz@cap-tic.fr>

  Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
 */

class ZoneModuleContext extends enicZone
{
    public function _createContent(&$toReturn)
    {
        $carnetDeLiaison = (CopixConfig::exists('kernel|carnetDeLiaison') && CopixConfig::get('kernel|carnetDeLiaison'));

        $ppo = new CopixPPO ();
        $toReturn = "";
        $step = $this->getParam('STEP', 'open');
        $ppo->curmod = $this->getParam('MODULE', '');
        $titlepage = $this->getParam('TITLE_PAGE', '');
        $titleContext = $this->getParam('TITLE_CONTEXT', '');

        //_dump($curmod);
        $ppo->myNode = CopixSession::get('myNode');
        $ppo->myNodeData = Kernel::getNodeInfo($ppo->myNode['type'], $ppo->myNode['id'], false);
        $ppo->panelClass = strtolower($ppo->myNode['type']);
        $ppo->closeUrl = CopixUrl::get('kernel|dashboard|default');
		$ppo->closeButton = $this->getParam('closeButton', true);
		$ppo->moduleIsForUser = strpos($ppo->myNode['type'], 'USER_');

        if (strpos($ppo->myNode['type'], 'USER_') === false) {
            // Pour tous sauf les users...
            $modules = Kernel::getModEnabled($ppo->myNode['type'], $ppo->myNode['id'], _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        } elseif (strpos($ppo->myNode['type'], 'USER_ELE') !== false) {
            // Cas particulier, les enfants (vue parent)
            $parents = Kernel::getNodeParents($ppo->myNode['type'], $ppo->myNode['id']);
            $parent = Kernel::filterNodeList($parents, 'BU_CLASSE');
            if (count($parent)) {
                $modules = Kernel::getModEnabled($parent[0]['type'], $parent[0]['id'], _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
            }
            $perso->node_type = $ppo->myNode['type'];
            $perso->node_id = $ppo->myNode['id'];
            $perso->module_type = 'MOD_CARNET';
            $perso->module_id = 'ELEVE_' . $ppo->myNode['id'];
            $perso->module_nom = Kernel::Code2Name('MOD_CARNET');
            if ($carnetDeLiaison)
                $modules[] = clone $perso;
        }

        if ($step == 'open') {
            $ppo->flashMessages = '';
            if (Kernel::getFlashMessage('success')) {
                $ppo->flashMessages .= '<p class="mesgSuccess">' . Kernel::getFlashMessage('success').'</p>';
                Kernel::setFlashMessage('success', '');
            }
            if (Kernel::getFlashMessage('warning')) {
                $ppo->flashMessages .= '<p class="mesgError"> ' . Kernel::getFlashMessage('warning').'</p>';
                Kernel::setFlashMessage('warning', '');
            }
            if (Kernel::getFlashMessage('error')) {
                $ppo->flashMessages .= '<p class="mesgError">' . Kernel::getFlashMessage('error').'</p>';
                Kernel::setFlashMessage('error', '');
            }

            $ppo->title = Kernel::Code2Name('mod_' . $this->module);
            if ($ppo->titleContext) {
                $ppo->title = $titleContext;
            }

           $ppo->module = $this->module;
		   // $toReturn .= '	<div class="content content-'.$this->module.'">';

            $toReturn = $this->_usePPO ($ppo, 'module.header.tpl');

        } else {
            

            // Cas des groupes : on ajoute les membres et admin, selon les droits
            if ($ppo->myNode['type'] == 'CLUB') {
                //_dump($modules);
                //_dump($curmod);
                //_dump(_request('module'));
                //_dump($nodes[$node['type']][$node['id']]['modules']);

                /*
                  $addModule = new CopixPPO ();
                  $addModule->node_type = $ppo->myNode['type'];
                  $addModule->node_id = $ppo->myNode['id'];
                  $addModule->module_type = 'MOD_COMPTES';
                  $addModule->module_id = 0;
                  $addModule->module_nom = CopixI18N::get ('groupe|groupe.group.members');
                  $modules[] = $addModule;

                  $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
                  $mondroit = Kernel::getLevel($ppo->myNode['type'], $ppo->myNode['id']);
                  if ($groupeService->canMakeInGroupe('ADMIN', $mondroit)) {
                  $addModule = new CopixPPO ();
                  $addModule->node_type = $ppo->myNode['type'];
                  $addModule->node_id = $ppo->myNode['id'];
                  $addModule->module_type = 'MOD_ADMIN';
                  $addModule->module_id = 0;
                  $addModule->module_nom = CopixI18N::get ('groupe|groupe.group.admin');
                  $modules[] = $addModule;
                  }
                 */

                $addModule = new CopixPPO ();
                $addModule->node_type = $ppo->myNode['type'];
                $addModule->node_id = $ppo->myNode['id'];
                $addModule->module_type = 'MOD_ADMIN';
                $addModule->module_id = 0;
                $addModule->module_nom = CopixI18N::get('groupe|groupe.group.admin');
                $modules[] = $addModule;

                // Patch pour highlight des membres et de l'admin
                if ($ppo->curmod == 'groupe' && _request('action') == 'getHomeMembers')
                    $ppo->curmod = 'comptes';
                elseif ($ppo->curmod == 'groupe' && (_request('action') == 'getHomeAdmin' || _request('action') == 'getDelete' || _request('action') == 'getHomeAdminMembers' || _request('action') == 'getHomeAdminModules' || _request('action') == 'getEdit'))
                    $ppo->curmod = 'admin';
            }

            if (!empty($modules)) {
				$ppo->modules = $modules;
				$toReturn = $this->_usePPO ($ppo, 'module.toolset.tpl');
            }
            
			
			$toReturn .= $this->_usePPO ($ppo, 'module.footer.tpl');
        }
		
        return true;
    }

}

