<?php
/*
	@file 		usermenu.zone.php
	@desc		Build user menu
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/


/**
 * Zone affichant le menu
 */
class ZoneUserMenu extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		$user = _currentUser ();
//		echo '<pre>';
//		print_r($user->getExtra('id'));
//		echo '</pre>';
		
		if ($user->isConnected()) {
			$ppo->usermenu = "<ul class=\"usermenu\">";
			$menuitem = array("title", "url", "class");
			$logoff = array("title", "url", "class");
			$utype = $user->getExtra('type');
			$uid = $user->getExtra('id');

			$menuitems = array();
			
			$menuitem["title"] = _i18n('kernel|kernel.codes.mod_agenda');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'agenda','mid'=>''));
			$menuitem["class"] = "menu-agenda";
			array_push($menuitems, $menuitem);

			$menuitem["title"] = _i18n('kernel|kernel.codes.mod_minimail');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'minimail','mid'=>''));
			$menuitem["class"] = "menu-minimail";
      // Indicateur des minimails non lus
      $nb = _dao("minimail|minimail_to")->getNbRecvUnread(_currentUser()->getId());
      if ($nb > 0) {
        $menuitem["before"] = '<a title="'.$menuitem["title"].'" id="counter" href="'.$menuitem["url"].'">'.$nb.'</a>';
      }
			array_push($menuitems, $menuitem);
      $menuitem["before"] = '';

			$menuitem["title"] = _i18n('kernel|kernel.codes.mod_annuaire');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'annuaire','mid'=>''));
			$menuitem["class"] = "menu-annuaire";
			array_push($menuitems, $menuitem);
      
			$menuitem["title"] = _i18n('kernel|kernel.codes.mod_malle');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'malle','mid'=>''));
			$menuitem["class"] = "menu-malle";
			array_push($menuitems, $menuitem);

			$menuitem["title"] = _i18n('kernel|kernel.codes.mod_ressource');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'ressource', 'mid'=>'2'));
			$menuitem["class"] = "menu-ressource";
			array_push($menuitems, $menuitem);

			$menuitem["title"] = _i18n('auth|auth.nav.prefs');
			$menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'prefs','mid'=>''));
			$menuitem["class"] = "menu-prefs";
			array_push($menuitems, $menuitem);

			if ($user->hasAssistance()) {
				$menuitem["title"] = _i18n('kernel|kernel.codes.mod_assistance');
				$menuitem["url"] = CopixUrl::get ('assistance||');
				$menuitem["class"] = "menu-assistance";			
				array_push($menuitems, $menuitem);
				}	
			
			if (_sessionGet('user_animateur')) {
				$logout["title"] = _i18n('auth|auth.buttons.ctrlout');
				$logout["url"] = CopixUrl::get ('assistance||switch');
				$logout["class"] = "logout";			
				}
			else {
				$logout["title"] = _i18n('auth|auth.buttons.logout');
				$logout["url"] = CopixUrl::get ('auth|log|out');
				$logout["class"] = "logout";			
				}

			foreach ($menuitems as $item) {
				$ppo->usermenu .= "<li class=\"".$item["class"]."\">";
        if (isset($item["before"]))
          $ppo->usermenu .= $item["before"];
				$ppo->usermenu .= "<a class=\"item ".$item["class"]."\" href=\"".$item["url"]."\" title=\"".$item["title"]."\"><span class=\"hidden\">".$item["title"]."</span></a>";
        
				$ppo->usermenu .= "</li>";
				}
			$ppo->usermenu .= "	<li class=\"".$logout["class"]."\">";
			$ppo->usermenu .= "<a class=\"".$logout["class"]."\" href=\"".$logout["url"]."\" title=\"".$logout["title"]."\"><span class=\"hidden\">".$logout["title"]."</span></a>";
			$ppo->usermenu .= "	</li>";
			$ppo->usermenu .= "</ul>";
			}
		else $ppo->usermenu = "";

		$toReturn = $this->_usePPO ($ppo, 'usermenu.tpl');
		return true;
	}
} ?>
