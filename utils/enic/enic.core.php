<?php

/*
 * CONFIG 
 */
define('ENIC_PATH',dirname (__FILE__) . '/');


/*
 * FACTORY
 */
class enic{
    public function  add($name) {
       if(!file_exists(ENIC_PATH.'/lib/enic.'.$name.'.php'))
           trigger_error('Enic class missing : '.$name, E_USER_ERROR);
       require(ENIC_PATH.'/lib/'.$name.'.php');

       //load class :
       $className = 'enic'.ucfirst($name);
       $this->name = new $className();
       
       return $this->name;
    }
}

class enicBlock{

    public $children;
    public $stack;

    public function __construct(){
        $this->children = false;
    }

    public function add($name, $content=null, $opt=null){
        $nameStr = $this->sanitize($name);
        $className = get_class();
        $this->$nameStr = new get_class();
        $this->$nameStr->name = $name;
        $this->$nameStr->content = $content;
        $this->$nameStr->opt = $opt;
    }

    public function set($name, $content=null, $opt=null){

    }

    public function del($name){

    }

    public function action($name, $opt){

    }

    public function display(){

    }

    public function sanitize($name){
        $str = strtr(strtolower(trim($name)), "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ","aaaaaaooooooeeeeciiiiuuuuyn");
	$str = preg_replace('#([^.a-z0-9]+)#i', '_', $str);
        $str = preg_replace('#-{2,}#','_',$str);
        $str = preg_replace('#-$#','',$str);
        $str = preg_replace('#^-#','',$str);
	return $str;
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
$menu = $enic->add('menu');


$menu->add('player');
$menu->player->add('menu1');
