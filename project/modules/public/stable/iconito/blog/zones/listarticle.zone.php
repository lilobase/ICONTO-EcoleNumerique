<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listarticle.zone.php,v 1.10 2007-10-15 14:12:55 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneListArticle extends CopixZone
{
   public function _createContent (&$toReturn)
   {
        $tpl  = new CopixTpl ();
        $blog    = $this->getParam('blog', '');
        $cat     = $this->getParam('cat' , null);
        $critere = $this->getParam('critere' , null);

      //on récupère l'ensemble des articles du blog
      $dao = _dao('blog|blogarticle');

      if ($cat == null) {
         $date  = $this->getParam('date' , null);
         $arData = $dao->getAllArticlesFromBlog($blog->id_blog, $date);
      }else{
         $arData = $dao->getAllArticlesFromBlogByCat($blog->id_blog, $cat->id_bacg);
         $tpl->assign ('cat', $cat);
      }

      //on filtre si on a fait une recherche sur les articles
      if($critere != null){
          $arData = $dao->getAllArticlesFromBlogByCritere($blog->id_blog, $critere);
        }
      //var_dump($arData);

      //on construit un tableau associatif entre l'identifiant de l'article et le nombre de commentaires
      foreach($arData as $article) {
            //var_dump($article);
            $daoArticleComment = & CopixDAOFactory::getInstanceOf ('blog|blogarticlecomment');
            $record   = _record ('blog|blogarticlecomment');

            $criteres = _daoSp();
            $criteres->addCondition('id_bact', '=', $article->id_bact);
            $criteres->addCondition('is_online', '=', 1);
            $resultat = $daoArticleComment->findBy($criteres);

            $arNbCommentByArticle[$article->id_bact] = count($resultat);
      }

      if (count($arData)>0) {

          //encodage des URL des catégories pour caractères spéciaux
          foreach($arData as $key=>$data){
            //Modification suite à apparition d'un warning due à l'absence de catégories , vboniface 06.11.2006
            $arData[$key]->key=$key;
            if (isset($arData[$key]->categories)) {
                foreach($arData[$key]->categories as $keyCat=>$categorie){
                          $arData[$key]->categories[$keyCat]->url_bacg = urlencode($categorie->url_bacg);
                  }
            }
          }

         if (count($arData) <= intval(CopixConfig::get('blog|nbMaxArticles'))) {
            $tpl->assign ('pager'                , "");
            $tpl->assign ('listArticle'          , $arData);
            $tpl->assign ('arNbCommentByArticle' , $arNbCommentByArticle);
         }else{
             $params = Array(
               'perPage'    => intval(CopixConfig::get('blog|nbMaxArticles')),
               'delta'      => 5,
               'recordSet'  => $arData,
               'template'   => '|pager.tpl'
            );
            $Pager = CopixPager::Load($params);

            $tpl->assign ('pager'                , $Pager->GetMultipage());
            $tpl->assign ('listArticle'          , $Pager->data);
            $tpl->assign ('arNbCommentByArticle' , $arNbCommentByArticle);
         }
         //rajout suite à bug mantis 54 vboniface 06.11.2006
         $tpl->assign ('blog' , $blog);
      }

      // retour de la fonction :
      $toReturn = $tpl->fetch('listarticle.tpl');
      return true;
   }
}
