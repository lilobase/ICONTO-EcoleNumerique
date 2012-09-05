<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listarchive.zone.php,v 1.7 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneListArchive extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      $tpl  = new CopixTpl ();

      $blog = $this->getParam('blog', '');

      $dao = _dao('blog|blogarticle');
      $tpl->assign ('listArchive' , $dao->getAllArchivesFromBlog($blog->id_blog));
      $tpl->assign ('blog' , $blog);


      $toReturn = $tpl->fetch('listarchive.tpl');
      return true;
   }
}
