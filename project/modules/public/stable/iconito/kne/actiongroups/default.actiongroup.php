<?php

    class ActionGroupDefault extends enicActionGroup
    {
        public function __construct()
        {
            parent::__construct();
            $this->service =& $this->service('kneService');
        }

        public function beforeAction ()
        {
        _currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault()
        {
            $ppo = new CopixPPO();

            // Le KNE est activé sur une classe, il nous faut son école
            $classId = (int)$this->request('id_classe');
            $parent = Kernel::getNodeParents('BU_CLASSE', $classId);
            if ($parent[0] && $parent[0]['type'] == 'BU_ECOLE')
                $schoolId = (int)$parent[0]['id'];
            else
                $schoolId = null;

            $KneRessources = $this->service('KneService')->getRessources($schoolId);

            $ppo->ressources = $KneRessources;
            return _arPPO($ppo, 'default.tpl');
        }

        public function processGo()
        {
            return $this->go('kne||', array('id_classe' => $this->request('id')));
        }

    }