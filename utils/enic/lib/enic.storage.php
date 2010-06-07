<?php
/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
interface enicStorage {
    /*
     * set new or modify existing value
     */
    public function save($iName, $iData);

    /*
     * delete existing value
     */
    public function del($iName);

    /*
     * get existing value
     */
    public function load($iName);

    /*
     * return boolean in function of existing data
     */
    public function exists($iName);
}

/*
 * add valid methode : check the validity of data
 */
interface enicCacheStorage extends enicStorage {

    /*
     * valid : return boolean in functin of validity
     */
    public function valid($iName);

    /*
     * redifine set with validity
     */
    public function set($iName, $iData, $iValidity, $iType);

    /*
     * get datas
     */
    public function get($iName);

}

/*
 * Storage class for file;
 */
class enicFiles implements enicStorage {

    public $path;

    public $rootPath;

    public function __construct(){
        if(empty($this->rootPath))
                $this->rootPath = COPIX_CACHE_PATH.'enic/';

        $this->path = (empty($this->path)) ? $this->rootPath : $this->rootPath.$this->path;

        if(!is_dir($this->path))
            trigger_error('enicFile -Storage- construct() : directory not found in : '.$this->path, E_USER_ERROR);
    }

    public function startExec(){}

    public function load($iName){
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- get() : file not found for : '.$iName, E_USER_ERROR);

        return file_get_contents($this->path.$iName);

    }

    public function exists($iName){
        return file_exists($this->path.$iName);
    }

    public function save($iName, $iData){
        return file_put_contents($this->path.$iName, $iData);
    }

    public function del($iName){
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- del() : file not found for : '.$iName, E_USER_ERROR);

        return unlink($this->path.$iName, $iData);
    }
}

/*
 * storage file for Cache
 */
class enicFileCache extends enicFiles implements enicCacheStorage {

    public function __construct(){
        parent::__construct();
    }

    public function valid($iName){
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- valid() : file not found for : '.$iName, E_USER_ERROR);

        $fileData = file($this->path.$iName, FILE_IGNORE_NEW_LINES);
        $validityLimit = implode('||', $fileData[0]);
        $validityLimit = trim($validityLimit[0]);

        return ($validityLimit >= time());
    }

    public function type($iName){
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- valid() : file not found for : '.$iName, E_USER_ERROR);

        $fileData = file($this->path.$iName, FILE_IGNORE_NEW_LINES);
        $type = implode('||', $fileData[0]);
        $oType = trim($type[1]);

        return $oType;
    }

    public function set($iName, $iData, $iValidity, $iType){
        $content = $iValidity.'||'.$iType.PHP_EOL.$iData;
        parent::save($iName, $content);
    }

    public function get($iName){
        $content = parent::get($iName);
        return substr($content, strpos(PHP_EOL, $content));
    }
}


/*
 * storage in session
 *//*
class enicSession implements enicStorage {
    
    public function __construct($iPath = null){
        
    }
}
*/
?>
