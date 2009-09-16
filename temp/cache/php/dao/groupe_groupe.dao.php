<?php 
class CompiledDAORecordgroupe implements ICopixDAORecord{
 var $id = null;
 var $titre = null;
 var $description = null;
 var $is_open = null;
 var $createur = null;
 var $date_creation = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->id= $dbRecord['id'];
}else{
 $this->id= $dbRecord->id;
} if (is_array ($dbRecord)){
 $this->titre= $dbRecord['titre'];
}else{
 $this->titre= $dbRecord->titre;
} if (is_array ($dbRecord)){
 $this->description= $dbRecord['description'];
}else{
 $this->description= $dbRecord->description;
} if (is_array ($dbRecord)){
 $this->is_open= $dbRecord['is_open'];
}else{
 $this->is_open= $dbRecord->is_open;
} if (is_array ($dbRecord)){
 $this->createur= $dbRecord['createur'];
}else{
 $this->createur= $dbRecord->createur;
} if (is_array ($dbRecord)){
 $this->date_creation= $dbRecord['date_creation'];
}else{
 $this->date_creation= $dbRecord->date_creation;
} return $this; }
public function getDAOId () {
$daoId = 'groupe|groupe';return $daoId;
}}

class CompiledDAOgroupe implements ICopixDAO  { 
   var $_table='module_groupe_groupe';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT groupe_groupe.id, groupe_groupe.titre, groupe_groupe.description, groupe_groupe.is_open, groupe_groupe.createur, DATE_FORMAT(groupe_groupe.date_creation, \'%Y%m%d\') date_creation FROM module_groupe_groupe groupe_groupe';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->is_open) > 0){
   if (! is_numeric ($pRecord->is_open)){
      $errorObject->addError ('is_open', _i18n ('copix:dao.errors.numeric','is_open'));
  }
  }
  if (strlen ($pRecord->createur) > 0){
   if (! is_numeric ($pRecord->createur)){
      $errorObject->addError ('createur', _i18n ('copix:dao.errors.numeric','createur'));
  }
  }
     if (CopixDateTime::yyyymmddToDate ($pRecord->date_creation) === false){
        $errorObject->addError ('date_creation', _i18n ('copix:dao.errors.date','date_creation'));
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE groupe_groupe.id '.($id === null ? 'IS' : '=').'  '.($id === null ? 'NULL' : ':groupe_groupe_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':groupe_groupe_id'=>($id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id)))), $this->getDAOId ());
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
         array('id'=>array('id', 'autoincrement','groupe_groupe','%s'), 'titre'=>array('titre', 'string','groupe_groupe','%s'), 'description'=>array('description', 'string','groupe_groupe','%s'), 'is_open'=>array('is_open', 'integer','groupe_groupe','%s'), 'createur'=>array('createur', 'integer','groupe_groupe','%s'), 'date_creation'=>array('date_creation', 'date','groupe_groupe','%s')), $ct);
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
         array('id'=>array('id', 'autoincrement','groupe_groupe','%s'), 'titre'=>array('titre', 'string','groupe_groupe','%s'), 'description'=>array('description', 'string','groupe_groupe','%s'), 'is_open'=>array('is_open', 'integer','groupe_groupe','%s'), 'createur'=>array('createur', 'integer','groupe_groupe','%s'), 'date_creation'=>array('date_creation', 'date','groupe_groupe','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('groupe|groupe');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
if (($object->id !== null) && $pUseId){    $query = 'INSERT INTO module_groupe_groupe (id,titre,description,is_open,createur,date_creation) VALUES (:id, :titre, :description, :is_open, :createur, :date_creation)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id'=>$object->id, ':titre'=>$object->titre, ':description'=>$object->description, ':is_open'=>$object->is_open, ':createur'=>$object->createur, ':date_creation'=>CopixDateTime::yyyymmddToFormat ($object->date_creation, 'Y-m-d'))));
}else{    $query = 'INSERT INTO module_groupe_groupe (titre,description,is_open,createur,date_creation) VALUES (:titre, :description, :is_open, :createur, :date_creation)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':titre'=>$object->titre, ':description'=>$object->description, ':is_open'=>$object->is_open, ':createur'=>$object->createur, ':date_creation'=>CopixDateTime::yyyymmddToFormat ($object->date_creation, 'Y-m-d'))));
}if (! $pUseId){$object->id= $ct->lastId();}
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('groupe|groupe');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_groupe_groupe SET  titre= :titre, description= :description, is_open= :is_open, createur= :createur, date_creation= :date_creation where id '.($object->id === null ? 'IS' : '=').'  '.($object->id === null ? 'NULL' : ':groupe_groupe_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':titre'=>$object->titre, ':description'=>$object->description, ':is_open'=>$object->is_open, ':createur'=>$object->createur, ':date_creation'=>CopixDateTime::yyyymmddToFormat ($object->date_creation, 'Y-m-d'), ':groupe_groupe_id'=>($object->id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id))));
   return $affectedRows;
 }
 public function delete ($id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_groupe_groupe where id '.($id === null ? 'IS' : '=').'  '.($id === null ? 'NULL' : ':groupe_groupe_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':groupe_groupe_id'=>($id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_groupe_groupe';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id'=>array('id', 'autoincrement','groupe_groupe','%s'), 'titre'=>array('titre', 'string','groupe_groupe','%s'), 'description'=>array('description', 'string','groupe_groupe','%s'), 'is_open'=>array('is_open', 'integer','groupe_groupe','%s'), 'createur'=>array('createur', 'integer','groupe_groupe','%s'), 'date_creation'=>array('date_creation', 'date','groupe_groupe','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_groupe_groupe groupe_groupe';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id'=>array('id', 'autoincrement','groupe_groupe','%s'), 'titre'=>array('titre', 'string','groupe_groupe','%s'), 'description'=>array('description', 'string','groupe_groupe','%s'), 'is_open'=>array('is_open', 'integer','groupe_groupe','%s'), 'createur'=>array('createur', 'integer','groupe_groupe','%s'), 'date_creation'=>array('date_creation', 'date','groupe_groupe','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getListPublic ($offset, $count){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE groupe_groupe.is_open'.('1'===null ? ' IS ' : ' = ').''.('1' === null ? 'NULL' : intval('1')).' ORDER BY groupe_groupe.date_creation desc';
    return new CopixDAORecordIterator ($ct->doQuery ($query, array (),  intval($offset),  intval($count)), $this->getDAOId ());
 }
 function getListPublicAll (){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE groupe_groupe.is_open'.('1'===null ? ' IS ' : ' = ').''.('1' === null ? 'NULL' : intval('1')).' ORDER BY groupe_groupe.date_creation desc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getListAll (){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE 1=1  ORDER BY groupe_groupe.date_creation desc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getGroupe ($id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE groupe_groupe.id'.($id===null ? ' IS ' : ' = ').''.($id === null ? 'NULL' : intval($id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id',
     'fieldName' => 'id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'autoincrement',
     'table' => 'groupe_groupe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'titre' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'titre',
     'fieldName' => 'titre',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'titre',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'groupe_groupe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'description' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'description',
     'fieldName' => 'description',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'description',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'groupe_groupe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'is_open' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'is_open',
     'fieldName' => 'is_open',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'is_open',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'groupe_groupe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'createur' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'createur',
     'fieldName' => 'createur',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'createur',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'groupe_groupe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'date_creation' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'date_creation',
     'fieldName' => 'date_creation',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'date_creation',
     'isPK' => false,
     'isFK' => false,
     'type' => 'date',
     'table' => 'groupe_groupe',
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
$daoId = 'groupe|groupe';return $daoId;
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