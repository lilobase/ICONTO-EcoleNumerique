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
	  $id = $this->getParam ('nodeId');
	  $type = $this->getParam ('nodeType');
	  
	  $ppo->parent = Kernel::getNodeInfo ($type, $id);

    // Récupérations des enfants du noeud
	  $childs = Kernel::getNodeChilds ($type, $id);
	  
	  // Récupéreration des personnes du noeud courant
	  switch ($type) {
			case "BU_GRVILLE":
			  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
			  break;
			case "BU_VILLE":
			  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
			  break;
			case "BU_ECOLE":
  		  $ppo->childs = Kernel::filterNodeList ($childs, 'USER_*');
  		  break;
  		case "BU_CLASSE":    	  
    	  $ppo->childs = Kernel::getNodeChilds ($type, $id);
				
				$eleves = Kernel::filterNodeList ($ppo->childs, 'USER_ELE');
				foreach ($eleves as $eleve) {
				  
					$parents = Kernel::getNodeChilds ($eleve['type'], $eleve['id']);
					$parents = Kernel::filterNodeList ($parents, 'USER_RES');
					foreach ($parents as $parent) {
					  
						$ppo->childs[] = $parent;
					}
				}

				$ppo->childs = Kernel::filterNodeList ($ppo->childs, 'USER_*');
    	  
    	  break;
		}
		
		// Dédoublonnage et tri
		$ppo->childs = Kernel::sortNodeList ($ppo->childs, 'comptes');
		$ppo->childs = Kernel::uniqNodeList ($ppo->childs);
		
		// Ajoute le type d'utilisateur en toute lettres.
		foreach ($ppo->childs AS $child_key=>$child_val) {

		  $ppo->childs[$child_key]['type_nom'] = Kernel::Code2Name ($child_val['type']);
		}
	  
    $toReturn = $this->_usePPO ($ppo, '_persons_data.tpl');
  }
}