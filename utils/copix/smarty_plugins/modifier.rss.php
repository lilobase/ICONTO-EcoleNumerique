<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: modifier.rss.php,v 1.1 2006-11-29 14:10:48 cbeyer Exp $

* @author   Christophe Beyer

* @copyright 2006 CAP-TIC

* @link      http://www.cap-tic.fr

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/



/**

 * Plugin smarty type modifier
 * Purpose: Convertit une chaine de caractères à afficher dans un fichier XML pour un flux RSS sortant. Peut s'ajouter avec |wiki
 * Input: Chaine de caractères
 * Output: Chaine de caractères
 * Example:  {$text|rss} {$text|wiki|rss}
 * @return string
 */

function smarty_modifier_rss ($string)
{
    $txt = (trim($string));
    $txt = str_replace("<BR>",'<BR/>',$txt);
    $txt = str_replace("&",'&amp;',$txt);
    $txt = str_replace("<","&lt;",$txt);
    $txt = str_replace(">","&gt;",$txt);
    $txt = str_replace (chr(146), "'", $txt);

    return $txt;

}

