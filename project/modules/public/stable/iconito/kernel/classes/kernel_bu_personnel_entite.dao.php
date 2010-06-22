<?php
/**
* @package    Iconito
* @subpackage kernel
* @author     Sébastien CAS
*/

class DAOKernel_bu_personnel_entite {

  /**
	 * Retourne une entité selon un identifiant de personne et un type
	 *
	 * @param int     $id  
	 * @param string $type
	 *
	 * @return DAORecord or false
	 */
  public function getByIdReferenceAndType ($id, $reference, $type_ref) {

    $sql = $this->_selectQuery.' AND kernel_bu_personnel_entite.id_per='.$id.' AND kernel_bu_personnel_entite.reference='.$reference.' AND kernel_bu_personnel_entite.type_ref="'.$type_ref.'"';
    
    $results = _doQuery ($sql);
    
    return isset ($results[0]) ? $results[0] : false;
  }
  
  /**
   * Supprime une entité
   *
   * @param int    $id  
 	 * @param int    $reference
 	 * @param string $type_ref
   */
  public function delete ($id, $reference, $type_ref) {
    
    $sql = 'DELETE FROM kernel_bu_personnel_entite WHERE id_per='.$id.' AND reference='.$reference.' AND type_ref="'.$type_ref.'"';
            
    return _doQuery($sql); 
  }
  
  /**
   * Retourne les références et rôles des personnes
   *
   * @param array $ids
   *
   * @return array
   */
  public function findReferenceAndRoleByIds (array $ids) {
  	if(0==count($ids)) return array();
  	
    $sql = 'SELECT type_ref, reference, role '
      . 'FROM kernel_bu_personnel_entite '
      . 'WHERE kernel_bu_personnel_entite.id_per IN ('.implode(',', $ids).')';
    
    return _doQuery ($sql);
  }
}