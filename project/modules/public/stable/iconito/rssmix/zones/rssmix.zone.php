<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ZoneRssMix extends enicZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign('urladmin', $this->url('rssmix|default|default'));
        $tpl->assign('userIsAdmin', Kernel::isAdmin());
        $tpl->assign('urlRssMix', $this->url('rssmix|default|GetRssFeedAjax'));
        $toReturn = $tpl->fetch('rssmix|zone.tpl');
        $this->addJs('js/jcarousellite/jcarousellite.js');
        $this->addCss('styles/module_rssmix.css');
        return true;
    }


}
