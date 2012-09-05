<?php

class ZoneModMinimailPrefs extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $toReturn = $tpl->fetch ('minimail|modprefs.tpl');

        return true;
    }

}
