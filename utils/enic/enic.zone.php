<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
abstract class enicZone extends CopixZone {

    //enic attributes
    protected $user;
    protected $matrix;
    protected $menu;
    protected $options;
    protected $model;
    protected $js;
    protected $css;

    //internal attributes
    protected $shared;

    /*
     * CONSTRUCTOR
     */
    public function __construct(){
        //test the user connexion, desactivate for public access
	//_currentUser()->assertCredential ('group:[current_user]');

        //pre-load enic classes
        enic::to_load('matrix');

        //load enic classes in current object :
        $this->user         =& enic::get('user');
        $this->options      =& enic::get('options');
        $this->matrix       =& enic::get('matrixCache');
        $this->menu         =& enic::get('menu');
        $this->model        =& enic::get('model');
        $this->db           =& enic::get('model');
        $this->js           =& enic::get('javascript');
        $this->css          =& enic::get('css');
        $this->session      =& enic::get('session');
        $this->flash        =& enic::get('flash');
        $this->html         =& enic::get('html');

        //define properties :
        $this->module   = $this->request('module');
        $this->actiongroup   = $this->request('group');
        $this->action   = $this->request('action');
    }

    /*
     *
     * DEFINE CONTROLLER HELPERS
     *
     */

    protected function addCss($iPathToJs){
        $this->css->addFile($iPathToJs);
    }

    protected function addJs($iPathToJs){
        $this->js->addFile($iPathToJs);
    }

    protected function service($iService){
        if(!is_string($iService))
            trigger_error('Enic failed to load Service : invalid name', E_USER_WARNING);

        if(!isset($this->shared['s'.$iService]))
            $this->shared['s'.$iService] =& CopixClassesFactory::create($iService);

        return $this->shared['s'.$iService];
    }

    protected function request($iName, $iType = 'other', $default = null){
        $oReturn = CopixRequest::get($iName, $default);

        switch($iType){
            case 'str':
                return (string)$oReturn;
            break;
            case 'int':
                return $oReturn*1;
            break;
            case 'other':
                return $oReturn;
            break;
        }
    }

    protected function i18n($iKey){
        return CopixI18N::get($iKey);
    }

    protected function url($iUrl, $iParams = array()){
         return CopixUrl::get ($iUrl, $iParams);
    }

    protected function error($iMsg, $i18n = true, $iBack = null){

        //build msg
        $msg = ($i18n) ? $this->i18n($iMsg) : $iMsg;

        //build url
        $back = (empty($iBack)) ? $this->module.'|'.$this->actiongroup.'|' : $iBack;
        $back = $this->url($back);

        return CopixActionGroup::process('genericTools|Messages::getError', array ('message' => $msg, 'back' => $back));
    }

    protected function go($iUrl = 'default', $iParams = array()){
        //build url :
        $back = ($iUrl == 'default') ? $this->module.'||' : $iUrl;

        return _arRedirect($this->url($iUrl, $iParams));
    }

    protected function addImg($iPath){
        $this->html->addImg($iPath);
    }

}
?>
