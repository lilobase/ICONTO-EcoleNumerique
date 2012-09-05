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

$vueSemaine = new CopixAction ('Agenda', 'getVueSemaine');
$zoneToday  = new CopixAction ('Agenda', 'getZoneToday');

$default    = & $vueSemaine;
