<?php
/**
 * Grvilles - Zone
 *
 * @package	Iconito
 * @subpackage  Grvilles
 * @version     $Id: grvilles.zone.php,v 1.1 2009-08-31 09:59:53 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneGrvilles extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        // $tpl = new CopixTpl ();

        // $toReturn = $tpl->fetch ('login.bar.tpl');
        $toReturn = '';

        return true;
    }
}
