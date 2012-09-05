<?php
/**
* Fichier de description du module Agenda
* @package	Iconito
* @subpackage Agenda
* @author	Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

$prepareEdit = new CopixAction ('Lecon', 'doPrepareEdit');
$create      = new CopixAction ('Lecon', 'doCreate');
$edit        = new CopixAction ('Lecon', 'processGetEdit');
$valid       = new CopixAction ('Lecon', 'doValid');
