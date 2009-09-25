<?php

class DAOGroupe {
	
	/**
	 * Liste de groupes
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param integer $forum Id du forum
	 * @return array Tableau avec les groupes
	 */
	function getListPublic ($offset, $count, $kw=null) {
		$criteres = _daoSp ();
		
		$criteres->addCondition ('is_open', '=', 1);
		
		// Découpage du pattern
		if ($kw) {
	  	$testpattern=str_replace(array(" ","%20"), "%20", $kw);
	  	$temp = split ("%20", $testpattern);
			$criteres->startGroup ();
			foreach ($temp as $word) {
	   		if ($word != "") {
					$criteres->addCondition ('titre', 'LIKE', "%$word%", 'or');
					$criteres->addCondition ('description', 'LIKE', "%$word%", 'or');
				}
	  	}
			$criteres->endGroup ();
		}		
		
		if ($offset)
			$criteres->setOffset ($offset);
		if ($count)
	    $criteres->setCount ($count);
		$criteres->orderBy (array ('date_creation', 'desc'));
		$list = _ioDao ('groupe|groupe')->findBy ($criteres);
		
		
		$arGroupes = array();
		foreach ($list as $groupe) {
			$parent = Kernel::getNodeParents ("CLUB", $groupe->id );
			$ok = true;
			
			if (Kernel::getKernelLimits('ville')) {
				if ($parent) {
					$ville = GroupeService::getGroupeVille($parent[0]['id']);
					//echo "id=".$groupe->id." / ville=$ville<br>";
					if (!in_array($ville, Kernel::getKernelLimits('ville_as_array')))
						$ok = false;
				} else
					$ok = false;
			}

			if ($ok) {
				$groupe->parent = $parent;
				$arGroupes[] = $groupe;
			}	
		}
		
		$results = $arGroupes;
		return $results;
	}

}


?>
