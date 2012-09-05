<?php
class ActionGroupCharte extends enicActionGroup
{
    public function processShowChart()
    {
    }

    public function processValid()
    {
        $this->flash->redirect = (isset($this->flash->redirect)) ? $this->flash->redirect : $this->url('||');

//        $this->js->button('.button');
        $ppo = new CopixPPO();
        $charte = $this->service('CharteService')->getCharte();

        $this->flash->userType = $charte['user_type'];

        $ppo->url = $charte['file_url'];
        if(empty($ppo->url))
            return $this->go('auth|log|out');

        CopixHTMLHeader::addCSSLink (_resource("styles/module_charte.css"));

        return _arPPO($ppo, 'charte.tpl');
    }

    public function processRedirect()
    {
        $accept = ($this->request('typeAction') == 'accept') ? true : false ;

        CopixHTMLHeader::addCSSLink (_resource("styles/module_charte.css"));
        if($accept){
            $typeUser = (isset($this->flash->userType)) ? $this->flash->userType : 'USER_ALL';
            $this->service ('CharteService')->addUserValidation($typeUser);
            $_SESSION['chartValid'] = true;
            return $this->go(isset($this->flash->redirect) ? $this->flash->redirect : '||');
        }else{
            $ppo = new CopixPPO();
            return _arPPO($ppo, 'charte.no.tpl');
        }


    }

    public function processAdmin()
    {
        //check if the user is admin :
        if(!Kernel::isAdmin())
            return $this->error('charte.noRight', true, '||');

        $ppo = new CopixPPO();
        $ppo->errors = (isset($this->flash->errors)) ? $this->flash->errors : null;
        $ppo->success = (isset($this->flash->success)) ? $this->flash->success : null;
        $ppo->chartes = $this->service('CharteService')->getChartesTypes();
        $ppo->radio = array(1 => 'oui', 0 => 'non');
        $ppo->idClasseur = $ppo->idMalle = null;

        $modsAvailable = Kernel::getModAvailable($this->user->type);
        $malleAvailable = Kernel::filterModuleList ($modsAvailable, 'MOD_MALLE');

        // Malle activée
        if (!empty($malleAvailable)) {

          $modsEnabled = Kernel::getModEnabled ($this->user->type, $this->user->idEn);
          $mal = Kernel::filterModuleList ($modsEnabled, 'MOD_MALLE');

          // Si la malle est bien initialisée
          if (!empty($mal)) {

            $ppo->idMalle = $mal[0]->module_id;
          } else {

            return $this->error ('charte.admin.noMalle', true, 'malle||');
          }
        } else {

          $classeurAvailable = Kernel::filterModuleList ($modsAvailable, 'MOD_CLASSEUR');

          // Classeur activé
          if (!empty($classeurAvailable)) {

            Kernel::createMissingModules($this->user->type, $this->user->idEn);
            $modsEnabled = Kernel::getModEnabled ($this->user->type, $this->user->idEn);
            $classeur = Kernel::filterModuleList ($modsEnabled, 'MOD_CLASSEUR');

            if (!empty($classeur)) {

              $ppo->idClasseur = $classeur[0]->module_id;
            }
          }
        }

        CopixHTMLHeader::addCSSLink (_resource("styles/module_charte.css"));

        return _arPPO($ppo, 'charte.admin.tpl');

    }

    public function processAdminAction()
    {
        //check if the user is admin :
        if(!Kernel::isAdmin())
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
            //if default : bad argument
            default:
                return $this->error('charte.badArgs');
            break;
        }

        return $this->go('charte|charte|admin');

    }

}
