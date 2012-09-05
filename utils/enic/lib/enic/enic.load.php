<?php

class enicLoad
{
//enic attributes
    protected $user;
    protected $options;
    protected $matrix;
    protected $model;
    protected $db;
    protected $js;
    protected $css;
    protected $session;
    protected $flash;
    protected $html;
    protected $helpers;

    public function startExec()
    {
    }

    /*
     * CONSTRUCTOR
     */
    public function __construct()
    {
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

    protected function redirect($iUrl = 'default', $iParams = array())
    {
        return $this->helpers->go($iUrl, $iParams);
    }

    protected function go($iUrl = 'default', $iParams = array())
    {
        return $this->helpers->go($iUrl, $iParams);
    }

    protected function istyReq($iVar)
    {
        return $this->helpers->istyReq($iVar);
    }

    protected function addImg($iPath)
    {
        $this->html->addImg($iPath);
    }

}