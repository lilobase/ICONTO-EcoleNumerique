<?php

    class ActionGroupDefault extends enicActionGroup
    {
        public function __construct()
        {
            parent::__construct();
            $this->service =& $this->service('rssnotifierService');
        }

        public function beforeAction ()
        {
        _currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault()
        {
            $ppo = new CopixPPO();
            $ppo->items = $this->service->getItems();
            $ppo->source = $this->service->getSource();
            $ppo->summary = $this->service->getSummary();
            $ppo->title = $this->service->getTitle();
            return _arPPO($ppo, 'default.tpl');
        }

        public function processGetJson()
        {
            echo json_encode(($this->service->getItems(5)));

            return _arNone();
        }

    }