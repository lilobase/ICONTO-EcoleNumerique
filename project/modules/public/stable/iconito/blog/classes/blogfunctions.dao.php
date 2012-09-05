<?php
/**
* @package	copix
* @version	$Id: blogfunctions.dao.class.php,v 1.6 2006-10-09 16:21:31 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogfunctions
{
   /**
    * @param
    * createBlogFunctions
    * @return
    */
   public function createBlogFunctions ($id_blog, $tabBlogFunctions)
   {
             $blogFunctions = _record('blog|blogfunctions');
       foreach($tabBlogFunctions as $fct) {
               eval('$blogFunctions->'.$fct->value.'='.$fct->selected.';');
       }
       $blogFunctions->id_blog = $id_blog;
             $this->insert($blogFunctions);
   }


   /**
    * @param
    * updateBlogFunctions
    * @return
    */
   public function updateBlogFunctions ($id_blog, $tabBlogFunctions)
   {
             $blogFunctions = _record('blogfunctions');
       foreach($tabBlogFunctions as $fct) {
               eval('$blogFunctions->'.$fct->value.'='.$fct->selected.';');
       }
       $blogFunctions->id_blog = $id_blog;
             $this->update($blogFunctions);
   }

}


