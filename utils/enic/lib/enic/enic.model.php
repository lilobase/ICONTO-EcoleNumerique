<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enicmodel
 *
 * @author arnox
 */
class enicModel extends enicMod
{
    //last insert id
    public $lastId;

    //number of affected rows
    public $rowCount;

    //db connexion
    protected $_db;

    //results storage
    protected $_results;

    public function startExec()
    {
        //get database config
        require (COPIX_VAR_PATH . 'config/db_profiles.conf.php');

        //get options from copix
        $dbOpt = $_db_profiles[$_db_default_profile];

        //create connection
        try{
            $this->_db = new PDO('mysql:'.$dbOpt['connectionString'], $dbOpt['user'], $dbOpt['password']);
        }catch(Exception $e){
            trigger_error('Enic Model : connexion fail, '.$e->getMessage(), E_USER_ERROR);
        }

    }

    /*
     * secure datas
     * type : int, str, null, bool, lob
     */
    public function quote($str, $type = 'str')
    {
        switch($type){
            case 'int':
                $param = PDO::PARAM_INT;
            break;
            case 'bool':
                $param = PDO::PARAM_BOOL;
            break;
            case 'str':
            default:
                $param = PDO::PARAM_STR;
            break;
        }

        return $this->_db->quote($str, $param);
    }

    /*
     * Create 'and execute query
     */
    public function query($query)
    {
        $this->lastId = null;
        $this->lastQuery = $query;
        $this->_results = $this->_db->query($query);
        if($this->_results === false){
            echo $this->errorInfo();
            return false;
        }

        return $this;
    }

    public function exec($query)
    {
        $this->count = '';
    }

    public function toArray()
    {
        $oReturn = $this->_results->fetchAll();
        $this->close();
        return $oReturn;
    }

    public function toString()
    {
        $oReturn = $this->_results->fetch();
        $this->close();
        return $oReturn[0];
    }

    public function toInt()
    {
        return $this->toString()*1;
    }

    public function createMultiple($iTable, $iDatas)
    {
        foreach($iDatas as $datas)
            $this->create ($iTable, $datas);

        return true;
    }

    public function update($iTable, $iDatas, $cond = null)
    {
        //extract id :
        if(isset($iDatas['id']) && !empty($iDatas['id'])){
            $id = $iDatas['id']*1;
            unset($iDatas['id']);
        }elseif(empty($cond)){
            trigger_error('Enic Internal Error : Wrong dataType "id" in model::update', E_USER_ERROR);
            return false;
        }

        //extract condition
        $cond = (!empty($cond)) ? $cond : 'id = '.$id;

        //fetch & prepare datas
        foreach($iDatas as $field => $data)
            $update[] = '`'.$field.'` = '.$data;

        //make query
        $query = 'UPDATE '.$iTable.' SET '.implode(', ', $update).' WHERE '.$cond;
        return $this->query($query)->close();
    }

    public function createOrUpdate($iTable, $iDatas)
    {
         //fetch & prepare datas
        foreach($iDatas as $field => $data)
            $update[] = '`'.$field.'` = '.$data;

        //make query
        $query = 'REPLACE '.$iTable.' SET '.implode(', ', $update);
        return $this->query($query)->close();

    }

    public function create($iTable, $iDatas, $iForceId = false)
    {
        //delete ID if exists
        if(array_key_exists('id', $iDatas) && !$iForceId)
            unset($iDatas['id']);

        foreach ($iDatas as $field => $value){
            $fields[] = '`'.$field.'`';
            $values[] = $value;
        }

        $query = 'INSERT INTO '.$iTable.' ('.implode(', ', $fields).') VALUES ('.implode(',', $values).')';

        return $this->query($query)->close();
    }

    public function delete($iTable, $iCond)
    {
        //test if is Id
        if(is_int($iCond))
            $iCond = 'id = '.$iCond;

        $query = 'DELETE FROM '.$iTable.' WHERE '.$iCond;
        return $this->query($query)->close();

    }

    public function close()
    {
        $this->lastId = $this->_db->lastInsertId();
        $this->_results->closeCursor();
    }

    public function count()
    {
        $return = $this->_results->fetch();
        $this->close();

        return $return[0]*1;
    }

    public function errorInfo()
    {
        //get errorInfo
        $errorInfos = $this->_db->errorInfo();
        return 'PDO error : '.$errorInfos[2];
    }

    public function toArray1()
    {
        $result = $this->toArray();
        if($result == null)
            return null;
        return $result[0];
    }
}
