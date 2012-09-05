<?php
/*
 * Class de cache automatisé
 *
 * La classe se base sur le nom de la class appelante pour appeler le cache : enicMatricCache => class enicMatrix
 */
enic::to_load('storage');
class enicCache extends enicMod
{
    public $storage;
    public $validity;
    public $range;

    public function __construct()
    {
        parent::__construct();

        //define storage :
        if(empty($this->storage))
            $this->storage = 'session';
        $this->storage = $this->storage.'Cache';

        //define validity :
        if(empty($this->validity))
            $this->validity = 3600;

        return true;

    }

    //if enicCache is extended : return the extended Cache :
    public function getClass()
    {
        //get className (remove 'Cache') :
        $className = substr(get_class($this),0 , -5);

        //if enicCache is extends : continue to object cache manager
        if($className != 'enic'){
            return $this->setEnic($className);
        }else{
            return $this;
        }
    }

    protected function buildId($iName)
    {
        $oName = $iName;
        $oName = strtolower($iName);

        //if is by user :
        if($this->range == 'user'){
            $user   =& enic::get('user');
            $oName  .= $user->id;
        }

        return $oName;
    }

    /*
     * options is :
     * validity => int
     * type => object | array | int | string
     * storage => file | session
     */
    public function set($iName, $iDatas, $iOpt = array())
    {
        //get options
        $storage    = (isset($iOpt['storage'])) ? $iOpt['storage'].'Cache' : $this->storage;
        $type       = (isset($iOpt['type'])) ? $iOpt['type'] : false;
        $validity   = (isset($iOpt['validity'])) ? $iOpt['validity'] : $this->validity;

        //set validity
        $validity = time()+$validity;

        //get type
        if($type === false){
            if(is_array($iDatas))
                $type = 'array';
            elseif(is_int($iDatas))
                $type = 'int';
            elseif(is_string($iDatas))
                $type = 'string';
            elseif(is_float($iDatas))
                $type = 'float';
            elseif(is_object($iDatas))
                $type = 'object';
            else
                $type = null;
        }

        //get storage
        $storageObject = enic::get($storage);

        //refactoring datas :
        switch ($type){
            case 'array':
            case 'object':
                $oDatas = enicSerializeCache::before($iDatas);
            break;
            default:

            break;
        }

        //set the name :
        $name = $this->buildId($iName);

        //push datas :
        $storageObject->set($name, $oDatas, $validity, $type);

        return $iDatas;
    }

    /*
     * get item from cache
     * $options :
     * storage => 'file' | 'session'
     */
    public function get($iName, $opt = array())
    {
        //check if the class is already in enicCache
        if(isset(enic::$l[$iName]))
            return enic::$l[$iName];


        //set storage from options
        $storage = (isset($iOpt['storage'])) ? $iOpt['storage'].'Cache' : $this->storage;

        //buildId
        $name = $this->buildId($iName);

        if(!$this->exists($iName) || !$this->valid($iName))
            return null;

        //get the ObjectStorage
        $storageObject = enic::get($storage);
        $datas = $storageObject->get($name);

        $type = $storageObject->type($name);

        //refactoring datas :
        switch ($type){
            case 'array':
            case 'object':
                $oDatas = enicSerializeCache::after($datas);
            break;
            default:

            break;
        }

        enic::$l[$iName] =& $oDatas;
        return enic::$l[$iName];
    }

    protected function setEnic($iClassName)
    {
        //get the class :
        $className = strtolower(substr($iClassName, 4));
        $oReturn = $this->get($className);

        if($oReturn !== null){
            return $oReturn;
        }

        //execute class
        $datas = enic::get($className);

        //caching result
        return $this->set($className, $datas);

    }

    public function valid($iName)
    {
        //get storage
        $storageObject = enic::get($this->storage);

        $name = $this->buildId($iName);

        return $storageObject->valid($name);
    }

    public function exists($iName)
    {
        //get storage
        $storageObject = enic::get($this->storage);

        $name = $this->buildId($iName);

        return $storageObject->exists($name);
    }
}

interface enicCacheInterface
{
    /*
     * before caching
     */
    public static function before($iDatas);

    /*
     * After caching
     */
    public static function after($iDatas);
}

/*
 * caching system for object
 */
class enicSerializeCache implements enicCacheInterface
{
    public static function before($iDatas)
    {
        return serialize($iDatas);
    }

    public static function after($iDatas)
    {
        return unserialize($iDatas);
    }

}


