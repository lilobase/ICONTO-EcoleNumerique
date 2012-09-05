<?php
/**
 * Grvilles - Classes
 *
 * @package	Iconito
 * @subpackage  Grvilles
 * @version     $Id: grvilles.class.php,v 1.1 2009-08-31 09:59:53 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */


class Regroupements
{
    public function getMenu()
    {
        $menu = array();

        $menu[] = array(
            'txt' => 'Regroupements',
            'url' => CopixUrl::get ('regroupements||')
        );

        $menu[] = array(
            'txt' => 'Groupes de villes',
            'url' => CopixUrl::get ('regroupements|villes|')
        );

        $menu[] = array(
            'txt' => 'Groupes d\'&eacute;coles',
            'url' => CopixUrl::get ('regroupements|ecoles|')
        );

        return($menu);
    }
}

