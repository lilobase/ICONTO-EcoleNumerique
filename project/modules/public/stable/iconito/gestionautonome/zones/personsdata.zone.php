<?php
/**
 * @package     
 * @subpackage  
 * @author      
 */

/**
 * 
 */
class ZonePersonsData extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $id        = $this->getParam ('node_id');
	  $ppo->type = $this->getParam ('node_type');
	  $ppo->tab  = ($this->getParam('tab')) ? $this->getParam('tab') : 0;
	  
	  if (!is_null($ppo->type) && !is_null($id)) {
	    
	    $ppo->parent = Kernel::getNodeInfo ($ppo->type, $id);

      // Récupérations des enfants du noeud           
  	  $childs = Kernel::getNodeChilds ($ppo->type, $id);

  	  // Récupéreration des personnes du noeud courant
  	  switch ($ppo->type) {
  			case "BU_GRVILLE":
  			  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
  			  break;
  			case "BU_VILLE":
  			  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
  			  break;
  			case "BU_ECOLE":;
    		  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
    		  break;
    		case "BU_CLASSE":    	  
      	  $childs = Kernel::getNodeChilds ($ppo->type, $id);

  				$ppo->students = Kernel::filterNodeList ($childs, 'USER_ELE');
  				$ppo->persons = Kernel::filterNodeList ($childs, 'USER_ENS');

  				// Dédoublonnage et tri
        	$ppo->students = Kernel::sortNodeList ($ppo->students, 'comptes');
        	$ppo->persons = Kernel::uniqNodeList ($ppo->persons);
      	  break;
  		}

  		if (isset ($ppo->childs)) {

  		  // Dédoublonnage et tri
    		$ppo->childs = Kernel::sortNodeList ($ppo->childs, 'comptes');
    		$ppo->childs = Kernel::uniqNodeList ($ppo->childs);

    		// Ajoute le type d'utilisateur en toute lettres.
    		foreach ($ppo->childs AS $child_key=>$child_val) {

    		  $ppo->childs[$child_key]['type_nom'] = Kernel::Code2Name ($child_val['type']);
    		}
  		}

  		if (isset ($ppo->students)) {

  		  foreach ($ppo->students AS $child_key=>$child_val) {

    		  $ppo->students[$child_key]['type_nom'] = Kernel::Code2Name ($child_val['type']);
    		}
  		}
  		if (isset ($ppo->persons)) {

  		  foreach ($ppo->persons AS $child_key=>$child_val) {

    		  $ppo->persons[$child_key]['type_nom'] = Kernel::Code2Name ($child_val['type']);
    		}
  		}
	  }

    $toReturn = $this->_usePPO ($ppo, '_persons_data.tpl');
  }
}