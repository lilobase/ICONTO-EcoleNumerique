<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneListeEleves extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

    // Récupération des paramètres
    $ppo->cahierId   = $this->getParam('cahierId');
    $ppo->elevesSelectionnes = $this->getParam('elevesSelectionnes');
    if (is_null($ppo->elevesSelectionnes) || !is_array($ppo->elevesSelectionnes)) {

      $ppo->elevesSelectionnes = array();
    }

    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);

    // Récupération des élèves de la classe
    $eleveDAO = _ioDAO ('kernel|kernel_bu_ele');
    $ppo->eleves = $eleveDAO->getStudentsByClass ($cahierInfos[0]->node_id);

    // Récupération des niveaux de la classe
    $classeNiveauDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO   = _ioDAO ('kernel|kernel_bu_classe_niveau');

    $classeNiveaux = $classeNiveauDAO->getByClass ($cahierInfos[0]->node_id);

    $ppo->nomsNiveau = array();
    $ppo->idsNiveau  = array();
    foreach ($classeNiveaux as $classeNiveau) {


      $niveau = $classLevelDAO->get ($classeNiveau->niveau);
      $ppo->nomsNiveau[]  = $niveau->niveau_court;
      $ppo->idsNiveau[]   = $niveau->id_n;
    }

    $toReturn = $this->_usePPO ($ppo, '_liste_eleves.tpl');
  }
}