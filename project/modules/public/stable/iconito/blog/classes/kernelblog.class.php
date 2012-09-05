<?php

_classInclude('blog|blogutils');
_classInclude('blog|blogauth');
require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.blog_format_article.php');
require_once (COPIX_UTILS_PATH.'../../smarty/plugins/shared.make_timestamp.php');

class KernelBlog
{
    /* Crée un blog dans la base de données
      Renvoie son ID ou NULL si erreur
      $infos peut contenir
      $title = Titre du blog
      $author = Auteur du premier article (id user). Si non positionné, prend la session. Sinon, valeur par défaut (voir conf du module)
     */

    public function create($infos = array())
    {
        $blogDAO = _dao('blog|blog');

        $res = null;

        $tabBlogFunctions = returnAllBlogFunctions();

        $blog = _record('blog|blog');
        if ($infos['title'])
            $blog->name_blog = $infos['title'].( (isset($infos['subtitle']) && strlen($infos['subtitle']) > 0) ? ' ('.$infos['subtitle'].')' : '');
        else
            $blog->name_blog = CopixI18N::get('blog|blog.default.titre');
        $blog->id_ctpt = 1;
        $blog->style_blog_file = 0;


        $is_public_default = CopixConfig::exists('blog|blog.default.is_public') ? CopixConfig::get('blog|blog.default.is_public') : 1;

        $blog->is_public = isset($infos['is_public']) ? $infos['is_public'] : $is_public_default;
        $blog->has_comments_activated = 0;
        $blog->type_moderation_comments = CopixConfig::get('blog|blog.default.type_moderation_comments');
        $blog->default_format_articles = CopixConfig::get('blog|blog.default.default_format_articles');
        $blog->privacy = CopixConfig::exists('blog|blog.default.privacy') ? CopixConfig::get('blog|blog.default.privacy') : 0;

        $blogDAO->insert($blog);

        if ($blog->id_blog !== NULL) {

            // On détermine l'URL titre
            $blog->url_blog = KernelBlog::calcule_url_blog($blog->id_blog, $blog->name_blog);
            $blogDAO->update($blog);

            // On ajoute une catégorie
            $categoryDAO = _dao('blog|blogarticlecategory');
            $category = _record('blog|blogarticlecategory');
            $category->id_blog = $blog->id_blog;
            $category->name_bacg = CopixI18N::get('blog|blog.default.categorie');
            $category->url_bacg = killBadUrlChars($category->name_bacg).date('YmdHis');
            $category->order_bacg = $categoryDAO->getNewPos($blog->id_blog);
            $categoryDAO->insert($category);
        }

        return ($blog->id_blog !== NULL) ? $blog->id_blog : NULL;
    }

    /**
      Renvoie différentes infos chiffrées d'un blog, dans un tableau
     */
    public function getStats($id_blog)
    {
        $dao = _dao('blog|blogarticle');
        $res = array();
        $arData = $dao->getAllArticlesFromBlog($id_blog, NULL);
        $nbArticles = count($arData);
        $res['nbArticles'] = array(
            'name' => CopixI18N::get('blog|blog.stats.nbArticles', array($nbArticles)),
            'value' => $nbArticles,
        );
        //print_r($arData);
        if ($nbArticles > 0) {
            $date = BDToDateTime($arData[0]->date_bact, $arData[0]->time_bact, 'mysql');
            $mktime = smarty_make_timestamp($date);
            $date = CopixDateTime::mktimeToDatetime($mktime);
            $res['lastUpdate'] = array(
                'name' => CopixI18N::get('blog|blog.stats.lastUpdate', array($date)),
                'value_order' => $mktime,
            );
        }
        //print_r($res);
        return $res;
    }

    /**
     * Statistiques du module blog
     *
     * Renvoie des éléments chiffrés relatifs aux blogs et dédiés à un utilisateur système : nombre de blogs, d'articles...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/20
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbForums"] ["nbTopics"] ["nbMessages"]
     */
    public function getStatsRoot()
    {
        $res = array();
        $sql = 'SELECT COUNT(id_blog) AS nb FROM module_blog';
        $a = _doQuery($sql);
        $res['nbBlogs'] = array('name' => CopixI18N::get('blog|blog.stats.nbBlogs', array($a[0]->nb)));
        $sql = 'SELECT COUNT(id_bact) AS nb FROM module_blog_article WHERE is_online=1';
        $a = _doQuery($sql);
        $res['nbArticles'] = array('name' => CopixI18N::get('blog|blog.stats.nbArticles', array($a[0]->nb)));
        $sql = 'SELECT COUNT(id_bacc) AS nb FROM module_blog_articlecomment WHERE is_online=1';
        $a = _doQuery($sql);
        $res['nbComments'] = array('name' => CopixI18N::get('blog|blog.stats.nbComments', array($a[0]->nb)));
        return $res;
    }

    public function delete($id_blog)
    {

        //suppression du blog
        $blogDAO = & _dao('blog|blog');
        $blogDAO->delete($id_blog);

        //suppression des pages liées au blog
        $daoPage = & CopixDAOFactory::getInstanceOf('blog|blogpage');
        $record = _record('blog|blogpage');

        $criteres = _daoSp();
        $criteres->addCondition('id_blog', '=', $id_blog);
        $resultat = $daoPage->findBy($criteres);
        $daoPage = & _dao('blog|blogpage');
        foreach ($resultat as $page) {
            $daoPage->delete($page);
        }

        //suppression des liens liés au blog
        $daoLien = & CopixDAOFactory::getInstanceOf('blog|bloglink');
        $record = _record('blog|bloglink');

        $criteres = _daoSp();
        $criteres->addCondition('id_blog', '=', $id_blog);
        $resultat = $daoLien->findBy($criteres);

        foreach ($resultat as $lien) {
            $daoLien->delete($lien);
        }

        //suppression des catégories du blog
        $daoCategorie = & CopixDAOFactory::getInstanceOf('blog|blogarticlecategory');
        $record = _record('blog|blogarticlecategory');

        $criteres = _daoSp();
        $criteres->addCondition('id_blog', '=', $id_blog);
        $resultat = $daoCategorie->findBy($criteres);

        foreach ($resultat as $categorie) {
            $daoCategorie->delete($categorie);
        }

        //suppression des articles, des commentaires et des liens catégories / articles
        $arIdBact = array();
        $daoArticle = & CopixDAOFactory::getInstanceOf('blog|blogarticle');
        $record = _record('blog|blogarticle');

        $criteres = _daoSp();
        $criteres->addCondition('id_blog', '=', $id_blog);
        $resultat = $daoArticle->findBy($criteres);

        foreach ($resultat as $article) {
            $daoArticle->delete($article);
        }

        Kernel::unregisterModule("MOD_BLOG", $id_blog);

        return true;
    }

    /*
      Publication distante (autre module).
      id du blog + données -> infos sur la nouvelle données dans le blog
     */

    public function publish($id, $data)
    {

        $articleDAO = _dao('blog|blogarticle');
        $article = _record('blog|blogarticle');
        $article->id_blog = $id;
        $article->name_bact = $data['title'];
        $article->format_bact = 'wiki';
        $article->sumary_bact = $data['body'];
        $article->sumary_html_bact = smarty_modifier_blog_format_article($article->sumary_bact, $article->format_bact);
        $article->content_bact = '';
        $article->content_html_bact = smarty_modifier_blog_format_article($article->content_bact, $article->format_bact);
        $article->author_bact = 'Publication par mail...';
        $article->date_bact = CopixDateTime::dateToTimestamp(date('d/m/Y'));
        $article->time_bact = timeToBD(date('H:i'));
        $article->url_bact = killBadUrlChars($article->name_bact);
        $article->sticky_bact = 0;
        $article->is_online = 1;
        $articleDAO->insert($article);

        $article->url_bact = killBadUrlChars($article->id_bact.'-'.$article->name_bact);
        $articleDAO->update($article);


        return( "yo".print_r($article, true)."yo" );
    }

    /**
     * Détermine l' "url_blog" unique d'un blog, à partir de son titre
     *
     * A partir du titre d'un blog, en déduit son "url_blog". Regarde dans la BDD si un blog de même titre n'existe pas déjà pour rendre cette valeur unique (au besoin, ajoute un numéro à la fin pour le rendre unique). Ne doit être appellé qu'à la création d'un blog.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/10/23
     * @param integer $id Id du blog
     * @param string $titre Titre du blog
     * @return string url_blog à stocker dans la BDD. Chaine vide si problème
     */
    public function calcule_url_blog($id, $titre)
    {
        if (strlen($titre) > 97)
            $titre = substr($titre, 0, 97);
        //print_r("titre=$titre<br>");
        $titre = killBadUrlChars($titre);
        $exists = true;
        $fusible = 1;
        while ($exists && $fusible < 1000) {
            $id_nom = $titre.(($fusible > 1) ? $fusible : "");
            $sql = "SELECT id_blog FROM module_blog WHERE url_blog='".addslashes($id_nom)."'";
            if ($id)
                $sql .= " AND id_blog!=$id";
            //print_r ("sql=$sql<br>");
            $p = _doQuery($sql);
            $exists = ($p) ? true : false;
            $fusible++;
        }
        if (!$exists)
            $res = $id_nom;
        else
            $res = '';
        return $res;
    }

    public function getNotifications(&$module, &$lastvisit)
    {
        $lastvisit_date = substr($lastvisit->date, 0, 8);
        $lastvisit_time = substr($lastvisit->date, 8, 4);


        $blog = _dao('blog|blog')->get($module->module_id);


        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)){ // Si on est admin, recherche des nouveaux commentaires
            $new_comments = _dao('blog|blogarticlecomment')->findBy(
                _daoSp()
                    ->addCondition('id_blog', '=', $module->module_id)
                    ->addCondition('is_online', '=', 1)
                    ->addCondition('authorid_bacc', '!=', _currentUser()->getExtra("user_id"))
                    ->startGroup ('AND')
                        ->addCondition('date_bacc', '>', $lastvisit_date)
                        ->startGroup ('OR')
                            ->addCondition('date_bacc', '=', $lastvisit_date)
                            ->addCondition('time_bacc', '>=', $lastvisit_time, 'AND')
                        ->endGroup ()
                    ->endGroup ()
            );

            $module->notification_number = count($new_comments);
            $module->notification_message = count($new_comments)." commentaire".(count($new_comments)>1?"s":"");
        } else { // Si on n'est pas admin, recherche des nouveaux articles
            $new_posts = _dao('blog|blogarticle')->findBy(
                _daoSp()
                    ->addCondition('id_blog', '=', $module->module_id)
                    ->addCondition('is_online', '=', 1)
                    ->addCondition('author_bact', '!=', _currentUser()->getExtra("user_id"))
                    ->startGroup ('AND')
                        ->addCondition('date_bact', '>', $lastvisit_date)
                        ->startGroup ('OR')
                            ->addCondition('date_bact', '=', $lastvisit_date)
                            ->addCondition('time_bact', '>=', $lastvisit_time, 'AND')
                        ->endGroup ()
                    ->endGroup ()
            );

            $module->notification_number = count($new_posts);
            $module->notification_message = count($new_posts)." article".(count($new_posts)>1?"s":"");
        }


        return true;
    }

}

