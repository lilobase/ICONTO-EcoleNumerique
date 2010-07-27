<?php
/*
	@file 		moduleContext.zone.php
	@desc		Set any module in its dashboard context
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/

class ZoneModuleContext extends enicZone {

	function _createContent (&$toReturn) {

		//CopixHTMLHeader::addCSSLink (_resource("styles/module_kernel.css"));

		$ppo = new CopixPPO ();		
		$toReturn = "";
		$step = $this->getParam ('STEP', 'open');
		$curmod = $this->getParam ('MODULE', '');
		$titlepage = $this->getParam ('TITLE_PAGE', '');
		
		$myNode = CopixSession::get ('myNode');
		$myNodeData = Kernel::getNodeInfo($myNode['type'], $myNode['id'], false);
		$panelClass = strtolower($myNode['type']);
		$closeUrl = CopixUrl::get('kernel|dashboard|default');

		if ( strpos($myNode['type'], 'USER_') === false ) {
			$modules = Kernel::getModEnabled($myNode['type'], $myNode['id'],_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
		}
		
		if ($step=='open') {
			
			$toReturn  = '<div class="dashboard tools_right ink_blue font_dash">';
			$toReturn .= '<div class="dashpanel '.$panelClass.'">';
			$toReturn .= '	<div class="title">';
			$toReturn .= '		<div class="groupname">'.$myNodeData['nom'].'</div>';
			$toReturn .= '		<div class="wcontrol">';
			$toReturn .= '		<a class="dashclose" href="'.$closeUrl.'"></a>';
			$toReturn .= '		</div>';
			$toReturn .= '		<span>'.$this->module.'</span>';
			$toReturn .= '	</div>';
			$toReturn .= '	<div class="content">';

		}
		else {
			$toReturn  = '	</div>';

        // Cas des groupes : on ajoute les membres et admin, selon les droits
        if ($myNode['type'] == 'CLUB') {
          //_dump($modules);
          
          //_dump($curmod);
          //_dump(_request('module'));
          //_dump($nodes[$node['type']][$node['id']]['modules']);
          
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
          
          // Patch pour highlight des membres et de l'admin
          if ($curmod == 'groupe' && _request('action')=='getHomeMembers')
            $curmod = 'comptes';
          elseif ($curmod == 'groupe' && (_request('action')=='getHomeAdmin' || _request('action')=='getDelete' || _request('action')=='getHomeAdminMembers' || _request('action')=='getHomeAdminModules' || _request('action')=='getEdit'))
            $curmod = 'admin';
          
          
        }
        
      
      
			if (isset($modules)) {
				$toReturn .= '	<div class="toolset">';
				$toReturn .= '		<ul>';
				
				foreach ($modules as $module) {
					$module_type = explode('_', $module->module_type);
					$module_type = strtolower($module_type[1]);
					$module_id = (isset($module->module_id)) ? $module->module_id : '';
					$highlight = ($module_type == $curmod)? ' class="selected"' : '';
					$target = (isset($module->module_popup)) ? ' target="_blank"' : '';
					$class = ' class="'.$module->module_type.'"';
					$title = ' title="'.$module->module_nom.'"';
					$url = CopixUrl::get('kernel||go', array('ntype'=>$myNode['type'],'nid'=>$myNode['id'],'mtype'=>$module_type,'mid'=>$module_id));
					$href = ' href="'.$url.'"';
					$toReturn .= '<li'.$highlight.'>';
					$toReturn .= '<a'.$title.$class.$href.$target.'>';
					$toReturn .= '<span>'.$module->module_nom.'</span>';
					$toReturn .= '</a>';
					$toReturn .= '</li>';
				}
				
				$toReturn .= '		</ul>';
				$toReturn .= '	</div>';
			}
			$toReturn .= '</div>';
			$toReturn .= '</div>';
		}
/*			
		$ppo->legals = _i18n('public|public.nav.copyright');
		$ppo->legals .= " | <a href=".CopixUrl::get ('aide||')." title="._i18n('public|public.aide')."><b>"._i18n('public|public.aide')."</b></a>";
		$ppo->legals .= " | <a href=".CopixUrl::get ('public||aPropos')." title="._i18n('public|public.apropos').">"._i18n('public|public.apropos')."</a>";
		
		
		$toReturn = $this->_usePPO ($ppo, 'legals.tpl');
*/
		return true;
	}
	
}
?>