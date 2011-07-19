<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/


class KernelClasseur {

  /* 
	 * Crée un classeur
	 * Renvoie son ID ou NULL si erreur
	*/
	function create ($infos = array()) {
		
		$return = null;
		
		_classInclude('classeur|classeurservice');
		$dao = _dao('classeur|classeur');
		$new = _record('classeur|classeur');
		$new->titre = ($infos['title']) ? $infos['title'] : CopixI18N::get ('classeur|classeur.moduleDescription');
		$new->cle   = ClasseurService::createKey();
		$new->date_creation = date('Y-m-d H:i:s');
		$dao->insert ($new);
		if (!is_null($new->id)) {
      
      $path2data = realpath('./static/classeur');
      $folder = $path2data.'/'.$new->id.'-'.$new->cle;
      if ($mkdir = mkdir($folder, 0777)) {
        
        chmod($folder, 0777);
        $return = $new->id;
      }
      
      if (!$return) {
        
        $dao->delete ($new->id);
      }
		}
		
		return $return;
	}
}
