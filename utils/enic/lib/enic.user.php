<?php
/*
 * Enic class with user informations
 */

class enicUser extends enicTree {

    public function startExec(){
        $userId = _currentUser()->getId();
        $userInfos = Kernel::getUserInfo('ID', $userId);

        $this->id = $userInfos['id'];
        $this->type = $userInfos['type'];
        $this->login = $userInfos['login'];
        $this->nom = $userInfos['nom'];
        $this->prenom = $userInfos['prenom'];
    }

    public function addExec(){

    }

}
?>
