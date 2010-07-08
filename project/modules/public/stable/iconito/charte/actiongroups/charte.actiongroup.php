<?php
class ActionGroupCharte extends enicActionGroup{

    public function processShowChart(){

    }

    public function processValid(){

    }

    public function processAdmin(){
        //check if the user is admin :
        if(!$this->user->root)
            return $this->error('charte.noRight', true, '||');

        $this->js->button(".button");

        $ppo = new CopixPPO();
        $ppo->errors = (isset($this->flash->errors)) ? $this->flash->errors : null;
        $ppo->success = (isset($this->flash->success)) ? $this->flash->success : null;
        $ppo->chartes = $this->service('CharteService')->getChartesTypes();
        $ppo->radio = array(1 => 'oui', 0 => 'non');

        return _arPPO($ppo, 'charte.admin.tpl');

    }

    public function processAdminAction(){

        //check if the user is admin :
        if(!$this->user->root)
            return $this->error ('charte.noRight');

        //get action
        $action = $this->request('typeaction');

        //get the targeted items
        $target = $this->request('target');

        //security : force user type
        $userType = array('children', 'adults', 'all');

        //security
        if(empty ($target) || !in_array($target, $userType))
            return $this->error('charte.badArgs');

        //build array datas of users type
        $user_children = array('USER_ELE');
        $user_adults = array('USER_EXT', 'USER_VIL', 'USER_RES', 'USER_ENS');
        $user_all = array('USER_ALL');

        //foreach action :
        switch ($action){

            case 'suppr_validation':
                $this->service('CharteService')->deleteUserValidation(${'user_'.$target});
                $this->flash->success = $this->i18n('charte.successSupprValid');
            break;

            case 'suppr_charte':
                $this->service('CharteService')->delCharte(${'user_'.$target});
                $this->flash->success = $this->i18n('charte.successSupprChart');
            break;

            case 'new_charte':
                $url = $this->request('ca-file_url');
                $active = $this->request('ca-activate');
                
                if(empty($url)){
                    $this->flash->errors = array($target => $this->i18n('charte.noUrl'));
                    break;
                }

                $this->service('CharteService')->addCharte(${'user_'.$target}, $url, 1, $active);
                $this->flash->success = $this->i18n('charte.successAddChart');
            break;
            //if défault : bad argument
            default:
                return $this->error('charte.badArgs');
            break;
        }

        return $this->go('charte|charte|admin');
        
    }

}
