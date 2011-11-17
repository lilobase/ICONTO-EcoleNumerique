<?php

    class ActionGroupDefault extends enicActionGroup {

        public function __construct(){
            parent::__construct();
            $this->service =& $this->service('rssEtagereService');
        }

        public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault(){
            $ppo = new CopixPPO();
            
            $ppo->title = $this->service->getTitle();
            $ppo->desc = $this->service->getDescription();
            $ppo->items = $this->service->getItems();
            return _arPPO($ppo, 'default.tpl');
        }

    }