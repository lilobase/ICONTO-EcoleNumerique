<?php 
class CompiledDAORecordkernel_link_user2node implements ICopixDAORecord{
 var $user_type = null;
 var $user_id = null;
 var $node_type = null;
 var $node_id = null;
 var $droit = null;
 var $debut = null;
 var $fin = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->user_type= $dbRecord['user_type'];
}else{
 $this->user_type= $dbRecord->user_type;
} if (is_array ($dbRecord)){
 $this->user_id= $dbRecord['user_id'];
}else{
 $this->user_id= $dbRecord->user_id;
} if (is_array ($dbRecord)){
 $this->node_type= $dbRecord['node_type'];
}else{
 $this->node_type= $dbRecord->node_type;
} if (is_array ($dbRecord)){
 $this->node_id= $dbRecord['node_id'];
}else{
 $this->node_id= $dbRecord->node_id;
} if (is_array ($dbRecord)){
 $this->droit= $dbRecord['droit'];
}else{
 $this->droit= $dbRecord->droit;
} if (is_array ($dbRecord)){
 $this->debut= $dbRecord['debut'];
}else{
 $this->debut= $dbRecord->debut;
} if (is_array ($dbRecord)){
 $this->fin= $dbRecord['fin'];
}else{
 $this->fin= $dbRecord->fin;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_link_user2node';return $daoId;
}}

class CompiledDAOkernel_link_user2node implements ICopixDAO  { 
   var $_table='kernel_link_user2node';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_link_user2node.user_type, kernel_link_user2node.user_id, kernel_link_user2node.node_type, kernel_link_user2node.node_id, kernel_link_user2node.droit, kernel_link_user2node.debut, kernel_link_user2node.fin FROM kernel_link_user2node';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->user_id) > 0){
   if (! is_numeric ($pRecord->user_id)){
      $errorObject->addError ('user_id', _i18n ('copix:dao.errors.numeric','user_id'));
  }
  }
  if (strlen ($pRecord->node_id) > 0){
   if (! is_numeric ($pRecord->node_id)){
      $errorObject->addError ('node_id', _i18n ('copix:dao.errors.numeric','node_id'));
  }
  }
  if (strlen ($pRecord->droit) > 0){
   if (! is_numeric ($pRecord->droit)){
      $errorObject->addError ('droit', _i18n ('copix:dao.errors.numeric','droit'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($user_type,$user_id,$node_type,$node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE kernel_link_user2node.user_type '.($user_type === null ? 'IS' : '=').'  '.($user_type === null ? 'NULL' : ':kernel_link_user2node_user_type').'  AND kernel_link_user2node.user_id '.($user_id === null ? 'IS' : '=').'  '.($user_id === null ? 'NULL' : ':kernel_link_user2node_user_id').'  AND kernel_link_user2node.node_type '.($node_type === null ? 'IS' : '=').'  '.($node_type === null ? 'NULL' : ':kernel_link_user2node_node_type').'  AND kernel_link_user2node.node_id '.($node_id === null ? 'IS' : '=').'  '.($node_id === null ? 'NULL' : ':kernel_link_user2node_node_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_user2node_user_type'=>($user_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_type), ':kernel_link_user2node_user_id'=>($user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_id), ':kernel_link_user2node_node_type'=>($node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_type), ':kernel_link_user2node_node_id'=>($node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_id)))), $this->getDAOId ());
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
         array('user_type'=>array('user_type', 'string','kernel_link_user2node','%s'), 'user_id'=>array('user_id', 'integer','kernel_link_user2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_user2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_user2node','%s'), 'droit'=>array('droit', 'integer','kernel_link_user2node','%s'), 'debut'=>array('debut', 'string','kernel_link_user2node','%s'), 'fin'=>array('fin', 'string','kernel_link_user2node','%s')), $ct);
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
         array('user_type'=>array('user_type', 'string','kernel_link_user2node','%s'), 'user_id'=>array('user_id', 'integer','kernel_link_user2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_user2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_user2node','%s'), 'droit'=>array('droit', 'integer','kernel_link_user2node','%s'), 'debut'=>array('debut', 'string','kernel_link_user2node','%s'), 'fin'=>array('fin', 'string','kernel_link_user2node','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_link_user2node');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_link_user2node (user_type,user_id,node_type,node_id,droit,debut,fin) VALUES (:user_type, :user_id, :node_type, :node_id, :droit, :debut, :fin)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':user_type'=>$object->user_type, ':user_id'=>$object->user_id, ':node_type'=>$object->node_type, ':node_id'=>$object->node_id, ':droit'=>$object->droit, ':debut'=>$object->debut, ':fin'=>$object->fin)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_link_user2node');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_link_user2node SET  droit= :droit, debut= :debut, fin= :fin where user_type '.($object->user_type === null ? 'IS' : '=').'  '.($object->user_type === null ? 'NULL' : ':kernel_link_user2node_user_type').'  AND user_id '.($object->user_id === null ? 'IS' : '=').'  '.($object->user_id === null ? 'NULL' : ':kernel_link_user2node_user_id').'  AND node_type '.($object->node_type === null ? 'IS' : '=').'  '.($object->node_type === null ? 'NULL' : ':kernel_link_user2node_node_type').'  AND node_id '.($object->node_id === null ? 'IS' : '=').'  '.($object->node_id === null ? 'NULL' : ':kernel_link_user2node_node_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':droit'=>$object->droit, ':debut'=>$object->debut, ':fin'=>$object->fin, ':kernel_link_user2node_user_type'=>($object->user_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->user_type), ':kernel_link_user2node_user_id'=>($object->user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->user_id), ':kernel_link_user2node_node_type'=>($object->node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->node_type), ':kernel_link_user2node_node_id'=>($object->node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->node_id))));
   return $affectedRows;
 }
 public function delete ($user_type,$user_id,$node_type,$node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_user2node where user_type '.($user_type === null ? 'IS' : '=').'  '.($user_type === null ? 'NULL' : ':kernel_link_user2node_user_type').'  AND user_id '.($user_id === null ? 'IS' : '=').'  '.($user_id === null ? 'NULL' : ':kernel_link_user2node_user_id').'  AND node_type '.($node_type === null ? 'IS' : '=').'  '.($node_type === null ? 'NULL' : ':kernel_link_user2node_node_type').'  AND node_id '.($node_id === null ? 'IS' : '=').'  '.($node_id === null ? 'NULL' : ':kernel_link_user2node_node_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_link_user2node_user_type'=>($user_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_type), ':kernel_link_user2node_user_id'=>($user_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $user_id), ':kernel_link_user2node_node_type'=>($node_type === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_type), ':kernel_link_user2node_node_id'=>($node_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $node_id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_link_user2node';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_type'=>array('user_type', 'string','kernel_link_user2node','%s'), 'user_id'=>array('user_id', 'integer','kernel_link_user2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_user2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_user2node','%s'), 'droit'=>array('droit', 'integer','kernel_link_user2node','%s'), 'debut'=>array('debut', 'string','kernel_link_user2node','%s'), 'fin'=>array('fin', 'string','kernel_link_user2node','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_link_user2node';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('user_type'=>array('user_type', 'string','kernel_link_user2node','%s'), 'user_id'=>array('user_id', 'integer','kernel_link_user2node','%s'), 'node_type'=>array('node_type', 'string','kernel_link_user2node','%s'), 'node_id'=>array('node_id', 'integer','kernel_link_user2node','%s'), 'droit'=>array('droit', 'integer','kernel_link_user2node','%s'), 'debut'=>array('debut', 'string','kernel_link_user2node','%s'), 'fin'=>array('fin', 'string','kernel_link_user2node','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByUser ($user_type, $user_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_link_user2node.user_type'.($user_type===null ? ' IS ' : ' = ').''. $ct->quote ($user_type).' AND kernel_link_user2node.user_id'.($user_id===null ? ' IS ' : ' = ').''.($user_id === null ? 'NULL' : intval($user_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByNode ($node_type, $node_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_link_user2node.node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).' AND kernel_link_user2node.node_id'.($node_id===null ? ' IS ' : ' = ').''.($node_id === null ? 'NULL' : intval($node_id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getByUserAndNodeType ($user_type, $user_id, $node_type){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_link_user2node.user_type'.($user_type===null ? ' IS ' : ' = ').''. $ct->quote ($user_type).' AND kernel_link_user2node.user_id'.($user_id===null ? ' IS ' : ' = ').''.($user_id === null ? 'NULL' : intval($user_id)).' AND kernel_link_user2node.node_type'.($node_type===null ? ' IS ' : ' = ').''. $ct->quote ($node_type).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'user_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'user_type',
     'fieldName' => 'user_type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'user_type',
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_link_user2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'user_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'user_id',
     'fieldName' => 'user_id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'user_id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_link_user2node',
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
     'isPK' => true,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_link_user2node',
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
     'table' => 'kernel_link_user2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'droit' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'droit',
     'fieldName' => 'droit',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'droit',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_link_user2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'debut' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'debut',
     'fieldName' => 'debut',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'debut',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_link_user2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'fin' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'fin',
     'fieldName' => 'fin',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'fin',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_link_user2node',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'kernel|kernel_link_user2node';return $daoId;
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