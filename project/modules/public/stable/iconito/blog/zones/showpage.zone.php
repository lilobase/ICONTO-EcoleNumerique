<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showpage.zone.php,v 1.8 2007-09-04 09:59:55 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneShowPage extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      $tpl  = new CopixTpl ();

      $blog = $this->getParam('blog', '');

      //on récupère l'ensemble des articles du blog
      $dao = _dao('blog|blogpage');
            $page = $dao->getPageByUrl($blog->id_blog, $this->getParam('page', ''));

            if (!$page) {
                $toReturn = $tpl->fetch('showpage.tpl');
          return true;
          }

      $tpl->assign ('page', $page);

            $plugStats = CopixPluginRegistry::get ("stats|stats");
            $plugStats->setParams(array('objet_a'=>$page->id_bpge));


      // retour de la fonction :
      $toReturn = $tpl->fetch('showpage.tpl');
      return true;
   }
}
