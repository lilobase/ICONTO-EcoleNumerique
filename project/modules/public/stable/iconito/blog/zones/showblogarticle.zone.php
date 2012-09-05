<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblogarticle.zone.php,v 1.13 2007-12-20 16:17:27 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* Admin des articles (combos de fitrages + articles)
* @param id_head // the current copixheading indice can be null if racine
*/

_classInclude('blog|blogauth');

class ZoneShowBlogArticle extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog', null);
        $blog = $this->getParam('blog', null);
        $kind = $this->getParam('kind', '0');
        $selectCategory = $this->getParam('selectCategory', '');
        $selectMonth = $this->getParam('selectMonth', '');
        $id_bact = $this->getParam('id_bact', '');

        // Recherche de toutes les catégories de la base
        $query = null;
        $clause='';
        if(strlen($selectCategory)>0) {
            $clause = ' AND ';
            $query = ' artctg.id_bacg = '.$selectCategory;
        }
        if(strlen($selectMonth)>0) {
            $query = $query.$clause.' art.date_bact LIKE \''.$selectMonth.'%\'';
        }
        $id_bacg = null;
        $blogArticleDAO = _dao('blog|blogarticle');
        $res = $blogArticleDAO->findArticles($id_blog, $id_bacg, $query);
        //print_r($res);
        // Manipulation du tableau résultat.
        $commentDAO = _dao('blog|blogarticlecomment');
        $tabArticles = Array();
        foreach($res as $r) {
            $r->categories = array();
            $r->categories = $blogArticleDAO->findCategoriesForArticle($r->id_bact);
            $r->nbComment = $commentDAO->countNbCommentForArticle($r->id_bact);
            $r->nbComment_offline = $commentDAO->countNbCommentForArticle($r->id_bact, 0);
            if($id_bact==$r->id_bact) $r->expand=true; else $r->expand=false;
            array_push($tabArticles, $r);
        }
        // Préparation du filtre CATEGORIES
        $blogArticleCategoryDAO = _dao('blog|blogarticlecategory');
        $tabArticleCategory = $blogArticleCategoryDAO->findAllOrder($id_blog);
        // Préparation du filtre MOIS
        $resArticleMonth = $blogArticleDAO->findListMonthForArticle($id_blog);
        $tabArticleMonth = array();
        $lastMonth = null;
        foreach($resArticleMonth as $month) {
            $monthYear = substr($month->date_bact,4,2).'/'.substr($month->date_bact,0,4);
            if ($monthYear != $lastMonth) {
                $tmp = array (
                    'value' => substr($month->date_bact,0,6),
                    'text' => $monthYear);
                $tabArticleMonth[] = $tmp;
                $lastMonth = $monthYear;
            }
        }

        //capability
        //$tpl->assign ('canManageArticle' , BlogAuth::canMakeInBlog('ADMIN_ARTICLES',create_blog_object($id_blog)));
        $tpl->assign ('id_blog', $id_blog);
        $tpl->assign ('blog', $blog);
        $tpl->assign ('kind', $kind);
        $tpl->assign ('tabArticleMonth', $tabArticleMonth);
        $tpl->assign ('tabArticleCategory', $tabArticleCategory);
        $tpl->assign ('selectCategory', $selectCategory);
        $tpl->assign ('selectMonth', $selectMonth);
        $tpl->assign ('id_bact', $id_bact);

        // Uniquement pour la partie Article
        if (count($tabArticles)>0) {
            $params = Array(
            'perPage'    => intval(CopixConfig::get ('blog|nbMaxArticles')),
            'delta'      => 5,
            'recordSet'  => $tabArticles,
            );
            $pager = CopixPager::Load($params);
            //print_r($pager);
            $tpl->assign ('pagerArticles' , $pager->GetMultipage());
            $tpl->assign ('tabArticles' , $pager->data);
            $tpl->assign ('p', $this->getParam('p', ''));
        }else{
            $tpl->assign ('p', $this->getParam('p', ''));
            $tpl->assign ('tabArticles' , array());
        }

        $tpl->assign ('canDelete', BlogAuth::canMakeInBlog('ADMIN_ARTICLE_DELETE',$blog));
        $tpl->assign ('canAdminComments', BlogAuth::canMakeInBlog('ADMIN_COMMENTS',$blog));

        // retour de la fonction :
        $toReturn = $tpl->fetch('blog.show.article.tpl');
        return $toReturn;
    }
}
