<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZonePersonsData extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        // Récupération des paramètres
        $id = $this->getParam('node_id');
        $ppo->type = $this->getParam('node_type');
        $ppo->tab = $this->getParam('tab') ? $this->getParam('tab') : 0;

        // Récupération de l'utilisateur connecté
        $ppo->user = _currentUser();

        if (!is_null($ppo->type) && !is_null($id)) {

            $ppo->parent = Kernel::getNodeInfo($ppo->type, $id);

            $personnelDAO = _ioDAO('kernel|kernel_bu_personnel');
            switch ($ppo->type) {

                case 'BU_GRVILLE':
                    $ppo->persons = $personnelDAO->findCitiesAgentsByCitiesGroupId($id);
                    break;
                case 'BU_VILLE':
                    $ppo->persons = $personnelDAO->findCityAgentsByCityId($id);
                    break;
                case 'BU_ECOLE':
                    $personEntityDAO = _ioDAO('kernel|kernel_bu_personnel_entite');
                    $classroomDAO = _ioDAO('kernel|kernel_bu_ecole_classe');
                    $classnames = array();

                    $ppo->persons = $personnelDAO->findAdministrationStaffAndPrincipalBySchoolId($id);

                    foreach ($ppo->persons as $person) {

                        // Pour les enseignants, on récupère leurs affectations pour déterminer s'il est possible de les désaffecter de l'école
                        if ($person->role == DAOKernel_bu_personnel_entite::ROLE_TEACHER
                            && $personEntityDAO->hasTeacherRoleInSchool($person->numero, $id, true)) {

                            $person->hasTeacherRoleInSchool = true;

                            // Récupération du nom des classes ou il est affecté
                            $classnames = array();
                            $personEntities = $personEntityDAO->getTeacherRoleInSchool($person->numero, $id, true);
                            foreach ($personEntities as $personEntity) {

                                $class = $classroomDAO->get($personEntity->pers_entite_reference);
                                $classnames[] = $class->nom;
                            }

                            $person->classrooms = implode(', ', $classnames);
                        } else {

                            $person->hasTeacherRoleInSchool = false;
                        }
                    }

                    break;
                case 'BU_CLASSE':
                    // Récupération des enseignants
                    $ppo->persons = $personnelDAO->findTeachersByClassroomId($id);

                    // Récupération des élèves
                    $studentDAO = _ioDAO('kernel|kernel_bu_ele');
                    $ppo->students = $studentDAO->getStudentsByClass($id);

                    // Récupération des parents
                    $responsableDAO = _ioDAO('kernel|kernel_bu_res');
                    $ppo->responsables = $responsableDAO->getParentsInClasse($id);

                    break;
            }
        }

        // Récupération du catalogue de vocabulaire à utiliser
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->type, $id);

        // Récupération de l'année scolaire suivante
        $ppo->nextGrade = _ioDAO('kernel|kernel_bu_annee_scolaire')->getNextGrade(_sessionGet('grade'));

        $toReturn = $this->_usePPO($ppo, '_persons_data.tpl');
    }

}