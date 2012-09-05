<?php

class DAOKernel_bu_annee_scolaire
{
    /**
     * Retourne l'année scolaire courante
     *
     * @return DAORecordKernel_bu_annee_scolaire
     */
    public function getCurrent ()
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('current', '=', 1);

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne l'année scolaire suivante
     *
     * @return DAORecordKernel_bu_annee_scolaire
     */
    public function getNextGrade ($grade = null)
    {
      $sql = $this->_selectQuery
        . ', kernel_bu_annee_scolaire AS2'
        . ' WHERE kernel_bu_annee_scolaire.id_as > AS2.id_as';

      if (is_null ($grade)) {

        $sql .= ' AND AS2.current = 1';
      } else {

        $sql .= ' AND AS2.id_as = '.$grade;
      }

      $sql .= ' ORDER BY kernel_bu_annee_scolaire.id_as'
        . ' LIMIT 1';

      $results = _doQuery ($sql);

    return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne les écoles d'une ville accessibles pour un utilisateur
     *
     * @param int   $cityId  Identifiant de la ville
     * @param array $groups  Groupes
   *
     * @return CopixDAORecordIterator
     */
    public function findByCityIdAndUserGroups ($cityId, $groups)
    {
        $groupsIds = array(
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );

    foreach ($groups as $key => $group) {

      $id = substr($key, strrpos($key, '_')+1);

      if (preg_match('/^administration_staff/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^principal/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher_school/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher/', $key)) {

        $groupsIds['classroomsIds'][] = $id;
      }
    }

    if (empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {

      return array();
    }

        $sql = $this->_selectQuery
          . ', kernel_bu_ecole_classe '
          . 'WHERE kernel_bu_ecole.numero=kernel_bu_ecole_classe.ecole '
          . 'AND kernel_bu_ecole.id_ville='.$cityId;

        $conditions = array();
        if (!empty ($groupsIds['schoolsIds'])) {

          $conditions[] = 'kernel_bu_ecole.numero IN ('.implode(',', $groupsIds['schoolsIds']).')';
        }
        if (!empty ($groupsIds['classroomsIds'])) {

          $conditions[] = 'kernel_bu_ecole_classe.id IN ('.implode(',', $groupsIds['classroomsIds']).')';
        }

        $sql .= ' AND ('.implode('OR', $conditions).')';
        $sql .= ' GROUP BY kernel_bu_ecole.numero';
        $sql .= ' ORDER BY kernel_bu_ecole_classe.nom';

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
    }
}