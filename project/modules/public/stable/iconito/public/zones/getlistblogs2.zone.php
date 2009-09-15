<?php

require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'kernelblog.class.php');
require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'blogutils.class.php');
require_once (COPIX_MODULE_PATH.'public/'.COPIX_CLASSES_DIR.'publicutils.class.php');
require_once (COPIX_MODULE_PATH.'annuaire/'.COPIX_CLASSES_DIR.'annuaireservice.class.php');

/**
 * Zone qui affiche la liste des blogs
 * 
 * @package Iconito
 * @subpackage	Public
 */
class ZoneGetListBlogs2 extends CopixZone {

	/**
	 * Affiche la liste des blogs ayant au moins un article
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/23
	 * @param string $kw Mot-clé pour la recherche (option)
	 */
	function _createContent (&$toReturn) {
		
		$kw = $this->getParam('kw',null);
		$grville = $this->getParam('grville',null);
		
		$tpl = & new CopixTpl ();

		$villes = AnnuaireService::getVillesInGrville ($grville);
		//print_r($villes);
		$tpl->assign('villes', $villes);
		
		$ecoles = array();
		foreach ($villes as $ville) {
			//$ecoles
			$ec = AnnuaireService::getEcolesInVille ($ville['id'], array('directeur'=>false));
			foreach ($ec as $k=>$e) {
				$blog = getNodeBlog ('BU_ECOLE', $e['id']);
				//print_r($blog);
				if ($blog && $blog->is_public==1) {
					$ec[$k]['blog']['url_blog'] = $blog->url_blog;
				}
			}
			$ecoles[$ville['id']] = $ec;
		}
		//print_r($ecoles);
		$tpl->assign('ecoles', $ecoles);

	 	$dao = CopixDAOFactory::create("blog|blog");

		$dbw = & CopixDbFactory::getDbWidget ();
		
		
		if ($kw)
			$critere = " SELECT * FROM module_blog WHERE is_public=1 AND name_blog LIKE '%".addslashes($kw)."%' ORDER BY name_blog";
		else
			$critere = " SELECT * FROM module_blog WHERE is_public=1 AND 1 ORDER BY name_blog";
		
		$sql = $dbw->fetchAll($critere);
		$list = array();
		
		$arTypes = array();
		if (CopixConfig::exists ('public|blogs.types') && CopixConfig::get ('public|blogs.types'))
			$arTypes = explode(",", CopixConfig::get ('public|blogs.types'));
		$arTypes[] = 'CLUB';
		
		//print_r($sql);
		foreach ($sql as $blog) {
			$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
			if ($parent) {
				
				if ($arTypes && !in_array($parent['type'],$arTypes))
					continue;
				
				$blog->parent = $parent['nom'];
				switch ($parent['type']) {
					case 'CLUB' :	$blog->type = CopixI18N::get ('public.blog.typeClub'); break;
					case 'BU_CLASSE' :
						$blog->type = CopixI18N::get ('public.blog.typeClasse');
						$blog->parent .= ' - '.$parent['ALL']->eco_nom;
						if ($parent['ALL']->eco_type)
							$blog->parent .= ' - '.$parent['ALL']->eco_type.'';
						break;

					//case 'BU_ECOLE' : $blog->type = CopixI18N::get ('public.blog.typeEcole'); break;
					//case 'BU_VILLE' : $blog->type = CopixI18N::get ('public.blog.typeVille'); break;
					//case 'BU_GRVILLE' : $blog->type = CopixI18N::get ('public.blog.typeGrville'); break;
					//default : $blog->type = $parent['type']; break;
				}
				if (!isset($blog->type)) continue;
				$blog->stats = KernelBlog::getStats ($blog->id_blog);
				//print_r($blog);
			
				if ($blog->stats['nbArticles']['value']>0)
					$list[] = $blog;
			}
		}
		
		usort ($list, "order_tab_blogs");
		
		$tpl->assign('list', $list);
		
		$toReturn = $tpl->fetch('getlistblogszone2.tpl');
		return true;
		
	}
}
?>