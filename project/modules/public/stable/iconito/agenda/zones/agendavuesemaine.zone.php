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

_classInclude('agenda|agendaservices');
_classInclude('agenda|dateservices');
_classInclude('agenda|agendaauth');

class ZoneAgendaVueSemaine extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $service       = new DateService;
        $serviceAgenda = new AgendaService();
        $serviceType   = new AgendaType();
        $serviceAuth   = new AgendaAuth;

        //on determine la date du jour en timestamp
        $dimanche = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 0);
        $lundi    = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 1);
        $mardi    = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 2);
        $mercredi = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 3);
        $jeudi    = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 4);
        $vendredi = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 5);
        $samedi   = $service->numweekToDate($this->getParam('elementsSemaineAffichee')->numSemaine, $this->getParam('elementsSemaineAffichee')->annee, 6);


        $tpl = new CopixTpl ();

        //date du jour
        $tpl->assign('dimanche'        , date('d', $dimanche));
        $tpl->assign('lundi'           , date('d', $lundi));
        $tpl->assign('mardi'           , date('d', $mardi));
        $tpl->assign('mercredi'        , date('d', $mercredi));
        $tpl->assign('jeudi'           , date('d', $jeudi));
        $tpl->assign('vendredi'        , date('d', $vendredi));
        $tpl->assign('samedi'          , date('d', $samedi));

        $tpl->assign('date_deb'        , date('Ymd', $lundi));
        $tpl->assign('date_fin'        , date('Ymd', $dimanche));
        $tpl->assign('moisDebutSemaine', $service->moisNumericToMoisLitteral(date('m', $lundi)));
        $tpl->assign('moisFinSemaine'  , $service->moisNumericToMoisLitteral(date('m', $dimanche)));

        $tpl->assign('semaine'         , $this->getParam('elementsSemaineAffichee')->numSemaine);
        $tpl->assign('annee'           , $this->getParam('elementsSemaineAffichee')->annee);

        //on vérifie si un agenda de classe est affiché
        //$lecon = false;
        $readLecon  = false;
        $writeLecon = false;
        $idAgendaScolaire = null;
        $agendasAffiches = $this->getParam('agendasAffiches',null);
        foreach($this->getParam('elementsSemaineAffichee')->agendas as $id_agenda){
            if($serviceAgenda->getTypeAgendaByIdAgenda($id_agenda) == $serviceType->getClassRoom()){
                //on vérifie si l'utilisateur peut écrire des leçons
                if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteLecon()){
                    $writeLecon = true;
                    //$lecon = true;
                    $idAgendaScolaire = $id_agenda;
                    break;
                }
                //on vérifie si l'utilisateur peut lire les leçons
                if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getRead()){
                    $readLecon = true;
                    //$lecon = true;
                    //$idAgendaScolaire = $id_agenda;
                    //break;
                }
            }
        }

        //on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affichés
        $writeAgenda = false;
        $agendasAffiches = $this->getParam('agendasAffiches',null);
        foreach($this->getParam('elementsSemaineAffichee')->agendas as $id_agenda){
            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
                $writeAgenda = true;
                break;
            }
        }

        //on construit un tableau de droits pour chaque agenda affiché
        $arDroits = array();
        foreach($this->getParam('elementsSemaineAffichee')->agendas as $id_agenda){

            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getRead()){
                $arDroits[$id_agenda]->canRead = true;
            } else{
                $arDroits[$id_agenda]->canRead = false;
            }
            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
                $arDroits[$id_agenda]->canWrite = true;
            } else{
                $arDroits[$id_agenda]->canWrite = false;
            }
            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getModerate()){
                $arDroits[$id_agenda]->canModerate = true;
            } else{
                $arDroits[$id_agenda]->canModerate = false;
            }
        }


        //on construit le tableau de couleurs associées au type d'agenda
        $arColorByIdAgenda = array();
        foreach($this->getParam('elementsSemaineAffichee')->agendas as $id_agenda){
            $arColor = $serviceType->getColors($serviceAgenda->getTypeAgendaByIdAgenda($id_agenda));
            $i = 0;
            foreach($arColorByIdAgenda as $idAgenda=>$couleurAgenda){
                if($arColorByIdAgenda[$idAgenda] == $arColor[$i]){
                    $i = $i + 1;
                }
            }
            if($i < count($arColor)){
                $arColorByIdAgenda[$id_agenda] = $arColor[$i];
            } else{
                $arColorByIdAgenda[$id_agenda] = $arColor[0];
            }
        }

        $tpl->assign('arColorByIdAgenda', $arColorByIdAgenda);

        //on détermine l'heure de début et de fin pour l'affichage du calendrier
        $tpl->assign('heure_deb'       , $this->getParam('heureDeb'));
        $tpl->assign('heure_fin'       , $this->getParam('heureFin'));

        $tpl->assign('arEventByDay'   , $this->getParam('arEventByDay'));

        $tpl->assign('readLecon'     , $readLecon);
        $tpl->assign('writeLecon'    , $writeLecon);
        $tpl->assign('agendaScolaire', $idAgendaScolaire);
        $tpl->assign('arLecons'      , $this->getParam('arLecons'));
        $tpl->assign('arTravauxEnClasse', $this->getParam('arTravauxEnClasse'));
        $tpl->assign('arTravauxAFaire', $this->getParam('arTravauxAFaire'));
        $tpl->assign('agenda2cahier'  , $this->getParam('agenda2cahier'));

        $tpl->assign('writeAgenda' , $writeAgenda);
        $tpl->assign('arDroits'    , $arDroits);
        $tpl->assign('todayJour' , date('d'));
        $tpl->assign('todaySemaine' , date('W'));
        $tpl->assign('todayAnnee' , date('Y'));

        //paramètres pour passer d'une semaine à l'autre
        $tpl->assign('semaine_precedente', $service->dateToWeeknum(mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)-7, date('Y', $lundi))));
        $tpl->assign('semaine_suivante'  , $service->dateToWeeknum(mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)+7, date('Y', $lundi))));
        $tpl->assign('annee_precedente'  , date('Y', mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)-4, date('Y', $lundi))));
        $tpl->assign('annee_suivante'    , date('Y', mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)+10, date('Y', $lundi))));

        $listAgendas = $this->getParam('listAgendas',null);
        $tpl->assign('listAgendas',$listAgendas);

        $toReturn = $tpl->fetch ('vuesemaine.agenda.ptpl');
        return true;
    }
}
