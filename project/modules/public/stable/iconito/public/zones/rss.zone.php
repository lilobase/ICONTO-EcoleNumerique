<?php

/**
 * Zone qui affiche le RSS de tous les blogs du site
 *
 * @package Iconito
 * @subpackage	Public
 */

class ZoneRss extends CopixZone
{
    /**
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/23
     */
    public function _createContent(&$toReturn)
    {
        $blog = $this->getParam('blog', null);

        $tpl = new CopixTpl ();

        $arArticle = _ioDAO('blog|blogarticle')->findPublic(array(
            'categories'    => true,
            'nb'            => intval(CopixConfig::get('public|rss.nbArticles')),
        ));

        $rss = array(
            'title'         => CopixI18N::get('public|public.rss.flux.title'),
            'link'          => CopixUrl::get(),
            'description'   => CopixI18N::get('public|public.rss.flux.description'),
            'language'      => 'fr-fr',
            'copyright'     => "Iconito",
            'generator'     => "Iconito",
            'logo'          => 0,
        );
        $tpl->assign('rss', $rss);
        $tpl->assign('blog', $blog);
        $tpl->assign('listArticle', $arArticle);

        $toReturn = $tpl->fetch('rss.tpl');
        return true;
    }

}

