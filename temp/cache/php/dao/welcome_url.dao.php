<?php 
class CompiledDAORecordurl implements ICopixDAORecord{
 var $url = null;
 var $node_type = null;
 var $node_id = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->url= $dbRecord['url'];
}else{
 $this->url= $dbRecord->url;
} if (is_array ($dbRecord)){
 $this->node_type= $dbRecord['node_type'];
}else{
 $this->node_type= $dbRecord->node_type;
} if (is_array ($dbRecord)){
 $this->node_id= $dbRecord['node_id'];
}else{
 $this->node_id= $dbRecord->node_id;
} return $this; }
public function getDAOId () {
$daoId = 'welcome|url';return $daoId;
}}

class CompiledDAOurl implements ICopixDAO  { 
   var $_table='module_welcome_url';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT module_welcome_url.url, module_welcome_url.node_type, module_welcome_url.node_id FROM module_welcome_url';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->node_id) > 0){
   if (! is_numeric ($pRecord->node_id)){
      $errorObject->addError ('node_id', _i18n ('copix:dao.errors.numeric','node_id'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($url){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE module_welcome_url.url '.($url === null ? 'IS' : '=').'  '.($url === null ? 'NULL' : ':module_welcome_url_url').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':module_welcome_url_url'=>($url === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $url)))), $this->getDAOId ());
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
         array('url'=>array('url', 'string','module_welcome_url','%s'), 'node_type'=>array('node_type', 'string','module_welcome_url','%s'), 'node_id'=>array('node_id', 'integer','module_welcome_url','%s')), $ct);
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
         array('url'=>array('url', 'string','module_welcome_url','%s'), 'node_type'=>array('node_type', 'string','module_welcome_url','%s'), 'node_id'=>array('node_id', 'integer','module_welcome_url','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('welcome|url');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO module_welcome_url (url,node_type,node_id) VALUES (:url, :node_type, :node_id)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':url'=>$object->url, ':node_type'=>$object->node_type, ':node_id'=>$object->node_id)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('welcome|url');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_welcome_url SET  node_type= :node_type, node_id= :node_id where url '.($object->url === null ? 'IS' : '=').'  '.($object->url === null ? 'NULL' : ':module_welcome_url_url').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':node_type'=>$object->node_type, ':node_id'=>$object->node_id, ':module_welcome_url_url'=>($object->url === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->url))));
   return $affectedRows;
 }
 public function delete ($url){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_welcome_url where url '.($url === null ? 'IS' : '=').'  '.($url === null ? 'NULL' : ':module_welcome_url_url').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':module_welcome_url_url'=>($url === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $url))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_welcome_url';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('url'=>array('url', 'string','module_welcome_url','%s'), 'node_type'=>array('node_type', 'string','module_welcome_url','%s'), 'node_id'=>array('node_id', 'integer','module_welcome_url','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_welcome_url';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('url'=>array('url', 'string','module_welcome_url','%s'), 'node_type'=>array('node_type', 'string','module_welcome_url','%s'), 'node_id'=>array('node_id', 'integer','module_welcome_url','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getBuUrl ($url){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE module_welcome_url.url'.($url===null ? ' IS ' : ' = ').''. $ct->quote ($url).' ORDER BY module_welcome_url.node_type asc, module_welcome_url.node_id asc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByNode ($node_type, $node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE module_welcome_url.node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).' AND module_welcome_url.node_id'.($node_id===null ? ' IS ' : ' = ').''.($node_id === null ? 'NULL' : intval($node_id)).' ORDER BY module_welcome_url.url asc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'url' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'url',
     'fieldName' => 'url',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'url',
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'module_welcome_url',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'node_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'node_type',
     'fieldName' => 'node_type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'node_type',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'module_welcome_url',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'node_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'node_id',
     'fieldName' => 'node_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'node_id',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'module_welcome_url',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'welcome|url';return $daoId;
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