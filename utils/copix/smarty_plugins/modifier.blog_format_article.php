<?php

/**
* @package   copix
* @subpackage SmartyPlugins
* @version   $Id: modifier.blog_format_article.php,v 1.3 2008-12-05 11:06:27 cbeyer Exp $
* @author   Jouanneau Laurent
*           see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/



/**
 * Plugin smarty type modifier
 * Purpose: formate un article de blog selon le format choisi vers l'HTML
 * Input:
 * Example:  {$text|blog_format_article:wiki}  {$text|blog_format_article:html}
 * @param string  $txt  Texte saisi à convertir en HTML
 * @param string  $format Format choisi
 * @return string
 */

function smarty_modifier_blog_format_article ($txt, $format)
{
    $res = '';
    switch ($format) {
        case 'wiki' :
            require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.wiki.php');
            $res = smarty_modifier_wiki ($txt);
            break;
        case 'dokuwiki' :
            require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.dokuwiki.php');
            $res = smarty_modifier_dokuwiki ($txt);
            break;
        case 'html' :
        case 'fckeditor' :
        case 'ckeditor' :
            $res = $txt;
            break;
        default :
            $res = "Format $format non géré dans smarty_modifier_blog_format_article";
    }
    return $res;
    /*
    require_once(COPIX_UTILS_PATH.'CopixWikiRenderer.lib.php');

    if($config_file_selector == '' )

      $wiki= new CopixWikiRenderer();

    else

      $wiki= new CopixWikiRenderer($config_file_selector);

    return $wiki->render($string);
    */
}

