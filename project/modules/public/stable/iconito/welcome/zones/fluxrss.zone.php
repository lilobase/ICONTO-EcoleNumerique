<?php

/**
 * Affichage de la liste des actualites d'un blog
 *
 * @package Iconito
 * @subpackage Welcome
 */
class ZoneFluxrss extends CopixZone
{
    /**
     * Affiche la liste des fluxrss d'un blog
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/23
     * @param string $titre Titre a donner a la zone
     * @param string $blog URL_blog du blog
     * @param integer $nb Nombre de pages a afficher
     * @param integer $page Id d'une page precise a afficher
     * @param integer $colonnes Nb de colonnes. Par defaut : 1
     * @param integer $content Si on veut afficher le contenu des pages. Par defaut : 0
     * @param boolean $hr Affiche un HR entre chaque page. Par defaut : 0
     * @param boolean $showtitle (option) Si on veut afficher le titre des articles. Par defaut : true
     * @param integer $truncate (option) Limit de cesure du texte. Par defaut : 0 (pas de cesure)
     */
    public function _createContent(&$toReturn)
    {
        $titre = $this->getParam('titre');
        $blog = $this->getParam('blog');
        $nb = $this->getParam('nb');

        $tpl = new CopixTpl ();
        $tpl->assign('titre', $titre);
        $tpl->assign('blog', $blog);
        $tpl->assign('nb', $nb);

        $dao = _dao('blog|blogfluxrss');
        $tpl->assign ('listFluxRss' , $dao->getAllFluxRssFromBlog($blog));

        $toReturn = $tpl->fetch('zone_fluxrss.tpl');

        return true;
    }

}

