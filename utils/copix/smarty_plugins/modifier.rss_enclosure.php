<?php

/**

* @package   copix

* @subpackage SmartyPlugins

* @version   $Id: modifier.rss_enclosure.php,v 1.3 2007-03-26 15:10:55 cbeyer Exp $

* @author   Christophe Beyer

* @copyright 2007 CAP-TIC

* @link      http://www.cap-tic.fr

* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file

*/

_classInclude('malle|malleservice');

/**

 * Plugin smarty type modifier
 * Purpose: A partir d'un article du RSS, extrait ce qu'il faut afficher en "enclosure"
 * Input: Chaine de caractères (tout l'article)
 * Output: Chaine de caractères (tags <enclosure> avec le bonc ontenu ou chaine vide si aucun contenu multimédia
 * Example:  {$text|rss_enclosure}
 * @return string
 */

function smarty_modifier_rss_enclosure ($string)
{
    $txt = '';

    //<enclosure url="http://www.scripting.com/mp3s/weatherReportSuite.mp3" length="12216320" type="audio/mpeg" />
    if (preg_match_all("/\[\[(.*)\]\]/sU", $string, $regs, PREG_SET_ORDER)) {
        //print_r($regs);
        foreach ($regs as $reg) {
            //print_r($reg);
            list($url, $type) = explode("|", $reg[1]);

            $url = rawurldecode($url);

            $length = @filesize($url);
            $ext = substr($url,strrpos ($url, ".")+1);
            $infos = MalleService::getTypeInfos ('', $url);
            //print_r($infos);
            if ($length && $infos['type_mime']) {

                $file = $url;

                $pos = strrpos($file, '/');
                if ($pos === false)	{ $name = $file; $href=$name; } else								{ $name = substr($file,$pos+1); $href=substr($file,0,$pos+1).rawurlencode($name); }

                $txt .= '<enclosure url="'.CopixUrl::get().$href.'" length="'.$length.'" type="'.$infos['type_mime'].'" />';
            }
        }
    }
    return $txt;
}

