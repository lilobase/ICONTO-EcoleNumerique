<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: modifier.substrpos.php,v 1.1 2007-03-23 16:22:01 cbeyer Exp $

* @author   Christophe Beyer

* @copyright 2007 CAP-TIC

* @link      http://www.cap-tic.fr

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/


/**

 * Plugin smarty type modifier
 * Purpose: A partir d'une chaine de caractères (typiquement une URL), en extrait la fin, en commençant à la dernière occurence d'un caractère (par défaut le slash).
 * Input: Chaine de caractères (URL ou autre)
 * Output: Chaine de caractères.
 * Example:  {$text|substrpos} {$text|substrpos:/}
 * @return string
 */

function smarty_modifier_substrpos ($string, $char="/")
{
    $txt = $string;
    $pos = strrpos($string, $char);
    if ($pos !== false) {
      $txt = substr($string,$pos+1);
    }
    return $txt;
}

