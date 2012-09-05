<?php
/**
* @package Iconito
* @subpackage	Blog
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/
class ZoneEditArticle extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO

        CopixHTMLHeader::addCSSLink (_resource("styles/module_blog_admin.css"));
        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_blog.js');

        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog', '');

        $tpl->assign ('article'           , $this->getParam('article',null));
        $tpl->assign ('kind'              , $this->getParam('kind',null));
        $tpl->assign ('id_blog'           , $id_blog);
        $tpl->assign ('id_bact'           , $this->getParam('id_bact', ''));
        $tpl->assign ('errors'            , $this->getParam('errors', ''));
        $tpl->assign ('showErrors'        , $this->getParam('showErrors', false));
        $tpl->assign ('preview'						, $this->getParam('preview', '0'));
        $tpl->assign ('tabArticleCategory', $this->getParam('tabArticleCategory', null));
        $tpl->assign ('can_format_articles', CopixConfig::get ('blog|blog.default.can_format_articles'));
        $tpl->assign ('default_format_articles', CopixConfig::get ('blog|blog.default.default_format_articles'));

        $formats = CopixConfig::get ('blog|blog.formats_articles');
        $tabFormats = explode (',',$formats);
        $values = $output = array();
        foreach ($tabFormats as $k) {
            $values[] = $k;
            $output[] = CopixI18N::get('blog|blog.default_format_articles.'.$k);
        }
        $tpl->assign ('format_bact', array('values'=>$values, 'output'=>$output));

        $art = $this->getParam('article');

        $tpl->assign ('edition_sumary', CopixZone::process ('kernel|edition', array('field'=>'sumary_bact', 'format'=>$art->format_bact, 'content'=>$art->sumary_bact, 'options' => array('toolbarSet' => 'IconitoBlog'), 'object'=>array('type'=>'MOD_BLOG', 'id'=>$this->getParam('id_blog')), 'height'=>160)));
        $tpl->assign ('edition_content', CopixZone::process ('kernel|edition', array('field'=>'content_bact', 'format'=>$art->format_bact, 'content'=>$art->content_bact, 'options' => array('toolbarSet' => 'IconitoBlog'), 'object'=>array('type'=>'MOD_BLOG', 'id'=>$this->getParam('id_blog')), 'height'=>290)));



        //cat�gorie de l'article
        if($this->getParam('kind',null) == 1){
            $article = $this->getParam('article', null);
            $idCategorie = $article->tabSelectCat[0];

            foreach($this->getParam('tabArticleCategory', null) as $key=>$obj){
                if($obj->id_bacg == $idCategorie){
                    $categorie = $obj->name_bacg;
                }
            }
            $tpl->assign ('categorie', $categorie);
        }
        $tpl->assign ('canWriteOnline' , BlogAuth::canMakeInBlog('ADMIN_ARTICLE_MAKE_ONLINE',create_blog_object($id_blog)));

        // retour de la fonction :
        $toReturn = $tpl->fetch('article.edit.tpl');
        return true;
    }
}
