<?php 
class CompiledDAORecordcopixmodule implements ICopixDAORecord{
 var $name_cpm = null;
 var $path_cpm = null;
 var $version_cpm = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->name_cpm= $dbRecord['name_cpm'];
}else{
 $this->name_cpm= $dbRecord->name_cpm;
} if (is_array ($dbRecord)){
 $this->path_cpm= $dbRecord['path_cpm'];
}else{
 $this->path_cpm= $dbRecord->path_cpm;
} if (is_array ($dbRecord)){
 $this->version_cpm= $dbRecord['version_cpm'];
}else{
 $this->version_cpm= $dbRecord->version_cpm;
} return $this; }
public function getDAOId () {
$daoId = 'copix:copixmodule';return $daoId;
}}

class CompiledDAOcopixmodule implements ICopixDAO  { 
   var $_table='copixmodule';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT copixmodule.name_cpm, copixmodule.path_cpm, copixmodule.version_cpm FROM copixmodule';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if ($pRecord->name_cpm === null){
    $errorObject->addError ('name_cpm', _i18n ('copix:dao.errors.required',_i18n ('copix:copixmodule.dao.fields.name_cpm')));
  }
  if (strlen ($pRecord->name_cpm) > 255){
      $errorObject->addError ('name_cpm', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('copix:copixmodule.dao.fields.name_cpm'), 255)));
  }
  if ($pRecord->path_cpm === null){
    $errorObject->addError ('path_cpm', _i18n ('copix:dao.errors.required',_i18n ('copix:copixmodule.dao.fields.name_cpm')));
  }
  if (strlen ($pRecord->path_cpm) > 255){
      $errorObject->addError ('path_cpm', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('copix:copixmodule.dao.fields.name_cpm'), 255)));
  }
  if (strlen ($pRecord->version_cpm) > 255){
      $errorObject->addError ('version_cpm', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('copix:copixmodule.dao.fields.version_cpm'), 255)));
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($name_cpm){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE copixmodule.name_cpm '.($name_cpm === null ? 'IS' : '=').'  '.($name_cpm === null ? 'NULL' : ':copixmodule_name_cpm').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':copixmodule_name_cpm'=>($name_cpm === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $name_cpm)))), $this->getDAOId ());
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
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('name_cpm'=>array('name_cpm', 'string','copixmodule','%s'), 'path_cpm'=>array('path_cpm', 'string','copixmodule','%s'), 'version_cpm'=>array('version_cpm', 'string','copixmodule','%s')), $ct);
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
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('name_cpm'=>array('name_cpm', 'string','copixmodule','%s'), 'path_cpm'=>array('path_cpm', 'string','copixmodule','%s'), 'version_cpm'=>array('version_cpm', 'string','copixmodule','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('copix:copixmodule');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO copixmodule (name_cpm,path_cpm,version_cpm) VALUES (:name_cpm, :path_cpm, :version_cpm)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':name_cpm'=>$object->name_cpm, ':path_cpm'=>$object->path_cpm, ':version_cpm'=>$object->version_cpm)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('copix:copixmodule');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE copixmodule SET  path_cpm= :path_cpm, version_cpm= :version_cpm where name_cpm '.($object->name_cpm === null ? 'IS' : '=').'  '.($object->name_cpm === null ? 'NULL' : ':copixmodule_name_cpm').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':path_cpm'=>$object->path_cpm, ':version_cpm'=>$object->version_cpm, ':copixmodule_name_cpm'=>($object->name_cpm === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->name_cpm))));
   return $affectedRows;
 }
 public function delete ($name_cpm){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM copixmodule where name_cpm '.($name_cpm === null ? 'IS' : '=').'  '.($name_cpm === null ? 'NULL' : ':copixmodule_name_cpm').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':copixmodule_name_cpm'=>($name_cpm === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $name_cpm))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM copixmodule';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('name_cpm'=>array('name_cpm', 'string','copixmodule','%s'), 'path_cpm'=>array('path_cpm', 'string','copixmodule','%s'), 'version_cpm'=>array('version_cpm', 'string','copixmodule','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM copixmodule';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('name_cpm'=>array('name_cpm', 'string','copixmodule','%s'), 'path_cpm'=>array('path_cpm', 'string','copixmodule','%s'), 'version_cpm'=>array('version_cpm', 'string','copixmodule','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
public function getFieldsDescription() {
$fields = array (
  'name_cpm' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'name_cpm',
     'fieldName' => 'name_cpm',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'copix:copixmodule.dao.fields.name_cpm',
     'caption' => NULL,
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'copixmodule',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '255',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'path_cpm' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'path_cpm',
     'fieldName' => 'path_cpm',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'copix:copixmodule.dao.fields.name_cpm',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'copixmodule',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '255',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'version_cpm' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'version_cpm',
     'fieldName' => 'version_cpm',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'copix:copixmodule.dao.fields.version_cpm',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'copixmodule',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '255',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'copix:copixmodule';return $daoId;
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