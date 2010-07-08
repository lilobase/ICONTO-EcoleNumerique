<?php
class ActionGroupCharte extends enicActionGroup{

    public function processShowChart(){

    }

    public function processValid(){

    }

    public function processAdmin(){
        //check if the user is admin :
        /*if(!$this->user->root)
            return $this->error('charte.noRight', true, '||');*/


        $ppo = new CopixPPO();
        $ppo->errors = (isset($this->flash->error)) ? $this->flash->error : null;
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
                $this->service('ChartService')->deleteUserValidation(${'user_'.$userType});
                $this->flash->success = $this->i18n('charte.successSupprValid');
            break;

            case 'suppr_chart':
                $this->service('ChartService')->delChart(${'user_'.$userType});
                $this->flash->success = $this->i18n('charte.successSupprChart');
            break;

            case 'new_chart':
                $url = $this->request('file_url');
                $active = $this->request('activate');
                
                if(empty($url))
                    $this->flash->error[$userType] = $this->i18n('charte.noUrl');

                $this->service('ChartService')->addChart(${'user_'.$userType}, $url, 1, $active);
                $this->flash->success = $this->i18n('charte.successSupprChart');
            break;
            //if défault : bad argument
            default:
                return $this->error('charte.badArgs');
            break;
        }

        $this->go('charte|charte|admin');
        
    }

}
