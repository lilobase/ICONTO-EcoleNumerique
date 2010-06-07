<?php
/*
 * Enic class with user informations
 */

class enicUser extends enicTree {

    public $director;
    public $id;
    public $type;
    public $root;
    public $login;
    public $nom;
    public $prenom;

    public function startExec(){
        $userId = _currentUser()->getId();
        $userInfos = Kernel::getUserInfo('ID', $userId);

        $this->director = false;
        $this->id = $userInfos['id'];
        $this->type = $userInfos['type'];
        $this->root = false;
        $this->login = $userInfos['login'];
        $this->nom = $userInfos['nom'];
        $this->prenom = $userInfos['prenom'];
    }

    public function addExec(){

    }

}
?>
