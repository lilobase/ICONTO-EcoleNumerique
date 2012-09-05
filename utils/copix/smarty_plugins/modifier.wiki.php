<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: modifier.wiki.php,v 1.6 2007-11-13 10:37:42 cbeyer Exp $

* @author   Jouanneau Laurent

*           see copix.aston.fr for other contributors.

* @copyright 2001-2005 CopixTeam

* @link      http://copix.aston.fr

* @link      http://copix.org

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/





/**

 * Plugin smarty type modifier

 * Purpose:  convert a formated wiki text to html text

 * Input:

 * Example:  {$text|wiki}  {$text|wiki:"myModule|mywiki"}

 * @param string  $string  string to convert

 * @param string  $config_file_selector   config to use with wiki renderer

 * @return string

 */

function smarty_modifier_wiki ($string, $config_file_selector = '')
{
        $php_version = explode('.', PHP_VERSION);
        if ((int)$php_version[0]>=5) {
        require_once(COPIX_UTILS_PATH.'CopixWikiRendererPhp5.lib.php');
        if($config_file_selector == '' )
          $wiki= new WikiRenderer();
        else
          $wiki= new WikiRenderer($config_file_selector);
        } else {
        require_once(COPIX_UTILS_PATH.'CopixWikiRenderer.lib.php');
        if($config_file_selector == '' )
          $wiki= new CopixWikiRenderer();
        else
          $wiki= new CopixWikiRenderer($config_file_selector);
        }
    return $wiki->render($string);

}

