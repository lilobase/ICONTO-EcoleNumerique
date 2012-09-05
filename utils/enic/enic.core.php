<?php

/*
 * CONFIG
 */
define('ENIC_PATH',dirname (__FILE__) . '/');

//get enic Action Group
require(ENIC_PATH.'enic.actiongroup.php');
//get enic Zone
require(ENIC_PATH.'enic.zone.php');
//get enic Service
require(ENIC_PATH.'enic.service.php');

/*
 * FACTORY
 */
class enic
{
    //internal link
    public static $l = array();

    /*
     * load block in global container
     */
    public static function load($type, $name = null)
    {
        //load file
        enic::to_load($type);

        //get name
        $name = (!empty($name)) ? enic::sanitize($name) : $type;

        if(in_array($name, self::$l))
            trigger_error('Item <em>'.$name.'</em> in <strong>ENIC ROOT CORE</strong> already exists', E_USER_WARNING);

        //load class in local ref
        $className = 'enic'.ucfirst($type);
        self::$l[$name] = new $className();

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
    public static function to_load($type)
    {
        //get class name
        $className = 'enic'.ucfirst($type);
        $classPath = ENIC_PATH.'/lib/enic/enic.'.strtolower($type).'.php';

        //test if class or file exists
        if(!class_exists($className) && !interface_exists($className) && !file_exists($classPath))
             trigger_error('Enic File missing : '.strtolower($type), E_USER_ERROR);

        //require class file
        if(!class_exists($className) && !interface_exists($className))
            require_once($classPath);

        //test if class is callable
        if(!class_exists($className) && !interface_exists($className))
            trigger_error('Enic class missing : '.$className, E_USER_ERROR);
    }

    /*
     * get the named object
     */
    public static function get($type, $name = null)
    {
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
    public static function sanitize($name)
    {
        $str = strtr(strtolower(trim($name)), "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ","aaaaaaooooooeeeeciiiiuuuuyn");
    $str = preg_replace('#([^.a-z0-9]+)#i', '_', $str);
        $str = preg_replace('#_{2,}#','_',$str);
        $str = preg_replace('#_$#','',$str);
//        $str = preg_replace('#^_#','',$str);
    return $str;
    }

    public static function zend_load($name)
    {
        $classPath = ENIC_PATH.'/lib/Zend/'.$name.'.php';

        require_once $classPath;
    }

    public static function externals_load($name)
    {
        $classPath = ENIC_PATH.'/lib/externals/class.'.$name.'.php';

        require_once $classPath;
    }

}

abstract class enicMod
{
    public function __construct()
    {
    }

    public function startExec()
    {
    }

}
