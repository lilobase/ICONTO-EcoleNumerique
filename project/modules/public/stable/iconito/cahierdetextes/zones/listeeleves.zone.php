<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneListeEleves extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres
    $nid = $this->getParam('nid');
    $ppo->elevesSelectionnes = $this->getParam('elevesSelectionnes');
    if (is_null($ppo->elevesSelectionnes) || !is_array($ppo->elevesSelectionnes)) {
      
      $ppo->elevesSelectionnes = array();
    }
    
    // Récupération des élèves de la classe
    $eleveDAO = _ioDAO ('kernel|kernel_bu_ele');
    $ppo->eleves = $eleveDAO->getStudentsByClass ($nid);
    
    // Récupération des niveaux de la classe
    $classeNiveauDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
      
    $classeNiveaux = $classeNiveauDAO->getByClass ($nid);

    $ppo->nomsNiveau = array ();
    $ppo->idsNiveau  = array ();      
    foreach ($classeNiveaux as $classeNiveau) {

      $niveau = $classLevelDAO->get ($classeNiveau->niveau);
      $ppo->nomsNiveau[]  = $niveau->niveau_court;
      $ppo->idsNiveau[]   = $niveau->id_n;
    }
    
    $toReturn = $this->_usePPO ($ppo, '_liste_eleves.tpl');
  }
}