<?php
/**
* @package    Iconito
* @subpackage kernel
*/

class DAOKernel_bu_personnel_entite
{
  const
      ROLE_TEACHER              = 1,
      ROLE_PRINCIPAL            = 2,
    ROLE_ADMINISTRATION_STAFF = 3,
    ROLE_CITY_AGENT           = 4,
    ROLE_CITY_GROUP_AGENT     = 5;

  /**
     * Retourne les enregistrements liés à une ressource
     *
     * @param int     $id_ref     Identifiant de la référence
     * @param string  $type_ref   Type de la référence
     *
     * @return DAORecord or false
     */
  public function getByIdReferenceAndTypeReference ($id_ref, $type_ref)
  {
    $sql = $this->_selectQuery.' AND kernel_bu_personnel_entite.reference='.$id_ref.' AND kernel_bu_personnel_entite.type_ref="'.$type_ref.'"';

    return _doQuery ($sql);
  }

  /**
     * Retourne une entité selon un identifiant de personne et un type
     *
     * @param int     $id_per   Identifiant de la personne
     * @param int     $id_ref   Identifiant de la référence
     * @param string  $type     Type de la référence
     *
     * @return DAORecord or false
     */
  public function getByIdReferenceAndType ($id_per, $id_ref, $type_ref)
  {
    $sql = $this->_selectQuery.' AND kernel_bu_personnel_entite.id_per='.$id_per.' AND kernel_bu_personnel_entite.reference='.$id_ref.' AND kernel_bu_personnel_entite.type_ref="'.$type_ref.'"';

    $results = _doQuery ($sql);

    return isset ($results[0]) ? $results[0] : false;
  }

  /**
   * Supprime une entité
   *
   * @param int    $id_per
      * @param int    $id_ref
      * @param string $type_ref
   */
  public function delete ($id_per, $id_ref, $type_ref)
  {
    $sql = 'DELETE FROM kernel_bu_personnel_entite WHERE id_per='.$id_per.' AND reference='.$id_ref.' AND type_ref="'.$type_ref.'"';

    return _doQuery($sql);
  }

  /**
   * Retourne les références et rôles des personnes
   *
   * @param array $ids
   *
   * @return array
   */
  public function findReferenceAndRoleByIds (array $ids)
  {
      if(0==count($ids)) return array();

    $sql = 'SELECT type_ref, reference, role '
      . 'FROM kernel_bu_personnel_entite '
      . 'WHERE kernel_bu_personnel_entite.id_per IN ('.implode(',', $ids).')';

    return _doQuery ($sql);
  }

  /**
   * Retourne si une entité à un rôle d'enseignant dans les classes d'une école donnée
   *
   * @param int   $id
   * @param int   $schoolId
   * @param bool  $forCurrentGrade
   *
   * @return bool
   */
  public function hasTeacherRoleInSchool ($id, $schoolId, $forCurrentGrade = false)
  {
    $schoolClassDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');

    if ($forCurrentGrade) {

      if (is_null($grade = _sessionGet('grade'))) {

        $grade = Kernel::getAnneeScolaireCourante ()->id_as;
      }
      $classrooms = $schoolClassDAO->getBySchool ($schoolId, $grade);
    } else {

      $classrooms = $schoolClassDAO->getBySchool ($schoolId);
    }

    foreach ($classrooms as $classroom) {

      if (self::getByIdReferenceAndType($id, $classroom->id, 'CLASSE')) {

        return true;
      }
    }

    return false;
  }

  /**
   * Retourne si une entité à un rôle d'enseignant dans les classes d'une école donnée
   *
   * @param int   $id
   * @param int   $schoolId
   * @param bool  $forCurrentGrade
   *
   * @return bool
   */
  public function getTeacherRoleInSchool ($id, $schoolId, $forCurrentGrade = false)
  {
    $toReturn = false;

    $schoolClassDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');

    if ($forCurrentGrade) {

      if (is_null($grade = _sessionGet('grade'))) {

        $grade = Kernel::getAnneeScolaireCourante ()->id_as;
      }
      $classrooms = $schoolClassDAO->getBySchool ($schoolId, $grade);
    } else {

      $classrooms = $schoolClassDAO->getBySchool ($schoolId);
    }

    foreach ($classrooms as $classroom) {

      if ($entity = self::getByIdReferenceAndType($id, $classroom->id, 'CLASSE')) {

        $toReturn[] = $entity;
      }
    }

    return $toReturn;
  }

  /**
   * Met à jour le rôle d'une entité
   *
   * @param int     $id_per
   * @param int     $id_ref
   * @param string  $type_ref
   * @param int     $role
   */
  public function updateRole ($id_per, $id_ref, $type_ref, $role)
  {
    $sql = 'UPDATE kernel_bu_personnel_entite SET kernel_bu_personnel_entite.role =:role '
      . 'WHERE kernel_bu_personnel_entite.id_per=:id_per '
      . 'AND kernel_bu_personnel_entite.reference=:id_ref '
      . 'AND kernel_bu_personnel_entite.type_ref=:type_ref';

    return _doQuery ($sql, array (
      ':role'   => $role,
      ':id_per' => $id_per,
      ':id_ref' => $id_ref,
      ':type_ref' => $type_ref,
    ));
  }
}