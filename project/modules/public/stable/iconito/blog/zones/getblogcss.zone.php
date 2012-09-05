<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: getblogcss.zone.php,v 1.10 2007-06-01 16:08:43 cbeyer Exp $
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

class ZoneGetBlogCss extends CopixZone
{
   public function _createContent (&$toReturn)
   {
            $res = '';

      $blog = $this->getParam('blog',null);
      $editFile = $this->getParam('editFile',false);

                $hisFile = COPIX_VAR_PATH.CopixConfig::get ('blog|cssPath').$blog->id_blog.'.css';

                if (file_exists($hisFile) && ($blog->style_blog_file==1||$editFile)) { // CSS personnalisée
                    $file = $hisFile;
                } else {	// CSS de base
                    //$file = COPIX_WWW_PATH.CopixConfig::get ('blog|cssPath').$blog->logo_blog;

                    $parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
                    //print_r($parent);
                    switch ($parent['type']) {
                        case 'BU_CLASSE' : 	$file = 'styles/module_blog_classe.css'; break;
                        case 'BU_VILLE' : 	$file = 'styles/module_blog_ville.css'; break;
                        case 'BU_ECOLE' : 	$file = 'styles/module_blog_ecole.css'; break;
                        case 'BU_GRVILLE' : $file = 'styles/module_blog_grville.css'; break;
                        default : 					$file = 'styles/module_blog_groupe.css'; break;
                    }
                }
                //print_r("file=$file");
                if (file_exists($file)) {
                    $res = file_get_contents ($file);
                }

      // retour de la fonction :
      $toReturn = $res;
      return true;
   }
}
