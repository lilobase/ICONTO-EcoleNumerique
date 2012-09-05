<?php
/**
* Actiongroup du module Agenda
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
_classInclude('agenda|semaineparams');
require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');

class ActionGroupEvent extends CopixActionGroup
{
    /**
    * Fonction qui est appelée lors de la modification d'un évènement
    * Récupère l'objet 'event' en  base de données grâce à l'id_event
    * Rédirige vers l'action "edit" de l'actiongroup
    */
    public function doPrepareEdit ()
    {
        $serviceAuth   = new AgendaAuth;

        //récupération de l'objet event en base de donnée
        $daoSearchParams = & CopixDAOFactory::createSearchParams ();
        $daoSearchParams->addCondition ('id_event', '=', _request('id_event'));

        $daoEvent = & CopixDAOFactory::getInstanceOf ('Event');
        $arEvent  = $daoEvent->findBy ($daoSearchParams);

        if (count($arEvent)>0){
            $event = $arEvent[0];
        } else{
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.error.eventnotinbase'),
            'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //on vérifie si l'utilisateur a les droits de modification sur l'agenda concerné
        if($serviceAuth->getCapability($event->id_agenda) < $serviceAuth->getModerate()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //$event->datedeb_event    = DateService::dateBddToDateFr($event->datedeb_event);
        //$event->datedeb_event    = CopixDateTime::timestampToDate($event->datedeb_event, '');
        //$event->datefin_event    = DateService::dateBddToDateFr($event->datefin_event);
        //$event->datefin_event    = CopixDateTime::timestampToDate($event->datefin_event, '');
        //$event->endrepeatdate_event    = ($event->endrepeatdate_event) ? DateService::dateBddToDateFr($event->endrepeatdate_event) : '';
        //$event->endrepeatdate_event    = ($event->endrepeatdate_event) ? CopixDateTime::timestampToDate($event->endrepeatdate_event, '') : '';
        $this->_setSessionEvent($event);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|event|edit'));
    }


    /**
    * Fonction qui est appelée lorsque l'on veut insérer un nouvel évènement
    * Créé un objet vide  et initialise la propriété id_agenda
    * Stock l'objet en session
    * @author Audrey Vassal <avassal@sqli.com>
    * @return redirige vers l'action "edit" de l'actiongroup
    */
    public function doCreate ()
    {
        $serviceAuth   = new AgendaAuth;


        //on ne peut ajouter un évènement que s'il existe un agenda
        $obj = new AgendaService();
        $listAgendas = $obj->getAvailableAgenda();

        if (!count($listAgendas)>0){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.error.missingParameters'),
            'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //récupération de la liste des agendas affichés
        $listAgendasAffiches = $obj->getAgendaAffiches();

        //on vérifie les droits des utilisateurs sur la liste des agendas affichés
        foreach((array)$listAgendasAffiches as $id_agenda){
            //on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
                $ableToWrite = true;
            }
        }
        if(!isset($ableToWrite) || !$ableToWrite){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }
        //initialisation de l'objet event
        $event = _record('event');
        $event->id_agenda        = $listAgendas[0]->id_agenda;
        $event->datedeb_event    = $this->getRequest('jourCourant');
        $event->datefin_event    = $this->getRequest('jourCourant');
        $event->heuredeb_event   = $this->getRequest('heureDeb', '');
        $event->heurefin_event   = $this->getRequest('heureFin', '');
        //var_dump($event);
        //die();
        $this->_setSessionEvent($event);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|event|edit'));
    }


    /**
    * Récupère l'objet en session
    * Créé un objet vide  et initialise la propriété id_agenda
    * @author Audrey Vassal <avassal@sqli.com>
    */
    public function processGetEdit ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));

        CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

        $serviceAuth   = new AgendaAuth;
        $serviceAgenda = new AgendaService();

        if (!$toEdit = $this->_getSessionEvent ()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.unableToGetEdited'),
            'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        //var_dump($toEdit);

        //récupération de la liste des agendas affichés
        $listAgendasAffiches = $serviceAgenda->getAgendaAffiches();
        //on vérifie les droits des utilisateurs sur la liste des agendas affichés
        foreach((array)$listAgendasAffiches as $id_agenda){
            //on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
            if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
                $ableToWrite = true;
            }
        }
        if(!$ableToWrite){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }


        $obj = new AgendaService();
        $listAgendas = $obj->getAvailableAgenda();

        //récupération de la liste des agendas affichés

        $serviceAgenda = new AgendaService;

        //on récupère en session la liste des agendas en cours de visualisation
        $arAgendasAffiches      = $serviceAgenda->getAgendaAffiches();
        $arTitleAgendasAffiches = $serviceAgenda->getArTitleAgendaByArIdAgenda($arAgendasAffiches);

        //template pour agenda
        $tplAgenda = new CopixTpl();
        $tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendaeditevent', array('arTitleAgendasAffiches'=>$arTitleAgendasAffiches, 'e'=>$this->getRequest('e'), 'errors'=>$this->getRequest('errors'), 'toEdit'=>$toEdit)));

        //template principal
        $tpl = new CopixTpl();
     $tpl->assign ('BODY_ON_LOAD', "setDatePicker('#datedeb_event,#datefin_event,#dateendrepeat_event')");
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.title.editEvent'));

        $menu = $serviceAgenda->getAgendaMenu('create');
        $tpl->assign ('MENU', $menu);

        $tpl->assign ('MAIN'      , $tplAgenda->fetch('agenda|main.agenda.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Fonction qui est appelée lorsque l'on valide la saisie d'un évènement
    * Met à jour l'objet avec les données du formulaire
    * Vérifie les informations saisies dans le formulaire
    * @author Audrey Vassal <avassal@sqli.com>
    * @return redirige vers l'action "getVueSemaine" de l'actiongroup agenda
    */
    public function doValid ()
    {
        $serviceAuth   = new AgendaAuth;

        //initialisation des cases à cocher


        if (!$toValid = $this->_getSessionEvent()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.error.cannotFindSession'),
            'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        //demande de mettre l'objet à jour en fonction des valeurs saisies dans le formulaire
        $this->_validFromForm ($toValid);
        //var_dump($toValid);

        //on vérifie les droits
        if($serviceAuth->getCapability($toValid->id_agenda) < $serviceAuth->getWriteAgenda()){
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        $errors = $this->_check($toValid);
        //var_dump($toValid);

        if (count($errors)>0){//s'il y a des erreurs
            $this->_setSessionEvent($toValid);
            return CopixActionGroup::process('agenda|Event::getEdit', array('e'=>1, 'errors'=>$errors));
        } else{

            $daoEvent = & CopixDAOFactory::getInstanceOf ('event');
            $record   = _record('event');

            $criteres = _daoSp ();
            $criteres->addCondition('id_event', '=', $toValid->id_event);
            $resultat = $daoEvent->findBy($criteres);

            $modif = false;
            if (count($resultat) > 0){//modification
                $record = $resultat[0];
                $modif = true;
            }

            //on fait l'enregistrement en base
            if(isset($toValid->endrepeat_event) && $toValid->endrepeat_event == 'nbfois' && $toValid->nb_fois != null){//on determine la date de fin dans le cas où il s'agit d'une répétion n fois
                $obj = new AgendaService();
                $dateFin = $obj->getDateEndRepeatByNbFois($toValid->nb_fois, $toValid->repeat_event, $toValid->datefin_event);
            }

            //die ("dateFin=$dateFin");

            $record->id_agenda        = $toValid->id_agenda;
            $record->title_event      = $toValid->title_event;
            $record->desc_event       = $toValid->desc_event;
            $record->place_event      = $toValid->place_event;
            //$record->datedeb_event    = CopixDateTime::dateToTimestamp ($toValid->datedeb_event);//convertion des dates au format bdd
            $record->datedeb_event    = CopixDateTime::dateToYYYYMMDD ($toValid->datedeb_event, '/');
            //$record->datefin_event    = CopixDateTime::dateToTimestamp ($toValid->datefin_event);//convertion des dates au format bdd
            $record->datefin_event    = CopixDateTime::dateToYYYYMMDD ($toValid->datefin_event, '/');
            $record->heuredeb_event   = $toValid->heuredeb_event;
            $record->heurefin_event   = $toValid->heurefin_event;
            $record->alldaylong_event = (isset($toValid->alldaylong_event)) ? $toValid->alldaylong_event : 0;

            //si il y a répétition de l'évènement
            if(isset($toValid->repeat) && $toValid->repeat == 1){
                $record->everyday_event   = ($toValid->repeat_event == 'everyday_event' && $toValid->repeat == 1) ? 1 : 0;
                $record->everyweek_event  = ($toValid->repeat_event == 'everyweek_event' && $toValid->repeat == 1) ? 1 : 0;
                $record->everymonth_event = ($toValid->repeat_event == 'everymonth_event' && $toValid->repeat == 1) ? 1 : 0;
                $record->everyyear_event  = ($toValid->repeat_event == 'everyyear_event' && $toValid->repeat == 1) ? 1 : 0;

                //date de fin de répétition (à voir selon ce qui est coché)
                if(isset($dateFin)){
                    $record->endrepeatdate_event = CopixDateTime::dateToYYYYMMDD ($dateFin, '/');

                } elseif(isset($toValid->dateendrepeat_event) && $toValid->endrepeat_event == 'date'){
                    $record->endrepeatdate_event = CopixDateTime::dateToTimestamp ($toValid->dateendrepeat_event);
                } else{
                    $record->endrepeatdate_event = ($toValid->endrepeat_event == '99999999') ? $toValid->endrepeat_event : null;
                }
            }
            //si pas de répétition, on met tous les champs à 0
            else{
                $record->everyday_event = 0;
                $record->everyweek_event = 0;
                $record->everymonth_event = 0;
                $record->everyyear_event = 0;
                $record->endrepeatdate_event = null;
            }

            if($modif == true){//on fait une modification
                $daoEvent->update ($record);
            } else{//on fait un ajout
                $daoEvent->insert ($record);
                //if (!$record-id_event)
                //	return CopixActionGroup::process('agenda|Event::getEdit', array('e'=>1, 'errors'=>$errors));
            }

            //var_dump($record);
            //die("a");

            //on vide la session
            $this->_setSessionEvent(null);

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|agenda|vueSemaine'));
        }
    }

    /**
    * Fonction qui est appelée lors de la suppression d'un évènement
    * Récupère l'objet 'event' en  base de données grâce à l'id_event
    * Supprime l'objet en base de données
    */
    public function doDelete ()
    {
        $serviceAuth   = new AgendaAuth;

        if (!_request('id_event')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.missingParameters'),
                        'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        $daoEvent = & CopixDAOFactory::getInstanceOf ('event');
        if (!$toDelete = $daoEvent->get (_request('id_event'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.unableToFind'),
                        'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }


        //on vérifie si l'utilisateur a les droits de suppression sur l'agenda concerné
        if($serviceAuth->getCapability($toDelete->id_agenda) < $serviceAuth->getModerate()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }


        //Confirmation screen ?
        if (!_request('confirm')){
            return CopixActionGroup::process ('genericTools|Messages::getConfirm',
                array ('title'=>CopixI18N::get ('agenda.title.confirmdelevent'),
                        'message'=>CopixI18N::get ('agenda.message.confirmdelevent'),
                        'confirm'=>CopixUrl::get('agenda|event|delete', array('id_event'=>$toDelete->id_event, 'confirm'=>'1')),
                        'cancel'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //Delete event
        $daoEvent->delete($toDelete->id_event);
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|agenda|vueSemaine'));
    }

    /**
    * Fonction qui fait la vérification sur les champs de saisie du formulaire d'ajout d'un évènement
    * @access: private
    * @return array $toReturn tableau qui contient les erreurs de saisie de l'utilisateur
    */
    public function _check ($obj)
    {
        $toReturn = array();

    //var_dump($obj);

        $datedeb 		 = $obj->datedeb_event;
        $datefin 		 = $obj->datefin_event;
        $datejusquau = (isset($obj->dateendrepeat_event)) ? $obj->dateendrepeat_event : null;

        $datedebTs 		 = CopixDateTime::dateToTimestamp($datedeb);
        $datefinTs 		 = CopixDateTime::dateToTimestamp($datefin);
        $datejusquauTs = CopixDateTime::dateToTimestamp($datejusquau);


        //conversion des heures au format hhmm pour pouvoir les comparer
        $heuredeb = dateService::heureWithSeparateurToheureWithoutSeparateur($obj->heuredeb_event);
        $heurefin = dateService::heureWithSeparateurToheureWithoutSeparateur($obj->heurefin_event);

        $endrepeat_event = isset($obj->endrepeat_event) ? $obj->endrepeat_event : false;
        //$dateendrepeat_event = $this->getRequest('dateendrepeat_event', null);
        $repeat_event = isset($obj->repeat_event) ? $obj->repeat_event : false;

        //vérification si les champs sont bien remplis
        if ($obj->title_event == null || $obj->title_event == ''){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.notitle');
        }

        if ($endrepeat_event == 'nbfois' && isset($obj->nb_fois) && !is_numeric($obj->nb_fois)){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nbFoisIsNotNumeric');
        }

        if (!$datedeb) {
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nodatedeb');
        }

        if (!$datefin) {
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nodatefin');
        }

        if ($obj->alldaylong_event == null && ($obj->heuredeb_event == null || $obj->heuredeb_event =='')){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.noheuredeb');
        }

        if ($obj->alldaylong_event == null && ($obj->heurefin_event == null || $obj->heurefin_event =='')){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.noheurefin');
        }

        if ($endrepeat_event == 'nbfois' && ($obj->nb_fois == '' || $obj->nb_fois == null)){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nonbfois');
        }

        if ($endrepeat_event == 'date' && ($datejusquau == '' || $datejusquau == null) && ($obj->repeat == 1)){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nodateendrepeat');
        }

        if ($endrepeat_event == 'date' && ($obj->endrepeat_event == '' || $obj->endrepeat_event == null)){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nodatefinrepeat');
        }

        if(isset($obj->repeat) && $obj->repeat == 1 && (!isset($obj->endrepeat_event) || $obj->endrepeat_event == null)){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.noprecisionrepeat');
        }

        //var_dump($datedebTs);
        //var_dump($datefinTs);

        //vérification sur le format des dates
        if ($datedeb) {
            if (CopixDateTime::timestampToDate ($datedebTs) === false)
                $toReturn[] = CopixI18N::get('agenda|agenda.error.formdatedeb');
        }

        if ($datefin) {
            if (CopixDateTime::timestampToDate ($datefinTs) === false)
                $toReturn[] = CopixI18N::get('agenda|agenda.error.formdatefin');
        }

        //vérification sur la cohérence des dates de début et de fin
        if ($datedeb && $datefin && $datedebTs && $datefinTs && $datedebTs > $datefinTs){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.inversiondate');
        }

        if (isset($obj->repeat) && $obj->repeat == 1 && $datedebTs && $datejusquauTs && $obj->endrepeat_event != null && $datedebTs > $datejusquauTs && $obj->endrepeat_event == 'date'){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.inversiondaterepeat');
        }

        //vérification sur la cohérence des heures de début et de fin
        if ($datedebTs && $datefinTs && $datedebTs == $datefinTs && $heuredeb > $heurefin && $obj->alldaylong_event != 1 && ($obj->heurefin_event !=null || $obj->heurefin_event != '')){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.inversionheure');
        }


        if ($datejusquau) {
            if (CopixDateTime::timestampToDate ($datejusquauTs) === false)
                $toReturn[] = CopixI18N::get('agenda|agenda.error.formdatejusqu');
        }

        //vérification sur le format des heures
        if ($obj->heuredeb_event != null || $obj->heuredeb_event !='') {
            if (!ereg("([0-2])?[0-9]:[0-5][0-9]", $obj->heuredeb_event)){
                $toReturn[] = CopixI18N::get('agenda|agenda.error.formheuredeb');
            } else {
                $heure = split(":", $obj->heuredeb_event);
                if($heure[0] < 0 || $heure[0] > 23 || $heure[1] < 0 || $heure[1] > 59){
                    $toReturn[] = CopixI18N::get('agenda|agenda.error.formheuredeb');
                }
            }
        }

        if ($obj->heurefin_event != null || $obj->heurefin_event !='') {
            if (!ereg("([0-2])?[0-9]:[0-5][0-9]",$obj->heurefin_event)){
                $toReturn[] = CopixI18N::get('agenda|agenda.error.formheurefin');
            } else {
                $heure = split(":", $obj->heurefin_event);
                if($heure[0] < 0 || $heure[0] > 23 || $heure[1] < 0 || $heure[1] > 59){
                    $toReturn[] = CopixI18N::get('agenda|agenda.error.formheurefin');
                }
            }
        }

        //vérifier que la fréquence de répétition est cohérente avec la durée de l'évènement
        if($obj->datedeb_event && $obj->datefin_event && $obj->heuredeb_event && $obj->heurefin_event && $repeat_event == 'everyday_event' && DateService::getNomberDaysBeetweenTwoDates($obj->datedeb_event, $obj->datefin_event, $obj->heuredeb_event, $obj->heurefin_event) > 1){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.freqrepetitionday');
        }

        if($repeat_event == 'everyweek_event' && DateService::getNomberDaysBeetweenTwoDates($obj->datedeb_event, $obj->datefin_event, $obj->heuredeb_event, $obj->heurefin_event) > 7){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.freqrepetitionweek');
        }

        if($repeat_event == 'everymonth_event' && DateService::getNomberDaysBeetweenTwoDates($obj->datedeb_event, $obj->datefin_event, $obj->heuredeb_event, $obj->heurefin_event) > 28){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.freqrepetitionmonth');
        }

        if($repeat_event == 'everyyear_event' && DateService::getNomberDaysBeetweenTwoDates($obj->datedeb_event, $obj->datefin_event, $obj->heuredeb_event, $obj->heurefin_event) > 365){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.freqrepetitionyear');
        }

        return $toReturn;
    }

    /**
    * Mise en session des paramètres de l'évènement en édition
    * @access: private.
    */
    public function _setSessionEvent ($toSet)
    {
        $tmp = _ioDao('event');
        $toSession = ($toSet !== null) ? serialize($toSet) : null;
        _sessionSet('modules|agenda|edited_event', $toSession);
    }

    /**
    * Récupération en session des paramètres de l'évènement en édition
    * @access: private.
    */
    public function _getSessionEvent ()
    {
        $tmp = _ioDao('event');
        $inSession = _sessionGet ('modules|agenda|edited_event');
        return ($inSession) ? unserialize ($inSession) : null;
    }

    /**
    * @access: private.
    */
    public function _validFromForm (& $toUpdate)
    {
        $toCheck = array ('id_agenda', 'title_event', 'desc_event','place_event', 'datefin_event', 'datedeb_event', 'alldaylong_event', 'repeat', 'repeat_event', 'endrepeat_event', 'nb_fois', 'dateendrepeat_event');
        foreach ($toCheck as $elem){
            if (_request($elem)){
                if ($elem == 'repeat' || $elem == 'alldaylong_event')
                    $toUpdate->$elem = (_request($elem))*1;
                elseif ($elem == 'datedeb_event' || $elem == 'datefin_event' || $elem == 'dateendrepeat_event')
                    $toUpdate->$elem = Kernel::_validDateProperties(_request($elem));
                else
                    $toUpdate->$elem = _request($elem);
            } else {
                $toUpdate->$elem = null;
            }
        }

        //cas particulier de l'heure
        if (_request('heuredeb_event')){
            //cas de l'heure saisie sur 4 caractère (9:00 au lieu de 09:00)
            if (strlen(_request('heuredeb_event')) == 4) {
                $toUpdate->heuredeb_event = '0'._request('heuredeb_event');
            }else{
                $toUpdate->heuredeb_event = _request('heuredeb_event');
            }
        }
        if (_request('heurefin_event')){
            //cas de l'heure saisie sur 4 caractère (9:00 au lieu de 09:00)
            if (strlen(_request('heurefin_event')) == 4) {
                $toUpdate->heurefin_event = '0'._request('heurefin_event');
            }else{
                $toUpdate->heurefin_event = _request('heurefin_event');
            }
        }
    }
}
