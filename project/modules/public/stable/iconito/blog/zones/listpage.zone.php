<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listpage.zone.php,v 1.8 2007-09-04 09:59:54 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneListPage extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      $tpl  = new CopixTpl ();

      $blog = $this->getParam('blog', '');

      $dao = _dao('blog|blogpage');
      $arPage = $dao->getAllPagesFromBlog($blog->id_blog);
      foreach($arPage as $key=>$page) {
               $arPage[$key]->url_bpge = urlencode($page->url_bpge);
      }
      $tpl->assign ('listPage' , $arPage);

      $toReturn = $tpl->fetch('listpage.tpl');
      return true;
   }
}
