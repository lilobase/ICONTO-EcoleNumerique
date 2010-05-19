<?php

class enicMatrix extends enicTree {

    public function startExec(){

    }

    public function addExec(){
        $user =& enic::get('user');
        $this->_right = rightMatrix::getRight($user->type);
    }

}
?>
