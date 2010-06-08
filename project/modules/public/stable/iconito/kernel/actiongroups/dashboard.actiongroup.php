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
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_kernel.css"));
		CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe.css"));
		
		
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
			}
			elseif( $nodes[$node['type']][$node['id']]['droit'] < $node['droit'] ) $nodes[$node['type']][$node['id']] = $node;
		}
			

                /* DRAFT WORKING */
                //_dump($nodes);
                /*$rClasse = Kernel::getNodeInfo ('BU_CLASSE', $nodes['BU_CLASSE'][1]['id'], false);
                CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse));*/
                echo $this->matrix->display();

		$tplModule->assign ("nodes", $nodes);
		$result = $tplModule->fetch("dashboard.tpl");
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
		die('stop');
		return _arRedirect (_url ('dashboard|getHome'));
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
			if ( strpos(_request("ntype"), 'USER_') === false ) {
				$loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get (_request("mtype").'|default|go', array('id'=>_request("mid")) ));
				}
			else {
				$loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get (_request("mtype").'||'));
				}
			return $loadModule;
		}
		$loadModule = new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
		return $loadModule;
	}



}
?>
