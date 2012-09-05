<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneClassroom extends CopixZone
{
  /**
   * Affichage des classes
   */
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération de l'année scolaire
    if (is_null($grade = _sessionGet('grade'))) {

      $grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }

      if (is_null($schoolId = $this->getParam('school_id'))) {

        $toReturn = '';
        return;
      }

      $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');

      if (_currentUser ()->testCredential ('module:school|'.$schoolId.'|classroom|create@gestionautonome')) {

      $ppo->classrooms = $classroomDAO->getBySchool ($schoolId, $grade);
      } else {

      $groups = _currentUser ()->getGroups ();
      $ppo->classrooms = $classroomDAO->findBySchoolIdAndUserGroups ($schoolId, $groups['gestionautonome|iconitogrouphandler'], $grade);
      }

    $toReturn = $this->_usePPO ($ppo, '_classroom.tpl');
  }
}