<?php

class enicMatrix extends enicTree {

    public function startExec(){
        
    }

    public function addExec(){
        $user =& enic::get('user');
        $this->_right = rightMatrix::getRight($user->type);
    }

    public function getRight($id = 0){
        return true;
    }

    public function  __call($name,  $arguments) {

        //make _id node
        $idNode = '_'.$arguments[0];

        //get ref from type node
        switch ($name){

            case 'classe':
                $node = $this->_root->villes->ville->ecole->classe;
            break;
            case 'ecole':
                $node = $this->_root->villes->ville->ecole;
            break;
            case 'ville':
                $node = $this->_root->villes->ville;
            break;
            case 'villes':
                $node = $this->_root->villes;
            break;

            default:
                trigger_error('Enic Matrix : unknow nodeType', E_USER_ERROR);
                return false;
            break;

        }

        //if id is not define : return global right on type node
        if(!isset($node->$idNode))
            return $node->_other;

        //in other case : return node
        return $node->$idNode;


    }

}
?>



$this->matrix->villes->ville->ecole->_id->_right->communiquer->enseignants;
$right = $this->matrix->ecole($id)->_right;
$right->communiquer->enseignants;
$right->enseignants;
