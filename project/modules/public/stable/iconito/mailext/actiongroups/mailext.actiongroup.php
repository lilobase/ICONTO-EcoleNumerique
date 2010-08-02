<?php

class ActionGroupMailExt extends EnicActionGroup {

    public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

    }

    public function processUpdateMail(){

        $mailConf = $this->service('mailExtService')->getConf();


 

    }

    public function processAdmin(){

        $mailConf = $this->service('mailExtService')->getConf();


        $ppo = new CopixPPO();

        $this->addCss('styles/module_mailext.css');
        return _arPPO($ppo, 'admin.tpl');

    }

    public function processValidMail(){

        $action = $this->request('typeAction');

        if(empty($action))
            $this->error('mailext.badOperation');

        //valid datas
        

    }



}