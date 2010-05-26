<?php

class enicMatrix extends enicList {

    public function startExec(){
        //get user info
        $user =& enic::get('user');

        //build the matrix list
        $this->add('villes')->add('ville')->add('ecole')->add('classe');

        //load the groupe node in matrix
        $this->load('nodeMatrix', 'groupes');

        //load the other groups
        $this->groupes->load('nodeMatrix', '_other');
        
        //start the iteration to complete the nodes when the user is member
        rightMatrixHelpers::completeUp($user->type, $user->id);

        //foreach GRVILL : complete tree :
        foreach($this->villes->_children as $child){
            if($this->villes->$child->nom == 'other')
                continue;
            rightMatrixHelpers::completeDown('BU_GRVILLE', $this->villes->$child->id);
        }
    }

    public function addExec(){
        //load others
        $this->load('nodeMatrix', '_other');
        
    }

    public function __get($name){
        return false;
    }

    public function  __call($name, $arguments = null) {

        //make _id node
        if(!empty($arguments))
            $idNode = '_'.$arguments[0];

        $bool = (isset($arguments[1])) ? $arguments[1] : false;

            //get ref from type node
        switch ($name){

            case 'classe':
            case 'CLASSE':
            case 'BU_CLASSE':
                $node = $this->_root->villes->ville->ecole->classe;
            break;
            case 'ecole':
            case 'ECOLE':
            case 'BU_ECOLE':
                $node = $this->_root->villes->ville->ecole;
            break;
            case 'ville':
            case 'VILLE':
            case 'BU_VILLE';
                $node = $this->_root->villes->ville;
            break;
            case 'villes':
            case 'GVILLE':
            case 'BU_GRVILLE':
                $node = $this->_root->villes;
            break;
            case 'CLUB':
            case 'groupes':
                $node = $this->_root->groupes;
            break;
            case 'ROOT':
                $node = $this->_root;
            break;

            default:
                trigger_error('Enic Matrix : unknow nodeType or wrong method : <strong>'.$name.'</strong>', E_USER_ERROR);
                return false;
            break;

        }
       //if no id : return $node
        if(!isset($idNode))
            return ($bool) ? true : $node;

        //if id is not define : return global right on type node
        if(!isset($node->$idNode))
            return ($bool) ? false : $node->_other;

        //in other case : return node
        return ($bool) ? true : $node->$idNode;
    }
    
    
    /*
     * DISPLAY THE WHOLE TREE WITH RIGHT
     */
    public function displayMain(){
        $html = '<li>'.$this->_name.'</li>';
        $html .= '<ul>';
        foreach($this->_children as $child){
            $html .= '<li>'.$this->$child->nom.'</li>';
            $html .= '<ul>';
                $html .= (isset($this->$child->id)) ? '<li>Id : '.$this->$child->id.'</li>' : '';
                $html .= (isset($this->$child->type)) ? '<li>Type : '.$this->$child->type.'</li>' : '';
                $html .= '<li>-----</li>';
                $html .= '<li> Admin : '.(($this->$child->admin_of) ? 'true' : 'false' ).'</li>';
                $html .= '<li> Member : '.(($this->$child->member_of) ? 'true' : 'false' ).'</li>';
                $html .= '<li> Descendant : '.(($this->$child->descendant_of) ? 'true' : 'false' ).'</li>';
                    foreach($this->$child->_right as $key => $right){
                        $html .='<ul>';
                            $html .= '<li>'.$key.' : </li>';
                            $html .= '<ul>';
                                $attr = get_object_vars($right);
                                if(is_array($attr)){
                                    foreach($attr as $keyi => $righti){
                                        $html .= '<li>'.$keyi.' : '.(($righti) ? 'true' : 'false' ). '</li>';
                                    }
                                }else{
                                    $html .= '<li>No RIGHT </li>';
                                }
                            $html .= '</ul>';
                        $html .= '</ul>';
                    }
            $html .= '</ul>';
        }
        $html .= '</ul>';

        return $html;
    }

}


class rightMatrixHelpers{

    /*
     * get and push the member's parent nodes and parent's of parents (reccursive)
     */
    protected static $matrix;

    public static function completeUp($type, $id, $first = true){
        //get the actual matrix:
        $matrix = self::getMatrix();

        //get kernel
        $kernel = new Kernel();

        //list parents and add each at the tree
        foreach($kernel->getNodeParents($type, $id) as $userNode){

            //get the node type :
            $nodeType = $userNode['type'];
            $node = $matrix->$nodeType();
            $idNode = '_'.$userNode['id'];
            
            //if already exists : pass the loading
            if(!isset($node->$idNode)){
                $node->loadOnce('nodeMatrix', $idNode);
                $node->$idNode->nom = (isset($userNode['nom'])) ? $userNode['nom'] : null;
                $node->$idNode->type = $userNode['type'];
                $node->$idNode->id = $userNode['id'];
            }
            
            //detect 'nd apply rigth
            if(isset($userNode['droit']) && ($userNode['droit'] > 60))
                $node->$idNode->admin_of = true;

            //if the member is direct member
            if($first){
                //in all case : user is member
                $node->$idNode->member_of = true;
            }else{
                $node->$idNode->descendant_of = true;
            }

            //add parent in kernelParent
            $node->$idNode->kernelParent = $id;

            //reccursive
            self::completeUp($userNode['type'], $userNode['id'], false);

        }

    }

    /*
     * reccursive to create the complet tree
     */
    public static function completeDown($type, $id){
        //load matrix ref object
        $matrix = self::getMatrix();
        
        //load kernel
        $kernel = new Kernel();

        //list type of user :
        $userType = array('USER_ENS', 'USER_EXT', 'USER_VIL', 'USER_ELE', 'USER_RES');

        //list child and add each at the Tree
        foreach($kernel->getNodeChilds($type, $id) as $userNode){

            //if is a user : pass
            if(in_array($userNode['type'], $userType))
                continue;

            //get the node type :
            $nodeType = $userNode['type'];

            //call the ref to node type in matrix list
            $node = $matrix->$nodeType();
            $idNode = '_'.$userNode['id'];

            //if already exists : pass the loading
            if(!isset($node->$idNode)){
                //load node with informations
                $node->loadOnce('nodeMatrix', $idNode);
                $node->$idNode->nom = (isset($userNode['nom'])) ? $userNode['nom'] : null;
                $node->$idNode->type = $userNode['type'];
                $node->$idNode->id = $userNode['id'];
            }

            //add parent is kernelParent array
            $node->$idNode->kernelChildren[] = $id;

            self::completeDown($userNode['type'], $userNode['id'], false);

        }
    }

    /*
     * get Matrix (singleton)
     */
    protected static function getMatrix(){
        if(empty(self::$matrix))
           self::$matrix =& enic::get('matrix');
        return self::$matrix;
    }



    /*
     * finish the tree with additionnal infos from anothers nodes
     */
    public static function completeChildren(){

    }
}
?>