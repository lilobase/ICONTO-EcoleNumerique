<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listpagejs.zone.php,v 1.3 2009-03-11 13:32:52 cbeyer Exp $
* @author	Christophe Beyer
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneListPageJs extends CopixZone {

   /**
	 * Affichage des dernieres pages d'un blog au format Javascript
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/01/23
	 * @param mixed $blog Recordset du blog
	 * @param integer $nb (option) Nombre d'articles a afficher. Si null, prend nbJsArticles dans la conf. Par defaut : null
	 * @param boolean $content (option) Indique si on affiche le corps des pages. Par defaut : false
	 * @param integer $colonnes Nb de colonnes. Par defaut : 1
	 * @param boolean $hr (option) Si on veut afficher un HR entre les pages. Par defaut : false
   */
   function _createContent (&$toReturn) {
    
    $blog = $this->getParam('blog',null);
    $nb = $this->getParam('nb',null);
    $nb = intval($nb);
    $colonnes = $this->getParam('colonnes');
    $colonnes = intval($colonnes);
		if (!$colonnes) $colonnes=1;
		
		$content = $this->getParam('content',false);
    $hr = $this->getParam('hr',false);

		if (!$nb || $nb<0 || $nb>20)
			$nb = CopixConfig::get('blog|nbJsArticles');
		
		$tpl  = & new CopixTpl ();
		
	  //on r�cup�re l'ensemble des articles du blog
    $dao = CopixDAOFactory::create('blog|blogpage');
      
    $arData = $dao->getAllPagesFromBlog($blog->id_blog);
    
   	//print_r($arData);
		
    if (count($arData) <= $nb) {
			$listPage = $arData;
    } else {
      $params = array(
               'perPage'    => $nb,
               'delta'      => 1,
               'recordSet'  => $arData,
               'template'   => '|pager.tpl'
      );
      $Pager = CopixPager::Load($params);
      $tpl->assign ('pager'                , $Pager->GetMultipage());
      $listPage = $Pager->data;
    }
    //print_r($tpl);
		
		$tpl->assign ('listPage',$listPage);
		
		$nbPages = count($listPage);
		
		// Nb elements par colonnes
		$parCols = ceil($nbPages/$colonnes);

		$tpl->assign ('blog' , $blog);
		$tpl->assign ('content' , $content);
		$tpl->assign ('hr' , $hr);
		$tpl->assign ('parCols', $parCols);
		$tpl->assign ('widthColonne', round(100/$colonnes,1).'%');	

    $txt = $tpl->fetch('listpagejs.tpl');
    $txt = trim(str_replace ("\r\n", "", $txt));
    $txt = trim(str_replace ("\n", "", $txt));
    
    $toReturn = "var blogJsPages='".addslashes($txt)."';";
    
    return true;
  }
}
?>