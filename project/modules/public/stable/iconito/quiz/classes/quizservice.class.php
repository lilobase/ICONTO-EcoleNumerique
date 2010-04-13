<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of quizserviceclass
 *
 * @author arnox
 */
class QuizService {
    public function start($id){
        return true;
    }
}

//function to set or get var from/to session
function qSession($key, $value = false){
    if($value){
        return CopixSession::set('iconito|quiz|'.$key, $value);
    }
    return CopixSession::get('inconito|quiz|'.key);
}
?>
