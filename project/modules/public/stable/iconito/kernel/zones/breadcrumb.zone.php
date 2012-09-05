<?php
/**
 * @package ecolenumerique
 * @subpackage kernel
 * @version  mainmenu.zone.php 2010-01-25 sholtz
 * @author   Stephane Holtz <sholtz@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Zone affichant le menu
 */
class ZoneBreadcrumb extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $ppo = new CopixPPO ();
        $user = _currentUser ();

        if ($user->isConnected()) {
            $ppo->breadcrumb = _i18n('kernel|kernel.breadcrumb.fake');
            } else $ppo->breadcrumb = _i18n('kernel|kernel.breadcrumb.home');;

        $toReturn = $this->_usePPO ($ppo, 'breadcrumb.tpl');
        return true;
    }
}