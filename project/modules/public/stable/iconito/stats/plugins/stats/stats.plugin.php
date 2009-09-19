<?php
/**
* @package   copix
* @subpackage plugins
* @version   $Id: stats.plugin.php,v 1.5 2007-07-19 13:51:50 cbeyer Exp $
* @author   Christophe Beyer
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class PluginStats extends CopixPlugin {
		
		var $save = true;	// Permet d'empêcher de logger

		var $module = '';
		var $action = '';

		var $module_id = 0;
		var $parent_type = '';
		var $parent_id = '';
		var $objet_a = '';
		var $objet_b = '';
		

   function beforeSessionStart(){
   }

   /**
    * @param CopixAction   $copixaction   action courante
    */
   function beforeProcess(&$copixaction){
	 
	 	
    
   }

   /**
    * @param CopixActionReturn      $ToProcess
    */
   function afterProcess($actionreturn){
		
		//print_r($copixaction);
		// === Si les stats ne sont pas du tout activés, on zappe ===
		$statsEnabled = CopixConfig::get ('default|statsEnabled');
		if (!$statsEnabled)
			return;
		// ======
		
		
	 	$objMetier = new DAOPluginStats ();
    include_once (COPIX_UTILS_PATH.'CopixUtils.lib.php');
		
		$par = getUrlTab ($_GET);
		
		//print_r($this);
		
		$module = ($this->module) ? $this->module : ( isset($par['module']) ? $par['module'] : '');
		$action = ($this->action) ? $this->action : ( isset($par['action']) ? $par['action'] : '');
		
		$modules = array ();
		$modules['blog'] = 'MOD_BLOG';
		$modules['groupe'] = 'MOD_GROUPE';
		$modules['minimail'] = 'MOD_MINIMAIL';
		
		$actions = array();
		$actions['blog'] = array(
			'default' => 'listArticle',
			'listArticle' => 'listArticle',
			'showArticle' => 'showArticle',
			'showPage' => 'showPage',
		);
		$actions['groupe'] = array(
			'getHome' => 'getHome',
		);
		$actions['minimail'] = array(
			'sendMinimail' => 'sendMinimail',
			'getMessage' => 'readMinimail',
		);
		
		
		//var_dump($this);
		
		//print_r($module);
		//print_r($action);
		if (isset($modules[$module]) && isset($actions[$module][$action])) {
			
			
			//$par['profil'] = _currentUser()->getExtra('type');
			$par['profil'] = 1;
			$par['module_id'] = $this->module_id;
			$par['parent_type'] = $this->parent_type;
			$par['parent_id'] = $this->parent_id;
			$par['module_type'] = $modules[$module];
			$par['action'] = $actions[$module][$action];
			$par['objet_a'] = $this->objet_a;
			$par['objet_b'] = $this->objet_b;

			$chaine = $par['module_type'].'/'.$par['module_id']. '/'.$par['action'].'/'. $par['objet_a'].'/'.$par['objet_b'];
			
			if ($this->config->cache == true && _sessionGet ('cache|stats|'.$chaine))
				return;
			//Kernel::deb($chaine);
			
	    $objMetier->add ($par);
			if ($this->config->cache == true)
				_sessionSet ('cache|stats|'.$chaine, 1);
		}
		
   }
	 
	 /**
	  * Force des paramètres
    * @param array params Paramètres à personnaliser
		*/ 
	 function setParams ($params) {
	 	if (isset($params['module']))	$this->module = $params['module'];
	 	if (isset($params['module_id']))	$this->module_id = $params['module_id'];
	 	if (isset($params['parent_type']))	$this->parent_type = $params['parent_type'];
	 	if (isset($params['parent_id']))	$this->parent_id = $params['parent_id'];
	 	if (isset($params['objet_a']))	$this->objet_a = $params['objet_a'];
	 	if (isset($params['objet_b']))	$this->objet_b = $params['objet_b'];
	 	if (isset($params['action']))	$this->action = $params['action'];
	 }
	 
	 /**
	  * Force des paramètres
    * @param boolean $save Définit si on sauve ou pas comme prévu
		*/ 
	 function setSave ($save) {
	 	$this->module_id = $save;
	 }

}

/**
* Objet métier pour ajouter des urls en base.
*/
class DAOPluginStats {
 
  function add ($params){
		
		$record = _record('stats|logs');
		
		$record->date = date('Y-m-d H:i:s');
		//$record->date = CopixDateTime::timeStampToyyyymmddhhiiss(mktime());
		$record->profil = $params['profil'];
		$record->module_type = $params['module_type'];
		$record->module_id = $params['module_id'];
		$record->action = $params['action'];

		if ($params['parent_type'])
			$record->parent_type = $params['parent_type'];
		if ($params['parent_id'])
			$record->parent_id = $params['parent_id'];
		if ($params['objet_a'])
			$record->objet_a = $params['objet_a'];
		if ($params['objet_b'])
			$record->objet_b = $params['objet_b'];
		
		_ioDao('stats|logs')->insert($record);

  }
}


?>
