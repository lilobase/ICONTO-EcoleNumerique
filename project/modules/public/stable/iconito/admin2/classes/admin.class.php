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


class Admin {

	/**
	 * D�termine si l'usager courant peut acc�der � la rubrique d'administration
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/05
	 * @return boolean True s'il peut, false sinon
	 */
  function canAdmin () {
    return (_currentUser()->getExtra('type') == 'USER_EXT' && _currentUser()->getExtra('id')==1);
  }

	
	/**
	 * Renvoie le menu de toutes les pages d'admin
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/19
	 * @return string Contenu du menu (liens...)
	 */
  function getMenu () {
    $menu = array();
		$menu[] = array(
			'txt' => CopixI18N::get ('admin2|admin.shortDescription'),
			'url' => CopixUrl::get ('admin2||')
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('admin2|admin.menu.cache'),
			'url' => CopixUrl::get ('admin2|cache|')
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('admin2|admin.menu.stats'),
			'url' => CopixUrl::get ('admin2|stats|')
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('kernel|demo.titlePage'),
			'url' => CopixUrl::get ('kernel|demo|')
		);
		$menu[] = array(
			'txt' => CopixI18N::get ('admin2|admin.menu.phpinfo'),
			'url' => CopixUrl::get ('admin2|admin|phpinfo')
		);
    return $menu;
  }

}

?>