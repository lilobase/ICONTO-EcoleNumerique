<?php

    class ActionGroupDefault extends enicActionGroup {

        public function __construct(){
            parent::__construct();
            $this->service =& $this->service('kneService');
        }

        public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault(){
            $ppo = new CopixPPO();

            $schoolId = (int)$this->request('id_ecole');
            $service = $this->service('KneService')->getRessources($schoolId);

            var_dump($service);
            return _arPPO($ppo, 'default.tpl');
        }

        public function processGo(){
            return $this->go('kne||', array('id_ecole' => $this->request('id')));
        }

    }