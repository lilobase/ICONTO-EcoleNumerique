<?php
abstract class enicList
{
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

    public function __construct()
    {
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
     public function add($name, $content = null, $opt = null, $type = false)
     {
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
     public function addExec()
     {
        return true;
     }

     /*
      * exec at the list initiate
      * overloading
      */
     public function startExec()
     {
         return true;
     }

     /*
      * exec in reccursivity
      * overloading
      */
     public function action()
     {
         return true;
     }

     /*
      * go back in list
      * linkable
      */
     public function back($name = ':first')
     {
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
     public function go($name)
     {
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
     public function get($name)
     {
         return $this->_root->go($name);
     }


     /*
      * set new data in existing object
      */
    public function set($name, $content=false, $opt=false)
    {
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
    public function del($name)
    {
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
     public function setDatas($name, $value)
     {
         $this->_root->_datas[$name] = $value;
         return $this;
     }

     public function setDatasArray($key, $value)
     {
         $this->_root->_datas[$key][] = $value;
         return $this;
     }

     /*
      * get data from the global container
      */
    public function getDatas($name)
    {
        if(isset($this->_root->_datas[$name]))
            return $this->_root->_datas[$name];
        else
            return false;
    }

    /*
     * display before main & child
     * overloading
     */
    public function displayHeader()
    {
        return '';
    }

    /*
     * display after header 'nd before child's display
     */
    public function displayMain()
    {
        return '';
    }

    /*
     * display at the end of the display process
     */
    public function displayFooter()
    {
        return '';
    }

    /*
     * display process
     */
     public function display($topLimit = 0, $bottomLimit = 0)
     {
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
    public function load($type, $name, $content=null, $opt=null)
    {
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
    public function loadOnce($type, $name, $content=null, $opt=null)
    {
        //create computer readable name
        $nameStr = enic::sanitize($name);

        if(isset($this->$nameStr))
            return $this;

        return $this->load($type, $name, $content, $opt);
    }

    /*
     * test if item exists
     */
    public function exists($name)
    {
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
class enicErrors
{
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
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