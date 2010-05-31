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

        //fetch and apply right on node :
        rightMatrixHelpers::loadRightOnTree();

        //complet right tree :
        foreach($this->villes->_children as $child){
            rightMatrixHelpers::applyRightOnTree('ville');
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
                $html .= '<li> Director : '.(($this->$child->director_of) ? 'true' : 'false' ).'</li>';
                $html .= '<li> Descendant : '.(($this->$child->descendant_of) ? 'true' : 'false' ).'</li>';
                $html .= '<li> Childrens : '.implode(' ,',$this->$child->kernelChildren).'</li>';
                $html .= '<li> Parent : '.$this->$child->kernelParent.'</li>';
                    foreach($this->$child->_right as $key => $right){
                        $html .='<ul>';
                            $html .= '<li>'.$key.' : </li>';
                            $html .= '<ul>';
                                $attr = get_object_vars($right);
                                if(is_array($attr)){
                                    foreach($attr as $keyi => $righti){
                                        if($key == 'count'){
                                            $html .= '<li>'.$keyi.' : '.$righti. '</li>';
                                            continue;
                                        }
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
    protected static $kernel;

    public static function completeUp($type, $id, $first = true){
        //get the actual matrix:
        $matrix =& enic::get('matrix');

        //get users infos:
        $user =& enic::get('user');

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
            if(isset($userNode['droit']) && ($userNode['droit'] > 60)){
                $node->$idNode->admin_of = true;

                //if user is director
                if($userNode['type'] === 'BU_ECOLE'){
                    $node->$idNode->director_of = true;
                    $user->director = $userNode['id'];
                }

                //SuperAdmin case :
                if($userNode['type'] === 'ROOT'){
                    $node->$idNode->root = true;
                    $user->root = true;
                }
            }            

            //if the member is direct member
            if($first){
                //in all case : user is member
                $node->$idNode->member_of = true;
            }else{
                $node->$idNode->descendant_of = true;
            }

            //reccursive
            self::completeUp($userNode['type'], $userNode['id'], false);

        }

    }

    /*
     * reccursive to create the complet tree
     */
    public static function completeDown($type, $id){
        //load matrix ref object
        $matrix =& enic::get('matrix');
        
        //load kernel
        if(empty(self::$kernel))
            self::$kernel = new Kernel();
        $kernel = self::$kernel;

        //list type of user :
        $userType = array('USER_ENS', 'USER_EXT', 'USER_VIL', 'USER_ELE', 'USER_RES');

        //list child and add each at the Tree
        $children = $kernel->getNodeChilds($type, $id, true, array('skip_user' => true));
        foreach($children as $userNode){
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
            //load parent id :
            $node->$idNode->kernelParent = $id;
            
            //add parent is kernelParent array, club is special case : is attached to root
            if($node->$idNode->type != 'CLUB'){
                $parentNode =  '_'.$node->$idNode->kernelParent;
                $node->_parentObject->$parentNode->kernelChildren[] = $userNode['id'];
            }

            //reccursif
            self::completeDown($userNode['type'], $userNode['id'], false);

        }
    }



    /*
     * finish the tree with additionnal infos from anothers nodes
     */
    public static function loadRightOnTree(){
        //get enic Model library
        $db =& enic::get('model');

        //get user infos
        $user =& enic::get('user');

        //matrix :
        $matrix =& enic::get('matrix');

        //get the right for the type of user
        $datas = $db->query('SELECT * FROM module_rightmatrix WHERE user_type_in = \''.$user->type.'\'')->toArray();
        $db->close();

        //if user is director :
        if($user->director !== false)
            $datas = array_merge($db->query('SELECT * FROM module_rightmatrix WHERE user_type_in = \'USER_DIR\'')->toArray(), $datas);

        //load right only on descendant_of node :
        foreach($datas as $data){
            $node = $matrix->$data['node_type']();
            foreach($node->_children as $child){
                if($node->$child->descendant_of !== true && $node->$child->descendant_of !== true)
                    continue;
                $node->$child->_right->$data['right']->$data['user_type_out'] = true;
                $node->$child->_right->$data['user_type_out']->$data['right'] = true;
                $node->$child->_right->count->$data['right']++;
            }
        }
    }

    /*
     * final treatment
     */
    public static function applyRightOnTree($name){
        //get user infos
        $user = enic::get('user');

        //matrix :
        $matrix =& enic::get('matrix');

        //get node
        $node = $matrix->$name();
        $parentNode = $node->_parentObject;

        //apply right from parent :
        foreach($parentNode->_children as $child){
            //get right from children
            foreach($parentNode->$child->_right as $key => $right){
                //pass count infos :
                if($key == 'count')
                    continue;
                
                foreach($right as $keyi => $righti){
                    if($righti === false)
                        continue;

                    
                    //apply right on children :
                    foreach($parentNode->$child->kernelChildren as $kChild){
                        $idNode = '_'.$kChild;
                        $node->$idNode->_right->$key->$keyi = $righti;
                       
                        //add count infos :
                        if($key == 'voir' || $key == 'communiquer')
                            $node->$idNode->_right->count->$key += 1;
                    }
                }
            }
        }

        //reccursive !!!! :D
        if($node->_child == false)
            return true;
        else
            return self::applyRightOnTree($node->_child);
    }
}
?>