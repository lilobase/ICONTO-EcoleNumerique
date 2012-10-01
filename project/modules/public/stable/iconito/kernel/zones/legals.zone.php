<?php


class ZoneLegals extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";

        $ppo->legals = _i18n('public|public.nav.copyright');
//		$ppo->legals .= " | <a href=".CopixUrl::get ('aide||')." title="._i18n('public|public.aide')."><b>"._i18n('public|public.aide')."</b></a>";
        $ppo->legals .= "  - <a href=\"".CopixUrl::get ('public||aPropos')."\" title=\""._i18n('public|public.apropos')."\">"._i18n('public|public.apropos')."</a>";

        $toReturn = $this->_usePPO ($ppo, 'legals.tpl');
        return true;
    }



}
