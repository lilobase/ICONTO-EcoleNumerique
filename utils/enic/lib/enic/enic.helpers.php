<?php

class enicHelpers extends enicMod
{
    protected $shared;

    public function  __construct()
    {
        parent::__construct();

        //define properties :
        $this->module   = $this->request('module');
        $this->actiongroup   = $this->request('group');
        $this->action   = $this->request('action');
    }

    public function service($iService)
    {
        if(!is_string($iService))
            trigger_error('Enic failed to load Service : invalid name', E_USER_WARNING);

        if(!isset($this->shared['s'.$iService]))
            $this->shared['s'.$iService] =& CopixClassesFactory::create($iService);

        return $this->shared['s'.$iService];
    }

    public function request($iName, $iType = 'other', $default = null)
    {
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

    public function uniqueId()
    {
        return sha1(microtime().mt_rand(0, 10000));

    }

    public function i18n($iKey)
    {
        return CopixI18N::get($iKey);
    }

    public function url($iUrl, $iParams = array())
    {
         return CopixUrl::get ($iUrl, $iParams);
    }

    public function error($iMsg, $i18n = true, $iBack = null)
    {
        //build msg
        $msg = ($i18n) ? $this->i18n($iMsg) : $iMsg;

        //build url
        $back = (empty($iBack)) ? $this->module.'|'.$this->actiongroup.'|' : $iBack;
        $back = $this->url($back);

        return CopixActionGroup::process('genericTools|Messages::getError', array ('message' => $msg, 'back' => $back));
    }

    public function go($iUrl = 'default', $iParams = array())
    {
        //build url :
        $back = ($iUrl == 'default') ? $this->module.'||' : $iUrl;

        return _arRedirect($this->url($iUrl, $iParams));
    }

    public function isty($iVar)
    {
        return (isset($iVar) && !empty($iVar));
    }

    public function istyReq($iVar)
    {
        return $this->isty($this->request($iVar));
    }

    public function config($iVar)
    {
        return CopixConfig::get($iVar);
    }

    public function word_cut($string,$length,$cutString = '...')
    {
    if(strlen($string) <= $length) {
        return $string;
    }
    $str = substr($string,0,$length-strlen($cutString)+1);
    return substr($str,0,strrpos($str,' ')).$cutString;
    }

}