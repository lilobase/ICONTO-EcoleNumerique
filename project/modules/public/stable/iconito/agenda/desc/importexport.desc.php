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

$prepareImport  = new CopixAction ('ImportExport', 'processGetPrepareImport');
$import         = new CopixAction ('ImportExport', 'doImport');
$afterImport    = new CopixAction ('ImportExport', 'getAfterImport');
$prepareExport  = new CopixAction ('ImportExport', 'processGetPrepareExport');
$export         = new CopixAction ('ImportExport', 'doExport');
$afterExport    = new CopixAction ('ImportExport', 'getAfterExport');

