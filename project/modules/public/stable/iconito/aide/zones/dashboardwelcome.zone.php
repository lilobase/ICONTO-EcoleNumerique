<?php


class ZoneDashboardWelcome extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";

        $toReturn = $this->_usePPO ($ppo, 'dashboardwelcome.tpl');
        return true;
    }



}
