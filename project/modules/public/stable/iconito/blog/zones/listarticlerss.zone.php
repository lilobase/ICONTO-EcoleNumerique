<?php
/**
* @package Iconito
* @subpackage Blog
* @version   $Id: listarticlerss.zone.php,v 1.2 2006-12-19 10:47:38 cbeyer Exp $
* @author	Christophe Beyer
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneListArticleRss extends CopixZone
{
   /**
     * Affichage du flux RSS d'un blog (flux sortant)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/28
     * @param mixed $blog Recordset du blog
   */
   public function _createContent (&$toReturn)
   {
    $blog = $this->getParam('blog',null);

        $tpl  = new CopixTpl ();

      //on récupère l'ensemble des articles du blog
    $dao = _dao('blog|blogarticle');

    $arData = $dao->getAllArticlesFromBlog($blog->id_blog, NULL);
    //print_r($arData);
    //$arData = $dao->getAllArticlesFromBlogByCritere($blog->id_blog, NULL);

    if (count($arData) <= intval(CopixConfig::get('blog|nbRssArticles'))) {
      $tpl->assign ('listArticle'          , $arData);
    } else {
      $params = Array(
               'perPage'    => intval(CopixConfig::get('blog|nbRssArticles')),
               'delta'      => 5,
               'recordSet'  => $arData,
               'template'   => '|pager.tpl'
      );
      $Pager = CopixPager::Load($params);
      $tpl->assign ('pager'                , $Pager->GetMultipage());
      $tpl->assign ('listArticle'          , $Pager->data);
    }

    //print_r($blog);
    $rss = array (
      'title' => $blog->name_blog,
      'link' => CopixUrl::get().CopixUrl::get ('blog||', array('blog'=>$blog->url_blog)),
      'description' => $blog->name_blog,
      'language' => 'fr-fr',
      'copyright' => "Iconito",
//      'webmaster' => $blog->name_blog,
      'generator' => "Iconito",
      'logo' => ($blog->logo_blog) ? 1 : 0,
    );
        $tpl->assign ('rss' , $rss);
        $tpl->assign ('blog' , $blog);

    $toReturn = $tpl->fetch('listarticlerss.tpl');
    return true;

  }

}
