<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
abstract class enicZone extends CopixZone
{
    //enic attributes
    protected $user;
    protected $matrix;
    protected $options;
    protected $model;
    protected $js;
    protected $css;

    //internal attributes
    protected $shared;

    /*
     * CONSTRUCTOR
     */
    public function __construct()
    {
        //test the user connexion, desactivate for public access
    //_currentUser()->assertCredential ('group:[current_user]');

        //pre-load enic classes
        enic::to_load('matrix');

        //load enic classes in current object :
        $this->user         =& enic::get('user');
        $this->options      =& enic::get('options');
        $this->matrix       =& enic::get('matrixCache');
        $this->model        =& enic::get('model');
        $this->db           =& enic::get('model');
        $this->js           =& enic::get('javascript');
        $this->css          =& enic::get('css');
        $this->session      =& enic::get('session');
        $this->flash        =& enic::get('flash');
        $this->html         =& enic::get('html');
        $this->helpers      =& enic::get('helpers');

        //define properties :
        $this->module   = $this->helpers->module;
        $this->actiongroup   = $this->helpers->actiongroup;
        $this->action   = $this->helpers->action;
    }

    /*
     *
     * DEFINE CONTROLLER HELPERS
     *
     */

    protected function addCss($iPathToJs)
    {
        $this->css->addFile($iPathToJs);
    }

    protected function addJs($iPathToJs)
    {
        $this->js->addFile($iPathToJs);
    }

    protected function service($iService)
    {
        return $this->helpers->service($iService);
    }

    protected function request($iName, $iType = 'other', $default = null)
    {
        return $this->helpers->request($iName, $iType, $default);
    }

    protected function i18n($iKey)
    {
        return $this->helpers->i18n($iKey);
    }

    protected function url($iUrl, $iParams = array())
    {
         return $this->helpers->url($iUrl, $iParams);
    }

    protected function error($iMsg, $i18n = true, $iBack = null)
    {
       return $this->helpers->error($iMsg, $i18n, $iBack);
    }

    protected function go($iUrl = 'default', $iParams = array())
    {
        return $this->helpers->go($iUrl, $iParams);
    }

    protected function addImg($iPath)
    {
        $this->html->addImg($iPath);
    }

}
