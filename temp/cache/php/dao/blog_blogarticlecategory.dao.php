<?php  Copix::RequireOnce ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blogarticlecategory.dao.php');

class CompiledDAORecordblogarticlecategory extends DAORecordblogarticlecategory implements ICopixDAORecord{
 var $id_bacg = null;
 var $id_blog = null;
 var $order_bacg = null;
 var $name_bacg = null;
 var $url_bacg = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->id_bacg= $dbRecord['id_bacg'];
}else{
 $this->id_bacg= $dbRecord->id_bacg;
} if (is_array ($dbRecord)){
 $this->id_blog= $dbRecord['id_blog'];
}else{
 $this->id_blog= $dbRecord->id_blog;
} if (is_array ($dbRecord)){
 $this->order_bacg= $dbRecord['order_bacg'];
}else{
 $this->order_bacg= $dbRecord->order_bacg;
} if (is_array ($dbRecord)){
 $this->name_bacg= $dbRecord['name_bacg'];
}else{
 $this->name_bacg= $dbRecord->name_bacg;
} if (is_array ($dbRecord)){
 $this->url_bacg= $dbRecord['url_bacg'];
}else{
 $this->url_bacg= $dbRecord->url_bacg;
} return $this; }
public function getDAOId () {
$daoId = 'blog|blogarticlecategory';return $daoId;
}}
  Copix::RequireOnce  ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blogarticlecategory.dao.php');

class CompiledDAOblogarticlecategory extends DAOblogarticlecategory implements ICopixDAO  { 
   var $_table='module_blog_articlecategory';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT blogarticlecategory.id_bacg, blogarticlecategory.id_blog, blogarticlecategory.order_bacg, blogarticlecategory.name_bacg, blogarticlecategory.url_bacg FROM module_blog_articlecategory blogarticlecategory';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if ($pRecord->id_blog === null){
    $errorObject->addError ('id_blog', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.id_blog')));
  }
  if (strlen ($pRecord->id_blog) > 20){
      $errorObject->addError ('id_blog', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.id_blog'), 20)));
  }
  if (strlen ($pRecord->id_blog) > 0){
   if (! is_numeric ($pRecord->id_blog)){
      $errorObject->addError ('id_blog', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blog.fields.id_blog')));
  }
  }
  if ($pRecord->order_bacg === null){
    $errorObject->addError ('order_bacg', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticlecategory.fields.order_bacg')));
  }
  if (strlen ($pRecord->order_bacg) > 10){
      $errorObject->addError ('order_bacg', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticlecategory.fields.order_bacg'), 10)));
  }
  if (strlen ($pRecord->order_bacg) > 0){
   if (! is_numeric ($pRecord->order_bacg)){
      $errorObject->addError ('order_bacg', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blogarticlecategory.fields.order_bacg')));
  }
  }
  if ($pRecord->name_bacg === null){
    $errorObject->addError ('name_bacg', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticlecategory.fields.name_bacg')));
  }
  if (strlen ($pRecord->name_bacg) > 100){
      $errorObject->addError ('name_bacg', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticlecategory.fields.name_bacg'), 100)));
  }
  if ($pRecord->url_bacg === null){
    $errorObject->addError ('url_bacg', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticlecategory.fields.url_bacg')));
  }
  if (strlen ($pRecord->url_bacg) > 100){
      $errorObject->addError ('url_bacg', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticlecategory.fields.url_bacg'), 100)));
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($id_bacg){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE blogarticlecategory.id_bacg '.($id_bacg === null ? 'IS' : '=').'  '.($id_bacg === null ? 'NULL' : ':blogarticlecategory_id_bacg').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticlecategory_id_bacg'=>($id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bacg)))), $this->getDAOId ());
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
         array('id_bacg'=>array('id_bacg', 'autoincrement','blogarticlecategory','%s'), 'id_blog'=>array('id_blog', 'int','blogarticlecategory','%s'), 'order_bacg'=>array('order_bacg', 'int','blogarticlecategory','%s'), 'name_bacg'=>array('name_bacg', 'string','blogarticlecategory','%s'), 'url_bacg'=>array('url_bacg', 'string','blogarticlecategory','%s')), $ct);
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
         array('id_bacg'=>array('id_bacg', 'autoincrement','blogarticlecategory','%s'), 'id_blog'=>array('id_blog', 'int','blogarticlecategory','%s'), 'order_bacg'=>array('order_bacg', 'int','blogarticlecategory','%s'), 'name_bacg'=>array('name_bacg', 'string','blogarticlecategory','%s'), 'url_bacg'=>array('url_bacg', 'string','blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticlecategory');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
if (($object->id_bacg !== null) && $pUseId){    $query = 'INSERT INTO module_blog_articlecategory (id_bacg,id_blog,order_bacg,name_bacg,url_bacg) VALUES (:id_bacg, :id_blog, :order_bacg, :name_bacg, :url_bacg)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_bacg'=>$object->id_bacg, ':id_blog'=>$object->id_blog, ':order_bacg'=>$object->order_bacg, ':name_bacg'=>$object->name_bacg, ':url_bacg'=>$object->url_bacg)));
}else{    $query = 'INSERT INTO module_blog_articlecategory (id_blog,order_bacg,name_bacg,url_bacg) VALUES (:id_blog, :order_bacg, :name_bacg, :url_bacg)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_blog'=>$object->id_blog, ':order_bacg'=>$object->order_bacg, ':name_bacg'=>$object->name_bacg, ':url_bacg'=>$object->url_bacg)));
}if (! $pUseId){$object->id_bacg= $ct->lastId();}
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticlecategory');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_blog_articlecategory SET  id_blog= :id_blog, order_bacg= :order_bacg, name_bacg= :name_bacg, url_bacg= :url_bacg where id_bacg '.($object->id_bacg === null ? 'IS' : '=').'  '.($object->id_bacg === null ? 'NULL' : ':blogarticlecategory_id_bacg').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_blog'=>$object->id_blog, ':order_bacg'=>$object->order_bacg, ':name_bacg'=>$object->name_bacg, ':url_bacg'=>$object->url_bacg, ':blogarticlecategory_id_bacg'=>($object->id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id_bacg))));
   return $affectedRows;
 }
 public function _compiled_delete ($id_bacg){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_articlecategory where id_bacg '.($id_bacg === null ? 'IS' : '=').'  '.($id_bacg === null ? 'NULL' : ':blogarticlecategory_id_bacg').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticlecategory_id_bacg'=>($id_bacg === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bacg))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_articlecategory';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bacg'=>array('id_bacg', 'autoincrement','blogarticlecategory','%s'), 'id_blog'=>array('id_blog', 'int','blogarticlecategory','%s'), 'order_bacg'=>array('order_bacg', 'int','blogarticlecategory','%s'), 'name_bacg'=>array('name_bacg', 'string','blogarticlecategory','%s'), 'url_bacg'=>array('url_bacg', 'string','blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_blog_articlecategory blogarticlecategory';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bacg'=>array('id_bacg', 'autoincrement','blogarticlecategory','%s'), 'id_blog'=>array('id_blog', 'int','blogarticlecategory','%s'), 'order_bacg'=>array('order_bacg', 'int','blogarticlecategory','%s'), 'name_bacg'=>array('name_bacg', 'string','blogarticlecategory','%s'), 'url_bacg'=>array('url_bacg', 'string','blogarticlecategory','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
public function getFieldsDescription() {
$fields = array (
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
     'type' => 'autoincrement',
     'table' => 'blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'id_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id_blog',
     'fieldName' => 'id_blog',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.id_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '20',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'order_bacg' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'order_bacg',
     'fieldName' => 'order_bacg',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticlecategory.fields.order_bacg',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '10',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'name_bacg' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'name_bacg',
     'fieldName' => 'name_bacg',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticlecategory.fields.name_bacg',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'url_bacg' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'url_bacg',
     'fieldName' => 'url_bacg',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticlecategory.fields.url_bacg',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticlecategory',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'blog|blogarticlecategory';return $daoId;
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