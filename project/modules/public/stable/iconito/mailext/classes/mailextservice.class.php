<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mailextservice
 *
 * @author alemaire
 */
class mailExtService extends enicService
{
    public function connect($server, $port, $protocol, $ssl, $tls, $user, $pass)
    {
        //build connection string
        $server = $server.':'.$port;
        $protocol = '/'.$protocol;
        $ssl = ($ssl == 1) ? '/ssl/novalidate-cert' : '';
        $tls = ($tls == 1) ? '/tls' : '/notls';
        $mailbox = '{'.$server.$protocol.$ssl.$tls.'}INBOX';

        return @imap_open($mailbox, $user, $pass);
    }

    /*
     * check new messages
     * return array('name' => (bool)false|(int)nb of new msg);
     */
    public function check()
    {
        $confs = $this->getConf();

        //test if conf exists
        if(empty($confs))
                return false;

        foreach($confs as $mail){
            $connect =& $this->connect($mail['server'], $mail['port'], $mail['protocol'], $mail['ssl'], $mail['tls'], $mail['login'], $mail['pass']);

            //test if connection is right
            if($connect === false){
                $oReturn[$conf['name']] = false;
                continue;
            }

            $oReturn[$conf['name']] = @imap_num_recent($connect);

            imap_close($connect);
        }

        return $oReturn;
    }

    public function checkById($id)
    {
        if($this->session->exists('time'.$id, 'mailext')){
            if($this->session->load('time'.$id, 'mailext') > time()){
                return $this->session->load('datas'.$id, 'mailext');
            }
        }

        $mail = $this->getConfById($id);

        if(empty($mail))
            return false;

        $connect =& $this->connect($mail['server'], $mail['port'], $mail['protocol'], $mail['ssl'], $mail['tls'], $mail['login'], $mail['pass']);

        if($connect === false)
            return false;

        $nbMail = @imap_num_recent($connect);

        $this->session->save('time'.$id, time()+(10*60), 'mailext');
        $this->session->save('datas'.$id, $nbMail, 'mailext');

        return $nbMail;
    }

    public function checkMailConf($iIdMailConf)
    {
        $mail = $this->getConfById($iIdMailConf);

        $test = $this->connect($mail['server'], $mail['port'], $mail['protocol'], $mail['ssl'], $mail['tls'], $mail['login'], $mail['pass']);

        return  ($test === false) ? false : true;
    }

    //return all conf data's linked to the current user
    public function getConf()
    {
        if(!isset($this->conf))
            $this->conf = $this->model->query('SELECT * FROM module_mailext WHERE user_id = '.$this->user->id)->toArray();
        return $this->conf;
    }

    public function getConfById($id)
    {
        if(!isset($this->confById))
            $this->confById = $this->model->query('SELECT * FROM module_mailext WHERE id = '.(int)$id)->toArray1();

        return $this->confById;
    }

    public function checkUserMailConf($iIdMailConf)
    {
        $userIdFromDb = $this->model->query('SELECT user_id FROM module_mailext WHERE id = '.(int)$iIdMailConf)->toString();

        return ($this->user->id == $userIdFromDb);
    }

    public function updateMailConf($iDatas)
    {
        $datas = $this->prepareMailConf($iDatas);
        $this->model->update('module_mailext',$datas);
    }

    public function createMailConf($iDatas)
    {
        $datas = $this->prepareMailConf($iDatas);
        $this->model->create('module_mailext', $datas);
    }

    public function prepareMailConf($iDatas)
    {
        $oDatas['id'] = (!empty($iDatas['id'])) ? $iDatas['id']*1 : null;
        $oDatas['user_id'] = $this->user->id;
        $oDatas['protocol'] = (!empty($iDatas['protocol'])) ? $this->model->quote($iDatas['protocol']) : 'imap' ;
        $oDatas['ssl'] = (!empty($iDatas['ssl'])) ? $iDatas['ssl']*1 : 0 ;
        $oDatas['server'] = $this->model->quote($iDatas['server']);
        $oDatas['login'] = $this->model->quote($iDatas['login']);
        $oDatas['name'] = (!empty($iDatas['name'])) ? $this->model->quote($iDatas['name']) : $oDatas['login'];
        $oDatas['pass'] = $this->model->quote($iDatas['pass']);
        $oDatas['webmail_url'] = $this->model->quote($iDatas['webmail_url']);
        $oDatas['imap_path'] = (!empty($iDatas['imap_path'])) ? $this->model->quote($iDatas['imap_path']) : 'NULL';
        if(!empty($iDatas['port'])){
            $oDatas['port'] = $iDatas['port']*1;
        }elseif($iDatas['protocol'] == 'imap'){
            if($oDatas['ssl'] == 1)
                $oDatas['port'] = 993;
            else
                $oDatas['port'] = 143;
        }elseif($iDatas['protocol'] == 'pop3'){
            if($oDatas['ssl'] == 1)
                $oDatas['port'] = 995;
            else
                $oDatas['port'] = 110;
        }
        $oDatas['tls'] = (!empty($iDatas['tls']) && $iDatas['tls'] == 1) ? 1 : 0;

        return $oDatas;
    }

    public function validMailConf($iDatas)
    {
        $errors = array();

        $required = array('server', 'login', 'pass');

        foreach($required as $require){
            if(empty($iDatas[$require])){
                $errors = $require.' : '.$this->i18n('mailext.required');
            }
        }

        $oReturn[] = empty($errors);
        $oReturn[] = $errors;

        return $oReturn;
    }

}
