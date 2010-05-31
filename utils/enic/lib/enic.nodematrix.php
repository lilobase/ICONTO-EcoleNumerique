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
    public $director_of;

    public function startExec(){
        
    }

    public function addExec(){
        $this->_right = new loadRightMatrix();
        $this->member_of = false;
        $this->descendant_of = false;
        $this->admin_of = false;
        $this->kernelChildren = array();
        $this->kernelParent = array();
        $this->director_of = false;
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

    public function __get($name){
        switch ($name){
            case 'USER_RES':
                return $this->parent;
            break;
            case 'USER_ELE':
                return $this->eleve;
            break;
            case 'USER_DIR':
                return $this->directeur;
            break;
            case 'USER_ENS':
                return $this->enseignant;
            break;
            case 'USER_VIL':
                return $this->agent_ville;
            break;
            case 'VOIR':
                return $this->voir;
            break;
            case 'COMM':
                return $this->communiquer;
            break;
        }
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
    public function __get($name){
        switch ($name){
            case 'USER_RES':
                return $this->parent;
            break;
            case 'USER_ELE':
                return $this->eleve;
            break;
            case 'USER_DIR':
                return $this->directeur;
            break;
            case 'USER_ENS':
                return $this->enseignant;
            break;
            case 'USER_VIL':
                return $this->agent_ville;
            break;
        }
    }

    public function __set($name, $value){
        switch ($name){
            case 'USER_RES':
                return $this->parent = $value;
            break;
            case 'USER_ELE':
                return $this->eleve = $value;
            break;
            case 'USER_DIR':
                return $this->directeur = $value;
            break;
            case 'USER_ENS':
                return $this->enseignant = $value;
            break;
            case 'USER_VIL':
                return $this->agent_ville = $value;
            break;
        }
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

    public function __get($name){
        switch ($name){
            case 'VOIR':
                return $this->voir;
            break;
            case 'COMM':
                return $this->communiquer;
            break;
        }
    }

    public function __set($name, $value){
        switch ($name){
            case 'VOIR':
                return $this->voir = $value;
            break;
            case 'COMM':
                return $this->communiquer = $value;
            break;
        }
    }
    
}
?>
