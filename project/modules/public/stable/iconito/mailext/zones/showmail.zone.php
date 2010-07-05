<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ZoneShowMail extends enicZone{

    function _createContent (&$toReturn) {


        $tpl = & new CopixTpl ();

        $toReturn = $tpl->fetch ('mailext|showmsg.tpl');

        return true;
    }


}
?>
