<?php 
class CompiledDAORecordkernel_mod_enabled implements ICopixDAORecord{
 var $node_type = null;
 var $node_id = null;
 var $module_type = null;
 var $module_id = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->node_type= $dbRecord['node_type'];
}else{
 $this->node_type= $dbRecord->node_type;
} if (is_array ($dbRecord)){
 $this->node_id= $dbRecord['node_id'];
}else{
 $this->node_id= $dbRecord->node_id;
} if (is_array ($dbRecord)){
 $this->module_type= $dbRecord['module_type'];
}else{
 $this->module_type= $dbRecord->module_type;
} if (is_array ($dbRecord)){
 $this->module_id= $dbRecord['module_id'];
}else{
 $this->module_id= $dbRecord->module_id;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_mod_enabled';return $daoId;
}}

class CompiledDAOkernel_mod_enabled implements ICopixDAO  { 
   var $_table='kernel_mod_enabled';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_mod_enabled.node_type, kernel_mod_enabled.node_id, kernel_mod_enabled.module_type, kernel_mod_enabled.module_id FROM kernel_mod_enabled';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->node_id) > 0){
   if (! is_numeric ($pRecord->node_id)){
      $errorObject->addError ('node_id', _i18n ('copix:dao.errors.numeric','node_id'));
  }
  }
  if (strlen ($pRecord->module_id) > 0){
   if (! is_numeric ($pRecord->module_id)){
      $errorObject->addError ('module_id', _i18n ('copix:dao.errors.numeric','module_id'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($node_type,$node_id,$module_type,$module_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE kernel_mod_enabled.node_type '.($node_type === null ? 'IS' : '=').'  '.($node_type === null ? 'NULL' : ':kernel_mod_enabled_node_type').'  AND kernel_mod_enabled.node_id '.($node_id === null ? 'IS' : '=').'  '.($node_id === null ? 'NULL' : ':kernel_mod_enabled_node_id').'  AND kernel_mod_enabled.module_type '.($module_type === null ? 'IS' : '=').'  '.($module_type === null ? 'NULL' : ':kernel_mod_enabled_module_type').'  AND kernel_mod_enabled.module_id '.($module_id === null ? 'IS' : '=').'  '.($module_id === null ? 'NULL' : ':kernel_mod_enabled_module_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_mod_enabled_node_type'=>($node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_type), ':kernel_mod_enabled_node_id'=>($node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_id), ':kernel_mod_enabled_module_type'=>($module_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $module_type), ':kernel_mod_enabled_module_id'=>($module_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $module_id)))), $this->getDAOId ());
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
         array('node_type'=>array('node_type', 'string','kernel_mod_enabled','%s'), 'node_id'=>array('node_id', 'integer','kernel_mod_enabled','%s'), 'module_type'=>array('module_type', 'string','kernel_mod_enabled','%s'), 'module_id'=>array('module_id', 'integer','kernel_mod_enabled','%s')), $ct);
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
         array('node_type'=>array('node_type', 'string','kernel_mod_enabled','%s'), 'node_id'=>array('node_id', 'integer','kernel_mod_enabled','%s'), 'module_type'=>array('module_type', 'string','kernel_mod_enabled','%s'), 'module_id'=>array('module_id', 'integer','kernel_mod_enabled','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_mod_enabled');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_mod_enabled (node_type,node_id,module_type,module_id) VALUES (:node_type, :node_id, :module_type, :module_id)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':node_type'=>$object->node_type, ':node_id'=>$object->node_id, ':module_type'=>$object->module_type, ':module_id'=>$object->module_id)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_mod_enabled');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_mod_enabled SET  where node_type '.($object->node_type === null ? 'IS' : '=').'  '.($object->node_type === null ? 'NULL' : ':kernel_mod_enabled_node_type').'  AND node_id '.($object->node_id === null ? 'IS' : '=').'  '.($object->node_id === null ? 'NULL' : ':kernel_mod_enabled_node_id').'  AND module_type '.($object->module_type === null ? 'IS' : '=').'  '.($object->module_type === null ? 'NULL' : ':kernel_mod_enabled_module_type').'  AND module_id '.($object->module_id === null ? 'IS' : '=').'  '.($object->module_id === null ? 'NULL' : ':kernel_mod_enabled_module_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_mod_enabled_node_type'=>($object->node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->node_type), ':kernel_mod_enabled_node_id'=>($object->node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->node_id), ':kernel_mod_enabled_module_type'=>($object->module_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->module_type), ':kernel_mod_enabled_module_id'=>($object->module_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->module_id))));
   return $affectedRows;
 }
 public function delete ($node_type,$node_id,$module_type,$module_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_mod_enabled where node_type '.($node_type === null ? 'IS' : '=').'  '.($node_type === null ? 'NULL' : ':kernel_mod_enabled_node_type').'  AND node_id '.($node_id === null ? 'IS' : '=').'  '.($node_id === null ? 'NULL' : ':kernel_mod_enabled_node_id').'  AND module_type '.($module_type === null ? 'IS' : '=').'  '.($module_type === null ? 'NULL' : ':kernel_mod_enabled_module_type').'  AND module_id '.($module_id === null ? 'IS' : '=').'  '.($module_id === null ? 'NULL' : ':kernel_mod_enabled_module_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_mod_enabled_node_type'=>($node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_type), ':kernel_mod_enabled_node_id'=>($node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_id), ':kernel_mod_enabled_module_type'=>($module_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $module_type), ':kernel_mod_enabled_module_id'=>($module_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $module_id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_mod_enabled';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('node_type'=>array('node_type', 'string','kernel_mod_enabled','%s'), 'node_id'=>array('node_id', 'integer','kernel_mod_enabled','%s'), 'module_type'=>array('module_type', 'string','kernel_mod_enabled','%s'), 'module_id'=>array('module_id', 'integer','kernel_mod_enabled','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_mod_enabled';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('node_type'=>array('node_type', 'string','kernel_mod_enabled','%s'), 'node_id'=>array('node_id', 'integer','kernel_mod_enabled','%s'), 'module_type'=>array('module_type', 'string','kernel_mod_enabled','%s'), 'module_id'=>array('module_id', 'integer','kernel_mod_enabled','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByNode ($node_type, $node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_mod_enabled.node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).' AND kernel_mod_enabled.node_id'.($node_id===null ? ' IS ' : ' = ').''.($node_id === null ? 'NULL' : intval($node_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function delByNode ($node_type, $node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_mod_enabled '.' WHERE node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).' AND node_id'.($node_id===null ? ' IS ' : ' = ').''.($node_id === null ? 'NULL' : intval($node_id)).'';
    return $ct->doQuery ($query);
 }
 function getByModule ($module_type, $module_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_mod_enabled.module_type'.($module_type===null ? ' IS ' : ' = ').''. $ct->quote ($module_type).' AND kernel_mod_enabled.module_id'.($module_id===null ? ' IS ' : ' = ').''.($module_id === null ? 'NULL' : intval($module_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function delByModule ($module_type, $module_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_mod_enabled '.' WHERE module_type'.($module_type===null ? ' IS ' : ' = ').''. $ct->quote ($module_type).' AND module_id'.($module_id===null ? ' IS ' : ' = ').''.($module_id === null ? 'NULL' : intval($module_id)).'';
    return $ct->doQuery ($query);
 }
public function getFieldsDescription() {
$fields = array (
  'node_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'node_type',
     'fieldName' => 'node_type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'node_type',
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_mod_enabled',
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
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_mod_enabled',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'module_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'module_type',
     'fieldName' => 'module_type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'module_type',
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_mod_enabled',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'module_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'module_id',
     'fieldName' => 'module_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'module_id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_mod_enabled',
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
$daoId = 'kernel|kernel_mod_enabled';return $daoId;
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