<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ZoneShowMail extends enicZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();
        $tpl->assign('content', $this->service('mailextService')->getConf());
        $tpl->assign('urlmail', $this->url('mailext|mailext|getMsg'));
        $toReturn = $tpl->fetch ('mailext|showmsg.tpl');
        $this->addCss('styles/module_mailext.css');
        return true;
    }


}
