<?php 
class CompiledDAORecordkernel_bu2user implements ICopixDAORecord{
 var $user_id = null;
 var $bu_type = null;
 var $bu_id = null;
 var $user_login = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->user_id= $dbRecord['user_id'];
}else{
 $this->user_id= $dbRecord->user_id;
} if (is_array ($dbRecord)){
 $this->bu_type= $dbRecord['bu_type'];
}else{
 $this->bu_type= $dbRecord->bu_type;
} if (is_array ($dbRecord)){
 $this->bu_id= $dbRecord['bu_id'];
}else{
 $this->bu_id= $dbRecord->bu_id;
} if (is_array ($dbRecord)){
 $this->user_login= $dbRecord['user_login'];
}else{
 $this->user_login= $dbRecord->user_login;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_bu2user';return $daoId;
}}

class CompiledDAOkernel_bu2user implements ICopixDAO  { 
   var $_table='kernel_link_bu2user';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_link_bu2user.user_id, kernel_link_bu2user.bu_type, kernel_link_bu2user.bu_id, auth_copixuser.login_dbuser user_login FROM kernel_link_bu2user JOIN dbuser auth_copixuser WHERE  kernel_link_bu2user.user_id=auth_copixuser.id_dbuser';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->user_id) > 0){
   if (! is_numeric ($pRecord->user_id)){
      $errorObject->addError ('user_id', _i18n ('copix:dao.errors.numeric','user_id'));
  }
  }
  if (strlen ($pRecord->bu_id) > 0){
   if (! is_numeric ($pRecord->bu_id)){
      $errorObject->addError ('bu_id', _i18n ('copix:dao.errors.numeric','bu_id'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($user_id,$bu_type,$bu_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' AND kernel_link_bu2user.user_id '.($user_id === null ? 'IS' : '=').'  '.($user_id === null ? 'NULL' : ':kernel_link_bu2user_user_id').'  AND kernel_link_bu2user.bu_type '.($bu_type === null ? 'IS' : '=').'  '.($bu_type === null ? 'NULL' : ':kernel_link_bu2user_bu_type').'  AND kernel_link_bu2user.bu_id '.($bu_id === null ? 'IS' : '=').'  '.($bu_id === null ? 'NULL' : ':kernel_link_bu2user_bu_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_bu2user_user_id'=>($user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_id), ':kernel_link_bu2user_bu_type'=>($bu_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $bu_type), ':kernel_link_bu2user_bu_id'=>($bu_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $bu_id)))), $this->getDAOId ());
    if (isset ($results[0])){return $results[0]; }else{return false; }
 }
 public function findAll (){
    return new CopixDAORecordIterator (CopixDB::getConnection ($this->_connectionName)->doQuery ($this->_selectQuery), $this->getDAOId ());
 }
 public function findBy ($searchParams, $joins=array()){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery;
    $params = array ();

	    if(count($joins)){
	        $query = preg_replace('/SELECT (.*?) FROM /','SELECT * FROM ',$query);
		    foreach($joins as $table=>$join){
     	        $query.=' LEFT JOIN '.$table;
        	    $query.=' ON '.$join[0].$join[1].$join[2].' ';
            }
        }    if (!$searchParams->isEmpty ()){
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_id'=>array('user_id', 'integer','kernel_link_bu2user','%s'), 'bu_type'=>array('bu_type', 'string','kernel_link_bu2user','%s'), 'bu_id'=>array('bu_id', 'integer','kernel_link_bu2user','%s'), 'user_login'=>array('login_dbuser', 'string','auth_copixuser','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function iFindAll (){
    return new CopixDAORecordIterator (CopixDB::getConnection ($this->_connectionName)->iDoQuery ($this->_selectQuery), $this->getDAOId ());
 }
 public function iFindBy ($searchParams, $joins=array()){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery;
    $params = array ();

	    if(count($joins)){
	        $query = preg_replace('/SELECT (.*?) FROM /','SELECT * FROM ',$query);
		    foreach($joins as $table=>$join){
     	        $query.=' LEFT JOIN '.$table;
        	    $query.=' ON '.$join[0].$join[1].$join[2].' ';
            }
        }    if (!$searchParams->isEmpty ()){
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_id'=>array('user_id', 'integer','kernel_link_bu2user','%s'), 'bu_type'=>array('bu_type', 'string','kernel_link_bu2user','%s'), 'bu_id'=>array('bu_id', 'integer','kernel_link_bu2user','%s'), 'user_login'=>array('login_dbuser', 'string','auth_copixuser','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_bu2user');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_link_bu2user (user_id,bu_type,bu_id,login_dbuser) VALUES (:user_id, :bu_type, :bu_id, :user_login)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':user_id'=>$object->user_id, ':bu_type'=>$object->bu_type, ':bu_id'=>$object->bu_id, ':user_login'=>$object->user_login)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_bu2user');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_link_bu2user SET  where user_id '.($object->user_id === null ? 'IS' : '=').'  '.($object->user_id === null ? 'NULL' : ':kernel_link_bu2user_user_id').'  AND bu_type '.($object->bu_type === null ? 'IS' : '=').'  '.($object->bu_type === null ? 'NULL' : ':kernel_link_bu2user_bu_type').'  AND bu_id '.($object->bu_id === null ? 'IS' : '=').'  '.($object->bu_id === null ? 'NULL' : ':kernel_link_bu2user_bu_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_bu2user_user_id'=>($object->user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->user_id), ':kernel_link_bu2user_bu_type'=>($object->bu_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->bu_type), ':kernel_link_bu2user_bu_id'=>($object->bu_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->bu_id))));
   return $affectedRows;
 }
 public function delete ($user_id,$bu_type,$bu_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_bu2user where user_id '.($user_id === null ? 'IS' : '=').'  '.($user_id === null ? 'NULL' : ':kernel_link_bu2user_user_id').'  AND bu_type '.($bu_type === null ? 'IS' : '=').'  '.($bu_type === null ? 'NULL' : ':kernel_link_bu2user_bu_type').'  AND bu_id '.($bu_id === null ? 'IS' : '=').'  '.($bu_id === null ? 'NULL' : ':kernel_link_bu2user_bu_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_bu2user_user_id'=>($user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_id), ':kernel_link_bu2user_bu_type'=>($bu_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $bu_type), ':kernel_link_bu2user_bu_id'=>($bu_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $bu_id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_bu2user';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_id'=>array('user_id', 'integer','kernel_link_bu2user','%s'), 'bu_type'=>array('bu_type', 'string','kernel_link_bu2user','%s'), 'bu_id'=>array('bu_id', 'integer','kernel_link_bu2user','%s'), 'user_login'=>array('login_dbuser', 'string','auth_copixuser','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_link_bu2user JOIN dbuser auth_copixuser WHERE  kernel_link_bu2user.user_id=auth_copixuser.id_dbuser';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_id'=>array('user_id', 'integer','kernel_link_bu2user','%s'), 'bu_type'=>array('bu_type', 'string','kernel_link_bu2user','%s'), 'bu_id'=>array('bu_id', 'integer','kernel_link_bu2user','%s'), 'user_login'=>array('login_dbuser', 'string','auth_copixuser','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByUserID ($id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_link_bu2user.user_id'.($id===null ? ' IS ' : ' = ').''.($id === null ? 'NULL' : intval($id)).' ORDER BY kernel_link_bu2user.bu_type asc, kernel_link_bu2user.bu_id asc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByLogin ($login){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND auth_copixuser.login_dbuser'.($login===null ? ' IS ' : ' = ').''. $ct->quote ($login).' ORDER BY kernel_link_bu2user.bu_type asc, kernel_link_bu2user.bu_id asc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByBUID ($bu_type, $bu_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_link_bu2user.bu_type'.($bu_type===null ? ' IS ' : ' = ').''. $ct->quote ($bu_type).' AND kernel_link_bu2user.bu_id'.($bu_id===null ? ' IS ' : ' = ').''.($bu_id === null ? 'NULL' : intval($bu_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByBUType ($bu_type){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_link_bu2user.bu_type'.($bu_type===null ? ' IS ' : ' = ').''. $ct->quote ($bu_type).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'user_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'user_id',
     'fieldName' => 'user_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'user_id',
     'isPK' => true,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_link_bu2user',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'auth_copixuser',
     'fkFieldName' => 'id_dbuser',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'bu_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'bu_type',
     'fieldName' => 'bu_type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'bu_type',
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_link_bu2user',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'bu_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'bu_id',
     'fieldName' => 'bu_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'bu_id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_link_bu2user',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'user_login' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'user_login',
     'fieldName' => 'login_dbuser',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'user_login',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'auth_copixuser',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'kernel|kernel_bu2user';return $daoId;
}private function _dirtyClearNullValuesForSelectQueries ($array){
$toReturn = array ();
foreach ($array as $key=>$value){
if ($value !== "___COPIX___DELETE___ME___FROM____DAO___QUERIES___"){
   $toReturn[$key] = $value;
}
 }
return $toReturn;
}}

?>