<?php

/**
 * Affichage de la liste des actualites d'un blog
 *
 * @package Iconito
 * @subpackage Welcome
 */
class ZoneActualites extends CopixZone
{
    /**
     * Affiche la liste des actualites d'un blog
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/11/10
     * @param string $titre Titre a donner a la zone
     * @param string $blog URL_blog du blog
     * @param integer $nb Nombre d'articles a afficher
     * @param integer $article Id d'un article precis a afficher
     * @param integer $colonnes Nb de colonnes. Par defaut : 1
     * @param integer $chapo Si on veut afficher les chapos. Par defaut : 0
     * @param string $hreflib Si renseigne, affiche ce texte comme libelle d'un lien menant a l'accueil du blog
     * @param boolean $hr Affiche un HR entre chaque article. Par defaut : 0
     * @param boolean $showtitle (option) Si on veut afficher le titre des articles. Par defaut : true
     * @param boolean $showdate (option) Si on veut afficher la date des articles. Par defaut : true
     * @param boolean $showcategorie (option) Si on veut afficher les categories des articles. Par defaut : true
     */
    public function _createContent (&$toReturn)
    {
        $titre = $this->getParam('titre');
        $blog = $this->getParam('blog');
        $colonnes = $this->getParam('colonnes',1);
        $nb = $this->getParam('nb');
        $chapo = $this->getParam('chapo', false);
        $hreflib = $this->getParam('hreflib');
        $hr = $this->getParam('hr', false);
        $article = $this->getParam('article');
    $showtitle = $this->getParam('showtitle',true);
    $showdate = $this->getParam('showdate',true);
    $showcategorie = $this->getParam('showcategorie',true);

        $tpl = new CopixTpl ();
        $tpl->assign ('titre', $titre);
        $tpl->assign ('blog', $blog);
        $tpl->assign ('nb', $nb);
        $tpl->assign ('colonnes', $colonnes);
        $tpl->assign ('chapo', $chapo);
        $tpl->assign ('hreflib', $hreflib);
        $tpl->assign ('hr', $hr);
        $tpl->assign ('article', $article);
    $tpl->assign ('showtitle', $showtitle);
    $tpl->assign ('showdate', $showdate);
    $tpl->assign ('showcategorie', $showcategorie);

        $toReturn = $tpl->fetch('zone_actualites.tpl');

        return true;

    }
}
