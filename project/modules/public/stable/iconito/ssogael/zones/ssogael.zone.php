<?php
/**
 * SsoGael - Zone
 *
 * @package	Iconito
 * @subpackage  SsoGael
 * @version     $Id: ssogael.zone.php,v 1.2 2007-02-15 16:12:44 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneSsogael extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        // $tpl = new CopixTpl ();

        // $toReturn = $tpl->fetch ('login.bar.tpl');
        $toReturn = '';

        return true;
    }
}
