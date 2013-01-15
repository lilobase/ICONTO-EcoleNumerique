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
class ZoneUserMenu extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $ppo = new CopixPPO ();
        $user = _currentUser ();
        //		echo '<pre>';
        //		print_r($user->getExtra('type'));
        //		echo '</pre>';

        $ppo->menuitems = "";
        $ppo->logout = "";

        $utype = $user->getExtra('type');
        $uid = $user->getExtra('id');
        $ppo->uLogged = $user->isConnected();
        $ppo->uType = $utype;
        $ppo->uId = $uid;

        if ($user->isConnected()) {
            $menuitem = array("title", "url", "class");
            $logoff = array("title", "url", "class");

            $menuitems = array();

            if( !_sessionGet('user_animateur') ) { // Prise de controle a distance

            if( CopixConfig::exists('default|conf_ModVisio') && (1==CopixConfig::get('default|conf_ModVisio'))) {
                $menuitem["title"] = _i18n('kernel|kernel.codes.mod_visio');
                $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'visio','mid'=>''));
                $menuitem["class"] = "menu-visio";
                array_push($menuitems, $menuitem);
            }

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
                $menuitem["before"] = '<a title="'.$menuitem["title"].'" id="counter" href="'.$menuitem["url"].'"><span id="counter-text">'.$nb.'</span></a>';
            }
            array_push($menuitems, $menuitem);
            $menuitem["before"] = '';

            $menuitem["title"] = _i18n('kernel|kernel.codes.mod_annuaire');
            $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'annuaire','mid'=>''));
            $menuitem["class"] = "menu-annuaire";
            array_push($menuitems, $menuitem);

            /*$menuitem["title"] = _i18n('kernel|kernel.codes.mod_malle');
            $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'malle','mid'=>''));
            $menuitem["class"] = "menu-malle";
            array_push($menuitems, $menuitem);*/

            $menuitem["title"] = _i18n('kernel|kernel.codes.mod_classeur');
            $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'classeur','mid'=>''));
            $menuitem["class"] = "menu-classeur";
            array_push($menuitems, $menuitem);

            if( ! CopixConfig::exists('|can_module_ressources') || CopixConfig::get('|can_module_ressources') ) {
            $menuitem["title"] = _i18n('kernel|kernel.codes.mod_ressource');
            $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>$utype,'nid'=>$uid,'mtype'=>'ressource', 'mid'=>'2'));
            $menuitem["class"] = "menu-ressource";
            array_push($menuitems, $menuitem);
            }

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

            } // Prise de controle a distance

            if (_sessionGet('user_animateur')) {
                $logout["title"] = _i18n('auth|auth.buttons.ctrlout');
                $logout["url"] = CopixUrl::get ('assistance||switch');
                $logout["class"] = "logout";
            } else {
                $logout["title"] = _i18n('auth|auth.buttons.logout');

                if( CopixConfig::exists('default|conf_Cas_actif') && CopixConfig::get ('default|conf_Cas_actif') ) {
                    $logout["url"] = CopixUrl::get ('auth|cas|logout');
                } else {
                    $logout["url"] = CopixUrl::get ('auth|log|out');
                }

                $logout["class"] = "logout";
            }

            if( CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance'))) {
                $menuitem["title"] = _i18n('kernel|kernel.codes.mod_aide');
                $menuitem["url"] = CopixUrl::get('kernel||go', array('ntype'=>'CLUB','nid'=>$groupeAssistance,'mtype'=>'blog'));
                $menuitem["class"] = "menu-aide";
                array_push($menuitems, $menuitem);
            }

            $ppo->menuitems = $menuitems;
            $ppo->logout = $logout;
        }
        $toReturn = $this->_usePPO ($ppo, 'usermenu.tpl');
        return true;
    }
}
