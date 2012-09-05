<?php


class ZoneReglettePages extends CopixZone
{
    /*
        Param�tres :
        nbPages = nombre total de page
        page = page courante
        url = URL acc�d�e en cliquant sur les pages (sans le param�tre page qui est ajout� automatiquement)

        */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        //$service = & CopixClassesFactory::Create ('Album');
        $nbPages = intval($this->getParam('nbPages'));
        $page = intval($this->getParam('page'));
        $url = $this->getParam('url');
                $separator_type = $this->getParam('separator', '?');

        // Nb de pages � afficher de chaque c�t� de la page courante
        $autour = $this->getParam('autour',3);
        // Nb de pages � chaque bout de la r�gle
        $extremite = $this->getParam('extremite',1);

        if ($nbPages<2) return true;

        $autourFrom = ($page-$autour<1) ? 1 : $page-$autour;
        $autourTo = ($page+$autour>$nbPages) ? $nbPages : $page+$autour;
        //print_r2($autourFrom);
        //print_r2($autourTo);

        $sep1 = $sep2 = '...';
        $pages1 = $pages2 = $pages3 = array();

        $pages1 = range(1,0+$extremite);
        $pages2 = range($autourFrom,$autourTo);
        $pages3 = range($nbPages-$extremite+1,$nbPages);
        if ($autourFrom<=$extremite+1) 	// Les premiers ... sautent
            $sep1 = '';
        if ($autourTo>=$nbPages-$extremite) 	// Les deuxi�mes ... sautent
            $sep2 = '';

        if (!$sep1) {	// On fusionne les premiers tableaux
            $pages2 = array_unique(array_merge ($pages1, $pages2));
            $pages1 = array();
        }
        if (!$sep2) {	// On fusionne les derniers tableaux
            $pages2 = array_unique(array_merge ($pages2, $pages3));
            $pages3 = array();
        }

        $pages = range(1,$nbPages);

        $tpl->assign('nbPages', $nbPages);
        $tpl->assign('page', $page);
        //$tpl->assign('pages', $pages);
        $tpl->assign('pages1', $pages1);
        $tpl->assign('pages2', $pages2);
        $tpl->assign('pages3', $pages3);
        $tpl->assign('url', $url);
        $tpl->assign('sep1', $sep1);
        $tpl->assign('sep2', $sep2);
                $tpl->assign('separator', $separator_type);

        // retour de la fonction :
    $toReturn = $tpl->fetch ('reglettepages.tpl');
    return true;

    }



}
