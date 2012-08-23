<?php
/**
 * @package     standard
 * @subpackage  auth
 * @author      Sébastien CAS
 */

/**
 * Zone affichant l'utilisateur connecté
 */
class ZoneUserLogged extends enicZone {

	function _createContent (& $toReturn) {
		//load PPO
		$ppo = new CopixPPO ();
		$ppo->user = _currentUser();
		//if user is connected : load personal informations
		if($ppo->user->isConnected()){
			$ppo->animateur = (_sessionGet('user_animateur')) ? 1 : 0;
			$ppo->ien = (_sessionGet('prisedecontrole_ien')) ? 1 : 0;
			$type = $ppo->user->getExtra('type');
			$sexe = ($ppo->user->getExtra('sexe')==2) ? 2 : '';
			$ppo->usertype = strtolower($type).$sexe;
		}

        // Get vocabulary catalog to use
		if ($myNode = CopixSession::get('myNode')) {
		  
		  $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
  		  $vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($myNode['type'], $myNode['id']);
		}
		
	  $ppo->vocabularyCatalogId = isset($vocabularyCatalog) ? $vocabularyCatalog->id_vc : CopixConfig::get('kernel|defaultVocabularyCatalog');
		
		$this->addJs('js/iconito/module_auth.js');
		$this->addCss('styles/module_auth.css');

		$ppo->conf_Cas_actif = (CopixConfig::exists('default|conf_Cas_actif')?CopixConfig::get ('default|conf_Cas_actif'):0);
		
		//load tpl
		$toReturn = $this->_usePPO ($ppo, 'userlogged.tpl');
	}
}
