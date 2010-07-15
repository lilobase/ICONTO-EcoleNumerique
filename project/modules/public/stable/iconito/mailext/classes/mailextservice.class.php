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
class mailExtService {
    
    public function  __construct() {
        $this->model =& enic::get('model');
        $this->user =& enic::get('user');
    }

    public function connect($server, $port, $protocol, $ssl, $user, $pass){
        
        //build connection string
        $server = $server.':'.port;
        $protocol = '/'.$protocol;
        $ssl = ($ssl == true) ? '/ssl/novalidate-cert' : ''; 
        $mailbox = '{'.$server.$port.$protocol.$ssl.'}INBOX';

        return imap_open($mailbox, $user, $pass);
    }
    
    public function check(){
        $confs = $this->getConf();
        
        //test if conf exists
        if(empty($confs))
                return false;
        
        foreach($confs as $conf){
            $connect =& $this->connect();

            //test if connection is right
            if($connect === false){
                $oReturn[$conf['pseudo']] = false;
                continue;
            }

            $oReturn[$conf['pseudo']] = imap_num_recent($connect);

            imap_close($connect);
        }

        return $oReturn;
    }

    //return all conf data's linked the current user
    public function getConf(){
        return $this->model->query('SELECT * FROM module_mailext WHERE user_id = '.$this->user->id)->toArray();
    }

    public function updateMailConf($iDatas){
        $datas = $this->prepareMailConf($iDatas);
        $this->model->update('module_mailext',$datas);
    }

    public function prepareMailConf($iDatas){
        $oDatas['id'] = (!empty($iDatas['id'])) ? $iDatas['id']*1 : null;
        $oDatas['user_id'] = $this->user->id;
        $oDatas['protocol'] = (!empty($iDatas['protocol'])) ? $this->model->quote($iDatas['protocol']) : 'imap' ;
        $oDatas['ssl'] = (!empty($iDatas['ssl'])) ? $iDatas['ssl']*1 : 0 ;
        $oDatas['server'] = $this->model->quote($iDatas['server']);
        $oDatas['pseudo'] = $this->model->quote($iDatas['pseudo']);
        $oDatas['name'] = (!empty($iDatas['name'])) ? $this->quote($iDatas['name']) : $oDatas['pseudo'];
        $oDatas['pass'] = $this->model->quote($iDatas['pass']);
        $oDatas['webmail'] = $this->model->quote($iDatas['webmail']);
        if(!empty($iDatas['port']))
            $iDatas['port']*1;
        elseif($oDatas['protocol'] == 'imap'){
            if($oDatas['ssl'] == 1)
                $oDatas['port'] = 993;
            else
                $oDatas['port'] = 143;
        }elseif($oDatas['protocol'] == 'pop3'){
            if($oDatas['ssl'] == 1)
                $oDatas['port'] = 995;
            else
                $oDatas['port'] = 110;
        }

    }

    public function validMailConf($iDatas){
        $errors = true;

        $required = array('server', 'pseudo', 'pass', 'webmail');

        foreach($required as $require){
            if(empty($iDatas[$require])){
                $errors[$require] = '';
            }
        }
        
    }
    
}
?>
