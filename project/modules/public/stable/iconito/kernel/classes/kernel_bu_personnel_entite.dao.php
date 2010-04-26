<?php
/**
* @package    remotetransmission
* @subpackage kernel
* @author     Sébastien CAS
*/

class DAOKernel_bu_personnel_entite {

  /**
	 * Retourne un user selon un identifiant de personne et un type
	 *
	 * @param int $id  
	 * @param string $type
	 * @return DAORecordDBUser or false
	 */
  public function getByIdReferenceAndType ($id, $reference, $type_ref) {

    $sql = 'SELECT * FROM kernel_bu_personnel_entite WHERE id_per='.$id.' AND reference='.$reference.' AND type_ref="'.$type_ref.'"';
    
    $results = _doQuery ($sql);
    
    return isset ($results[0]) ? $results[0] : false;
  }
  
  public function delete ($id, $reference, $type_ref) {
    
    $sql = 'DELETE FROM kernel_bu_personnel_entite WHERE id_per='.$id.' AND reference='.$reference.' AND type_ref="'.$type_ref.'"';
            
    return _doQuery($sql); 
  }
}

?>