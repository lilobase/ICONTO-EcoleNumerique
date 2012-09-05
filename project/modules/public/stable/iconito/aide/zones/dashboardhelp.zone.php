<?php


class ZoneDashboardHelp extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";

        $toReturn = $this->_usePPO ($ppo, 'dashboardhelp.tpl');
        return true;
    }



}
