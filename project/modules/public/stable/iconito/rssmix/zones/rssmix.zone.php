<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ZoneRssMix extends enicZone{

    function _createContent (&$toReturn) {


        $tpl = new CopixTpl ();

        $tpl->assign('urladmin', $this->url('rssmix|default|liste'));
        $tpl->assign('userIsAdmin', $this->user->root);
        $toReturn = $tpl->fetch ('rssmix|zone.tpl');
        //$this->addCss('styles/module_mailext.css');
        return true;
    }


}
?>
