<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneFilterClassLevel extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $ppo = new CopixPPO ();

        // Récupérations des filtres en session
        $ppo->selected        = $this->getParam ('selected', null);
        $ppo->withLabel       = $this->getParam ('with_label', true);
        $ppo->withEmpty       = $this->getParam ('with_empty', true);
        $ppo->labelEmpty      = $this->getParam ('label_empty', null);
        $ppo->name            = $this->getParam ('name', null);
        $ppo->all             = $this->getParam ('all', false);

        if (!is_null ($schoolId = $this->getParam('school_id', null))) {

            // Récupération des niveaux de la classe sélectionnée pour liste déroulante
            $classroomLevelDAO = _ioDAO ('kernel|kernel_bu_classe_niveau');

            $classroomId = $this->getParam('classroom_id', null);
            if (is_null($classroomId)) {
                if (_currentUser ()->testCredential ('module:school|'.$schoolId.'|classroom|create@gestionautonome') || $ppo->all) {
                    $niveaux = $classroomLevelDAO->findBySchoolId ($schoolId, $this->getParam ('grade', null));
                } else {
                    $groups = _currentUser ()->getGroups ();
                    $niveaux = $classroomLevelDAO->findBySchoolIdAndUserGroups ($schoolId, $groups['gestionautonome|iconitogrouphandler'], $this->getParam ('grade', null));
                }
            } else {
                $niveaux = $classroomLevelDAO->findByClassId ($classroomId);
            }

            $ppo->niveauxIds   = array();
            $ppo->niveauxNames = array();

            foreach ($niveaux as $niveau) {
                $ppo->niveauxIds[]   = $niveau->id_n;
                $ppo->niveauxNames[] = $niveau->niveau_court;
            }
        }

        $toReturn = $this->_usePPO ($ppo, '_filter_classlevel.tpl');
    }
}