<?php
/**
 * Assistance - Zone
 *
 * @package	Iconito
 * @subpackage  Assistance
 * @version     $Id: assistance.zone.php,v 1.1 2009-09-30 10:06:20 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneAssistance extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        // $tpl = new CopixTpl ();

        // $toReturn = $tpl->fetch ('login.bar.tpl');
        $toReturn = '';

        return true;
    }
}
