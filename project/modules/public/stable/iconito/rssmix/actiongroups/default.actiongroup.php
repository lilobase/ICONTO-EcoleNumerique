<?php

    class ActionGroupDefault extends enicActionGroup {

        public function __construct(){
            parent::__construct();
            $this->service =& $this->service('rssmixService');
        }

        public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault(){
            $ppo = new CopixPPO();

            return _arPPO($ppo, 'default.tpl');
        }

    }