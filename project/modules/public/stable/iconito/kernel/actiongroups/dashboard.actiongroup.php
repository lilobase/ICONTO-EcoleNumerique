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

    function  __construct() {
        $this->picturesPath = COPIX_VAR_PATH.'data/admindash/photos/';
        $this->thumbX = 150;
        $this->thumbY = 300;
        parent::__construct();
    }

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
		// _dump($nodes_all);
		
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
					
					$addModule = new CopixPPO ();
					$addModule->node_type = $node['type'];
					$addModule->node_id = $node['id'];
					$addModule->module_type = 'MOD_ADMIN';
					$addModule->module_id = 0;
					$addModule->module_nom = CopixI18N::get ('groupe|groupe.group.admin');
					$nodes[$node['type']][$node['id']]['modules'][] = $addModule;
					
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
                                                case 'ADMIN':
							$content['content'] = 'Administrateur';
							$content['picture'] = null;
                                                    break;
						default:
							$content['content'] = '';
							$content['picture'] = null;
							break;
					}
				}else{
                                    $content['picture'] = $this->url('kernel|dashboard|image', array('id' => $content['id']));
                                }

				//is admin :
				$is_admin = ($node['droit'] >= 60);

				//build html content
				$content_tpl = & new CopixTpl();
				$content_tpl->assign('content', $content['content']);
				$content_tpl->assign('picture', $content['picture']);
				$content_tpl->assign('is_admin', $is_admin);
				$content_tpl->assign('id', $node['id']);
				$content_tpl->assign('type', $node['type']);
				$content = $content_tpl->fetch('dashboard.nodes.tpl');

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
		//_dump($nodes);
		/* $rClasse = Kernel::getNodeInfo ('BU_CLASSE', $nodes['BU_CLASSE'][1]['id'], false);
		CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse)); */
		//echo $this->matrix->display();
		// _dump($nodes);

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
							$mid = $module->module_id;
							break;
						}
					}
				}
				// die('CopixUrl::get('._request("mtype").'|default|go   id='.$mid);
				$loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '|default|go', array('id' => $mid)));
			} else {
				if($mid)
					$loadModule = new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get(_request("mtype") . '|default|go', array('id' => $mid)));
				else
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

                $currentNode = CopixSession::get('myNode');
		$id_node = $currentNode['id'];
		$type_node = $currentNode['type'];
                
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
                $ppo->errors = ($this->flash->has('errors')) ? $this->flash->errors : null;
		$this->js->wysiwyg('#content_txt');
		return _arPPO($ppo, 'dashboard.admin.tpl');
	}

	function processEreg(){
            if(!$this->istyReq('id'))
                return $this->error ('kernel|dashboard.badOperation');

            //secure id
            $id = (int)$this->request('id');

            //get infos
            $zoneDatas = $this->db->query('SELECT * FROM module_admindash WHERE id = '.$id)->toArray1();

            //check security
            if(Kernel::getLevel($zoneDatas['type_zone'], $zoneDatas['id_zone']) < 60)
                return $this->error ('kernel|dashboard.admin.noRight');

            $datas['content'] = $this->db->quote($this->request('content_txt'));
            $datas['id'] = (int)$this->request('id');

            $this->db->update('module_admindash', $datas);

            //go to processModif
            return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));
	}

	function processAddPicture(){
            if(!$this->istyReq('id'))
                return $this->error ('kernel|dashboard.admin.badOperation');

            //secure id
            $id = (int)$this->request('id');

            //get infos
            $zoneDatas = $this->db->query('SELECT * FROM module_admindash WHERE id = '.$id)->toArray1();

            //check security
            if(Kernel::getLevel($zoneDatas['type_zone'], $zoneDatas['id_zone']) < 60)
                return $this->error ('kernel|dashboard.admin.noRight');

            //check error during upload
             if ($_FILES['image']['error'] > 0){
                $this->flash->errors = $this->i18n('kernel|dashboard.admin.errorPic');
                //go to processModif
                return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));
             }

             $ImageNews = $_FILES['image']['name'];

             //mime type
             $ListeExtension = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
            $ListeExtensionIE = array('jpg' => 'image/pjpg', 'jpeg'=>'image/pjpeg');


             //get extension & detect mime type
            $ExtensionPresumee = explode('.', $ImageNews);
            $ExtensionPresumee = strtolower($ExtensionPresumee[count($ExtensionPresumee)-1]);
            
            $ImageNews = getimagesize($_FILES['image']['tmp_name']);
            $extError = false;

            switch ($ExtensionPresumee) {
                case 'jpg':
                case 'jpeg':
                    if($ImageNews['mime'] != $ListeExtension[$ExtensionPresumee]  && $ImageNews['mime'] != $ListeExtensionIE[$ExtensionPresumee])
                        $extError = true;
                    $typeExt = 'jpeg';
                    break;

                case 'png':
                    if($ImageNews['mime'] != $ListeExtension[$ExtensionPresumee]  && $ImageNews['mime'] != $ListeExtensionIE[$ExtensionPresumee])
                        $extError = true;
                    $typeExt = 'png';
                    break;

                case 'gif':
                    if($ImageNews['mime'] != $ListeExtension[$ExtensionPresumee]  && $ImageNews['mime'] != $ListeExtensionIE[$ExtensionPresumee])
                        $extError = true;
                    $typeExt = 'gif';
                    break;

                default:
                    $extError = true;
                    break;
            }

        //check error during upload
        if ($extError){
            $this->flash->errors = $this->i18n('kernel|dashboard.admin.errorPic');
            //go to processModif
            return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));
        }

        $funcName = 'imagecreatefrom'.$typeExt;
        $ImageChoisie = $funcName($_FILES['image']['tmp_name']);
        $TailleImageChoisie = getimagesize($_FILES['image']['tmp_name']);

        //calcul des ratios :
        $ratio1 = $TailleImageChoisie[1]/$TailleImageChoisie[0];
        $ratio2 = $this->thumbX / $this->thumbY;

        if($ratio2 > $ratio1){
            $x2 = $ratio1 * $this->thumbX;
            $y2 = $this->thumbX;
	}else{
            $x2 = $this->thumbX;
            $y2 = $this->thumbX / $ratio1;
        }

        //create thumbs
           $NouvelleImage = imagecreatetruecolor($y2 , $x2) or die ("Erreur");

          imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $y2, $x2, $TailleImageChoisie[0],$TailleImageChoisie[1]);
          imagedestroy($ImageChoisie);
          $NomImageExploitable = 'dash'. $id;

          //delete the old pic
        if(!empty($zoneDatas['picture']) && file_exists($this->picturesPath.$zoneDatas['picture'])){
            unlink($this->picturesPath.$zoneDatas['picture']);
        }

          $funcName = 'image'.$typeExt;
          $funcName($NouvelleImage , $this->picturesPath.$NomImageExploitable.'.'.$typeExt, 100);

            $datas['picture'] = $this->db->quote($NomImageExploitable.'.'.$typeExt);
            $datas['id'] = (int)$this->request('id');

            $this->db->update('module_admindash', $datas);

        return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));
    }
    function processDelete(){
        if(!$this->istyReq('id'))
            return $this->error ('kernel|dashboard.badOperation');

        //secure id
        $id = (int)$this->request('id');

        //get infos
        $zoneDatas = $this->db->query('SELECT * FROM module_admindash WHERE id = '.$id)->toArray1();

        //check security
        if(Kernel::getLevel($zoneDatas['type_zone'], $zoneDatas['id_zone']) < 60)
            return $this->error ('kernel|dashboard.admin.noRight');

        //delete pic
        if(!empty($zoneDatas['picture']) && file_exists($this->picturesPath.$zoneDatas['picture'])){
            unlink($this->picturesPath.$zoneDatas['picture']);
        }


        //delete records
        $this->db->delete('module_admindash', $id);

        //go to processModif
        return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));
    }

    function processDeletePic(){
        if(!$this->istyReq('id'))
            return $this->error ('kernel|dashboard.badOperation');

        //secure id
        $id = (int)$this->request('id');

        //get infos
        $zoneDatas = $this->db->query('SELECT * FROM module_admindash WHERE id = '.$id)->toArray1();

        //check security
        if(Kernel::getLevel($zoneDatas['type_zone'], $zoneDatas['id_zone']) < 60)
            return $this->error ('kernel|dashboard.admin.noRight');

        if(!empty($zoneDatas['picture']) && file_exists($this->picturesPath.$zoneDatas['picture'])){
            unlink($this->picturesPath.$zoneDatas['picture']);
        }

            $datas['picture'] = 'null';
            $datas['id'] = (int)$this->request('id');

            $this->db->update('module_admindash', $datas);

         //go to processModif
        return $this->go('kernel|dashboard|processModif', array('node_id' => $zoneDatas['id_zone'], 'node_type' => $zoneDatas['type_zone'] ));

    }

    function processImage(){
        if(!$this->istyReq('id'))
            header("HTTP/1.0 404 Not Found");
        else{
            $id = (int)$this->request('id');
            
            //get pic name :
            $pic = $this->db->query('SELECT picture FROM module_admindash WHERE id = '.$id)->toString();
            
            if(!file_exists($this->picturesPath.$pic)){
                header("HTTP/1.0 404 Not Found");
            }else{
                
                $ext = explode('.', $pic);
                $ext = strtolower($ext[count($ext)-1]);
                header("Content-Type: image/".$ext);
                readfile($this->picturesPath.$pic, 'r+');
            }
        }
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

}