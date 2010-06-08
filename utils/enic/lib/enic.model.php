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
class enicModel extends enicMod {

    //last insert id
    public $lastId;
    
    //number of affected rows
    public $rowCount;

    //db connexion
    protected $_db;

    //results storage
    protected $_results;

    public function startExec(){

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
    public function quote($str, $type = 'str'){
        $param = PDO::PARAM_.strtoupper($type);
        return $this->_db->quote($str, $param);
    }

    /*
     * Create 'and execute query
     */
    public function query($query){
        $this->_results = $this->_db->query($query);
        if($this->_results === false){
            echo $this->errorInfo();
            return false;
        }
        return $this;
    }

    public function exec($query){

        $this->count = '';
    }

    public function toArray(){
        $oReturn = $this->_results->fetchAll();
        $this->close();
        return $oReturn;
    }

    public function toString(){
        $oReturn = $this->_results->fetch();
        $this->close();
        return $oReturn[0];
    }

    public function toInt(){

    }

    public function close(){
        $this->lastId = $this->_db->lastInsertId();
        $this->_results->closeCursor();
    }

    public function errorInfo(){
        //get errorInfo
        $errorInfos = $this->_db->errorInfo();
        return 'PDO error : '.$errorInfos[2];
    }
}
?>
