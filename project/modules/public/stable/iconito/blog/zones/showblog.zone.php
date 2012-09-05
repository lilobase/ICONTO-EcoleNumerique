<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblog.zone.php,v 1.5 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/

_classInclude('blog|blogauth');

class ZoneShowBlog extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog', '');

        $tpl->assign ('blog', $this->getParam('blog',null));
        $tpl->assign ('id_blog', $id_blog);
        $tpl->assign ('kind', $this->getParam('kind', ''));
        $tpl->assign ('tabBlogFunctions', $this->getParam('tabBlogFunctions', null));
        $tpl->assign ('can_format_articles', CopixConfig::get ('blog|blog.default.can_format_articles'));

        $tpl->assign ('RESULT', $this->getParam('RESULT', ''));

        $parent = Kernel::getModParentInfo("MOD_BLOG", $id_blog);
        if ($parent) {
            $mods = Kernel::getModEnabled ($parent['type'], $parent['id'], '', 0, 1);
            // _dump($mods);
            $mods = Kernel::filterModuleList ($mods, 'MOD_MAGICMAIL');
            if(count($mods)) {
                $magicmail_infos = _dao('module_magicmail')->get($mods[0]->module_id);
                $tpl->assign ('magicmail_infos', $magicmail_infos);
                // _dump($magicmail_infos);
                /*
                    'id' => '32',
                    'login' => 'cepapeti',
                    'domain' => 'magicmail.iconito.fr',
                 */
            }
        }

        // retour de la fonction :
        $toReturn = $tpl->fetch('blog.show.tpl');
        return true;
    }
}
