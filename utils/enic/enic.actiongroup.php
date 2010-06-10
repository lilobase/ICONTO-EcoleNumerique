<?php

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */

class enicActionGroup extends CopixActionGroup {

    protected $user;
    protected $matrix;
    protected $menu;
    protected $shared;
    
    public function __construct(){
        //test the user connexion, desactivate for public access
	//_currentUser()->assertCredential ('group:[current_user]');

        //load enic classes
        $this->user     =& enic::get('user');
        $this->options  =& enic::get('options');
        
        enic::to_load('cache');
        enic::to_load('matrix');
        $this->matrix   =& enic::get('matrixCache');
       
        $this->menu     =& enic::get('menu');
        $this->model    =& enic::get('model');

        $this->js       =& enic::get('javascript');

        $this->css      =& enic::get('css');

    }

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

}

