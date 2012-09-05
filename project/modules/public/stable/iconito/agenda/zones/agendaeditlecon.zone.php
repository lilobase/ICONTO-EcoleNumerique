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

_classInclude('agenda|dateservices');

class ZoneAgendaEditLecon extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl  = new CopixTpl ();
        $serviceDate   = new DateService();

        if ($this->getParam('e') == 1){
            $tpl->assign('showError', $this->getParam('e'));
        }

        $tpl->assign('listAgendas', $this->getParam('listAgendas'));
        $tpl->assign('arError'    , $this->getParam('errors'));
        $tpl->assign('toEdit'     , $this->getParam('toEdit'));


        $toReturn = $tpl->fetch ('editlecon.agenda.tpl');
        return true;
    }
}
