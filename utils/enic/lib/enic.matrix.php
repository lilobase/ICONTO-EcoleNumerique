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
        rightMatrixHelpers::completeMemberNodes($user->type, $user->id);
    }

    public function addExec(){
        //load others
        $this->load('nodeMatrix', '_other');
        
    }

    public function getRight($id = 0){
        return true;
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
            case 'BU_ECOLE';
                $node = $this->_root->villes->ville;
            break;
            case 'villes':
            case 'GVILLE':
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
                trigger_error('Enic Matrix : unknow nodeType : <strong>'.$name.'</strong>', E_USER_ERROR);
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
    public function displayHeader(){
        $html = '<ul>';
        
        return $html;
    }

    public function displayMain(){
        $html = '<li>'.$this->name.'</li>';
        $html .= '<ul>';
        foreach($this->_children as $child){
            $html .= '<li>'.$child->name.'</li>';
            $html .= '<ul>';
                $html .= '<li> Admin : '.($child->admin_of) ? 'true' : 'false' .'</li>';
                $html .= '<li> Member : '.($child->member_of) ? 'true' : 'false' .'</li>';
                $html .= '<li> Descendant : '.($child->descendant_of) ? 'true' : 'false' .'</li>';
                $html .= '<ul>';
                    foreach($child->_right as $key => $right){
                        $html .= '<li>'.$key.' : '.($right) ? 'true' : 'false' .'</li>';
                    }
                $html .= '</ul>';
            $html .= '</ul>';
        }
        $html .= '</ul>';

        return $html;
    }

    public function displayFooter(){
        $html = '</ul>';

        return $html;
    }

}


class rightMatrixHelpers{

    /*
     * get and push the member's parent nodes
     */
    protected static $matrix;

    public static function completeMemberNodes($type, $id, $first = true){
        //get the actual matrix:
        self::$matrix =& enic::get('matrix');
        $kernel = new Kernel();
        foreach($kernel->getNodeParents($type, $id) as $userNode){

            //get the node type :
            $nodeType = $userNode['type'];
            $node = self::$matrix->$nodeType();
            $idNode = '_'.$userNode['id'];
            $node->load('nodeMatrix', $idNode);
            $node->$idNode->nom = (isset($userNode['nom'])) ? $userNode['nom'] : null;

            //detect 'nd apply rigth
            if($userNode['droit'] > 60)
                $node->$idNode->admin_of = true;

            //if the member is direct member
            if($first){
            //in all case : user is member
            $node->$idNode->member_of = true;
            }else{
                $node->$idNode->descendant_of = true;
            }

            //reccursive
            self::completeMemberNodes($userNode['type'], $userNode['id']);

        }
    }

    /*
     * reccursive to create the 'descendant_of' tree
     */
    public static function completeDescendantOf(){

    }

    /*
     * finish the tree with additionnal infos from anothers nodes
     */
    public static function completeChildren(){

    }
}
?>