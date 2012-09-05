<?php
/**
* @package  Iconito
* @subpackage Prefs
* @version   $Id: default.desc.php,v 1.1 2006-04-18 11:45:21 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2005 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

$getprefs    = new CopixAction ('Prefs', 'getPrefs');
$setprefs    = new CopixAction ('Prefs', 'setPrefs');

$default    = & $getprefs;
