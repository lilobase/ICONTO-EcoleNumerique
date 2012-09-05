<?php
/**
 * SsoGael - Classes
 *
 * @package	Iconito
 * @subpackage  SsoGael
 * @version     $Id: ssogael.class.php,v 1.5 2007-02-15 16:12:44 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */


class Ssogael
{
    public function canSsoGael()
    {
        if( !Kernel::isEnseignant() && !Kernel::isAgentVille() ) return false;

        if( !CopixConfig::exists('|urlGael') || trim(CopixConfig::get('|urlGael'))=='' ) return false;

        $mysession = Kernel::getSessionBU();
        if( trim($mysession['cle_privee'])=='' ) return false;

        return true;
    }
}

