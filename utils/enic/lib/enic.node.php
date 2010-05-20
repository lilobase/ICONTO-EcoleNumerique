<?php
class enicNode extends enicTree{
    
    public $children;
    public $parent;

    public function startExec(){

    }

    public function addExec(){
        $this->_right = new enicRight();
    }


}

class enicRight{
    
    //right on users type
    public $parent;
    public $eleve;
    public $enseignant;
    public $directeur;
    public $agent_ville;
    public $voir;
    public $communiquer;

    //build right tree
    public function __construct(){
        $this->parent = new enicRightAction();
        $this->eleve = new enicRightAction();
        $this->enseignant = new enicRightAction();
        $this->directeur = new enicRightAction();
        $this->agent_ville = new enicRightAction();
        
        $this->voir = new enicRightTypeUser();
        $this->communiquer = new enicRightTypeUser();
    }
    
}

class enicRightTypeUser{
    //right on users type
    public $parent;
    public $eleve;
    public $enseignant;
    public $directeur;
    public $agent_ville;
    
    //build right tree
    public function __construct(){
        $this->parent = false;
        $this->eleve = false;
        $this->enseignant = false;
        $this->directeur = false;
        $this->agent_ville = false;
    }

}

class enicRightAction{
    
     //right on action
    public $voir;
    public $communiquer;
    
    //build right tree
    public function __construct(){
        $this->voir = false;
        $this->communiquer = false;
    }
    
}
?>
