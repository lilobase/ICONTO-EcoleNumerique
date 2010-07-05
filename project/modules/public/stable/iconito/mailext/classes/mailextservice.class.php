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
    
}
?>
