<?php
/**
 * @package    Iconito
 * @subpackage Cahierdetextes
 * @author     Julien Pottier
 */
class ZoneListeClasses extends CopixZone
{
    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();
        // Récupération des paramètres
        $ppo->ecoleId           = $this->getParam('ecoleId');
        $ppo->classesSelectionnees = $this->getParam('classesSelectionnees');
        if (is_null($ppo->classesSelectionnees) || !is_array($ppo->classesSelectionnees)) {
            $ppo->classesSelectionnees = array();
        }

        $anneeDAO    = _ioDAO('kernel|kernel_bu_annee_scolaire');
        $currentGrade = $anneeDAO->getCurrent();

        // Récupération des élèves de la classe
        $classeDAO    = _ioDAO('kernel|kernel_bu_ecole_classe');
        $ppo->classes = $classeDAO->getBySchool($ppo->ecoleId, $currentGrade->id_as);

        $toReturn = $this->_usePPO($ppo, '_liste_classes.tpl');
    }
}
