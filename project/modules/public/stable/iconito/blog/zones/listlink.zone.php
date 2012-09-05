<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listlink.zone.php,v 1.6 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneListLink extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      $tpl  = new CopixTpl ();

      $blog = $this->getParam('blog', '');

      $dao = _dao('blog|bloglink');
      $tpl->assign ('listLink' , $dao->getAllLinksFromBlog($blog->id_blog));

      $toReturn = $tpl->fetch('listlink.tpl');
      return true;
   }
}
