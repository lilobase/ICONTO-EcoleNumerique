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
class PluginStats extends CopixPlugin
{
        var $save = true;	// Permet d'empêcher de logger

        var $module = '';
        var $action = '';

        var $module_id = 0;
        var $parent_type = '';
        var $parent_id = '';
        var $objet_a = '';
        var $objet_b = '';


   public function beforeSessionStart()
   {
   }

   /**
    * @param CopixAction   $copixaction   action courante
    */
   public function beforeProcess(&$copixaction)
   {
   }

   /**
    * @param CopixActionReturn      $ToProcess
    */
   public function afterProcess($actionreturn)
   {
        //print_r($copixaction);
        // === Si les stats ne sont pas du tout activés, on zappe ===
        $statsEnabled = CopixConfig::get ('default|statsEnabled');
        if (!$statsEnabled)
            return;
        // ======


         $objMetier = new DAOPluginStats ();
    include_once (COPIX_UTILS_PATH.'CopixUtils.lib.php');

        $par = getUrlTab ();

        $module = ($this->module) ? $this->module : ( isset($par['module']) ? $par['module'] : '');
        $action = ($this->action) ? $this->action : ( isset($par['action']) ? $par['action'] : '');

        $modules = array ();
        $modules['blog'] = 'MOD_BLOG';
        $modules['groupe'] = 'MOD_GROUPE';
        $modules['minimail'] = 'MOD_MINIMAIL';

        $actions = array();
        $actions['blog'] = array(
            'default' => array('action'=>'listArticle'),
            'listArticle' => array('action'=>'listArticle'),
            'showArticle' => array('action'=>'showArticle', 'needObjetA'=>true),
            'showPage' => array('action'=>'showPage', 'needObjetA'=>true),
        );
        $actions['groupe'] = array(
            'getHome' => array('action'=>'getHome'),
        );
        $actions['minimail'] = array(
            'sendMinimail' => array('action'=>'sendMinimail', 'needObjetA'=>true),
            'getMessage' => array('action'=>'readMinimail', 'needObjetA'=>true),
        );

        //print_r($module);
        //print_r($action);
        if (isset($modules[$module]) && isset($actions[$module][$action])) {

      // Verification (pour eviter de logger des 404)
      if (isset($actions[$module][$action]['needObjetA']) && $actions[$module][$action]['needObjetA'] && !$this->objet_a) {
        return;
      }

            $par['profil'] = _currentUser()->getExtra('type');
            $par['module_id'] = $this->module_id;
            $par['parent_type'] = $this->parent_type;
            $par['parent_id'] = $this->parent_id;
            $par['module_type'] = $modules[$module];
            $par['action'] = $actions[$module][$action]['action'];
            $par['objet_a'] = $this->objet_a;
            $par['objet_b'] = $this->objet_b;

            $chaine = $par['module_type'].'/'.$par['module_id']. '/'.$par['action'].'/'. $par['objet_a'].'/'.$par['objet_b'];

            if ($this->config->cache == true && _sessionGet ('cache|stats|'.$chaine))
                return;

        $objMetier->add ($par);
            if ($this->config->cache == true)
                _sessionSet ('cache|stats|'.$chaine, 1);
        }

   }

     /**
      * Force des paramètres
    * @param array params Paramètres à personnaliser
        */
     function setParams ($params)
     {
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
     function setSave ($save)
     {
         $this->module_id = $save;
     }

}

/**
* Objet métier pour ajouter des urls en base.
*/
class DAOPluginStats
{
  public function add ($params)
  {
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


