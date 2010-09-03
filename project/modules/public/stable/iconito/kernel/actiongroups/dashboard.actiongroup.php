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

class ActionGroupDashboard extends enicActionGroup {

    function processDefault() {

        $tpl = & new CopixTpl ();
        $tplModule = & new CopixTpl ();

        //if user is not connected :
        if (!$this->user->connected) {
            $result = $tplModule->fetch("dashboard_public.tpl");
            $tpl->assign('MAIN', $result);
            return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
        }

        $acc = (_currentUser()->getExtraHome('titre1')) ? _currentUser()->getExtraHome('titre1') : '';
        if (!empty($acc))
            $tpl->assign('TITLE_PAGE', CopixI18N::get('kernel.title.accueil', array($acc)));
        else
            $tpl->assign('TITLE_PAGE', CopixI18N::get('kernel.title.accueilsimple'));

        $nodes_all = Kernel::getNodeParents($this->user->type, $this->user->idEn);

        $nodes = array();
        foreach ($nodes_all AS $node) {

			// var_dump($node);

            if( $node['type']=='CLUB' && CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance')) && $node['id']==$groupeAssistance) { 
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
					
					/*
					$modules_copy = $nodes[$node['type']][$node['id']]['modules'];
					foreach( $modules_copy AS $mod_key => $mod_val ) {
						// _dump($mod_key);
						// _dump($mod_val);
						// if($mod_val['module_type']=='MOD_MAGICMAIL') _dump($mod_val); 
						if($mod_val->module_type=='MOD_MAGICMAIL') unset($nodes[$node['type']][$node['id']]['modules'][$mod_key]);
					}
					*/
					
					//_dump($nodes[$node['type']][$node['id']]['modules']);

                    $addModule = new CopixPPO ();
                    $addModule->node_type = $node['type'];
                    $addModule->node_id = $node['id'];
                    $addModule->module_type = 'MOD_COMPTES';
                    $addModule->module_id = 0;
                    $addModule->module_nom = CopixI18N::get('groupe|groupe.group.members');
                    $nodes[$node['type']][$node['id']]['modules'][] = $addModule;

					$groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
					$mondroit = Kernel::getLevel($node['type'], $node['id']);
					// _dump($mondroit);
					if ($groupeService->canMakeInGroupe('ADMIN', $mondroit)) {
						$addModule = new CopixPPO ();
						$addModule->node_type = $node['type'];
						$addModule->node_id = $node['id'];
						$addModule->module_type = 'MOD_ADMIN';
						$addModule->module_id = 0;
						$addModule->module_nom = CopixI18N::get ('groupe|groupe.group.admin');
						$nodes[$node['type']][$node['id']]['modules'][] = $addModule;

                                        }
                                }
                                                /*
                 * ===== CONTENT GENERATION =====
                 *
                 */

                //get content from db :
                $content = $this->db->query('SELECT * FROM module_admindash WHERE id_zone = ' . $node['id'].' AND type_zone = "'.$node['type'].'"')->toArray1();

                //if no content : get default content
                if (empty($content)) {
                    switch ($node['type']) {
                        case 'BU_CLASSE':
                            $content['content'] = CopixZone::process('kernel|dashboardClasse', array('idZone' => $node['id']));
                            $content['picture'] = null;
                            break;
                        case 'BU_ECOLE':
                            $content['content'] = CopixZone::process('kernel|dashboardEcole', array('idZone' => $node['id']));
                            $content['picture'] = null;
                            break;
                        case 'BU_VILLE':
                            $content['content'] = CopixZone::process('kernel|dashboardVille', array('idZone' => $node['id']));
                            $content['picture'] = null;
                            break;
                        case 'CLUB':
                            $content['content'] = CopixZone::process('kernel|dashboardGrTravail', array('idZone' => $node['id']));
                            $content['picture'] = null;
                            break;
                        default:
                            $content['content'] = 'no content';
                            $content['picture'] = null;
                            break;
                    }
                }

                //is admin :
                $is_admin = ($node['droit'] > 60);

                //build html content
                $content_tpl = & new CopixTpl();
                $content_tpl->assign('content', $content['content']);
                $content_tpl->assign('picture', (isset($content['picture']) ? $content['picture'] : null));
                $content_tpl->assign('is_admin', $is_admin);
                $content_tpl->assign('id', $node['id']);
                $content_tpl->assign('type', $node['type']);
                $content = $content_tpl->fetch('dashboard.nodes.tpl');

                //free memory
                unset($content_tpl);
                unset($admindash_datas);

                /*
                 *  ===== END CONTENT GENERATION =====
                 */

                $nodes[$node['type']][$node['id']]['content'] = $content;
            } elseif ($nodes[$node['type']][$node['id']]['droit'] < $node['droit'])
                $nodes[$node['type']][$node['id']] = $node;
        }


        /* DRAFT WORKING */
        //_dump($nodes);
        /* $rClasse = Kernel::getNodeInfo ('BU_CLASSE', $nodes['BU_CLASSE'][1]['id'], false);
          CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse)); */
        //echo $this->matrix->display();
        //_dump($nodes);

        $tplModule->assign("nodes", $nodes);
        $result = $tplModule->fetch("dashboard.tpl");
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
    function go() {
        if (!is_null(_request("ntype")) && !is_null(_request("nid")) && !is_null(_request("mtype"))) {
            CopixSession::set('myNode', array('type' => _request("ntype"), 'id' => _request("nid")));
            if (_request("ntype") == 'CLUB' && _request("mtype") == 'comptes' && !_request("mid")) {
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('groupe||getHomeMembers', array('id' => _request("nid"))));
            } elseif (_request("ntype") == 'CLUB' && _request("mtype") == 'admin' && !_request("mid")) {
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('groupe||getHomeAdmin', array('id' => _request("nid"))));
            } elseif (strpos(_request("ntype"), 'USER_') === false) {
                $mid = _request("mid");
                // Si on ne connait pas l'ID du module, on tente de le detecter automatiquement
                if (!$mid) {
                    $modules = Kernel::getModEnabled(_request("ntype"), _request("nid"));
                    foreach ($modules as $module) {
                        if ($module->module_type == 'MOD_' . strtoupper(_request("mtype"))) {
                            $mid = $module->module_id;
                            break;
                        }
                    }
                }
                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '|default|go', array('id' => $mid)));
            } else {

                $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '||'));
            }
            return $loadModule;
        }
        $loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('||'));
        return $loadModule;
    }

    function processModif() {
        if(!$this->istyReq('node_id') || !$this->istyReq('node_type'))
            return $this->error ('kernel|dashboard.admin.badOperation');

        $id_node = (int)$this->request('node_id');
        $type_node = $this->request('node_type');
        if(Kernel::getLevel($type_node, $id_node) < 60)
            return $this->error ('kernel|dashboard.admin.noRight');

        $content = $this->db->query('SELECT * FROM module_admindash WHERE id_zone = ' . $id_node.' AND type_zone = "'.$type_node.'"')->toArray1();

        //if no content : generate default content
        if (empty($content)) {
            $admindash_datas = array();
            $admindash_datas['id_zone'] = $this->db->quote($id_node);
            $admindash_datas['type_zone'] = $this->db->quote($type_node);
            
            $this->db->create('module_admindash', $admindash_datas);

            //get content from db :
            $content = $this->db->query('SELECT * FROM module_admindash WHERE id_zone = ' . $id_node.' AND type_zone = "'.$type_node.'"')->toArray1();
        }

        $ppo = new CopixPPO();
        $ppo->content = $content;
        $this->js->wysiwyg('#content_txt');
        return _arPPO($ppo, 'dashboard.admin.tpl');
    }

    function processEreg(){

        if(!$this->istyReq('id') || !$this->istyReq('id_zone') || !$this->istyReq('type_zone'))
            return $this->error ('kernel|dashboard.admin.badOperation');

        $datas['content'] = $this->db->quote($this->request('content_txt'));
        $datas['id'] = (int)$this->request('id');

        $this->db->update('module_admindash', $datas);

        return $this->go('kernel|dashboard|processModif', array('node_id' => $this->request('id_zone'), 'node_type' => $this->request('type_zone')));
    }

    function processAddPicture(){

    }

}

