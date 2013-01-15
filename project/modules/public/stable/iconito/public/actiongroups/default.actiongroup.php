<?php

/**
 * Actiongroup frontoffice du module Public
 *
 * @package Iconito
 * @subpackage Public
 */
class ActionGroupDefault extends EnicActionGroup
{
    public function beforeAction()
    {
        //_currentUser()->assertCredential ('group:[current_user]');
    }

    public function processDefault()
    {
        //return _arRedirect (_url ('|getListBlogs'));
        return CopixActionGroup::process('public|default::getListBlogs');
    }

    /**
     * Affiche la liste des blogs
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/09
     * @todo Positionner $grville
     */
    public function processGetListBlogs()
    {
        if( ! CopixConfig::exists('|can_public_rssfeed') || CopixConfig::get('|can_public_rssfeed') ) {
        CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('public||rss', array()).'" type="application/rss+xml" title="'.htmlentities(CopixI18N::get ('public|public.rss.flux.title')).'" />');
        }
        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_annuaire.js');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('public|public.blog.annuaire'));

        $kw = $this->getRequest('kw', null);
        $grville = 1;

        $tplListe = new CopixTpl ();

        if ($ville_as_array = Kernel::getKernelLimits('ville_as_array')) {
            $tplListe->assign ('list', CopixZone::process ('GetListBlogs2', array('kw'=>$kw, 'ville'=>$ville_as_array)));
        } else
            $tplListe->assign ('list', CopixZone::process ('GetListBlogs2', array('kw'=>$kw, 'grville'=>$grville)));
        $tplListe->assign ('kw', $kw);

        $result = $tplListe->fetch("getlistblogs.tpl");

        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Page "a propos"
     *
     * @author Pierre-Nicolas Lapointe <pnlapointe@cap-tic.fr>
     * @since 2007/01/22
     */
    public function processAPropos()
    {
        $ppo = new CopixPPO ();
        $ppo->TITLE_PAGE = CopixI18N::get('public|public.apropos');

        $nametpl = 'apropos_' . CopixI18N::getLang() . '.html';

        return _arPPO ($ppo, $nametpl);

    }
	
	
	/**
     * Page Accessibilité
     *
     * @author Philippe Roser <proser@cap-tic.fr>
     * @since 2012/09/21
     */
    public function processAccessibilite()
    {
        $ppo = new CopixPPO ();
        $ppo->TITLE_PAGE = CopixI18N::get('public|public.nav.accessibility');

        $nametpl = 'accessibilite_'.CopixI18N::getLang().'.tpl';

        return _arPPO ($ppo, $nametpl);

    }

    /*
     * demande d'inscription
     */

    public function processGetreq()
    {
        $ecoleList = $this->db->query('SELECT * FROM kernel_bu_ecole ORDER BY `type`,nom')->toArray();

        $ppo = new CopixPPO();

        $ppo->errors = ($this->flash->has('errors')) ? $this->flash->errors : null;

        $ppo->content = ($this->flash->has('content')) ? $this->flash->content : null;

        $ppo->baseUrl = $this->url('public|default|getreqlistclasses');

        $ppo->actionUrl = $this->url('public|default|getreqereg');

        $ppo->ecoles = $ecoleList;

        return _arPPO($ppo, 'getreq.tpl');
    }

    public function processGetreqlistclasses()
    {
        $id_ecole = (int) $this->request('ecole_id');

        $classes = $this->db->query('SELECT * FROM kernel_bu_ecole_classe AS cl JOIN kernel_bu_annee_scolaire AS an ON cl.annee_scol = an.id_as WHERE an.current = 1 AND cl.is_validee = 1 AND cl.is_supprimee = 0 AND cl.ecole = ' . $id_ecole)->toArray();

        if (empty($classes)) {
            echo json_encode(array('aucune classe'));
            exit();
        }
        $oReturn = array();
        foreach ($classes as $cl) {
            $oReturn[$cl['id']] = utf8_encode($cl['nom']);
        }

        echo json_encode($oReturn);

        return _arNone();
    }

    public function processGetreqereg()
    {
        //check parent informations :
        $sub = 'parent';

        //array of required values :
        $required = array('nom', 'prenom', 'adresse', 'postal', 'city', 'teldom', 'mail');

        //errors array
        $errors = array();

        foreach ($required as $require){
            $value = $this->request($sub.$require);
            if (empty($value))
                $errors[$sub . $require] = $this->i18n('public.getreq.required');
        }

        //check childrens infos
        $sub = 'child';
        $required = array('nom', 'prenom', 'ecole', 'classe');
        $children = array();

        for ($i = 1; $i <= 4; $i++) {
            $valuei = $this->request($sub . $i . 'nom');
            $valueii = $this->request($sub . $i . 'prenom');

            if (empty($valuei) && empty($valueii) && $i != 1)
                continue;

            foreach($required as $require){
                $valueiii = $this->request($sub.$i.$require);
                if(empty($valueiii))
                    $errors[$sub.$i.$require] = $this->i18n ('public.getreq.required');
                else
                    $children[$i][$require] = $this->request ($sub.$i.$require);
            }
        }

        //if errors : go back
        if(!empty($errors)){
            $this->flash->errors = $errors;
            $this->flash->content = $_POST;

            return $this->go('public|default|getreq');
        }

        //compose informations

        //children Infos :

        $childText = '';
        foreach($children as $child){
            $childText .= 'Nom      : '.$child['nom'].PHP_EOL;
            $childText .= 'Prenom   : '.$child['prenom'].PHP_EOL;
            $childText .= 'Ecole    : '.$this->db->query('SELECT nom FROM kernel_bu_ecole WHERE numero = '.(int)$child['ecole'])->toString().PHP_EOL;
            $childText .= 'Classe   : '.$this->db->query('SELECT nom FROM kernel_bu_ecole_classe WHERE id = '.(int)$child['classe'])->toString().PHP_EOL.' ---------------- '.PHP_EOL;
        }


       //adult infos :
       $adult = array(
            'nom' => $this->request('parentnom'),
           'prenom' => $this->request('parentprenom'),
           'adresse' => $this->request('parentadresse'),
           'postal' => $this->request('parentpostal'),
           'city' => $this->request('parentcity'),
           'teldom' => $this->request('parentteldom'),
           'telpro' => $this->request('parenttelpro'),
           'mail' => $this->request('parentmail')
       );
        //
$mailContent = <<<EOT

Une nouvelle demande d'inscription à été réalisée information :

Informations lié à l'adulte :
=============================

    Nom-----------------: {$adult['nom']}
    Prenom--------------: {$adult['prenom']}
    Adresse-------------: {$adult['adresse']}
    Code Postal---------: {$adult['postal']}
    Ville---------------: {$adult['city']}
    Tel du domicile-----: {$adult['teldom']}
    Tel professionel----: {$adult['telpro']}
    Adresse Mail--------: {$adult['mail']}

Information sur l(es) enfant(s) :
=================================

$childText

~~~~~~~~~~~~~~~~~~~~~~
ce message à été généré et envoyé automatiquement par l'application ICONITO Ecole Numérique


EOT;


    //send Mail
    $mail = new CopixTextEMail('pnlabo@cap-tic.fr', '', '', utf8_decode('Nouvelle demande d\'inscription à iconito'), utf8_decode($mailContent));
    $mail->send();

    //ereg data in DB
    $dbEreg = array(
        'parent' => $this->db->quote(serialize($adult)),
        'enfants' => $this->db->quote(serialize($children)),
        'date' => time()
    );
    $this->db->create('module_getreq', $dbEreg);

    $ppo = new CopixPPO();

    return _arPPO($ppo, 'getreq.success.tpl');
}

    /**
     * Flux RSS des blogs de tout Iconito
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/11/27
     */
  public function processRss ()
  {
        $rss = CopixZone::process ('Rss');
        //echo "rss=$rss<p></p>";

        return _arContent ($rss, array ('content-type'=>CopixMIMETypes::getFromExtension ('xml')));
  }


  /**
   * Affiche la liste des ecoles
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/09/24
   */
   public function processEcoles ()
   {
    CopixHTMLHeader::addCSSLink(_resource("styles/module_fichesecoles.css"));
    $ppo = new CopixPPO();
    $ppo->ville = (int)$this->request('ville');
    $ppo->search = $this->request('search');
    $ppo->TITLE_PAGE = CopixI18N::get ('public|public.listEcoles');
    return _arPPO($ppo, 'ecoles.tpl');
    }

    public function processCache()
    {
        _classInclude('sysutils|cacheservices');
        CacheServices::clearCache ();
        CacheServices::clearConfDB ();
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('welcome||', array('cache'=>'cleared') ));
    }
}

