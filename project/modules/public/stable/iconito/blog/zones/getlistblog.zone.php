<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: getlistblog.zone.php,v 1.6 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/

_classInclude('blog|blogauth');

class ZoneGetListBlog extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      //Getting the user.
      //Create Services, and DAO
      $tpl = new CopixTpl ();

      $res = $this->getParam('resultats',null);

      //capability
      $tpl->assign ('canCreate' , BlogAuth::canCreate());
            $resultats = array();
            foreach($res as $r) {
          $r->canWrite = BlogAuth::canWrite($r->id_blog);
          $r->canRead = BlogAuth::canRead($r->id_blog);
                array_push($resultats, $r);
            }

      $tpl->assign ('resultats', $resultats);
      // retour de la fonction :
      $toReturn = $tpl->fetch('blog.get.list.tpl');
      return true;
   }
}
