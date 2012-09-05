<?php
/**
* @package Iconito
* @subpackage	Blog
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/
class ZoneEditPage extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO

        CopixHTMLHeader::addCSSLink (_resource("styles/module_blog_admin.css"));

    CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_blog.js');

        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog', '');

        $tpl->assign ('page', $this->getParam('page',null));
        $tpl->assign ('id_blog', $this->getParam('id_blog', ''));
        $tpl->assign ('id_bpge', $this->getParam('id_bpge', ''));
        $tpl->assign ('errors', $this->getParam('errors', ''));
        $tpl->assign ('showErrors', $this->getParam('showErrors', ''));
        $tpl->assign ('preview', $this->getParam('preview', '0'));
        $tpl->assign ('kind', $this->getParam('kind', '0'));
        $tpl->assign ('can_format_articles', CopixConfig::get ('blog|blog.default.can_format_articles'));
        $tpl->assign ('default_format_articles', CopixConfig::get ('blog|blog.default.default_format_articles'));

        //$tpl->assign ('wikibuttons', CopixZone::process ('kernel|wikibuttons', array('field'=>'content_bpge', 'object'=>array('type'=>'MOD_BLOG', 'id'=>$this->getParam('id_blog')))));

        $formats = CopixConfig::get ('blog|blog.formats_articles');
        $tabFormats = explode (',',$formats);
        $values = $output = array();
        foreach ($tabFormats as $k) {
            $values[] = $k;
            $output[] = CopixI18N::get('blog|blog.default_format_articles.'.$k);
        }
        $tpl->assign ('format_bpge', array('values'=>$values, 'output'=>$output));

        $pag = $this->getParam('page');
        //print_r($pag);
        if (!isset($pag->content_bpge))
            $pag->content_bpge = '';

        //$content = (isset($pag->content_bpge)) ? $pag->content_bpge : '';

        //$tpl->assign ('content_bpge', CopixZone::process ('kernel|edition', array('field'=>'content_bpge', 'format'=>'wiki', 'content'=>$content, 'object'=>array('type'=>'MOD_BLOG', 'id'=>$this->getParam('id_blog')), 'height'=>290)));
        $tpl->assign ('edition_content', CopixZone::process ('kernel|edition', array('field'=>'content_bpge', 'format'=>$pag->format_bpge, 'content'=>$pag->content_bpge, 'object'=>array('type'=>'MOD_BLOG', 'id'=>$this->getParam('id_blog')), 'height'=>290)));


        $tpl->assign ('canWriteOnline' , BlogAuth::canMakeInBlog('ADMIN_ARTICLE_MAKE_ONLINE',create_blog_object($id_blog)));

        // retour de la fonction :
        $toReturn = $tpl->fetch('page.edit.tpl');
        return true;
    }
}
