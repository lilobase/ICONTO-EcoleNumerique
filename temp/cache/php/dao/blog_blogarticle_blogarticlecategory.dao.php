<?php 
class CompiledDAORecordblogarticle_blogarticlecategory implements ICopixDAORecord{
 var $id_bact = null;
 var $id_bacg = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->id_bact= $dbRecord['id_bact'];
}else{
 $this->id_bact= $dbRecord->id_bact;
} if (is_array ($dbRecord)){
 $this->id_bacg= $dbRecord['id_bacg'];
}else{
 $this->id_bacg= $dbRecord->id_bacg;
} return $this; }
public function getDAOId () {
$daoId = 'blog|blogarticle_blogarticlecategory';return $daoId;
}}
  Copix::RequireOnce  ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blogarticle_blogarticlecategory.dao.php');

class CompiledDAOblogarticle_blogarticlecategory extends DAOblogarticle_blogarticlecategory implements ICopixDAO  { 
   var $_table='module_blog_article_blogarticlecategory';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT blogarticle_blogarticlecategory.id_bact, blogarticle_blogarticlecategory.id_bacg FROM module_blog_article_blogarticlecategory blogarticle_blogarticlecategory';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if ($pRecord->id_bact === null){
    $errorObject->addError ('id_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.id_bact')));
  }
  if (strlen ($pRecord->id_bact) > 20){
      $errorObject->addError ('id_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.id_bact'), 20)));
  }
  if (strlen ($pRecord->id_bact) > 0){
   if (! is_numeric ($pRecord->id_bact)){
      $errorObject->addError ('id_bact', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blogarticle.fields.id_bact')));
  }
  }
  if ($pRecord->id_bacg === null){
    $errorObject->addError ('id_bacg', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticlecategory.fields.id_bacg')));
  }
  if (strlen ($pRecord->id_bacg) > 20){
      $errorObject->addError ('id_bacg', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticlecategory.fields.id_bacg'), 20)));
  }
  if (strlen ($pRecord->id_bacg) > 0){
   if (! is_numeric ($pRecord->id_bacg)){
      $errorObject->addError ('id_bacg', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blogarticlecategory.fields.id_bacg')));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($id_bact,$id_bacg){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE blogarticle_blogarticlecategory.id_bact '.($id_bact === null ? 'IS' : '=').'  '.($id_bact === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bact').'  AND blogarticle_blogarticlecategory.id_bacg '.($id_bacg === null ? 'IS' : '=').'  '.($id_bacg === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bacg').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticle_blogarticlecategory_id_bact'=>($id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bact), ':blogarticle_blogarticlecategory_id_bacg'=>($id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bacg)))), $this->getDAOId ());
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
         array('id_bact'=>array('id_bact', 'int','blogarticle_blogarticlecategory','%s'), 'id_bacg'=>array('id_bacg', 'int','blogarticle_blogarticlecategory','%s')), $ct);
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
         array('id_bact'=>array('id_bact', 'int','blogarticle_blogarticlecategory','%s'), 'id_bacg'=>array('id_bacg', 'int','blogarticle_blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticle_blogarticlecategory');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO module_blog_article_blogarticlecategory (id_bact,id_bacg) VALUES (:id_bact, :id_bacg)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_bact'=>$object->id_bact, ':id_bacg'=>$object->id_bacg)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticle_blogarticlecategory');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_blog_article_blogarticlecategory SET  where id_bact '.($object->id_bact === null ? 'IS' : '=').'  '.($object->id_bact === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bact').'  AND id_bacg '.($object->id_bacg === null ? 'IS' : '=').'  '.($object->id_bacg === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bacg').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticle_blogarticlecategory_id_bact'=>($object->id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id_bact), ':blogarticle_blogarticlecategory_id_bacg'=>($object->id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id_bacg))));
   return $affectedRows;
 }
 public function delete ($id_bact,$id_bacg){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_article_blogarticlecategory where id_bact '.($id_bact === null ? 'IS' : '=').'  '.($id_bact === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bact').'  AND id_bacg '.($id_bacg === null ? 'IS' : '=').'  '.($id_bacg === null ? 'NULL' : ':blogarticle_blogarticlecategory_id_bacg').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticle_blogarticlecategory_id_bact'=>($id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bact), ':blogarticle_blogarticlecategory_id_bacg'=>($id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bacg))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_article_blogarticlecategory';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bact'=>array('id_bact', 'int','blogarticle_blogarticlecategory','%s'), 'id_bacg'=>array('id_bacg', 'int','blogarticle_blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_blog_article_blogarticlecategory blogarticle_blogarticlecategory';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bact'=>array('id_bact', 'int','blogarticle_blogarticlecategory','%s'), 'id_bacg'=>array('id_bacg', 'int','blogarticle_blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
public function getFieldsDescription() {
$fields = array (
  'id_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id_bact',
     'fieldName' => 'id_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.id_bact',
     'caption' => NULL,
     'isPK' => true,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticle_blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '20',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'id_bacg' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id_bacg',
     'fieldName' => 'id_bacg',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticlecategory.fields.id_bacg',
     'caption' => NULL,
     'isPK' => true,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticle_blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '20',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'blog|blogarticle_blogarticlecategory';return $daoId;
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