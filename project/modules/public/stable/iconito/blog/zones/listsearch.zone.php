<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listsearch.zone.php,v 1.7 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneListSearch extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      $tpl  = new CopixTpl ();

      $blog = $this->getParam('blog', '');

      $tpl->assign ('blog' , $blog);

      $toReturn = $tpl->fetch('listsearch.tpl');
      return true;
   }
}
