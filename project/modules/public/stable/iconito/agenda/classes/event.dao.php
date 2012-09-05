<?php

/**
 * @package     iconito
 * @subpackage  agenda
 * @author      Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
 */
class DAORecordEvent
{
  public function __toString()
  {
    return $this->getTitleEvent();
  }
}

class DAOEvent
{
  /**
   * Retourne les événements d'un agenda pour un intervalle donné
   *
   * @param integer  $agendaId    Identifiant de l'agenda
   * @param string   $dateDebut   Date de début de l'intervalle (format Ymd)
   * @param string   $dateFin     Date de fin de l'intervalle (format Ymd)
   *
   * @return CopixDAORecordIterator
   */
  public function findByAgendaAndDateInterval($agendaId, $dateDebut, $dateFin)
  {
    $c = _daoSp ();
    $c->addCondition ('id_agenda', '=', $agendaId);
    $c->addCondition ('datedeb_event', '<=', $dateFin);
    $c->startGroup ();
    $c->addCondition ('datefin_event', '>=', $dateDebut);
    $c->addCondition ('endrepeatdate_event', '>=', $dateDebut, 'or');
        $c->endGroup ();

    return $this->findBy ($c);
  }
}