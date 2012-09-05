<?php

/**
 * Zone qui affiche la gestion des droits d'un blog
 *
 * @package Iconito
 * @subpackage	Blog
 * @version   $Id: showblogdroits.zone.php,v 1.2 2007-06-04 10:22:54 cbeyer Exp $
 */

_classInclude('blog|blogauth');

class ZoneShowBlogDroits extends CopixZone
{
    /**
     * Affiche la liste des personnes ayant des droits spécifiques sur un blog
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/31
     * @param object $blog Blog (recordset)
     * @param integer $kind Numéro générique de la rubrique (ne pas y toucher)
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     */
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $blog = $this->getParam('blog', null);
        $kind = $this->getParam('kind', null);
        $droit = $this->getParam('droit', null);
        $errors = $this->getParam('errors');
        $membres = $this->getParam('membres');
        $droit = $this->getParam('droit');
        //Kernel::deb("droit=$droit");
        //print_r($blog);

        // On vérifie le droit d'être ici
        if (!BlogAuth::canMakeInBlog ("ADMIN_DROITS", $blog))
            return false;


        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $childs = Kernel::getNodeChilds( "MOD_BLOG", $blog->id_blog );
        foreach ($childs AS $k=>$v) {
            //print_r($v);
            $userInfo = Kernel::getUserInfo($v["type"], $v["id"]);
            $childs[$k]["login"] = $userInfo["login"];
            $childs[$k]["nom"] = $userInfo["nom"];
            $childs[$k]["prenom"] = $userInfo["prenom"];
            $childs[$k]["droitnom"] = $groupeService->getRightName($v['droit']);
        }
        //print_r($childs);

        $tplHome = new CopixTpl ();
        //$tplHome->assign ('groupe', $groupe[0]);
        $tpl->assign ('kind', $kind);
        $tpl->assign ('droit', $droit);
        $tpl->assign ('list', $childs);
        $tpl->assign ('errors', $errors);
        $tpl->assign ('membres', $membres);
        $tpl->assign ('linkpopup', CopixZone::process ('annuaire|linkpopup', array('field'=>'membres')));
        $tpl->assign ('droit_values', array(
        PROFILE_CCV_VALID=>$groupeService->getRightName(PROFILE_CCV_VALID),
        PROFILE_CCV_MODERATE=>$groupeService->getRightName(PROFILE_CCV_MODERATE),
        ));

        $tpl->assign ('blog', $blog);

        // retour de la fonction :
        $toReturn = $tpl->fetch('blog.show.droits.tpl');
        return true;
    }
}
