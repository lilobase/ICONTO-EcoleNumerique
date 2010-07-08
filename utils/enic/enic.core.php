<?php

/*
 * CONFIG 
 */
define('ENIC_PATH',dirname (__FILE__) . '/');

//get enic Action Group
require(ENIC_PATH.'enic.actiongroup.php');
//get enic Zone
require(ENIC_PATH.'enic.zone.php');

/*
 * FACTORY
 */
class enic{

    //internal link
    static $l = array();

    /*
     * load block in global container
     */
    public static function load($type, $name = null){

        //load file 
        enic::to_load($type);

        //get name
        $name = (!empty($name)) ? enic::sanitize($name) : $type;

        if(in_array($name, self::$l))
            trigger_error('Item <em>'.$name.'</em> in <strong>ENIC ROOT CORE</strong> already exists', E_USER_WARNING);

        //load class in local ref
        $className = 'enic'.ucfirst($type);
        self::$l[$name] =& new $className();

        //add name attribute
        self::$l[$name]->_name = $name;

        //execute the startExec
        self::$l[$name]->startExec();

        //test if the class is a containor class :
        if(method_exists(self::$l[$name], 'getClass')){
            self::$l[$name] =& self::$l[$name]->getClass();
        }

        return self::$l[$name];
    }

    /*
     * Load only CLass' file & check if the class is callable
     */
    public static function to_load($type){

        //get class name
        $className = 'enic'.ucfirst($type);

        //test if class or file exists
        if(!class_exists($className) && !interface_exists($className) && !file_exists(ENIC_PATH.'/lib/enic.'.strtolower($type).'.php'))
             trigger_error('Enic File missing : '.strtolower($type), E_USER_ERROR);

        //require class file
        if(!class_exists($className) && !interface_exists($className))
            require_once(ENIC_PATH.'/lib/enic.'.strtolower($type).'.php');

        //test if class is callable
        if(!class_exists($className) && !interface_exists($className))
            trigger_error('Enic class missing : '.$className, E_USER_ERROR);
    }

    /*
     * get the named object
     */
    public static function get($type, $name = null){

        //get name
        $name = (!empty($name)) ? enic::sanitize($name) : $type;

        //if exists return object
        if(isset(self::$l[$name]))
            return self::$l[$name];
        
        //else create new object
        return self::load($type, $name);
    }
    
    /*
     * sanitize a string
     */
    public static function sanitize($name){
        $str = strtr(strtolower(trim($name)), "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ","aaaaaaooooooeeeeciiiiuuuuyn");
	$str = preg_replace('#([^.a-z0-9]+)#i', '_', $str);
        $str = preg_replace('#_{2,}#','_',$str);
        $str = preg_replace('#_$#','',$str);
//        $str = preg_replace('#^_#','',$str);
	return $str;
    }

}

class enicTree{

    //array of children's name
    public $_children;

    //name to parent object
    public $_parent;

    //type of object
    public $_type;

    //ref to root item
    public $_root;

    //content
    public $_content;

    //ref to parent object
    public $_parentObject;

    //level of current item
    public $_level;
    
    //using in start/end group
    protected $_jump;
    protected $_lock;
    
    //global datas storage
    protected $_datas;



    /*
     * set default value
     */
    public function __construct(){
        $this->_jump = false;
        $this->_parent = false;
        $this->_children = array();
        $this->_type = get_class($this);
        $this->_root = $this;
        $this->_lock = false;
        $this->_name = false;
        $this->_parentObject = $this;
        $this->_level = 0;
    }

    /*
     * add new item
     * linkable
     */
    public function add($name, $content=null, $opt=null, $type = false){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        //load type if defined else get current type
        $className = ($type !== false) ? 'enic'.ucfirst($type) : get_class($this);
                
        //test if object already exists
        if(isset($this->$nameStr))
            trigger_error('item <em>'.$nameStr.'</em> in <strong>'.$className.'</strong> already exists', E_USER_WARNING);
        
        //load object
        $this->$nameStr = new $className();
        $this->_children[] = $nameStr;
        $this->$nameStr->_parent = enic::sanitize($this->_name);
        $this->$nameStr->_parentObject = $this;

        //load root item
        $this->$nameStr->_root = $this->_root;

        //load child's data
        $this->$nameStr->_name = $name;
        $this->$nameStr->_content = $content;
        $this->$nameStr->_opt = $opt;

        //set level
        $this->$nameStr->_level = $this->_level+1;

        //exec the addExec methode
        $this->$nameStr->addExec();
        
        //if jump : lock the child
        if($this->_jump === true)
            $this->$nameStr->_lock = true;
        
        //return child or current object to link methods
        if($this->_lock){
            return $this;
        }else{
            $this->_jump = false;
            return $this->$nameStr;
        }
    }

    /*
     * function call at each add action
     */
    public function addExec(){

        return true;
    }

    /*
     * modify existing item
     * linkable
     */
    public function set($name, $content=false, $opt=false){
        //create computer readable name
        $object = $this->get($name);

        //test if the object exists:
        if($object == false){
            trigger_error('error to set new values in item : <em>'.$name.'</em> not exists', E_USER_WARNING);
            return false;
        }

        //change content if is define
        if($content !== false)
            $object->_content = $content;

        //change options id is define
        if($opt !== false)
            $object->_opt = $opt;

        return $this;
    }

    /*
     * delete item
     */
    public function del($name){
         //create computer readable name
        $nameStr = enic::sanitize($name);

        //get key assoc to value
        $childKey = array_search($nameStr, $this->_children);

        //test if child is define
        if($childKey === false){
            trigger_error('error to delete item : <em>'.$name.'</em> not exists', E_USER_WARNING);
            return false;
        }

        //delete child
        unset($this->$nameStr);
        unset($this->_children[$childKey]);

        return true;
    }

    /*
     * ***
     */
    public function action(){
    }

    /*
     * method called in the display process
     */
    public function display($topLimit = 0, $bottomLimit = 0){
        //call display functions :
        $html = '';
        //if root item : no display and check the level of current item
        if($this->_level != 0 &&
                ($this->_level >= $topLimit || $topLimit == 0) ){
            $html .= $this->displayHeader();
            $html .= $this->displayMain();
        }

        //get display from child & test the item level
        if(($this->_level < $bottomLimit || $bottomLimit == 0) && !empty($this->_children))
            foreach($this->_children as $children)
                $html .= $this->$children->display($topLimit, $bottomLimit);

        //if root item : no display and check the level of current item
        if($this->_level !== 0 && ($this->_level >= $topLimit || $topLimit == 0) ){
            //end the display process
            $html .= $this->displayFooter();
        }

        //return generated html
        return $html;
    }

    /*
     * main Display
     */
    public function displayMain(){

        return '';
    }

    /*
     * display in the forward chain
     */
    public function displayHeader(){

        return '';
    }

    /*
     * display at the return chain
     */
     public function displayFooter(){

         return '';
     }

    /*
     * freeze node level
     * linkable
     */
    public function startGroup(){
        $this->_jump = true;
        return $this;
    }

    /*
     * stop level freezing
     * linkable
     */
    public function endGroup(){
        //unlock current object
        $this->_lock = false;
        
        //if no parents : return current object
        if($this->_level === 0)
            return $this;
        
        return $this->_parentObject;
    }

    /*
     * go forward to item in object tree
     * return object
     */
    public function go($name, $first = true){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        //array of matched item
        $refReturn = array();

        foreach($this->_children as $children){
            //search if the searched item is in direct children
            if($nameStr == $children){
                
                if($first)
                    return $this->$children;
                else
                    $refReturn[] = $this->$children;
            }else{
                $return = $this->$children->go($nameStr);

                //if not match continue to next child
                if($return === false)
                    continue;

                if($first)
                    return $return;
                else
                    $refReturn[] = $return;
            }
        }

        return (empty($refReturn)) ? false : $refReturn;
    }

    /*
     * search array of matched item
     */
    public function search($name){
        return $this->go($name, false);
    }

    /*
     * Go back in the object tree
     * use ':first' to go at the root
     * return object
     */
    public function back($name = false){

         //if name = :first : go to root
         if($name == ':first')
            return $this->_root;

         //test if is the current object :
         if($this->_name == $name)
            return $this;

         //test if is the parent :
         if($this->_parent == $name)
            return $this->_parentObject;

         //if is the first elem : return false
         if($this->_level == 0)
            return false;
         //other case : reccursive to parent
         return $this->_parentObject->back($name);
    }

    /*
     * load new type of item in object tree
     * linkable
     */
    public function load($type, $name, $content=null, $opt=null){
        //test if class already loaded by enic core
        $className = 'enic'.ucfirst($type);
        if(!class_exists($className))
            enic::to_load($type);

        return $this->add($name, $content, $opt, $type);
    }

    /*
     * load_once
     */
    public function loadOnce($type, $name, $content=null, $opt=null){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        if(isset($this->$nameStr))
            return $this;

        return $this->load($type, $name, $content, $opt);
    }

    /*
     * get specific named object
     * linkable
     */
    public function get($name){
        return $this->_root->go($name);
    }
    
    /*
     * function executed only once at start
     */
    public function startExec(){
        return true;
    }

    /*
     * set data to entire tree
     */
    public function setDatas($name, $value){
        $this->_root->_datas[$name] = $value;
        return $this;
    }

    /*
     * get data from global container
     */
    public function getDatas($name){
        if(isset($this->_root->_datas[$name]))
            return $this->_root->_datas[$name];
        else
            return false;
    }
    /*
     * debug function
     */
    public function debug(){
        echo '=======<br />';
        echo 'NAME : '.$this->_name.'<br />'.PHP_EOL;
        echo 'CONTENT '.var_dump($this->_content).'<br />'.PHP_EOL;
        echo 'TYPE : '.$this->_type.'<br />'.PHP_EOL;
        echo 'PARENT : '.$this->_parent.'<br />'.PHP_EOL;
        echo 'LEVEL : '.$this->_level.'<br />'.PHP_EOL;
        echo 'CONTENT '.print_r($this->_children).'<br />'.PHP_EOL;
        foreach($this->_children as $children){
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            $this->$children->debug().'<br />'.PHP_EOL;
        }
    }
}

abstract class enicList{
    
    //child's name
    public $_child;

    //child's object
    public $_childObject;

    //global data container
    public $_datas;

    //set level of each item
    public $_level;

    //name of parent
    public $_parent;

    //parent's object
    public $_parentObject;

    //ref to root
    public $_root;

    //type of item
    public $_type;

    //children (not in reccursive function)
    public $_children;

    public function __construct(){

        //init var :
        $this->_level = 0;
        $this->_parent = false;
        $this->_parentObject = $this;
        $this->_root = $this;
        $this->_type = get_class($this);
        $this->_children = array();
        $this->_name = false;
        $this->_child = false;

    }

    /*
     * add item
     * linkable
     */
     public function add($name, $content = null, $opt = null, $type = false){

         //test if item has already a child :
         if(!empty($this->_child))
            trigger_error('item <strong>'.$className.'</strong> is list : only one child in '.get_class($this), E_USER_WARNING);

        //create computer readable name
        $nameStr = enic::sanitize($name);

        //load type if defined else get current type
        $className = ($type !== false) ? 'enic'.ucfirst($type) : get_class($this);

        //test if object already exists
        if(isset($this->$nameStr))
            trigger_error('item <em>'.$nameStr.'</em> in <strong>'.$className.'</strong> already exists', E_USER_WARNING);

        //load object
        $this->_childObject = new $className();
        $this->_child = $nameStr;
        $this->_childObject->_parent = enic::sanitize($this->_name);
        $this->_childObject->_parentObject = $this;

        //load root item
        $this->_childObject->_root = $this->_root;

        //load child's data
        $this->_childObject->_name = $name;
        $this->_childObject->_content = $content;
        $this->_childObject->_opt = $opt;

        //set level
        $this->_childObject->_level = $this->_level+1;

        //exec the addExec methode
        $this->_childObject->addExec();

        //link name with ref
        $this->$nameStr = $this->_childObject;

        //return child
            return $this->_childObject;
     }

     /*
      * exec when item added
      * overloading
      */
     public function addExec(){
        return true;
     }

     /*
      * exec at the list initiate
      * overloading
      */
     public function startExec(){
         return true;
     }

     /*
      * exec in reccursivity
      * overloading
      */
     public function action(){
         return true;
     }

     /*
      * go back in list
      * linkable
      */
     public function back($name = ':first'){

         //if name = :first : go to root
         if($name == ':first')
            return $this->_root;

         //test if is the current object :
         if($this->_name == $name)
            return $this;

         //test if is the parent :
         if($this->_parent == $name)
            return $this->_parentObject;

         //if is the first elem : return false
         if($this->_level == 0)
            return false;
         //other case : reccursive to parent
         return $this->_parentObject->back($name);
     }

     /*
      * go forward in list
      */
     public function go($name){
         //test if is the current object :
         if($this->_name == $name)
            return $this;

         //test if is child
         if($this->_child == $name)
            return $this->_childObject;

         //if is the end of the list : return false
         if(empty($this->_child))
            return false;

         //another case : go forward
         return $this->_childObject->go($name);

     }

     /*
      * get an item
      */
     public function get($name){
         return $this->_root->go($name);
     }


     /*
      * set new data in existing object
      */
    public function set($name, $content=false, $opt=false){
        //create computer readable name
        $object = $this->get($name);

        //test if the object exists:
        if($object == false){
            trigger_error('error to set new values in item : <em>'.$name.'</em> not exists', E_USER_WARNING);
            return false;
        }

        //change content if is define
        if($content !== false)
            $object->_content = $content;

        //change options id is define
        if($opt !== false)
            $object->_opt = $opt;

        return $this;
    }

    /*
     * del an item
     */
    public function del($name){
        //get object
        $object = $this->get($name);

        //test if object exists
        if($object == false){
            trigger_error('error to set new values in item : <em>'.$name.'</em> not exists', E_USER_WARNING);
            return false;
        }

        //del item
        unset($object);

        return true;
    }

     /*
      * set data in the global container
      */
     public function setDatas($name, $value){
         $this->_root->_datas[$name] = $value;
         return $this;
     }

     public function setDatasArray($key, $value){
         $this->_root->_datas[$key][] = $value;
         return $this;
     }

     /*
      * get data from the global container
      */
    public function getDatas($name){
        if(isset($this->_root->_datas[$name]))
            return $this->_root->_datas[$name];
        else
            return false;
    }

    /*
     * display before main & child
     * overloading
     */
    public function displayHeader(){
        return '';
    }

    /*
     * display after header 'nd before child's display
     */
    public function displayMain(){
        return '';
    }

    /*
     * display at the end of the display process
     */
    public function displayFooter(){
        return '';
    }

    /*
     * display process
     */
     public function display($topLimit = 0, $bottomLimit = 0){
        //call display functions :
        $html = '';
        //if root item : no display and check the level of current item
        if($this->_level != 0 &&
                ($this->_level >= $topLimit || $topLimit == 0) ){
            $html .= $this->displayHeader();
            $html .= $this->displayMain();
        }

        //get display from child & test the item level
        if(($this->_level < $bottomLimit || $bottomLimit == 0) && !empty($this->_child))
                $html .= $this->_childObject->display($topLimit, $bottomLimit);

        //if root item : no display and check the level of current item
        if($this->_level !== 0 && ($this->_level >= $topLimit || $topLimit == 0) ){
            //end the display process
            $html .= $this->displayFooter();
        }

        //return generated html
        return $html;
    }

    /*
     * attache childrens to a node list
     */
    public function load($type, $name, $content=null, $opt=null){
        //test if class already loaded by enic core
        $className = 'enic'.ucfirst($type);
        if(!class_exists($className))
            enic::to_load($type);

        //create computer readable name
        $nameStr = enic::sanitize($name);

        //load type if defined else get current type
        $className = ($type !== false) ? 'enic'.ucfirst($type) : get_class($this);

        //test if object already exists
        if(isset($this->$nameStr))
            trigger_error('item <em>'.$nameStr.'</em> in <strong>'.$className.'</strong> already exists', E_USER_WARNING);

        //load object
        $this->$nameStr = new $className();
        $this->_children[] = $nameStr;
        $this->$nameStr->_parent = enic::sanitize($this->_name);
        $this->$nameStr->_parentObject = $this;

        //load root item
        $this->$nameStr->_root = $this->_root;

        //load child's data
        $this->$nameStr->_name = $name;
        $this->$nameStr->_content = $content;
        $this->$nameStr->_opt = $opt;

        //set level
        $this->$nameStr->_level = $this->_level+1;

        //exec the addExec methode
        $this->$nameStr->addExec();

        //return current objet
            return $this;
    }

    /*
     * load_once
     */
    public function loadOnce($type, $name, $content=null, $opt=null){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        if(isset($this->$nameStr))
            return $this;

        return $this->load($type, $name, $content, $opt);
    }

    /*
     * test if item exists
     */
    public function exists($name){
         //test if is the current object :
         if($this->_name == $name)
            return true;

         //test if is child
         if($this->_child == $name)
            return true;

         //if is the end of the list : return false
         if(empty($this->_child))
            return false;

         //another case : go forward
         return $this->_childObject->exists($name);
    }

}

/*
 * Error CLASS
 */
//desactivated 
//set_error_handler("enicErrors::errorHandler", E_ALL);
class enicErrors{

    public static function errorHandler($errno, $errstr, $errfile, $errline){
        switch($errno){

            case E_ERROR:
            case E_USER_ERROR:
                $errTitle = 'ENIC ERROR REPORTING';
                $html = file_get_contents(ENIC_PATH.'/html/errors.html', true);
                $html = str_replace('{title}', $errTitle, $html);
                $html = str_replace('{errstr}', $errstr, $html);
                $html = str_replace('{errfile}', $errfile, $html);
                $html = str_replace('{errline}', $errline, $html);

                echo $html;
                exit;
            break;

            case E_WARNING:
            case E_USER_WARNING:
                $color = 'red';
            break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $color = 'maroon';
            break;

        }
        $html = file_get_contents(ENIC_PATH.'/html/errors.inline.html', true);
        $html = str_replace('#color', $color, $html);
        $html = str_replace('{errstr}', $errstr, $html);
        $html = str_replace('{errfile}', $errfile, $html);
        $html = str_replace('{errline}', $errline, $html);

        echo $html;

        return true;
    }
}


abstract class enicMod{

    public function __construct(){

    }

    public function startExec(){

    }

}