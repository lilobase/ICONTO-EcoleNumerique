<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listarticlejs.zone.php,v 1.8 2009-03-11 13:32:52 cbeyer Exp $
* @author	Christophe Beyer
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneListArticleJs extends CopixZone
{
   /**
     * Affichage des derniers articles d'un blog au format Javascript
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/19
     * @param mixed $blog Recordset du blog
     * @param integer $nb (option) Nombre d'articles a afficher. Si null, prend nbJsArticles dans la conf. Par defaut : null
     * @param integer $article (option) Id d'un article precise a afficher
     * @param boolean $chapo (option) Indique si on affiche le chapo des articles. Par defaut : false
     * @param integer $colonnes Nb de colonnes. Par defaut : 1
     * @param boolean $hr (option) Si on veut afficher un HR entre les pages. Par defaut : false
     * @param boolean $showtitle (option) Si on veut afficher le titre des articles. Par defaut : true
     * @param boolean $showdate (option) Si on veut afficher la date des articles. Par defaut : true
     * @param boolean $showcategorie (option) Si on veut afficher les categories des articles. Par defaut : true
   */
   public function _createContent (&$toReturn)
   {
    $blog = $this->getParam('blog',null);
    $nb = $this->getParam('nb',null);
    $nb = intval($nb);
    $colonnes = $this->getParam('colonnes');
    $colonnes = intval($colonnes);
        if (!$colonnes) $colonnes=1;

    $chapo = $this->getParam('chapo',false);
    $hr = $this->getParam('hr',false);
        $article = $this->getParam('article',null);
    $article = intval($article);
    $showtitle = $this->getParam('showtitle',true);
    $showdate = $this->getParam('showdate',true);
    $showcategorie = $this->getParam('showcategorie',true);

        $tpl  = new CopixTpl ();

    $listArticle = array();
    if ($article) { // Article precis
      if ( ($rArticle=_dao('blog|blogarticle')->get($article)) && $rArticle->id_blog==$blog->id_blog) {

        $daoLink = _dao('blog|blogarticle_blogarticlecategory');
        $sp = _daoSp ();
              $sp->addCondition ('id_bact', '=', $rArticle->id_bact);

        $rArticle->categories = array();
              foreach ($daoLink->findBy($sp) as $object) {
                  $rArticle->categories[] = _dao('blog|blogarticlecategory')->get($object->id_bacg);
              }

        $listArticle[] = $rArticle;
      }
    } else { // Les derniers articles

      if (!$nb || $nb<0 || $nb>20)
              $nb = CopixConfig::get('blog|nbJsArticles');

      //on récupère l'ensemble des articles du blog
      $dao = _dao('blog|blogarticle');

      $arData = $dao->getAllArticlesFromBlog($blog->id_blog, NULL);

          if (count($arData)>0) {
              foreach($arData as $key=>$data){
                  if (isset($arData[$key]->categories)) {
                      foreach($arData[$key]->categories as $keyCat=>$categorie){
                      $arData[$key]->categories[$keyCat]->url_bacg = urlencode($categorie->url_bacg);
                }
                  }
              }
          }

      if (count($arData) <= $nb) {
              $listArticle = $arData;
      } else {
        $params = array(
                 'perPage'    => $nb,
                 'delta'      => 1,
                 'recordSet'  => $arData,
                 'template'   => '|pager.tpl'
        );
        $Pager = CopixPager::Load($params);
        $tpl->assign ('pager'                , $Pager->GetMultipage());
        $listArticle = $Pager->data;
      }
    }

        $tpl->assign ('listArticle',$listArticle);

        $nbArticles = count($listArticle);

        // Nb elements par colonnes
        $parCols = ceil($nbArticles/$colonnes);

//    print_r($arData);
        $tpl->assign ('blog' , $blog);
        $tpl->assign ('chapo' , $chapo);
        $tpl->assign ('hr' , $hr);
        $tpl->assign ('parCols', $parCols);
        $tpl->assign ('widthColonne', round(100/$colonnes,1).'%');
    $tpl->assign ('showtitle', $showtitle);
    $tpl->assign ('showdate', $showdate);
    $tpl->assign ('showcategorie', $showcategorie);

    $txt = $tpl->fetch('listarticlejs.tpl');
    $txt = trim(str_replace ("\r\n", "", $txt));
    $txt = trim(str_replace ("\n", "", $txt));

    $toReturn = "var blogJs='".addslashes($txt)."';";

    return true;
  }
}
