<?php

require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'kernelblog.class.php');
require_once (COPIX_MODULE_PATH.'public/'.COPIX_CLASSES_DIR.'publicutils.class.php');

/**
 * Zone qui affiche la liste des blogs
 * 
 * @package Iconito
 * @subpackage	Public
 */
class ZoneGetListBlogs extends CopixZone {

	/**
	 * Affiche la liste des blogs ayant au moins un article
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/23
	 * @param string $kw Mot-clé pour la recherche (option)
	 */
	function _createContent (&$toReturn) {
		
		$kw = $this->getParam('kw',null);
		
		$tpl = & new CopixTpl ();

	 	$dao = _dao("blog|blog");

		$dbw = & CopixDbFactory::getDbWidget ();
		
		if ($kw)
			$critere = " SELECT * FROM module_blog WHERE is_public=1 AND name_blog LIKE '%".addslashes($kw)."%' ORDER BY name_blog";
		else
			$critere = " SELECT * FROM module_blog WHERE is_public=1 AND 1 ORDER BY name_blog";
		
		$sql = $dbw->fetchAll($critere);
		$list = array();

		//print_r($sql);
		foreach ($sql as $blog) {
			$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
			if ($parent) {
				$blog->parent = $parent['nom'];
				switch ($parent['type']) {
					case 'CLUB' :	$blog->type = CopixI18N::get ('public.blog.typeClub'); break;
					case 'BU_CLASSE' : $blog->type = CopixI18N::get ('public.blog.typeClasse'); break;
					case 'BU_ECOLE' : $blog->type = CopixI18N::get ('public.blog.typeEcole'); break;
					case 'BU_VILLE' : $blog->type = CopixI18N::get ('public.blog.typeVille'); break;
					default : $blog->type = $parent['type']; break;
				}
			}
			$blog->stats = KernelBlog::getStats ($blog->id_blog);
			//print_r($blog);
			
			if ($blog->stats['nbArticles']['value']>0)
				$list[] = $blog;
		}
		
		usort ($list, "order_tab_blogs");
		
		$tpl->assign('list', $list);
		
		$toReturn = $tpl->fetch('getlistblogszone.tpl');
		return true;
		
	}
}
?>