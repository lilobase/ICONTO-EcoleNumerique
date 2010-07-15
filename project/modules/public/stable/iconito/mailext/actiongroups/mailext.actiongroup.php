<?php

class ActionGroupMailExt extends EnicActionGroup {

    public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

    }

    public function processUpdateMail(){



    }

    public function processValidMail(){

        $action = $this->request('typeAction');

        if(empty($action))
            $this->error('mailext.badOperation');

        //valid datas
        

    }



}