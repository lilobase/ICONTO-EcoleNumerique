<?php

class ActionGroupMailExt extends EnicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    public function processUpdate()
    {
        $idMailConf = $this->request('id');

        if(empty($idMailConf))
            return $this->error ('mailext.badOperation');

        $typeAction = ($idMailConf == 'new') ? 'create' : 'update';

        //check if the user is owner
        if($typeAction != 'create'){
            $checkUser = $this->service('mailExtService')->checkUserMailConf($idMailConf);
            if(!$checkUser)
                return $this->error ('mailext.badOperation');
        }

        //format datas
        $formData['id']             = $idMailConf;
        $formData['protocol']       = $this->request('protocol');
        $formData['server']         = $this->request('server');
        $formData['port']           = $this->request('port');
        $formData['ssl']            = $this->request('ssl')*1;
        $formData['login']          = $this->request('login');
        $formData['pass']           = $this->request('pass');
        $formData['imap_path']      = $this->request('imap_path');
        $formData['name']           = $this->request('name');
        $formData['webmail_url']    = $this->request('webmail_url');
        $formData['tls']            = $this->request('tls');

        $valid = $this->service('mailExtService')->validMailConf($formData);

        //if errors
        if($valid[0] == false){
            $this->flash->error = true;
            $this->flash->errorMsg = $valid[1];
            $this->flash->formData = $formData;
            $this->flash->mailConfId = $formData['id'];

            return $this->go('mailext|mailext|admin');
        }

        //insert data in DB :
        if($typeAction == 'create'){
            $this->service('mailExtService')->createMailConf($formData);
            $this->flash->mailConfId = $this->model->lastId;
        }else{
            $this->service('mailExtService')->updateMailConf($formData);
            $this->flash->mailConfId = $formData['id'];
        }

        return $this->go('mailext|mailext|validMailConf');

    }

    public function processAdmin()
    {
        $mailConf = $this->service('mailExtService')->getConf();

        //test if error occured
        if(isset($this->flash->error) && $this->flash->error || isset($this->flash->validMailConf)){
            $modifId = $this->flash->mailConfId;

            if($modifId == 'new'){

                //build new item from error array
                $newItem = $this->flash->formData;
                $newItem['error'] = $this->flash->errorMsg;
                array_unshift($mailConf, $newItem);
            }else{
                foreach($mailConf as $k => $mail){

                    if($modifId != $mail['id'])
                        continue;

                    if(isset($this->flash->error)){
                        $mailConf[$k] = $this->flash->formData;
                        $mailConf[$k]['error'] = $this->flash->errorMsg;
                    }else{
                        $mailConf[$k]['valid'] = $this->flash->validMailConf;
                    }
                    break;
                }
            }
        }

        $ppo = new CopixPPO();
        $ppo->mailConf = $mailConf;

        if(isset($this->flash->validMailConf))
            $ppo->validMailConf = $this->flash->validMailConf;

        $this->addCss('styles/module_mailext.css');
        return _arPPO($ppo, 'admin.tpl');

    }

    public function processValidMailConf()
    {
        if(!isset($this->flash->mailConfId))
            $this->error ('mailext.badOperation');

        $test = $this->service('mailExtService')->checkMailConf($this->flash->mailConfId);

        $this->flash->validMailConf = $test;
        $this->flash->mailConfId = $this->flash->mailConfId;

        return $this->go('mailext|mailext|admin');
    }

    public function processDeleteMailConf()
    {
        $id = (int)$this->request('id');

        //check security
        $checkUser = $this->service('mailExtService')->checkUserMailConf($id);
        if(!$checkUser)
            return $this->error ('mailext.badOperation');

        $this->db->delete('module_mailext', $id);

        return $this->go('mailext|mailext|admin');

    }

    public function processGetMsg()
    {
        $id = (int)$this->request('id_mail');

        $dataMail = $this->service('mailextService')->checkById($id);

        if($dataMail === false){
            echo $this->i18n('mailext.noConfigured');
            exit();
        }

        if($dataMail == 0){
            echo $this->i18n ('mailext.noMsg');
            exit();
        }

        echo $this->i18n('mailext.nbMsg').' <em><strong>'.$dataMail.'</strong> '.$this->i18n('mailext.msg').'</em>';


        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }


}