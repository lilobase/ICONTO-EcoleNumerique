<?php
class Plugincharte extends CopixPlugin
{
    public function beforeSessionStart (){}
    public function beforeProcess (& $action)
    {
        $user =& enic::get('user');
        //if user not connected : return true
        if($user->chartValid)
            return true;

        //array of authorized module
        $authMod = array('charte', 'auth', 'kernel');
        if(in_array($action->file->module, $authMod))
            return true;

        $action->useObj = 'charte|Charte';
        $action->useMeth = ($action->file->module == 'default') ? 'Valid' : 'processValid';
        $action->file->module = 'charte';
        $action->file->type = 'module';
        $action->file->fileName = 'Charte';

    }
    public function afterProcess ($actionreturn){}
    public function beforeDisplay (& $display){}
}

