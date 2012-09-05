<?php
/**
 * Sso - Zone
 *
 * @package	Iconito
 * @subpackage  Sso
 * @version     $Id: sso.zone.php,v 1.1 2006-11-13 15:44:35 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneSso extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        // $tpl = new CopixTpl ();

        // $toReturn = $tpl->fetch ('login.bar.tpl');
        $toReturn = '';

        return true;
    }
}
