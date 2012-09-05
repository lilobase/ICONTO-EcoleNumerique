<?php

require_once (COPIX_UTILS_PATH . 'CopixDateTime.class.php');

/**
 * @package	copix
 * @version	$Id: blogarticle.dao.class.php,v 1.19 2009-01-09 16:06:15 cbeyer Exp $
 * @author	Sylvain DACLIN see copix.aston.fr for other contributors.
 * @copyright 2001-2005 Copix Team
 * @link		http://copix.org
 * @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
 */
class DAOBlogArticle
{
    /**
      * findListMonthForArticle
      * @param
      * @return
      */
    public function findListMonthForArticle($id_blog)
    {
        $critere = 'SELECT art.date_bact as date_bact
    FROM module_blog_article as art WHERE art.id_blog = ' . $id_blog . ' ORDER BY art.date_bact DESC, art.id_bact DESC';
        return _doQuery($critere);
    }

    /**
     * getAllArchivesFromBlog
     * @param
     * @return
     */
    public function getAllArchivesFromBlog($id_blog)
    {
        $sp = _daoSp();
        $sp->addCondition('id_blog', '=', $id_blog);
        $sp->addCondition('is_online', '=', 1);
        $sp->orderBy(array('date_bact', 'desc'));
        $sp->orderBy(array('id_bact', 'desc'));

        $result = $this->findBy($sp);

        $arArchive = array();
        $lastMonth = null;
        foreach ($result as $article) {
            $monthYear = substr($article->date_bact, 4, 2) . '/' . substr($article->date_bact, 0, 4);
            if ($monthYear != $lastMonth) {
                $article->drawDate = CopixDateTime::YYYYMMtoYearMonthName(substr($article->date_bact, 0, 6));
                $article->dateValue = substr($article->date_bact, 0, 6);
                array_push($arArchive, $article);
                $lastMonth = $monthYear;
            }
        }
        return $arArchive;
    }

    /**
     * get article by name
     * @param  name
     * @return
     */
    public function getArticleByUrl($id_blog, $url_bact)
    {
        $sp = _daoSp();
        $sp->addCondition('url_bact', '=', urlencode($url_bact));
        $sp->addCondition('id_blog', '=', $id_blog);
        $sp->addCondition('is_online', '=', 1);

        if ($arArticle = $this->findBy($sp)) {

            $dao = _dao('blog|blogarticlecategory');
            $daoLink = _dao('blog|blogarticle_blogarticlecategory');
            $article = $arArticle[0];

            $sp = _daoSp();
            $sp->addCondition('id_bact', '=', $article->id_bact);

            $article->categories = array();
            foreach ($daoLink->findBy($sp) as $object) {
                $article->categories[] = $dao->get($object->id_bacg);
            }

            return $article;
        } else {
            return false;
        }
    }

    /**
     * get article by name
     * @param  name
     * @return
     */
    public function getArticleById($id_blog, $id_bact)
    {
        $article = $this->get($id_bact);

        if ($article && $article->id_blog == $id_blog && $article->is_online) {

            $dao = _dao('blog|blogarticlecategory');
            $daoLink = _dao('blog|blogarticle_blogarticlecategory');

            $sp = _daoSp();
            $sp->addCondition('id_bact', '=', $article->id_bact);
            $article->categories = array();
            foreach ($daoLink->findBy($sp) as $object) {
                $article->categories[] = $dao->get($object->id_bacg);
            }
            return $article;
        } else {
            return false;
        }
    }

    /**
     * Get all article from a blog
     */
    public function getAllArticlesFromBlog($id_blog, $date = null)
    {
        $sp = _daoSp();
        $sp->addCondition('id_blog', '=', $id_blog);
        $sp->addCondition('is_online', '=', 1);
        if ($date != null) {
            $sp->addCondition('date_bact', 'LIKE', $date . '%');
        }
        $sp->orderBy(array('date_bact', 'desc'));
        $sp->orderBy(array('time_bact', 'desc'));
        $sp->orderBy(array('id_bact', 'desc'));

        $arArticle = $this->findBy($sp);

        $dao = _dao('blog|blogarticlecategory');
        $daoLink = _dao('blog|blogarticle_blogarticlecategory');
        foreach ($arArticle as $key => $article) {
            $sp = _daoSp();
            $sp->addCondition('id_bact', '=', $article->id_bact);

            $arArticle[$key]->categories = array();
            foreach ($daoLink->findBy($sp) as $object) {
                $arArticle[$key]->categories[] = $dao->get($object->id_bacg);
            }
            $date = array();
            $date['Y'] = substr($article->date_bact, 0, 4);
            $date['m'] = substr($article->date_bact, 4, 2);
            $date['d'] = substr($article->date_bact, 6, 2);
            $date['H'] = substr($article->time_bact, 0, 2);
            $date['i'] = substr($article->time_bact, 2, 2);
            $arArticle[$key]->dateRFC822 = gmdate('D, d M Y H:i:s', mktime($date['H'], $date['i'], 0, $date['m'], $date['d'], $date['Y'])) . ' GMT';
        }

        return $arArticle;
    }

    /**
     * Get all article from a blog bu cat
     */
    public function getAllArticlesFromBlogByCat($id_blog, $id_bacg)
    {
        //on récupère les identifiants d'article correspondant à la catégorie
        $daoLink = _dao('blog|blogarticle_blogarticlecategory');
        $sp = _daoSp();
        $sp->addCondition('id_bacg', '=', $id_bacg);
        $arID = array();
        foreach ($daoLink->findBy($sp) as $object) {
            $arID[] = $object->id_bact;
        }
        if ($arID == array()) {
            return array();
        }

        $sp = _daoSp();
        $sp->addCondition('id_blog', '=', $id_blog);
        $sp->addCondition('id_bact', '=', $arID);
        $sp->addCondition('is_online', '=', 1);
        $sp->orderBy(array('date_bact', 'desc'));
        $sp->orderBy(array('time_bact', 'desc'));
        $sp->orderBy(array('id_bact', 'desc'));

        $arArticle = $this->findBy($sp);

        $dao = _dao('blog|blogarticlecategory');
        foreach ($arArticle as $key => $article) {
            $sp = _daoSp();
            $sp->addCondition('id_bact', '=', $article->id_bact);
            $arArticle[$key]->categories = array();
            foreach ($daoLink->findBy($sp) as $object) {
                $arArticle[$key]->categories[] = $dao->get($object->id_bacg);
            }
        }
        return $arArticle;
    }

    /**
     * Get all article from a blog by critere
     * @param string $critere mot du critere de recherche des articles
     * @return array $arArticle articles
     */
    public function getAllArticlesFromBlogByCritere($id_blog, $critere)
    {
        $arResultat = array();
        $arCritere = explode(" ", $critere);
        $daoLink = _dao('blog|blogarticle_blogarticlecategory');
        $dao = _dao('blog|blogarticlecategory');


        $arIds = array();
        foreach ($arCritere as $word) {

            $arArticle = $this->findByCritere($id_blog, '%' . $word . '%');

            foreach ($arArticle as $key => $article) {
                //var_dump($article->id_bact);
                //var_dump($article);
                if (!in_array($article->id_bact, $arIds)) {
                    $sp = _daoSp();
                    $sp->addCondition('id_bact', '=', $article->id_bact);
                    $arArticle[$key]->categories = array();
                    foreach ($daoLink->findBy($sp) as $object) {
                        $arArticle[$key]->categories[] = $dao->get($object->id_bacg);
                    }
                    $arIds[] = $article->id_bact;
                    $arResultat[] = $article;
                }
            }
        }

        return $arResultat;
    }

    /**
     * findAllOrder
     * @param
     * @return
     */
    public function findArticles($id_blog, $id_bacg, $query, $orderby = ' art.date_bact DESC, art.time_bact DESC, art.id_bact DESC')
    {
        $critere = ' SELECT DISTINCT art.id_bact as id_bact, ' .
                'art.id_blog as id_blog, ' .
                'art.name_bact as name_bact, ' .
                'art.sumary_html_bact as sumary_html_bact, ' .
                'art.content_html_bact as content_html_bact, ' .
                'art.author_bact as author_bact, ' .
                'art.date_bact as date_bact, ' .
                'art.time_bact as time_bact, ' .
                'art.url_bact as url_bact, ' .
                'art.sticky_bact as sticky_bact, ' .
                'art.is_online as is_online ' .
                ' FROM module_blog_article as art LEFT JOIN module_blog_article_blogarticlecategory as artctg ON art.id_bact = artctg.id_bact ' .
                ' WHERE art.id_blog = ' . $id_blog;

        $clause = ' AND ';
        if ($id_bacg != NULL) {
            $critere = $critere . $clause . ' AND   artctg.id_bacg = ' . $id_bacg;
        }
        if ($query != NULL) {
            $critere = $critere . $clause . $query;
        }
        if ($orderby != NULL) {
            $critere = $critere . ' order by ' . $orderby;
        }
        return _doQuery($critere);
    }

    public function findCategoriesForArticle($id_bact)
    {
        $critere = ' SELECT ctg.id_bacg as id_bacg, ' .
                'ctg.name_bacg as name_bacg, ' .
                'ctg.url_bacg as url_bacg ' .
                ' FROM module_blog_articlecategory as ctg LEFT JOIN module_blog_article_blogarticlecategory as artctg ON ctg.id_bacg = artctg.id_bacg ' .
                ' WHERE artctg.id_bact = ' . $id_bact;
        return _doQuery($critere);
    }

    /**
     * find old article
     * @param
     * @return
     */
    public function findOldArticle($id_blog)
    {
        $critere = ' SELECT MIN(art.date_bact) as min ' .
                ' FROM module_blog_article as art ' .
                ' WHERE art.id_blog = ' . $id_blog . '
            AND is_online=1';
        $result = _doQuery($critere);
        if ($result && $result[0]->min > 0) {
            return $result[0]->min;
        } else {
            return 0;
        }
    }

    /**
     * delete article and comments
     * @param
     * @return
     */
    public function delete($item)
    {
        // Delete link with category table
        $sqlDelete = 'DELETE FROM module_blog_article_blogarticlecategory WHERE id_bact=' . $item->id_bact;
        _doQuery($sqlDelete);

        // Delete article comment
        $sqlDelete = 'DELETE FROM module_blog_articlecomment WHERE id_bact=' . $item->id_bact;
        _doQuery($sqlDelete);

        // Delete article
        $sqlDelete = 'DELETE FROM module_blog_article WHERE id_bact=' . $item->id_bact;
        _doQuery($sqlDelete);
    }

    /**
     * Récupère la liste des derniers articles publiés dans des blogs publics. A utiliser pour des flux RSS ou des zones de la Une
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/02/21
     * @param array $options
     *      [nb] Nombre d'éléments à afficher
     *      [categories] Pour ajouter les catégories de chaque article
     *      [parent] Pour récupérer les infos sur le parent du blog
     *      [blogId] Pour limiter à un blog précis
     *      [future] Pour afficher ou non les articles post-datés (true par défaut)
     *
     */
    public function findPublic($options = array())
    {

        $limit = (isset($options['nb']) && $options['nb']) ? $options['nb'] : 10;

        $arTypes = array();
        if (CopixConfig::exists('public|blogs.types') && CopixConfig::get('public|blogs.types'))
            $arTypes = explode(",", CopixConfig::get('public|blogs.types'));
        $arTypes[] = 'CLUB';

        $params = array();

        $critere = 'SELECT ART.id_bact, ART.name_bact, ART.url_bact, ART.date_bact, ART.time_bact, ART.sumary_bact, ART.sumary_html_bact, BLOG.url_blog, KME.node_type AS parent_type, KME.node_id AS parent_id FROM module_blog BLOG, module_blog_article ART, kernel_mod_enabled KME WHERE ART.id_blog=BLOG.id_blog AND KME.module_id=BLOG.id_blog AND KME.module_type=\'MOD_BLOG\' AND BLOG.is_public=1 AND ART.is_online=1';

        $blogId = (isset($options['blogId']) && $options['blogId']) ? (int)$options['blogId'] : 0;
        $future = (isset($options['future'])) ? $options['future'] : true;

        if ($blogId) {
            $critere .= ' AND ART.id_blog = :blogId';
            $params['blogId'] = $blogId;
        } else {
            $critere .= ' AND KME.node_type IN (\'' . implode('\',\'', $arTypes) . '\')';
        }

        if (!$future) {
            $critere .= ' AND (ART.date_bact < :today1 OR (ART.date_bact = :today2 AND ART.time_bact <= :now))';
            $params['today1'] = $params['today2'] = date('Ymd');
            $params['now'] = date('Hi');
        }

        $critere .= ' ORDER BY ART.date_bact DESC, ART.time_bact DESC, ART.id_bact ASC';

        if (!$blogId && Kernel::getKernelLimits('ville'))
            $critere .= ' LIMIT ' . $limit * 10;
        else
            $critere .= ' LIMIT ' . $limit;

        $list = _doQuery($critere, $params);

        $arArticle = array();

        foreach ($list as $article) {

            $add = true;

            if (!$blogId) {
                switch ($article->parent_type) {
                    case 'CLUB' :
                        if (Kernel::getKernelLimits('ville')) {
                            $ville = GroupeService::getGroupeVille($article->parent_id);
                            if (!in_array($ville, Kernel::getKernelLimits('ville_as_array')))
                                $add = false;
                        }
                        break;
                }
            }


            if (isset($options['categories']) && $options['categories']) {
                $sp = _daoSp();
                $sp->addCondition('id_bact', '=', $article->id_bact);
                $article->categories = array();
                foreach (_ioDAO('blog|blogarticle_blogarticlecategory')->findBy($sp) as $object) {
                    $article->categories[] = _ioDAO('blog|blogarticlecategory')->get($object->id_bacg);
                }
            }

            $date = array();
            $date['Y'] = substr($article->date_bact, 0, 4);
            $date['m'] = substr($article->date_bact, 4, 2);
            $date['d'] = substr($article->date_bact, 6, 2);
            $date['H'] = substr($article->time_bact, 0, 2);
            $date['i'] = substr($article->time_bact, 2, 2);
            $article->dateRFC822 = gmdate('D, d M Y H:i:s', mktime($date['H'], $date['i'], 0, $date['m'], $date['d'], $date['Y'])) . ' GMT';

            if ($add) {

                if (!isset($options['parent']) || $options['parent']) {
                    $article->parent = Kernel::getNodeInfo($article->parent_type, $article->parent_id);
                }

                $arArticle[] = $article;
            }
        }

        if (!$blogId && Kernel::getKernelLimits('ville')) {
            $arArticle = array_slice($arArticle, 0, $limit);
        }

        return $arArticle;

    }

}

class DAORecordblogarticle
{
}

