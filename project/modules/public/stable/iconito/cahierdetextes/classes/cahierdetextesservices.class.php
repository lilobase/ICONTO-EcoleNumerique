<?php
/**
 *
 * @package Iconito
 * @subpackage Cahierdetextes
 * @author Jérémy FOURNAISE
 */
class CahierDeTextesServices
{
  /**
   * Makes URI for list of articles
   *
   * @param string  $date (YYYYmmdd)
   *
   * @return string
   */
  public function makeVueJourUrl($cahierId, $date, $nodeType, $nodeId)
  {
    if ($nodeType == "USER_ELE") {

      $url = CopixUrl::get ('cahierdetextes||voirTravaux',
        array('cahierId' => $cahierId, 'jour' => substr($date, 6, 2), 'mois' => substr($date, 4, 2), 'annee' => substr($date, 0, 4), 'eleve' => $nodeId));
    } else {

      $url = CopixUrl::get ('cahierdetextes||voirTravaux',
        array('cahierId' => $cahierId, 'jour' => substr($date, 6, 2), 'mois' => substr($date, 4, 2), 'annee' => substr($date, 0, 4)));
    }

    return $url;
  }
}
