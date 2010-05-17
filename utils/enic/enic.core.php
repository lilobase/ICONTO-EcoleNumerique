<?php

/*
 * CONFIG 
 */
define('ENIC_PATH',dirname (__FILE__) . '/');


/*
 * FACTORY
 */
class enic{
    /*
     * load block in global container
     */
    public function load($type, $name = null){

        //load file 
        enic::to_load($type);

        //get name
        $name = (!empty($name)) ? enic::sanitize($name) : $type;

        if(isset($this->$name))
            trigger_error('Item <em>'.$name.'</em> in <strong>ENIC ROOT CORE</strong> already exists', E_USER_WARNING);

        //load class in local ref
        $className = 'enic'.ucfirst($type);
        $this->$name = new $className();

        //execute the startExec
        $this->$name->startExec();

        return $this->$name;
    }

    /*
     * Load only CLass' file & check if the class is callable
     */
    public static function to_load($type){
        //test if file exists
        if(!file_exists(ENIC_PATH.'/lib/enic.'.strtolower($type).'.php'))
             trigger_error('Enic File missing : '.$type, E_USER_ERROR);

        //require class file
        require_once(ENIC_PATH.'/lib/enic.'.$type.'.php');

        //get class name
        $className = 'enic'.ucfirst($type);

        //test if class is callable
        if(!class_exists($className))
            trigger_error('Enic class missing : '.$className, E_USER_ERROR);
    }
    
    /*
     * sanitize a string
     */
    public static function sanitize($name){
        $str = strtr(strtolower(trim($name)), "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ","aaaaaaooooooeeeeciiiiuuuuyn");
	$str = preg_replace('#([^.a-z0-9]+)#i', '_', $str);
        $str = preg_replace('#_{2,}#','_',$str);
        $str = preg_replace('#_$#','',$str);
        $str = preg_replace('#^_#','',$str);
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
    
    //using in start/end group
    protected $_jump;
    protected $_lock;


    /*
     * set default value
     */
    public function __construct(){
        $this->_jump = false;
        $this->_parent = false;
        $this->_children = array();
        $this->_type = get_class($this);
        $this->_root = false;
        $this->_lock = false;
        $this->_name = false;
        $this->_parentObject = $this;
    }

    /*
     * add new item
     * linkable
     */
    public function add($name, $content=null, $opt=null, $type = false){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        //load type if defined else get current type
        $className = ($type !== false) ? $type : get_class($this);
                
        //test if object already exists
        if(isset($this->$nameStr))
            trigger_error('item <em>'.$nameStr.'</em> in <strong>'.$className.'</strong> already exists', E_USER_WARNING);
        
        //load object
        $this->$nameStr = new $className();
        $this->_children[] = $nameStr;
        $this->$nameStr->_parent = enic::sanitize($this->_name);
        $this->$nameStr->_parentObject = $this;

        //load root item
        if($this->_root === false)
            $this->_root = $this->back(':first');
        $this->$nameStr->_root = $this->_root;

        //load child's data
        $this->$nameStr->_name = $name;
        $this->$nameStr->_content = $content;
        $this->$nameStr->_opt = $opt;

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
     * modify existing item
     * linkable
     */
    public function set($name, $content=false, $opt=false){
        //create computer readable name
        $nameStr = enic::sanitize($name);

        //test if child is define
        if(!isset($this->$nameStr)){
            trigger_error('error to set new values in item : <em>'.$name.'</em> not exists', E_USER_WARNING);
            return false;
        }

        //change content if is define
        if($content !== false){
            $this->$nameStr->_content = $content;
        }

        //change options id is define
        if($opt !== false){
            $this->$nameStr->_opt = $opt;
        }

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
    public function display($childDisplay = true){
        //call display functions :
        $html = '';

        //if root item : no display
        if($this->_parent !== false){
            $html .= $this->displayIn();
            $html .= $this->displayMain();
        }

        //get display from child
        if($childDisplay !== false || empty($this->_children))
            foreach($this->_children as $children)
                $html .= $this->$children->display($childDisplay);

        //if root item : no display
        if($this->_parent !== false){
            //end the display process
            $html .= $this->displayOut();
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
    public function displayIn(){

        return '';
    }

    /*
     * display at the return chain
     */
     public function displayOut($html){

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
        if($this->_parent === false)
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
        //create computer readable name
        $nameStr = enic::sanitize($name);

        //get parent name
        $parent = $this->_parent;

        //go to root item
        if($name == ':first' || $name === false){
            //if it's the first : he has no parent
            if($parent === false)
                return $this;
            
            //another case : return root
            $this->_root;
        }

        //if parent is root : return false
        if($parent === false)
            return false;

        //if parent found : return parentObject
        if($nameStr == $parent)
            return $this->_parentObject;

        //another case : continue to level up
        $this->_parentObject->back($nameStr);
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
     * debug function
     */
    public function debug(){
        echo '=======<br />';
        echo 'NAME : '.$this->_name.'<br />'.PHP_EOL;
        echo 'CONTENT '.var_dump($this->_content).'<br />'.PHP_EOL;
        echo 'TYPE : '.$this->_type.'<br />'.PHP_EOL;
        echo 'PARENT : '.$this->_parent.'<br />'.PHP_EOL;
        foreach($this->_children as $children){
            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
            $this->$children->debug().'<br />'.PHP_EOL;
        }
    }
}

/*
 * Error CLASS
 */
set_error_handler("enicErrors::errorHandler", E_ALL);
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



//enic load :
$enic = new enic();
$menu =& $enic->load('menu');


$menu->add('player')->startGroup()
                        ->add('framasoft')
                        ->startGroup()
                            ->add('toto')
                            ->add('tata')
                        ->endGroup()
                        ->add('tiya', 'blablabla')
                    ->endGroup();
                    



//echo $menu->player->display();
var_dump($menu->go('tata')->display());