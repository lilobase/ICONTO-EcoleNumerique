<?php

/*
  @file 	dashboard.actiongroup.php
  @desc		Dashboard constructor
  @version 	1.0.2
  @date 	2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
  @author 	S.HOLTZ <sholtz@cap-tic.fr>

  Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
 */


_classInclude('welcome|welcome');
_classInclude('logs|logs');

class ActionGroupDashboard extends enicActionGroup
{
    public function __construct()
    {
        $this->picturesPath = COPIX_VAR_PATH . 'data/admindash/photos/';
        $this->thumbX = 150;
        $this->thumbY = 300;
        parent::__construct();
    }

    public function processDefault()
    {
        $tpl = new CopixTpl ();
        $tplModule = new CopixTpl ();

        if (!$this->user->connected) {
            return new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('welcome|default|'));
        }

        $tpl->assign('TITLE_PAGE', CopixI18N::get('kernel.title.accueilsimple'));

        $nodes_all = Kernel::getNodeParents($this->user->type, $this->user->idEn);
        $nodes_all = Kernel::sortNodeList($nodes_all);
        // _dump($nodes_all);

        $nodes = array();
        foreach ($nodes_all AS $node) {

            if ($node['type'] == 'CLUB' && CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance = CopixConfig::get('kernel|groupeAssistance')) && $node['id'] == $groupeAssistance) {
                continue;
            }

            if ($node['type'] == 'CLUB' && $node['droit'] < 20) {
                continue;
            }

            if (!isset($nodes[$node['type']]))
                $nodes[$node['type']] = array();

            //module not initialized : loaded into inconito
            if (!isset($nodes[$node['type']][$node['id']])) {
                $nodes[$node['type']][$node['id']] = $node;
                Kernel::createMissingModules($node['type'], $node['id']);
                $nodes[$node['type']][$node['id']]['modules'] = Kernel::getModEnabled($node['type'], $node['id'], $this->user->type, $this->user->idEn);

                // Cas des groupes : on ajoute les membres et admin, selon les droits
                if ($node['type'] == 'CLUB') {

                    $addModule = new CopixPPO ();
                    $addModule->node_type = $node['type'];
                    $addModule->node_id = $node['id'];
                    $addModule->module_type = 'MOD_ADMIN';
                    $addModule->module_id = 0;
                    $addModule->module_nom = CopixI18N::get('groupe|groupe.group.admin');
                    $nodes[$node['type']][$node['id']]['modules'][] = $addModule;
                }
                /*
                 * ===== CONTENT GENERATION =====
                 *
                 */

                //cas parent élève
                if ($node['type'] == 'USER_ELE') {
                    $contentNode = Kernel::getNodeParents($node['type'], $node['id']);
                    $contentNode = Kernel::filterNodeList($contentNode, 'BU_CLASSE');
                    if (empty($contentNode))
                        continue;
                    $contentNode = $contentNode[0];
                }else {
                    $contentNode = $node;
                }

                //get content from db :
                $content = $this->db->query('SELECT * FROM module_admindash WHERE id_zone = ' . $contentNode['id'] . ' AND type_zone = "' . $contentNode['type'] . '"')->toArray1();

                // Get vocabulary catalog to use
                $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
                $vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($contentNode['type'], $contentNode['id']);

                //if no content : get default content
                if (empty($content['content'])) {
                    switch ($contentNode['type']) {
                        case 'BU_CLASSE':
                        case 'USER_ELE':
                            $content['content'] = CopixZone::process('kernel|dashboardClasse', array('idZone' => $contentNode['id'], 'catalog' => $vocabularyCatalog->id_vc));
                            break;
                        case 'BU_ECOLE':
                            $content['content'] = CopixZone::process('kernel|dashboardEcole', array('idZone' => $contentNode['id'], 'catalog' => $vocabularyCatalog->id_vc));
                            break;
                        case 'BU_VILLE':
                            $content['content'] = CopixZone::process('kernel|dashboardVille', array('idZone' => $contentNode['id'], 'catalog' => $vocabularyCatalog->id_vc));
                            break;
                        case 'CLUB':
                            $content['content'] = CopixZone::process('kernel|dashboardGrTravail', array('idZone' => $contentNode['id'], 'catalog' => $vocabularyCatalog->id_vc));
                            break;
                        case 'ROOT':
                            if ($contentNode['droit'] >= 60) {
                                $contentTpl = new CopixTpl();
                                $content['content'] = $contentTpl->fetch('zone.dashboard.root.tpl');
                            } else {
                                $contentTpl = new CopixTpl();
                                $content['content'] = $contentTpl->fetch('zone.dashboard.userext.tpl');
                            }
                            break;
                        default:
                            $content['content'] = '';
                            break;
                    }
                }

                //twitter integration
                $twitterSrc = '';
                if(!empty($content['social_stream'])){
                    enic::to_load('social');
                    $twitter = new enicSocialTwitter();
                    $twitter->setUserName($content['social_stream']);
                    $twitter->setThemeByContext($contentNode['type']);
                    $twitterSrc = $twitter->printSource();
                }


                //is admin :
                $is_admin = ($contentNode['droit'] >= 60);

                //build html content
                $content_tpl = new CopixTpl();
                $content_tpl->assign('content', $content['content']);
                if (!empty($content['picture'])) {
                    $content_tpl->assign('picture', $content['picture']);
                }
                $content_tpl->assign('twitter', $twitterSrc);
                $content_tpl->assign('is_admin', $is_admin);
                $content_tpl->assign('id', $contentNode['id']);
                $content_tpl->assign('type', $contentNode['type']);
                $content_tpl->assign('catalog', $vocabularyCatalog->id_vc);
                if ($contentNode['type'] == "BU_ECOLE") {
                    $content_tpl->assign('idZone', $contentNode['id']);
                }

                $content = $content_tpl->fetch('dashboard.nodes.tpl');

                //add css
                $this->addCss('styles/dashboard_zone.css');
                //free memory
                unset($content_tpl);
                /*
                 *  ===== END CONTENT GENERATION =====
                 */

                $nodes[$node['type']][$node['id']]['content'] = $content;
            } elseif ($nodes[$node['type']][$node['id']]['droit'] < $node['droit'])
                $nodes[$node['type']][$node['id']] = $node;
        }


        /* DRAFT WORKING */
        // _dump($nodes);
        /* $rClasse = Kernel::getNodeInfo ('BU_CLASSE', $nodes['BU_CLASSE'][1]['id'], false);
          CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse)); */
        //echo $this->matrix->display();
        // _dump($nodes);

        $tplModule->assign("nodes", $nodes);

        if (count($nodes) == 0) {
            $result = $tplModule->fetch("dashboard-empty.tpl");
        } else {
            $result = $tplModule->fetch("dashboard.tpl");
        }

        $tpl->assign('MAIN', $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Acces direct a un module
     *
     * Fonction generique d'acces a un module depuis un noeud.
     *
     * @author Stephane Holtz <sholtz@cap-tic.fr>
     */
    public function go()
    {
        // NOTIFICATIONS : Préparation de l'enregistrement de la visite. Le module_id est à completer avant enregistrement.
        $lastvisit = _record("kernel|kernel_notifications_lastvisit");
        $lastvisit->user_id = $this->user->id; // id Copix
        $lastvisit->date = date('Y-m-d H:i:s');
        $lastvisit->node_type = _request("ntype");
        $lastvisit->node_id = _request("nid");
        $lastvisit->module_type = _request("mtype");
        $lastvisit->module_id = null;

        $mid = _request("mid", 0);
        if (!is_null(_request("ntype")) && !is_null(_request("nid")) && !is_null(_request("mtype"))) {
            CopixSession::set('myNode', array('type' => _request("ntype"), 'id' => _request("nid")));
            if (_request("ntype") == 'CLUB' && _request("mtype") == 'comptes' && !_request("mid")) {
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('groupe||getHomeMembers', array('id' => _request("nid"))));
            } elseif (_request("ntype") == 'CLUB' && _request("mtype") == 'admin' && !_request("mid")) {
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('groupe||getHomeAdmin', array('id' => _request("nid"))));
            } elseif (strpos(_request("ntype"), 'USER_') === false) {
                // Si on ne connait pas l'ID du module, on tente de le detecter automatiquement
                if (!$mid) {
                    $modules = Kernel::getModEnabled(_request("ntype"), _request("nid"));
                    foreach ($modules as $module) {
                        if ($module->module_type == 'MOD_' . strtoupper(_request("mtype"))) {
                            if (isset($module->module_id))
                                $mid = $module->module_id;
                            else
                                $mid = 0;
                            break;
                        }
                    }
                }
                // die('CopixUrl::get('._request("mtype").'|default|go   id='.$mid);
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '|default|go', array('id' => $mid)));
            } else {
                if ($mid)
                    $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '|default|go', array('id' => $mid)));
                else
                    $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '||'));
            }

            // NOTIFICATIONS : Enregistrement de la visite de l'utilisateur (avec suppression de doublons)
            if($mid)
                $lastvisit->module_id = $mid;
            if($lastvisit->user_id) {
                _dao ('kernel|kernel_notifications_lastvisit')->deleteBy( _daoSp ()
                    ->addCondition ('user_id', '=', $lastvisit->user_id)
                    ->addCondition ('node_type', '=', $lastvisit->node_type)
                    ->addCondition ('node_id', '=', $lastvisit->node_id)
                    ->addCondition ('module_type', '=', $lastvisit->module_type)
                    ->addCondition ('module_id', '=', $lastvisit->module_id)
                );
                _dao("kernel|kernel_notifications_lastvisit")->insert( $lastvisit );
            }

            // LOGS : Logs d'usage
            Logs::set (array(
                'type'=>'GO',
                'node_type'=>_request("ntype"), 'node_id'=>_request("nid"),
                'module_type'=>_request("mtype"), 'module_id'=>($mid?$mid:null)
            ));

            return $loadModule;
        }
        $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('||'));
        return $loadModule;
    }

    public function processModif()
    {
        //get parameters
        $id_node = (int) $this->request('node_id');
        $type_node = $this->request('node_type');

        //check parameters
        if(empty($id_node) || empty($type_node))
            return $this->error('kernel|dashboard.admin.badOperation');

        //check right
        if (Kernel::getLevel($type_node, $id_node) < 60)
            return $this->error('kernel|dashboard.admin.noRight');

        //set flash
        $this->flash->set('dashboardType', $type_node, 5);
        $this->flash->set('dashboardId', $id_node, 5);

        //get content
        $content = $this->db->query('SELECT * FROM module_admindash WHERE id_zone = ' . $id_node . ' AND type_zone = ' . $this->db->quote($type_node))->toArray1();

        //if new content
        if(empty($content))
            $content = array('social_stream' => '', 'content' => '');

        $this->addCss('styles/module_admindash.css');
        $ppo = new CopixPPO();
        $ppo->content = $content;
        $ppo->errors = ($this->flash->has('errors')) ? $this->flash->errors : null;
        $editorOptions = array(
            'field'=>'content_txt',
            'format' => 'fckeditor',
            'content'=>$content['content'],
            'options' => array(),
            'object'=>array(
                'type'=> $type_node,
                'id'=> $id_node),
            'height'=>200,
            'width' => 540
        );
        $ppo->editor = 	CopixZone::process ('kernel|edition', $editorOptions);

        return _arPPO($ppo, 'dashboard.admin.tpl');
    }

    public function processEreg()
    {
        //check flash
        if(!isset($this->flash->dashboardType) || !isset($this->flash->dashboardId))
            return $this->error('kernel|dashboard.admin.badOperation');

        $node_id = $this->flash->dashboardId;
        $node_type = $this->flash->dashboardType;

        //check security
        if (Kernel::getLevel($node_type, $node_id) < 60)
            return $this->error('kernel|dashboard.admin.noRight');

        $datas['content'] = $this->db->quote($this->request('content_txt'));
        $datas['social_stream'] = $this->db->quote($this->request('social_stream'));
        $datas['id_zone'] = (int)$node_id;
        $datas['type_zone'] = $this->db->quote($node_type);

        $this->db->createOrUpdate('module_admindash', $datas);

        //go to processModif
        return $this->helpers->go('||');
    }

    public function processDelete()
    {
        //check flash
        if(!isset($this->flash->dashboardType) || !isset($this->flash->dashboardId))
            return $this->error('kernel|dashboard.admin.badOperation');

        $node_id = $this->flash->dashboardId;
        $node_type = $this->flash->dashboardType;

        //check security
        if (Kernel::getLevel($node_type, $node_id) < 60)
            return $this->error('kernel|dashboard.admin.noRight');

        //delete records
        $this->db->update('module_admindash', array('content' => "''"), 'id_zone = '.$node_id.' AND type_zone = '.$this->db->quote($node_type));

        //go to index
        return $this->go('||');
    }

    public function processImage()
    {
        if (!$this->istyReq('id'))
            header("HTTP/1.0 404 Not Found");
        else {
            $id = (int) $this->request('id');

            //get pic name :
            $pic = $this->db->query('SELECT picture FROM module_admindash WHERE id = ' . $id)->toString();
            if (!file_exists($this->picturesPath . $pic)) {
                header("HTTP/1.0 404 Not Found");
            } else {

                $ext = explode('.', $pic);
                $ext = strtolower($ext[count($ext) - 1]);
                header("Content-Type: image/" . $ext);
                readfile($this->picturesPath . $pic, 'r+');
            }
        }
        return new CopixActionReturn(COPIX_AR_NONE, 0);
    }

}
