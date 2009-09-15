<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showbloglink.zone.php,v 1.4 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/
require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'blogauth.class.php');

class ZoneShowBlogLink extends CopixZone {
	function _createContent (&$toReturn) {
		//Getting the user.
		//Create Services, and DAO
		$tpl = & new CopixTpl ();

		$id_blog = $this->getParam('id_blog', '');
		//capability
		//$tpl->assign ('canManageLink' , BlogAuth::canMakeInBlog('ADMIN_LIENS',create_blog_object($id_blog)));

		$tpl->assign ('id_blog', $id_blog);
		$tpl->assign ('kind', $this->getParam('kind', ''));

		// Recherche de tous les liens de la base
		$blogLinkDAO = CopixDAOFactory::create('blog|bloglink');
		$tabLinks = $blogLinkDAO->findAllOrder($id_blog);

		$tpl->assign ('tabLinks', $tabLinks);

		// retour de la fonction :
		$toReturn = $tpl->fetch('blog.show.link.tpl');
		return true;
	}
}
?>