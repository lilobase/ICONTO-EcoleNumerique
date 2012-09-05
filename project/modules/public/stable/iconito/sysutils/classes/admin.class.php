<?php
/**
 * Admin - Classes
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: admin.class.php,v 1.4 2009-01-09 16:06:15 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */


class Admin
{
    /**
     * Détermine si l'usager courant peut accéder à la rubrique d'administration
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/05
     * @return boolean True s'il peut, false sinon
     */
    public function canAdmin ()
    {
        if (Kernel::isAdmin())
            return true;
        else
            return false;
    // return (_currentUser()->getExtra('type') == 'USER_EXT' && _currentUser()->getExtra('id')==1);
    }


    /**
     * Renvoie le menu de toutes les pages d'admin
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/19
     * @param string $iCurrentTab Onglet selectionne
     * @return string Contenu du menu (liens...)
     */
  public function getMenu ($iCurrentTab='')
  {
    $menu = array();
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.shortDescription'),
            'url' => CopixUrl::get ('sysutils||'),
            'current' => ($iCurrentTab == 'sysutils'),
        );
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.cache'),
            'url' => CopixUrl::get ('sysutils|cache|'),
            'current' => ($iCurrentTab == 'cache'),
        );
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.stats'),
            'url' => CopixUrl::get ('sysutils|stats|'),
            'current' => ($iCurrentTab == 'stats'),
        );
        if (Kernel::isSuperAdmin()) {
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.demo'),
            'url' => CopixUrl::get ('sysutils|demo|'),
            'current' => ($iCurrentTab == 'demo'),
        );

        /*
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.grvilles'),
            'url' => CopixUrl::get ('sysutils|grvilles|')
        );
        */
        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.phpinfo'),
            'url' => CopixUrl::get ('sysutils|admin|phpinfo'),
            'current' => ($iCurrentTab == 'phpinfo'),
        );
        }

        $menu[] = array(
            'txt' => CopixI18N::get ('sysutils|admin.menu.groupes'),
            'url' => CopixUrl::get ('sysutils|groupes|'),
            'current' => ($iCurrentTab == 'groupes'),
        );

    return $menu;
  }

}

