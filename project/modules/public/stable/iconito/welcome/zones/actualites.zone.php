<?php

/**
 * Affichage de la liste des actualites d'un blog
 * 
 * @package Iconito
 * @subpackage Welcome
 */
class ZoneActualites extends CopixZone {

	/**
	 * Affiche la liste des actualites d'un blog
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/11/10
	 * @param string $titre Titre a donner a la zone
	 * @param string $blog URL_blog du blog
	 * @param integer $nb Nombre d'articles a afficher
	 * @param integer $id Id d'un article precis a afficher
	 * @param integer $colonnes Nb de colonnes. Par defaut : 1
	 * @param integer $chapo Si on veut afficher les chapos. Par defaut : 0
	 * @param string $hreflib Si renseigne, affiche ce texte comme libelle d'un lien menant a l'accueil du blog
	 * @param boolean $hr Affiche un HR entre chaque article. Par defaut : 0
	 */
	function _createContent (&$toReturn) {
		
		$titre = $this->getParam('titre');
		$blog = $this->getParam('blog');
		$colonnes = $this->getParam('colonnes',1);
		$nb = $this->getParam('nb');
		$chapo = $this->getParam('chapo', false);
		$hreflib = $this->getParam('hreflib');
		$hr = $this->getParam('hr', false);
		$id = $this->getParam('id');
    
		$tpl = & new CopixTpl ();
		$tpl->assign ('titre', $titre);
		$tpl->assign ('blog', $blog);
		$tpl->assign ('nb', $nb);
		$tpl->assign ('colonnes', $colonnes);
		$tpl->assign ('chapo', $chapo);
		$tpl->assign ('hreflib', $hreflib);
		$tpl->assign ('hr', $hr);
		$tpl->assign ('id', $id);
    
		$toReturn = $tpl->fetch('zone_actualites.tpl');
		
		return true;
		
	}
}
?>
