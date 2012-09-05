<?php

/**
* @package   copix
* @subpackage SmartyPlugins
* @version   $Id: modifier.dokuwiki.php,v 1.2 2009-01-09 16:06:15 cbeyer Exp $
* @author   Jouanneau Laurent
*           see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/




/**
 * Application de la mise en forme Dokuwiki
 * Purpose:  convert a formated wiki text to html text
 * Input:
 * Example:  {$text|wiki}  {$text|wiki:"myModule|mywiki"}
 * @param string  $string  string to convert
 * @return string
 */


define('DOKU_INC','dokuwiki/');
define('DOKU_BASE','dokuwiki/');


require_once(DOKU_INC.'inc/init.php');
require_once(DOKU_INC.'inc/common.php');
//require_once(DOKU_INC.'inc/events.php');
//require_once(DOKU_INC.'inc/pageutils.php');
require_once(DOKU_INC.'inc/html.php');
//require_once(DOKU_INC.'inc/auth.php');
//require_once(DOKU_INC.'inc/actions.php');

require_once (COPIX_UTILS_PATH.'WikiIconito.lib.php');
_classInclude('malle|malleservice');
_classInclude('malle|kernelmalle');

function smarty_modifier_dokuwiki ($string)
{
    return utf8_decode(p_wiki_xhtml (utf8_encode($string)));
}

