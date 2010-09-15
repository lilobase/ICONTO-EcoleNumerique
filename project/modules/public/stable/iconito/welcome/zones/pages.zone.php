<?php

/**
 * Affichage de la liste des actualites d'un blog
 * 
 * @package Iconito
 * @subpackage Welcome
 */
class ZonePages extends CopixZone {

	/**
	 * Affiche la liste des pages d'un blog
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/01/23
	 * @param string $titre Titre a donner a la zone
	 * @param string $blog URL_blog du blog
	 * @param integer $nb Nombre de pages a afficher
	 * @param integer $id Id d'une page precise a afficher
	 * @param integer $colonnes Nb de colonnes. Par defaut : 1
	 * @param integer $content Si on veut afficher le contenu des pages. Par defaut : 0
	 * @param boolean $hr Affiche un HR entre chaque page. Par defaut : 0		
	 */
	function _createContent (&$toReturn) {
		
		$titre = $this->getParam('titre');
		$blog = $this->getParam('blog');
		$colonnes = $this->getParam('colonnes',1);
		$nb = $this->getParam('nb');
		$content = $this->getParam('content', false);
		$hr = $this->getParam('hr', false);
		$id = $this->getParam('id');
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('titre', $titre);
		$tpl->assign ('blog', $blog);
		$tpl->assign ('nb', $nb);
		$tpl->assign ('colonnes', $colonnes);
		$tpl->assign ('content', $content);
		$tpl->assign ('hr', $hr);
		$tpl->assign ('id', $id);
		
		$toReturn = $tpl->fetch('zone_pages.tpl');
		
		return true;
		
	}
}
?>
