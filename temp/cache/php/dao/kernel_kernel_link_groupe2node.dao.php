<?php 
class CompiledDAORecordkernel_link_groupe2node implements ICopixDAORecord{
 var $groupe_id = null;
 var $node_type = null;
 var $node_id = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->groupe_id= $dbRecord['groupe_id'];
}else{
 $this->groupe_id= $dbRecord->groupe_id;
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
$daoId = 'kernel|kernel_link_groupe2node';return $daoId;
}}

class CompiledDAOkernel_link_groupe2node implements ICopixDAO  { 
   var $_table='kernel_link_groupe2node';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_link_groupe2node.groupe_id, kernel_link_groupe2node.node_type, kernel_link_groupe2node.node_id FROM kernel_link_groupe2node';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->groupe_id) > 0){
   if (! is_numeric ($pRecord->groupe_id)){
      $errorObject->addError ('groupe_id', _i18n ('copix:dao.errors.numeric','groupe_id'));
  }
  }
  if (strlen ($pRecord->node_id) > 0){
   if (! is_numeric ($pRecord->node_id)){
      $errorObject->addError ('node_id', _i18n ('copix:dao.errors.numeric','node_id'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($groupe_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE kernel_link_groupe2node.groupe_id '.($groupe_id === null ? 'IS' : '=').'  '.($groupe_id === null ? 'NULL' : ':kernel_link_groupe2node_groupe_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_groupe2node_groupe_id'=>($groupe_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $groupe_id)))), $this->getDAOId ());
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
         array('groupe_id'=>array('groupe_id', 'integer','kernel_link_groupe2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_groupe2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_groupe2node','%s')), $ct);
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
         array('groupe_id'=>array('groupe_id', 'integer','kernel_link_groupe2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_groupe2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_groupe2node','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_link_groupe2node');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_link_groupe2node (groupe_id,node_type,node_id) VALUES (:groupe_id, :node_type, :node_id)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':groupe_id'=>$object->groupe_id, ':node_type'=>$object->node_type, ':node_id'=>$object->node_id)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_link_groupe2node');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_link_groupe2node SET  node_type= :node_type, node_id= :node_id where groupe_id '.($object->groupe_id === null ? 'IS' : '=').'  '.($object->groupe_id === null ? 'NULL' : ':kernel_link_groupe2node_groupe_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':node_type'=>$object->node_type, ':node_id'=>$object->node_id, ':kernel_link_groupe2node_groupe_id'=>($object->groupe_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->groupe_id))));
   return $affectedRows;
 }
 public function delete ($groupe_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_groupe2node where groupe_id '.($groupe_id === null ? 'IS' : '=').'  '.($groupe_id === null ? 'NULL' : ':kernel_link_groupe2node_groupe_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_groupe2node_groupe_id'=>($groupe_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $groupe_id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_groupe2node';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('groupe_id'=>array('groupe_id', 'integer','kernel_link_groupe2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_groupe2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_groupe2node','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_link_groupe2node';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('groupe_id'=>array('groupe_id', 'integer','kernel_link_groupe2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_groupe2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_groupe2node','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByNode ($node_type, $node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_link_groupe2node.node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).' AND kernel_link_groupe2node.node_id'.($node_id===null ? ' IS ' : ' = ').''.($node_id === null ? 'NULL' : intval($node_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'groupe_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'groupe_id',
     'fieldName' => 'groupe_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'groupe_id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_link_groupe2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
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
     'table' => 'kernel_link_groupe2node',
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
     'table' => 'kernel_link_groupe2node',
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
$daoId = 'kernel|kernel_link_groupe2node';return $daoId;
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