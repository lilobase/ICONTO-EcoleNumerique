<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblog.zone.php,v 1.5 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/

_classInclude('blog|blogauth');

class ZoneShowBlog extends CopixZone {
	function _createContent (&$toReturn) {
		//Getting the user.
		//Create Services, and DAO
		$tpl = & new CopixTpl ();

		$id_blog = $this->getParam('id_blog', '');

		$tpl->assign ('blog', $this->getParam('blog',null));
		$tpl->assign ('id_blog', $id_blog);
		$tpl->assign ('kind', $this->getParam('kind', ''));
		$tpl->assign ('tabBlogFunctions', $this->getParam('tabBlogFunctions', null));

		$tpl->assign ('RESULT', $this->getParam('RESULT', ''));

		// retour de la fonction :
		$toReturn = $tpl->fetch('blog.show.tpl');
		return true;
	}
}
?>
