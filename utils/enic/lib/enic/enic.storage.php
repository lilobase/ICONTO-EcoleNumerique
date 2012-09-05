<?php
/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
interface enicStorage
{
    /*
     * set new or modify existing value
     * @params $iName : Item's Key
     * @params $iData : Item's Datas
     * @params $iNameSpace : Optional, default : "default"
     *
     * @return value or false;
     */
    public function save($iName, $iData, $iNameSpace = 'dflt');

    /*
     * delete existing value
     */
    public function del($iName, $iNameSpace = 'dflt');

    /*
     * get existing value
     */
    public function load($iName, $iNameSpace = 'dflt');

    /*
     * return boolean in function of existing data
     */
    public function exists($iName, $iNameSpace = 'dflt');
}

/*
 * add valid methode : check the validity of data
 */
interface enicCacheStorage extends enicStorage
{
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
 * @TODO : implémenter NameSpace
 * @TODO : transformer l'interface en classe abstraite
 */
class enicFiles implements enicStorage
{
    //paths definitions
    public $path;
    public $rootPath;

    /*
     * CONSTRUCTOR
     * @TODOS : REFACTOR
     */
    public function __construct()
    {
        if(empty($this->rootPath))
                $this->rootPath = COPIX_CACHE_PATH.'enic/';

        $this->path = (empty($this->path)) ? $this->rootPath : $this->rootPath.$this->path;
        if(!is_dir($this->path)) {
            mkdir($this->path);
        }
    }

    //enic mod compatibility
    public function startExec(){}

    /*
     * get existing value
     */
    public function load($iName, $iNameSpace = 'dflt')
    {
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- load() : file not found for : '.$iName, E_USER_ERROR);

        return file_get_contents($this->path.$iName.'.cache');
    }

    /*
     * return boolean in function of existing data
     */
    public function exists($iName, $iNameSpace = 'dflt')
    {
        return file_exists($this->path.$iName.'.cache');
    }

     /*
     * set new or modify existing value
     */
    public function save($iName, $iData, $iNameSpace = 'dflt')
    {
        return file_put_contents($this->path.$iName.'.cache', $iData);
    }

    /*
     * delete existing value
     */
    public function del($iName, $iNameSpace = 'dflt')
    {
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- del() : file not found for : '.$iName, E_USER_ERROR);

        return unlink($this->path.$iName.'.cache', $iData);
    }
}

/*
 * storage file for Cache
 * @TODOS : implémenter le NameSpace "cache"
 */
class enicFileCache extends enicFiles implements enicCacheStorage
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * valid : return boolean in functin of validity
     */
    public function valid($iName)
    {
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- valid() : file not found for : '.$iName, E_USER_ERROR);

        $fileData = file($this->path.$iName.'.cache', FILE_IGNORE_NEW_LINES);
        $validityLimit = explode('||', $fileData[0]);
        $validityLimit = trim($validityLimit[0]);

        return ($validityLimit >= time());
    }

    public function type($iName)
    {
        if(!$this->exists($iName))
            trigger_error('enicFile -Storage- type() : file not found for : '.$iName, E_USER_ERROR);

        $fileData = file($this->path.$iName.'.cache', FILE_IGNORE_NEW_LINES);
        $type = explode('||', $fileData[0]);
        $oType = trim($type[1]);

        return $oType;
    }

    public function set($iName, $iData, $iValidity, $iType)
    {
        $content = $iValidity.'||'.$iType.PHP_EOL.'##'.$iData;
        parent::save($iName, $content);
    }

    public function get($iName)
    {
        $content = parent::load($iName);

        return trim(substr($content, strpos($content, '##')+2));
    }
}


/*
 * storage in session
 */
class enicSession implements enicStorage
{
    //enic mod compatibility
    public function startExec()
    {
        //BUILD SESSION ARRAY
        if(!isset($_SESSION['eS']))
            $_SESSION['eS'] = array();
    }

    /*
     * set new or modify existing value
     */
    public function save($iName, $iData, $iNameSpace = 'dflt')
    {
        $_SESSION['eS'][$iNameSpace][$iName] = $iData;
        return true;
    }

    /*
     * delete existing value
     */
    public function del($iName, $iNameSpace = 'dflt')
    {
        if(!isset($_SESSION['eS'][$iNameSpace][$iName]))
            trigger_error('enicSession -Storage- del() : file not found for : '.$iName, E_USER_ERROR);

        unset($_SESSION['eS'][$iNameSpace][$iName]);
    }

    /*
     * return datas
     */
    public function load($iName, $iNameSpace = 'dflt')
    {
        if(!isset($_SESSION['eS'][$iNameSpace][$iName]))
            trigger_error('enicSession -Storage- load() : file not found for : '.$iName, E_USER_ERROR);

        return $_SESSION['eS'][$iNameSpace][$iName];
    }

    /*
     * return boolean in function of existing data
     */
    public function exists($iName, $iNameSpace = 'dflt')
    {
        return isset($_SESSION['eS'][$iNameSpace][$iName]);
    }
}

class enicFlash extends enicSession
{
    public $history;

    public function startExec()
    {
        parent::startExec();

        //destroy previous history
        $this->history = array();

        //build flash array
        if(!isset($_SESSION['eS']['flashRegistry']))
            $_SESSION['eS']['flashRegistry'] = array();

        //run the flash garbage
        $this->flashGC();
    }

    /*
     * flash set
     */
    public function set($iName, $iDatas, $iIter = 1)
    {
        $this->save($iName, $iIter, 'flashRegistry');
        $this->save($iName, $iDatas, 'flashDatas');
    }

    /*
     * flash garbage collector
     */
    public function flashGC()
    {
        foreach($_SESSION['eS']['flashRegistry'] as $key => $value){

            //check validity
            if($value == 0){
                //save in history
                $this->history[$key] = $_SESSION['eS']['flashDatas'][$key];

                //remove datas
                unset($_SESSION['eS']['flashRegistry'][$key]);
                unset($_SESSION['eS']['flashDatas'][$key]);
            }else{
                $_SESSION['eS']['flashRegistry'][$key]--;
            }
        }
    }

    /**
     * extends flash vars lifetime
     */
    public function addCycle()
    {
        foreach($_SESSION['eS']['flashRegistry'] as $key => $value){

            $_SESSION['eS']['flashRegistry'][$key]++;
        }
    }

    /*
     * cancel previous GC cycle
     */
    public function cancel()
    {
        foreach($this->history as $key => $data)
            $this->set($key, $data);
    }

    /*
     * flash test value exists
     */
    public function has($iName)
    {
        return $this->exists($iName, 'flashDatas');
    }

    /*
     * flash getter
     */
    public function get($iName)
    {
        return $this->load($iName, 'flashDatas');
    }

    /*
     * magic getter
     */
    public function  __get($iName)
    {
        return $this->get($iName);
    }

    /*
     * magic setter
     */
    public function __set($iName, $iDatas)
    {
        return $this->set($iName, $iDatas);
    }

    /*
     * magic isset
     */
    public function  __isset($iName)
    {
        return $this->has($iName);
    }
}