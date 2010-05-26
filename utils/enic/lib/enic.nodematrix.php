<?php
class enicNodeMatrix extends enicTree{

    public $right;
    public $member_of;
    public $descendant_of;
    public $admin_of;
    public $kernelChildren;
    public $kernelParent;
    public $type;
    public $id;

    public function startExec(){
        
    }

    public function addExec(){
        $this->_right = new loadRightMatrix();
        $this->member_of = false;
        $this->descendant_of = false;
        $this->admin_of = false;
        $this->kernelChildren = array();
        $this->kernelParent = array();
        $this->nom = 'other';
    }


}

class loadRightMatrix{
    
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
        $this->parent = new loadRightMatrixAction();
        $this->eleve = new loadRightMatrixAction();
        $this->enseignant = new loadRightMatrixAction();
        $this->directeur = new loadRightMatrixAction();
        $this->agent_ville = new loadRightMatrixAction();
        $this->voir = new loadRightMatrixTypeUser();
        $this->communiquer = new loadRightMatrixTypeUser();
    }
    
}

class loadRightMatrixTypeUser{
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

class loadRightMatrixAction{
    
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
