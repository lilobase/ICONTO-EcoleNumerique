<?php
/**
* Zone du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneAgendaAfterImport extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign('nbInsertions', $this->getParam('nbInsertions'));

        $toReturn = $tpl->fetch ('afterimport.agenda.tpl');
        return true;
    }
}
