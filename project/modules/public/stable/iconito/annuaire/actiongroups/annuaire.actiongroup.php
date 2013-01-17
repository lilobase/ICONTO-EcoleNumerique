<?php

_classInclude('blog|blogutils');

/**
 * Actiongroup du module Annuaire
 *
 * @package Iconito
 * @subpackage Annuaire
 */
class ActionGroupAnnuaire extends EnicActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
    }

    /**
     * Redirection vers un annuaire. On peut demander à afficher un annuaire de ville ($id vaut alors "VILLE_XX"), d'école ("ECOLE_XX") ou de classe ("CLASSE_XX")
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/17
     * @param string $id Annuaire demandé
     */
    public function go()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        $classe = $ecole = $ville = null;

        if ((_request("id"))) {
            if (ereg('CLASSE_([0-9]+)', _request("id"), $regs))
                $classe = $regs[1];
            elseif (ereg('ECOLE_([0-9]+)', _request("id"), $regs))
                $ecole = $regs[1];
            elseif (ereg('VILLE_([0-9]+)', _request("id"), $regs))
                $ville = $regs[1];
        }

        // Annuaire par défaut, on regarde sa session
        if (!$classe && !$ecole && !$ville) {
            $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');
            $home = $annuaireService->getAnnuaireHome();
            switch ($home['type']) {
                case 'BU_VILLE' :
                    $ville = $home['id'];
                    break;
                case 'BU_ECOLE' :
                    $ecole = $home['id'];
                    break;
                case 'BU_CLASSE' :
                    $classe = $home['id'];
                    break;
                default : // On prend la 1e ville
            }
        }

        if ($classe)
            return new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('annuaire||getAnnuaireClasse', array('classe' => $classe)));
        elseif ($ecole)
            return new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('annuaire||getAnnuaireEcole', array('ecole' => $ecole)));
        elseif ($ville)
            return new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('annuaire||getAnnuaireVille', array('ville' => $ville)));
        else
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noGrville'), 'back' => CopixUrl::get('annuaire||')));
    }

    /**
     * Affichage d'un annuaire de ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/17
     * @param integer $ville Id de la ville
     * @todo Positionner grville selon $rVille
     */
    public function getAnnuaireVille()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/module_fichesecoles.js');
        CopixHTMLHeader::addCSSLink(_resource("styles/module_fichesecoles.css"));

        $ville = _request("ville") ? _request("ville") : NULL;
        $grville = 1;
        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');
        $criticErrors = array();

        $rVille = Kernel::getNodeInfo('BU_VILLE', $ville, false);

        $matrix = & enic::get('matrixCache');

        if (!$rVille)
            $criticErrors[] = CopixI18N::get('annuaire|annuaire.error.noVille');
        elseif (!$matrix->ville($ville)->_right->count->voir > 0)
            $criticErrors[] = CopixI18N::get('kernel|kernel.error.noRights');
        elseif (Kernel::getKernelLimits('ville') && !in_array($ville, Kernel::getKernelLimits('ville_as_array')))
            $criticErrors[] = CopixI18N::get('annuaire|annuaire.error.noVille');

        if ($criticErrors)
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => implode('<br/>', $criticErrors), 'back' => CopixUrl::get('annuaire||')));

        // Blog de la ville
        $blog = getNodeBlog('BU_VILLE', $ville, array('is_public' => 1));
        if ($blog)
            $rVille['blog'] = CopixUrl::get('blog||', array('blog' => $blog->url_blog));

        $ecoles = $annuaireService->getEcolesInVille($ville, array('droit' => 'voir', 'directeur' => true));
        $agents = $annuaireService->getAgentsInVille($ville, array('droit' => 'voir'));
        $agents = $annuaireService->checkVisibility($agents);

        $tplListe = new CopixTpl ();

        $canWrite_USER_VIL = $matrix->ville($ville)->_right->USER_VIL->communiquer;
        $tplListe->assign('canWrite_USER_VIL', $canWrite_USER_VIL);

        //print_r($rVille);
        // On cherche les blogs
        foreach ($ecoles as $k => $e) {
            $blog = getNodeBlog('BU_ECOLE', $e['id'], array('is_public' => 1));
            if ($blog)
                $ecoles[$k]['blog'] = CopixUrl::get('blog||', array('blog' => $blog->url_blog));
            // On zappe le site web
            $ecoles[$k]['web'] = NULL;
        }

        //foreach ($result AS $key=>$value) {


        $tplListe->assign('ecoles', $ecoles);
        $tplListe->assign('agents', $agents);
        $tplListe->assign('ville', $rVille);
        $tplListe->assign('grville', $grville);
        $tplListe->assign('kernel_ville_as_array', Kernel::getKernelLimits('ville_as_array'));
        $result = $tplListe->fetch("getannuaireville.tpl");

        $tpl = new CopixTpl ();
        $tpl->assign('TITLE_PAGE', $rVille["nom"]);

        $menu = array();
        if( ! CopixConfig::exists('|can_group_showlist') || CopixConfig::get('|can_group_showlist') ) {
        $menu[] = array('txt' => CopixI18N::get('groupe|groupe.annuaire'), 'url' => CopixUrl::get('groupe||getListPublic'), 'size' => '110');
        }
        $menu[] = array('txt' => CopixI18N::get('public|public.blog.annuaire'), 'url' => CopixUrl::get('public||getListBlogs'));
        $tpl->assign('MENU', $menu);

        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Affichage d'un annuaire d'école
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $ecole Id de l'école
     */
    public function getAnnuaireEcole()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/module_fichesecoles.js');
        CopixHTMLHeader::addCSSLink(_resource("styles/module_fichesecoles.css"));

        $ecole = _request("ecole") ? _request("ecole") : NULL;

        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');
        $fichesEcolesService = & CopixClassesFactory::Create('fichesecoles|FichesEcolesService');
        $criticErrors = array();

        $rEcole = Kernel::getNodeInfo('BU_ECOLE', $ecole, false);
        //print_r($rEcole);

        $matrix = & enic::get('matrixCache');

        if (!$rEcole)
            $criticErrors[] = CopixI18N::get('annuaire|annuaire.error.noEcole');
        elseif (!$matrix->ecole($ecole)->_right->count->voir > 0)
            $criticErrors[] = CopixI18N::get('kernel|kernel.error.noRights');

        if ($criticErrors)
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => implode('<br/>', $criticErrors), 'back' => CopixUrl::get('annuaire||')));

        $tplListe = new CopixTpl ();
        //$tplListe->assign ('ecoles', $ecoles);
        // Blog de l'école
        $blog = getNodeBlog('BU_ECOLE', $ecole, array('is_public' => 1));
        if ($blog)
            $rEcole['blog'] = CopixUrl::get('blog||', array('blog' => $blog->url_blog));

        //print_r($rEcole);
        //On se place sur la 1e classe
// BOOST 3s
//$start = microtime(true);
        $classes = $annuaireService->getClassesInEcole($ecole);
        //print_r($classes);
//echo "getClassesInEcole : ".(microtime(true)-$start)."<br />";
//echo "<pre>"; print_r($classes); die();
        if ($classes && $classes[0]['id']) {
            $rClasse = Kernel::getNodeInfo('BU_CLASSE', $classes[0]['id'], false);
            $tplListe->assign('infosclasse', CopixZone::process('annuaire|infosclasse', array('rClasse' => $rClasse)));
        }

// BOOST 3s
//$start = microtime(true);
        $tplListe->assign('infosecole', CopixZone::process('annuaire|infosecole', array('rEcole' => $rEcole, 'classes' => $classes)));
//echo "zone infosecole : ".(microtime(true)-$start)."<br />";

        $result = $tplListe->fetch('getannuaireecole.tpl');

        $tpl = new CopixTpl ();
        $tpl->assign('TITLE_PAGE', $rEcole["nom"] . " (" . $rEcole["desc"] . ")");
        $menu = array();
        if( ! CopixConfig::exists('|can_group_showlist') || CopixConfig::get('|can_group_showlist') ) {
        $menu[] = array('txt' => CopixI18N::get('groupe|groupe.annuaire'), 'url' => CopixUrl::get('groupe||getListPublic'), 'size' => '110');
        }
        $menu[] = array(
            'url' => CopixUrl::get('public||getListBlogs'),
            'txt' => CopixI18N::get('public|public.blog.annuaire'),
        );
        if ($fichesEcolesService->canMakeInFicheEcole($ecole, 'VIEW'))
            $menu[] = array(
                'url' => CopixUrl::get('fichesecoles||fiche', array('id' => $ecole)),
                'txt' => CopixI18N::get('annuaire|annuaire.fiche'),
            );
        $menu[] = array(
            'url' => CopixUrl::get('|getAnnuaireVille', array('ville' => $rEcole['ALL']->vil_id_vi)),
            'txt' => CopixI18N::get('annuaire|annuaire.backVille'),
        );

        $tpl->assign('MENU', $menu);
        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Affichage d'un annuaire de classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $classe Id de la classe
     */
    public function getAnnuaireClasse()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        $classe = _request("classe") ? _request("classe") : NULL;

        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');
        $criticErrors = array();

        $rClasse = Kernel::getNodeInfo('BU_CLASSE', $classe, false);

        $matrix = & enic::get('matrixCache');

        if (!$rClasse)
            $criticErrors[] = CopixI18N::get('annuaire|annuaire.error.noClasse');
        elseif (!$matrix->classe($classe)->_right->count->voir > 0)
            $criticErrors[] = CopixI18N::get('kernel|kernel.error.noRights');

        if ($criticErrors)
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => implode('<br/>', $criticErrors), 'back' => CopixUrl::get('annuaire||')));

        CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/module_fichesecoles.js');

        // Si c'est le détail d'une classe, on en déduit l'école
        $parent = Kernel::getNodeParents('BU_CLASSE', $classe);
        if ($parent[0]['type'] == 'BU_ECOLE')
            $ecole = $parent[0]['id'];

        $rEcole = Kernel::getNodeInfo('BU_ECOLE', $ecole, false);

        // Blog de l'école
        $blog = getNodeBlog('BU_ECOLE', $ecole, array('is_public' => 1));
        if ($blog)
            $rEcole['blog'] = CopixUrl::get('blog||', array('blog' => $blog->url_blog));

        $tplListe = new CopixTpl ();

        $tplListe->assign('infosecole', CopixZone::process('annuaire|infosecole', array('rEcole' => $rEcole)));
        $tplListe->assign('infosclasse', CopixZone::process('annuaire|infosclasse', array('rClasse' => $rClasse)));

        $tplListe->assign('classe', $rClasse);
        $result = $tplListe->fetch('getannuaireclasse.tpl');

        //print_r($rEcole);

        $tpl = new CopixTpl ();
        $tpl->assign('TITLE_PAGE', $rClasse["nom"]);

        $menu = array();
        if( ! CopixConfig::exists('|can_group_showlist') || CopixConfig::get('|can_group_showlist') ) {
        $menu[] = array('txt' => CopixI18N::get('groupe|groupe.annuaire'), 'url' => CopixUrl::get('groupe||getListPublic'), 'size' => '110');
        }
        $menu[] = array('txt' => CopixI18N::get('public|public.blog.annuaire'), 'url' => CopixUrl::get('public||getListBlogs'));
        $menu[] = array('txt' => CopixI18N::get('annuaire|annuaire.backEcole'), 'url' => CopixUrl::get('|getAnnuaireEcole', array('ecole' => $ecole)));
        $menu[] = array('txt' => CopixI18N::get('annuaire|annuaire.backVille'), 'url' => CopixUrl::get('|getAnnuaireVille', array('ville' => $rEcole['ALL']->vil_id_vi)));

        $tpl->assign('MENU', $menu);
        $tpl->assign('MAIN', $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Affichage d'une fiche détaillée d'un utilisateur. Appellé en Ajax
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/04/06
     * @param string $type Type de personne (USER_ELE, USER_ELE...)
     * @param integer $id Id de la personne
     */
    public function getUserProfil()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        $type = _request('type') ? _request('type') : NULL;
        $id = _request('id') ? _request('id') : NULL;

        $tpl = new CopixTpl ();
        $tpl->assign('zone', CopixZone::process('annuaire|getUserProfil', array('type' => $type, 'id' => $id)));
        $result = $tpl->fetch('getuser.tpl');

        //$tpl->assign ('MAIN', $result);
        header('Content-type: text/html; charset=utf-8');
        //echo utf8_encode($result);
        echo $result;

        return new CopixActionReturn(COPIX_AR_NONE, 0);
    }

    /**
     * Affichage de l'annuaire en version popup
     *
     * Affiche les discussions d'un forum et les informations sur les discussions (titre, dernier message...), avec un lien pour lire chaque discussion.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     */
    public function getPopup()
    {
        if (!Kernel::is_connected())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('annuaire|annuaire.error.noLogged'), 'back' => CopixUrl::get('||')));

        CopixHTMLHeader::addJSLink(_resource("js/iconito/module_annuaire_popup.js"));
        CopixHTMLHeader::addJSLink(_resource("js/jquery/jquery.tablesorter.min.js"));
        CopixHTMLHeader::addJSLink(_resource("js/jquery/jquery.metadata.js"));
        CopixHTMLHeader::addCSSLink(_resource("js/jquery/css/jquery.tablesorter.css"));

        $grville = _request('grville') ? _request('grville') : NULL;
        $ville = _request('ville') ? _request('ville') : NULL;
        $ecole = _request('ecole') ? _request('ecole') : NULL;
        $classe = _request('classe') ? _request('classe') : NULL;
        $field = _request('field') ? _request('field') : '';
        $profils = _request('profils') ? _request('profils') : array();
        $profil = $this->getRequest('profil'); // Si on force sur un profil unique a afficher

        $ALL = CopixConfig::get('annuaire|annu_combo_all');

        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');

        // Annuaire par défaut, on regarde sa session
        if (!$classe && !$ecole && !$ville) {
            $home = $annuaireService->getAnnuaireHome();
            //print_r($home);
            switch ($home['type']) {
                case 'BU_GRVILLE' :
                    $grville = $home['id'];
                    $ville = $ALL;
                    $ecole = $ALL;
                    $classe = $ALL;
                    break;
                case 'BU_VILLE' :
                    $info = Kernel::getNodeInfo($home['type'], $home['id']);
                    if ($info) {
                        $grville = $info['ALL']->vil_id_grville;
                    }
                    $ville = $home['id'];
                    $ecole = $ALL;
                    $classe = $ALL;
                    break;
                case 'BU_ECOLE' :
                    $info = Kernel::getNodeInfo($home['type'], $home['id']);
                    if ($info) {
                        $grville = $info['ALL']->vil_id_grville;
                        $ville = $info['ALL']->eco_id_ville;
                    }
                    $ecole = $home['id'];
                    $classe = $ALL;
                    break;
                case 'BU_CLASSE' :
                    $info = Kernel::getNodeInfo($home['type'], $home['id']);
                    //_dump($info);
                    if ($info) {
                        $grville = $info['parent']['ALL']->vil_id_grville;
                        $ville = $info['parent']['ALL']->eco_id_ville;
                        $ecole = $info['parent']['id'];
                    }
                    $classe = $home['id'];
                    //echo "grville=$grville / ville=$ville / ecole=$ecole / classe=$classe";
                    break;
            }
        }

        $comboEcoles = $comboClasses = true;
        // On force les valeurs des combos
        if ($profil) {
            switch ($profil) {
                case 'USER_VIL':
                    $comboEcoles = $comboClasses = false;
                    $ecole = $classe = $ALL;
                    break;
            }
        }

        $matrix = & enic::get('matrixCache');
        $helper = & enic::get('matrixHelpers');


        $right = _request('right', 'voir'); // voir ou communiquer

        $iCan = ('communiquer' == $right) ? 'iCanTalkToThisType' : 'iCanSeeThisType';

        $tplListe = new CopixTpl ();
        $visib = array(
            'USER_ELE' => $helper->$iCan('USER_ELE'),
            'USER_ENS' => $helper->$iCan('USER_ENS') || $helper->$iCan('USER_DIR'),
            'USER_RES' => $helper->$iCan('USER_RES'),
            'USER_EXT' => $helper->$iCan('USER_EXT'),
            'USER_ADM' => $helper->$iCan('USER_ADM'),
            'USER_VIL' => $helper->$iCan('USER_VIL'),
        );
        //_dump($visib);



        $debug = false;

        $start = microtime(true);
        $tplListe->assign('combogrvilles', CopixZone::process('annuaire|combogrvilles', array('droit' => $right, 'value' => $grville, 'fieldName' => 'grville', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_grville(this,this.form);"', 'linesSup' => array())));
        if ($debug)
            echo "combogrvilles " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";



        $start = microtime(true);
        $tplListe->assign('combovilles', CopixZone::process('annuaire|combovilles', array('droit' => $right, 'grville' => $grville, 'value' => $ville, 'fieldName' => 'ville', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_ville(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllVilles'))))));
        if ($debug)
            echo "combovilles " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";

        $start = microtime(true);
        if ($ville == $ALL && $comboEcoles) {
            $tplListe->assign('comboecoles', CopixZone::process('annuaire|comboecolesingrville', array('droit' => $right, 'grville' => $grville, 'value' => $ecole, 'fieldName' => 'ecole', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_ecole(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllEcoles'))))));
            if ($debug)
                echo "comboecolesingrville " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        } elseif ($comboEcoles) {
            $tplListe->assign('comboecoles', CopixZone::process('annuaire|comboecolesinville', array('droit' => $right, 'ville' => $ville, 'value' => $ecole, 'fieldName' => 'ecole', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_ecole(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllEcoles'))))));
            if ($debug)
                echo "comboecolesinville " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        }

        $start = microtime(true);
        if ($ville == $ALL && $ecole == $ALL && $comboClasses) {
            $tplListe->assign('comboclasses', CopixZone::process('annuaire|comboclassesingrville', array('droit' => $right, 'grville' => $grville, 'value' => $classe, 'fieldName' => 'classe', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllClasses'))))));
            if ($debug)
                echo "comboclassesingrville " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        } elseif ($ecole == $ALL && $comboClasses) {
            $tplListe->assign('comboclasses', CopixZone::process('annuaire|comboclassesinville', array('droit' => $right, 'ville' => $ville, 'value' => $classe, 'fieldName' => 'classe', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllClasses'))))));
            if ($debug)
                echo "comboclassesinville " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        } elseif ($ecole && $comboClasses) {
            $tplListe->assign('comboclasses', CopixZone::process('annuaire|comboclassesinecole', array('droit' => $right, 'ecole' => $ecole, 'value' => $classe, 'fieldName' => 'classe', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup' => array(0 => array('value' => $ALL, 'libelle' => CopixI18N::get('annuaire|annuaire.comboAllClasses'))))));
            if ($debug)
                echo "comboclassesinecole " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        } elseif ($comboClasses) {
            $tplListe->assign('comboclasses', CopixZone::process('annuaire|comboempty', array('fieldName' => 'classe', 'attribs' => 'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"')));
            if ($debug)
                echo "comboempty " . date("H:i:s") . " " . (microtime(true) - $start) . "<br />";
        }


        if (($ville_as_array = Kernel::getKernelLimits('ville_as_array'))) { // Limitation par URL, on verifie les parametres
            if ($ville && $ville != $ALL && !in_array($ville, $ville_as_array)) {
                $ville = 0;
            }
            if ($ecole && $ecole != $ALL && ($rEcole = Kernel::getNodeInfo('BU_ECOLE', $ecole, false)) && !in_array($rEcole['ALL']->vil_id_grville, $ville_as_array)) {
                $ecole = 0;
            }
            if ($classe && $classe != $ALL && ($rClasse = Kernel::getNodeInfo('BU_CLASSE', $classe, false)) && !in_array($rClasse['ALL']->eco_id_ville, $ville_as_array)) {
                $classe = 0;
            }
        }

        //kernel::myDebug ("grville=$grville / ville=$ville / ecole=$ecole / classe=$classe");
        //kernel::myDebug ($profils);




        if ($classe && $classe !== $ALL) { // Une classe précise
            $visib['USER_ELE'] = ($matrix->classe($classe)->_right->USER_ELE->$right);
            $visib['USER_ENS'] = ($matrix->classe($classe)->_right->USER_ENS->$right || $matrix->classe($classe)->_right->USER_DIR->$right);
            $visib['USER_RES'] = ($matrix->classe($classe)->_right->USER_RES->$right);
            $visib['USER_ADM'] = ($matrix->classe($classe)->_right->USER_ADM->$right);
            $visib['USER_EXT'] = ($matrix->classe($classe)->_right->USER_EXT->$right);;
            $visib['USER_VIL'] = ($matrix->classe($classe)->_right->USER_VIL->$right);
        } elseif ($ecole && $classe == $ALL && $ecole !== $ALL) { // Une école
            $visib['USER_ELE'] = ($matrix->ecole($ecole)->_right->USER_ELE->$right);
            $visib['USER_ENS'] = ($matrix->ecole($ecole)->_right->USER_ENS->$right || $matrix->ecole($ecole)->_right->USER_DIR->$right);
            $visib['USER_RES'] = ($matrix->ecole($ecole)->_right->USER_RES->$right);
            $visib['USER_ADM'] = ($matrix->ecole($ecole)->_right->USER_ADM->$right);
            $visib['USER_EXT'] = ($matrix->ecole($ecole)->_right->USER_EXT->$right);
            $visib['USER_VIL'] = ($matrix->ecole($ecole)->_right->USER_VIL->$right);
        } elseif ($ville && $classe == $ALL && $ecole == $ALL && $ville !== $ALL) { // Une ville
            $visib['USER_ELE'] = ($matrix->ville($ville)->_right->USER_ELE->$right);
            $visib['USER_ENS'] = ($matrix->ville($ville)->_right->USER_ENS->$right || $matrix->ville($ville)->_right->USER_DIR->$right);
            $visib['USER_RES'] = ($matrix->ville($ville)->_right->USER_RES->$right);
            $visib['USER_ADM'] = ($matrix->ville($ville)->_right->USER_ADM->$right);
            $visib['USER_EXT'] = ($matrix->ville($ville)->_right->USER_EXT->$right);
            $visib['USER_VIL'] = ($matrix->ville($ville)->_right->USER_VIL->$right);
        } elseif ($grville && $classe == $ALL && $ecole == $ALL && $ville == $ALL) { // Un groupe de villes
            $visib['USER_ELE'] = ($matrix->grville($grville)->_right->USER_ELE->$right);
            $visib['USER_ENS'] = ($matrix->grville($grville)->_right->USER_ENS->$right || $matrix->grville($grville)->_right->USER_DIR->$right);
            $visib['USER_RES'] = ($matrix->grville($grville)->_right->USER_RES->$right);
            $visib['USER_ADM'] = ($matrix->grville($grville)->_right->USER_ADM->$right);
            $visib['USER_EXT'] = ($matrix->grville($grville)->_right->USER_EXT->$right);
            $visib['USER_VIL'] = ($matrix->grville($grville)->_right->USER_VIL->$right);
        }

        //_dump($visib);


        // Si on restreint a un profil
        if ($profil && $visib[$profil]) {
            switch ($profil) {
                case 'USER_VIL':
                    $profils = array();
                    $profils['VIL'] = 1;
                    break;
            }
        }

        if (!$profils && $visib['USER_ELE'])
            $profils['ELE'] = 1;
        elseif (!$profils && $visib['USER_ENS'])
            $profils['PEC'] = 1;
        elseif (!$profils && $visib['USER_RES'])
            $profils['PAR'] = 1;
        elseif (!$profils && $visib['USER_EXT'])
            $profils['EXT'] = 1;
        elseif (!$profils && $visib['USER_ADM'])
            $profils['ADM'] = 1;
        elseif (!$profils && $visib['USER_VIL'])
            $profils['VIL'] = 1;

        //kernel::myDebug($visib);
        // =============== ELEVES =========================
        $eleves = array();
        if (isset($profils['ELE']) && $grville && $ville && $ecole && $classe && $visib['USER_ELE']) {
            if ($classe != $ALL) // Une classe précise
                $eleves = $annuaireService->getEleves('BU_CLASSE', $classe);
            elseif ($classe == $ALL && $ecole != $ALL) // Les eleves d'une école
                $eleves = $annuaireService->getEleves('BU_ECOLE', $ecole);
            elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les eleves d'une ville
                $eleves = $annuaireService->getEleves('BU_VILLE', $ville);
            elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les eleves d'un groupe de villes
                $eleves = $annuaireService->getEleves('BU_GRVILLE', $grville);
        }

        // =============== PERSONNEL =========================
        $personnel = array();
        if (isset($profils['PEC']) && $grville && $ville && $ecole && $classe && $visib['USER_ENS']) {
            if ($classe != $ALL) // Une classe précise
                $personnel = $annuaireService->getPersonnel('BU_CLASSE', $classe);
            elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une école
                $personnel = $annuaireService->getPersonnel('BU_ECOLE', $ecole);
            elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
                $personnel = $annuaireService->getPersonnel('BU_VILLE', $ville);
            elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
                $personnel = $annuaireService->getPersonnel('BU_GRVILLE', $grville);
        }

        // =============== PARENTS =========================
        $parents = array();
        if (isset($profils['PAR']) && $grville && $ville && $ecole && $classe && $visib['USER_RES']) {
            if ($classe != $ALL) // Une classe précise
                $parents = $annuaireService->getParents('BU_CLASSE', $classe);
            elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une école
                $parents = $annuaireService->getParents('BU_ECOLE', $ecole);
            elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
                $parents = $annuaireService->getParents('BU_VILLE', $ville);
            elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
                $parents = $annuaireService->getParents('BU_GRVILLE', $grville);
        }

        // =============== PERSONNEL ADMINISTRATIF =========================
        $adm = array();
        if (isset($profils['ADM']) && $grville && $ville && $ecole && $classe && $visib['USER_ADM']) {
            if (($classe != $ALL || $classe == $ALL) && $ecole != $ALL) // Les classes d'une école
                $adm = $annuaireService->getPersonnelAdm('BU_ECOLE', $ecole);
            elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
                $adm = $annuaireService->getPersonnelAdm('BU_VILLE', $ville);
            elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
                $adm = $annuaireService->getPersonnelAdm('BU_GRVILLE', $grville);
        }

        // =============== PERSONNEL EXTERIEUR =========================
        $ext = array();
        if (isset($profils['EXT']) && $grville && $ville && $ecole && $classe && $visib['USER_EXT']) {
            $ext = $annuaireService->getPersonnelExt('ROOT', 0);
        }

        // =============== PERSONNEL VILLE =========================
        $vil = array();
        if (isset($profils['VIL']) && $grville && $ville && $visib['USER_VIL']) {
            if ($ville != $ALL) // Dans une ville
                $vil = $annuaireService->getPersonnelVil('BU_VILLE', $ville);
            elseif ($ville == $ALL) // Dans un groupe de villes
                $vil = $annuaireService->getPersonnelVil('BU_GRVILLE', $grville);
        }

        $droits = array(
            'checkAll' => $annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL'),
        );


        $users = array();

        foreach ($eleves as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        foreach ($parents as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        foreach ($personnel as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        foreach ($adm as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        foreach ($vil as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        foreach ($ext as $user) {
            $users[$user->bu_type.'-'.$user->bu_id] = $user;
        }
        //_dump($eleves);

        /*
        if ('communiquer' === $right) {
            foreach ($users as $k => $user) {
                //print_r($user);
                //$matrix->communiquer();
            }
        }
        */

        usort($users, array('ActionGroupAnnuaire', '_usortPopup'));

        $tplListe->assign('field', $field);
        $tplListe->assign('grville', $grville);
        $tplListe->assign('eleves', $eleves);
        $tplListe->assign('personnel', $personnel);
        $tplListe->assign('parents', $parents);
        $tplListe->assign('ext', $ext);
        $tplListe->assign('adm', $adm);
        $tplListe->assign('vil', $vil);
        $tplListe->assign('profils', $profils);
        $tplListe->assign('droits', $droits);
        $tplListe->assign('ville', $ville);
        $tplListe->assign('ecole', $ecole);
        $tplListe->assign('classe', $classe);
        $tplListe->assign('visib', $visib);
        $tplListe->assign('profil', $profil);
        $tplListe->assign('users', $users);
        $tplListe->assign('right', $right);
        $result = $tplListe->fetch('getpopup.tpl');

        $ppo = new CopixPPO ();
        $ppo->result = $result;
        $ppo->TITLE_PAGE = CopixI18N::get('annuaire|annuaire.moduleDescription');
        CopixHTMLHeader::addJSLink(_resource("js/iconito/module_annuaire.js"));

        return _arPPO($ppo, array('template' => 'getpopup_ppo.tpl', 'mainTemplate' => 'default|main_popup.php'));
    }

    /**
     * Tri des users dans la vue popup
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/07/17
     * @see http://php.net/manual/fr/function.usort.php
     * @param object $a User A
     * @param object $a User B
     * @return integer
     */
    private function _usortPopup($a, $b)
    {
        if ($a->nom === $b->nom) {
            return strcmp($a->prenom, $b->prenom);
        }
        return strcmp($a->nom, $b->nom);
    }

}

