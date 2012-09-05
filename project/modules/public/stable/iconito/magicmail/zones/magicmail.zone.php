<?php
/**
 * Magicmail - Zone
 *
 * @package	Iconito
 * @subpackage  Magicmail
 * @version     $Id: magicmail.zone.php,v 1.1 2006-08-14 13:59:35 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ZoneMagicmail extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        // $tpl = new CopixTpl ();

        // $toReturn = $tpl->fetch ('login.bar.tpl');
        $toReturn = '';

        return true;
    }
}
