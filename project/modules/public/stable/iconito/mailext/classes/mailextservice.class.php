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
class mailExtService extends enicService{

    public function connect($server, $port, $protocol, $ssl, $user, $pass){
        
        //build connection string
        $server = $server.':'.$port;
        $protocol = '/'.$protocol;
        $ssl = ($ssl == 1) ? '/ssl/novalidate-cert' : '';
        $mailbox = '{'.$server.$protocol.$ssl.'}INBOX';

        return imap_open($mailbox, $user, $pass);
    }

    /*
     * check new messages
     * return array('name' => (bool)false|(int)nb of new msg);
     */
    public function check(){
        $confs = $this->getConf();
        
        //test if conf exists
        if(empty($confs))
                return false;
        
        foreach($confs as $mail){
            $connect =& $this->connect($mail['server'], $mail['port'], $mail['protocol'], $mail['ssl'], $mail['login'], $mail['pass']); // WARNING BAD ARGUMENTS FOR CONNECT

            //test if connection is right
            if($connect === false){
                $oReturn[$conf['name']] = false;
                continue;
            }

            $oReturn[$conf['name']] = imap_num_recent($connect);

            imap_close($connect);
        }

        return $oReturn;
    }

    public function checkMailConf($iIdMailConf){
        $mail = $this->model->query('SELECT * FROM module_mailext WHERE id = '.(int)$iIdMailConf)->toArray1();
        
        $test = $this->connect($mail['server'], $mail['port'], $mail['protocol'], $mail['ssl'], $mail['login'], $mail['pass']);
        
        return  ($test === false) ? false : true;
    }

    //return all conf data's linked to the current user
    public function getConf(){
        return $this->model->query('SELECT * FROM module_mailext WHERE user_id = '.$this->user->id)->toArray();
    }

    public function checkUserMailConf($iIdMailConf){
        $userIdFromDb = $this->model->query('SELECT user_id FROM module_mailext WHERE id = '.(int)$iIdMailConf)->toString();

        return ($this->user->id == $userIdFromDb);
    }

    public function updateMailConf($iDatas){
        $datas = $this->prepareMailConf($iDatas);
        $this->model->update('module_mailext',$datas);
    }

    public function createMailConf($iDatas){
        $datas = $this->prepareMailConf($iDatas);
        $this->model->create('module_mailext', $datas);
    }

    public function prepareMailConf($iDatas){
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
        if(!empty($iDatas['port']) && $iDatas['port'] != 0){
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

        return $oDatas;
    }

    public function validMailConf($iDatas){
        $errors = array();

        $required = array('server', 'login', 'pass', 'webmail_url');

        foreach($required as $require){
            if(empty($iDatas[$require])){
                $errors = $this->i18n('mailext.required');
            }
        }

        $oReturn[] = empty($errors);
        $oReturn[] = $errors;

        return $oReturn;
    }
    
}
?>
