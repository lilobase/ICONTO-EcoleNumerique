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
        if(_currentUser()->isConnected()){
            $userId = _currentUser()->getId();
            $userInfos = Kernel::getUserInfo('ID', $userId);

            $this->director = false;
            $this->idEn = $userInfos['id'];
            $this->id = $userId;
            $this->type = $userInfos['type'];
            $this->root = false;
            $this->login = $userInfos['login'];
            $this->nom = $userInfos['nom'];
            $this->prenom = $userInfos['prenom'];
            $this->connected = true;
        }else{
            $this->director = false;
            $this->id = 0;
            $this->type = 'USER_ANON';
            $this->root = false;
            $this->login = 'Anon';
            $this->nom = 'Anon';
            $this->prenom = 'Anon';
            $this->connected = false;
        }
    }

    public function addExec(){

    }

}
?>
