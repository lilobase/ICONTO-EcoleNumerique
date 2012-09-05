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
        //CopixHTMLHeader::addCSSLink (_resource("styles/module_kernel.css"));

        $ppo = new CopixPPO ();
        $toReturn = "";
        $step = $this->getParam('STEP', 'open');
        $curmod = $this->getParam('MODULE', '');
        $titlepage = $this->getParam('TITLE_PAGE', '');
        $titleContext = $this->getParam('TITLE_CONTEXT', '');

        //_dump($curmod);
        $myNode = CopixSession::get('myNode');
        $myNodeData = Kernel::getNodeInfo($myNode['type'], $myNode['id'], false);
        $panelClass = strtolower($myNode['type']);
        $closeUrl = CopixUrl::get('kernel|dashboard|default');

        if (strpos($myNode['type'], 'USER_') === false) {
            // Pour tous sauf les users...
            $modules = Kernel::getModEnabled($myNode['type'], $myNode['id'], _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        } elseif (strpos($myNode['type'], 'USER_ELE') !== false) {
            // Cas particulier, les enfants (vue parent)
            $parents = Kernel::getNodeParents($myNode['type'], $myNode['id']);
            $parent = Kernel::filterNodeList($parents, 'BU_CLASSE');
            if (count($parent)) {
                $modules = Kernel::getModEnabled($parent[0]['type'], $parent[0]['id'], _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
            }
            $perso->node_type = $myNode['type'];
            $perso->node_id = $myNode['id'];
            $perso->module_type = 'MOD_CARNET';
            $perso->module_id = 'ELEVE_' . $myNode['id'];
            $perso->module_nom = Kernel::Code2Name('MOD_CARNET');
            if ($carnetDeLiaison)
                $modules[] = clone $perso;

            // _dump($modules);
        }

        if ($step == 'open') {

            $toReturn = '';

            if (Kernel::getFlashMessage('success')) {
                $toReturn .= '<p class="mesgSuccess">' . Kernel::getFlashMessage('success').'</p>';
                Kernel::setFlashMessage('success', '');
            }
            if (Kernel::getFlashMessage('warning')) {
                $toReturn .= '<p class="mesgError"> ' . Kernel::getFlashMessage('warning').'</p>';
                Kernel::setFlashMessage('warning', '');
            }
            if (Kernel::getFlashMessage('error')) {
                $toReturn .= '<p class="mesgError">' . Kernel::getFlashMessage('error').'</p>';
                Kernel::setFlashMessage('error', '');
            }




            $toReturn .= '<div class="dashboard module_dash tools_right ink_blue font_dash">';
            $toReturn .= '<div class="dashpanel ' . $panelClass . '">';
            $toReturn .= '	<div class="title">';
            if (strpos($myNode['type'], 'USER_') !== false) {
                $toReturn .= '		<div class="groupname">' . $myNodeData['prenom'] . ' ' . $myNodeData['nom'] . '</div>';
            } else {
                $toReturn .= '		<div class="groupname">' . $myNodeData['nom'] . '</div>';
            }
            $toReturn .= '		<div class="wcontrol">';
            $toReturn .= '		<a class="dashclose" href="' . $closeUrl . '"></a>';
            $toReturn .= '		</div>';
            $toReturn .= '		<span>';
            $title = Kernel::Code2Name('mod_' . $this->module);
            if ($titleContext) {
                $title = $titleContext;
            }
            $toReturn .= $title . '</span>';
            $toReturn .= '	</div>';
            $toReturn .= '	<div class="content content-'.$this->module.'">';



        } else {
            $toReturn = '	</div>';

            // Cas des groupes : on ajoute les membres et admin, selon les droits
            if ($myNode['type'] == 'CLUB') {
                //_dump($modules);
                //_dump($curmod);
                //_dump(_request('module'));
                //_dump($nodes[$node['type']][$node['id']]['modules']);

                /*
                  $addModule = new CopixPPO ();
                  $addModule->node_type = $myNode['type'];
                  $addModule->node_id = $myNode['id'];
                  $addModule->module_type = 'MOD_COMPTES';
                  $addModule->module_id = 0;
                  $addModule->module_nom = CopixI18N::get ('groupe|groupe.group.members');
                  $modules[] = $addModule;

                  $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
                  $mondroit = Kernel::getLevel($myNode['type'], $myNode['id']);
                  if ($groupeService->canMakeInGroupe('ADMIN', $mondroit)) {
                  $addModule = new CopixPPO ();
                  $addModule->node_type = $myNode['type'];
                  $addModule->node_id = $myNode['id'];
                  $addModule->module_type = 'MOD_ADMIN';
                  $addModule->module_id = 0;
                  $addModule->module_nom = CopixI18N::get ('groupe|groupe.group.admin');
                  $modules[] = $addModule;
                  }
                 */

                $addModule = new CopixPPO ();
                $addModule->node_type = $myNode['type'];
                $addModule->node_id = $myNode['id'];
                $addModule->module_type = 'MOD_ADMIN';
                $addModule->module_id = 0;
                $addModule->module_nom = CopixI18N::get('groupe|groupe.group.admin');
                $modules[] = $addModule;

                // Patch pour highlight des membres et de l'admin
                if ($curmod == 'groupe' && _request('action') == 'getHomeMembers')
                    $curmod = 'comptes';
                elseif ($curmod == 'groupe' && (_request('action') == 'getHomeAdmin' || _request('action') == 'getDelete' || _request('action') == 'getHomeAdminMembers' || _request('action') == 'getHomeAdminModules' || _request('action') == 'getEdit'))
                    $curmod = 'admin';
            }

            if (!empty($modules)) {
                $toReturn .= '	<div class="toolset">';
                $toReturn .= '		<ul>';

                foreach ($modules as $module) {
                    // _dump($module);
                    $module_type = explode('_', $module->module_type);
                    $module_type = strtolower($module_type[1]);
                    $module_id = (isset($module->module_id)) ? $module->module_id : '';
                    $highlight = ($module_type == $curmod) ? ' class="selected"' : '';
                    $target = (isset($module->module_popup)) ? ' target="_blank"' : '';
                    $class = ' class="' . $module->module_type . '"';
                    $title = ' title="' . $module->module_nom . '"';
                    $url = CopixUrl::get('kernel||go', array('ntype' => $myNode['type'], 'nid' => $myNode['id'], 'mtype' => $module_type, 'mid' => $module_id));
                    $href = ' href="' . $url . '"';
                    $toReturn .= '<li' . $highlight . '>';
                    $toReturn .= '<a' . $title . $class . $href . $target . '>';
                    $toReturn .= '<span class="label">' . $module->module_nom . '</span>';
                    $toReturn .= '<span class="valign"></span>';
                    $toReturn .= '</a>';
                    $toReturn .= '</li>';
                }

                $toReturn .= '		</ul>';
                $toReturn .= '	</div>';
            }
            $toReturn .= '</div>';
            $toReturn .= '</div>';
        }
        return true;
    }

}

