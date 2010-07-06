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
        $mailbox = '{'.$server.$port.$protocol.$ssl.'}';

        $this->connection = imap_open($mailbox, $user, $pass);

        return $this->connection;
    }
    
    public function check(){
       return imap_num_recent($this->connection);
    }

    //return all conf data's linked the current user
    public function getMailConf(){
        return $this->model->query('SELECT * FROM module_mailext WHERE user_id = '.$this->user->id)->toArray();
    }

    public function updateMailConf($iDatas){

    }

    public function prepareMailConf($iDatas){
        $oDatas['id'] = $iDatas['id']*1;
        $oDatas['user_id'] = $this->user->id;
        $oDatas['protocol'] = (!empty($iDatas['protocol'])) ? $this->model->quote($iDatas['protocol']) : 'imap' ;
        $oDatas['ssl'] = (!empty($iDatas['ssl'])) ? $iDatas['ssl']*1 : 0 ;
        $oDatas['server'] = $this->model->quote($iDatas['server']);
        $oDatas['pseudo'] = $this->model->quote($iDatas['pseudo']);
        $oDatas['pass'] = $this->model->quote($iDatas['pass']);

    }

    public function validMailConf($iDatas){

    }
    
}
?>
