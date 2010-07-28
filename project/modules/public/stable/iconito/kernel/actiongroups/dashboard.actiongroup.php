<?php
/*
	@file 		dashboard.actiongroup.php
	@desc		Dashboard constructor
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/


_classInclude ('welcome|welcome');
  

class ActionGroupDashboard extends enicActionGroup {
	
	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');

	}
		
	
	function processDefault () {
		
		$tpl = & new CopixTpl ();
		$tplModule = & new CopixTpl ();

                //if user is not connected :
                if(!_currentUser()->isConnected()){
                    $result = $tplModule->fetch("dashboard_public.tpl");
                    $tpl->assign ('MAIN', $result);
                    return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
                }
                
		$acc = (_currentUser()->getExtraHome('titre1')) ? _currentUser()->getExtraHome('titre1') : '';
		if( $acc != '' )
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel.title.accueil', array($acc)));
		else
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel.title.accueilsimple'));
		
		$nodes_all = Kernel::getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
		
		$nodes = array();
		foreach($nodes_all AS $node) {
			if(!isset($nodes[$node['type']])) $nodes[$node['type']] = array();
			
			if(!isset($nodes[$node['type']][$node['id']])) {
				$nodes[$node['type']][$node['id']] = $node;
				Kernel::createMissingModules( $node['type'], $node['id'] );
				$nodes[$node['type']][$node['id']]['modules'] = Kernel::getModEnabled(
					$node['type'], $node['id'],
					_currentUser()->getExtra('type'), _currentUser()->getExtra('id')    );
        
        // Cas des groupes : on ajoute les membres et admin, selon les droits
        if ($node['type'] == 'CLUB') {
          //_dump($nodes[$node['type']][$node['id']]['modules']);
          
          $addModule = new CopixPPO ();
          $addModule->node_type = $node['type'];
          $addModule->node_id = $node['id'];
          $addModule->module_type = 'MOD_COMPTES';
          $addModule->module_id = 0;
          $addModule->module_nom = CopixI18N::get ('groupe|groupe.group.members');
          $nodes[$node['type']][$node['id']]['modules'][] = $addModule;
          
          $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
          $mondroit = Kernel::getLevel($node['type'], $node['id']);
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
        
		//add item content
                                switch($node['type']){
                                    case 'BU_CLASSE':
                                        $content = CopixZone::process ('kernel|dashboardClasse', array ('idZone'=>$node['id']));
                                    break;
                                    case 'BU_ECOLE':
                                        $content = CopixZone::process ('kernel|dashboardEcole', array ('idZone'=>$node['id']));
                                    break;
                                    case 'BU_VILLE':
                                        $content = CopixZone::process ('kernel|dashboardVille', array ('idZone'=>$node['id']));
                                    break;
                                    case 'CLUB':
                                        $content = CopixZone::process ('kernel|dashboardGrTravail', array ('idZone'=>$node['id']));
                                    break;
                                    default:
                                        $content = 'no content';
                                    break;
                                }
                                $nodes[$node['type']][$node['id']]['content'] = $content;
			}
			elseif( $nodes[$node['type']][$node['id']]['droit'] < $node['droit'] )
                            $nodes[$node['type']][$node['id']] = $node;
		}
			

                /* DRAFT WORKING */
                //_dump($nodes);
                /*$rClasse = Kernel::getNodeInfo ('BU_CLASSE', $nodes['BU_CLASSE'][1]['id'], false);
                CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse));*/
                //echo $this->matrix->display();

    //_dump($nodes);
    
		$tplModule->assign ("nodes", $nodes);
		$result = $tplModule->fetch("dashboard.tpl");
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
	

	/**
	 * Acces direct a un module
	 *
	 * Fonction generique d'acces a un module depuis un noeud.
	 *
	 * @author Stephane Holtz <sholtz@cap-tic.fr>
	 */
	function go () {
            if ( !is_null(_request("ntype")) && !is_null(_request("nid")) && !is_null(_request("mtype")) ) {
                CopixSession::set ('myNode', array ('type'=>_request("ntype"), 'id'=>_request("nid")));
                if (_request("ntype")=='CLUB' && _request("mtype")=='comptes' && !_request("mid")) {
                    $loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHomeMembers', array('id'=>_request("nid"))));
                } elseif (_request("ntype")=='CLUB' && _request("mtype")=='admin' && !_request("mid")) {
                    $loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHomeAdmin', array('id'=>_request("nid"))));
                } elseif ( strpos(_request("ntype"), 'USER_') === false ) {
                    $loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get (_request("mtype").'|default|go', array('id'=>_request("mid")) ));
                } else {
                    $loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get (_request("mtype").'||'));
                }
                return $loadModule;
            }
            $loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
            return $loadModule;
	}



}
?>
