<?php  Copix::RequireOnce ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blog.dao.php');

class CompiledDAORecordblog extends DAORecordblog implements ICopixDAORecord{
 var $id_blog = null;
 var $name_blog = null;
 var $id_ctpt = null;
 var $logo_blog = null;
 var $url_blog = null;
 var $style_blog_file = null;
 var $is_public = null;
 var $has_comments_activated = null;
 var $type_moderation_comments = null;
 var $default_format_articles = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->id_blog= $dbRecord['id_blog'];
}else{
 $this->id_blog= $dbRecord->id_blog;
} if (is_array ($dbRecord)){
 $this->name_blog= $dbRecord['name_blog'];
}else{
 $this->name_blog= $dbRecord->name_blog;
} if (is_array ($dbRecord)){
 $this->id_ctpt= $dbRecord['id_ctpt'];
}else{
 $this->id_ctpt= $dbRecord->id_ctpt;
} if (is_array ($dbRecord)){
 $this->logo_blog= $dbRecord['logo_blog'];
}else{
 $this->logo_blog= $dbRecord->logo_blog;
} if (is_array ($dbRecord)){
 $this->url_blog= $dbRecord['url_blog'];
}else{
 $this->url_blog= $dbRecord->url_blog;
} if (is_array ($dbRecord)){
 $this->style_blog_file= $dbRecord['style_blog_file'];
}else{
 $this->style_blog_file= $dbRecord->style_blog_file;
} if (is_array ($dbRecord)){
 $this->is_public= $dbRecord['is_public'];
}else{
 $this->is_public= $dbRecord->is_public;
} if (is_array ($dbRecord)){
 $this->has_comments_activated= $dbRecord['has_comments_activated'];
}else{
 $this->has_comments_activated= $dbRecord->has_comments_activated;
} if (is_array ($dbRecord)){
 $this->type_moderation_comments= $dbRecord['type_moderation_comments'];
}else{
 $this->type_moderation_comments= $dbRecord->type_moderation_comments;
} if (is_array ($dbRecord)){
 $this->default_format_articles= $dbRecord['default_format_articles'];
}else{
 $this->default_format_articles= $dbRecord->default_format_articles;
} return $this; }
public function getDAOId () {
$daoId = 'blog|blog';return $daoId;
}}
  Copix::RequireOnce  ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blog.dao.php');

class CompiledDAOblog extends DAOblog implements ICopixDAO  { 
   var $_table='module_blog';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT blog.id_blog, blog.name_blog, blog.id_ctpt, blog.logo_blog, blog.url_blog, blog.style_blog_file, blog.is_public, blog.has_comments_activated, blog.type_moderation_comments, blog.default_format_articles FROM module_blog blog';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if ($pRecord->name_blog === null){
    $errorObject->addError ('name_blog', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.name_blog')));
  }
  if (strlen ($pRecord->name_blog) > 100){
      $errorObject->addError ('name_blog', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.name_blog'), 100)));
  }
  if ($pRecord->id_ctpt === null){
    $errorObject->addError ('id_ctpt', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.id_ctpt')));
  }
  if (strlen ($pRecord->id_ctpt) > 20){
      $errorObject->addError ('id_ctpt', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.id_ctpt'), 20)));
  }
  if (strlen ($pRecord->id_ctpt) > 0){
   if (! is_numeric ($pRecord->id_ctpt)){
      $errorObject->addError ('id_ctpt', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blog.fields.id_ctpt')));
  }
  }
  if (strlen ($pRecord->logo_blog) > 100){
      $errorObject->addError ('logo_blog', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.logo_blog'), 100)));
  }
  if ($pRecord->url_blog === null){
    $errorObject->addError ('url_blog', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.url_blog')));
  }
  if (strlen ($pRecord->url_blog) > 100){
      $errorObject->addError ('url_blog', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.url_blog'), 100)));
  }
  if ($pRecord->style_blog_file === null){
    $errorObject->addError ('style_blog_file', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.style_blog_file')));
  }
  if (strlen ($pRecord->style_blog_file) > 0){
   if (! is_numeric ($pRecord->style_blog_file)){
      $errorObject->addError ('style_blog_file', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blog.fields.style_blog_file')));
  }
  }
  if ($pRecord->is_public === null){
    $errorObject->addError ('is_public', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.is_public')));
  }
  if (strlen ($pRecord->is_public) > 0){
   if (! is_numeric ($pRecord->is_public)){
      $errorObject->addError ('is_public', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blog.fields.is_public')));
  }
  }
  if ($pRecord->has_comments_activated === null){
    $errorObject->addError ('has_comments_activated', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.has_comments_activated')));
  }
  if (strlen ($pRecord->has_comments_activated) > 0){
   if (! is_numeric ($pRecord->has_comments_activated)){
      $errorObject->addError ('has_comments_activated', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blog.fields.has_comments_activated')));
  }
  }
  if ($pRecord->type_moderation_comments === null){
    $errorObject->addError ('type_moderation_comments', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.type_moderation_comments')));
  }
  if (strlen ($pRecord->type_moderation_comments) > 5){
      $errorObject->addError ('type_moderation_comments', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.type_moderation_comments'), 5)));
  }
  if ($pRecord->default_format_articles === null){
    $errorObject->addError ('default_format_articles', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blog.fields.type_moderation_comments')));
  }
  if (strlen ($pRecord->default_format_articles) > 10){
      $errorObject->addError ('default_format_articles', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blog.fields.type_moderation_comments'), 10)));
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($id_blog){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE blog.id_blog '.($id_blog === null ? 'IS' : '=').'  '.($id_blog === null ? 'NULL' : ':blog_id_blog').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blog_id_blog'=>($id_blog === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_blog)))), $this->getDAOId ());
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
         array('id_blog'=>array('id_blog', 'autoincrement','blog','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'id_ctpt'=>array('id_ctpt', 'int','blog','%s'), 'logo_blog'=>array('logo_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'style_blog_file'=>array('style_blog_file', 'integer','blog','%s'), 'is_public'=>array('is_public', 'integer','blog','%s'), 'has_comments_activated'=>array('has_comments_activated', 'integer','blog','%s'), 'type_moderation_comments'=>array('type_moderation_comments', 'string','blog','%s'), 'default_format_articles'=>array('default_format_articles', 'string','blog','%s')), $ct);
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
         array('id_blog'=>array('id_blog', 'autoincrement','blog','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'id_ctpt'=>array('id_ctpt', 'int','blog','%s'), 'logo_blog'=>array('logo_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'style_blog_file'=>array('style_blog_file', 'integer','blog','%s'), 'is_public'=>array('is_public', 'integer','blog','%s'), 'has_comments_activated'=>array('has_comments_activated', 'integer','blog','%s'), 'type_moderation_comments'=>array('type_moderation_comments', 'string','blog','%s'), 'default_format_articles'=>array('default_format_articles', 'string','blog','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blog');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
if (($object->id_blog !== null) && $pUseId){    $query = 'INSERT INTO module_blog (id_blog,name_blog,id_ctpt,logo_blog,url_blog,style_blog_file,is_public,has_comments_activated,type_moderation_comments,default_format_articles) VALUES (:id_blog, :name_blog, :id_ctpt, :logo_blog, :url_blog, :style_blog_file, :is_public, :has_comments_activated, :type_moderation_comments, :default_format_articles)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_blog'=>$object->id_blog, ':name_blog'=>$object->name_blog, ':id_ctpt'=>$object->id_ctpt, ':logo_blog'=>$object->logo_blog, ':url_blog'=>$object->url_blog, ':style_blog_file'=>$object->style_blog_file, ':is_public'=>$object->is_public, ':has_comments_activated'=>$object->has_comments_activated, ':type_moderation_comments'=>$object->type_moderation_comments, ':default_format_articles'=>$object->default_format_articles)));
}else{    $query = 'INSERT INTO module_blog (name_blog,id_ctpt,logo_blog,url_blog,style_blog_file,is_public,has_comments_activated,type_moderation_comments,default_format_articles) VALUES (:name_blog, :id_ctpt, :logo_blog, :url_blog, :style_blog_file, :is_public, :has_comments_activated, :type_moderation_comments, :default_format_articles)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':name_blog'=>$object->name_blog, ':id_ctpt'=>$object->id_ctpt, ':logo_blog'=>$object->logo_blog, ':url_blog'=>$object->url_blog, ':style_blog_file'=>$object->style_blog_file, ':is_public'=>$object->is_public, ':has_comments_activated'=>$object->has_comments_activated, ':type_moderation_comments'=>$object->type_moderation_comments, ':default_format_articles'=>$object->default_format_articles)));
}if (! $pUseId){$object->id_blog= $ct->lastId();}
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blog');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_blog SET  name_blog= :name_blog, id_ctpt= :id_ctpt, logo_blog= :logo_blog, url_blog= :url_blog, style_blog_file= :style_blog_file, is_public= :is_public, has_comments_activated= :has_comments_activated, type_moderation_comments= :type_moderation_comments, default_format_articles= :default_format_articles where id_blog '.($object->id_blog === null ? 'IS' : '=').'  '.($object->id_blog === null ? 'NULL' : ':blog_id_blog').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':name_blog'=>$object->name_blog, ':id_ctpt'=>$object->id_ctpt, ':logo_blog'=>$object->logo_blog, ':url_blog'=>$object->url_blog, ':style_blog_file'=>$object->style_blog_file, ':is_public'=>$object->is_public, ':has_comments_activated'=>$object->has_comments_activated, ':type_moderation_comments'=>$object->type_moderation_comments, ':default_format_articles'=>$object->default_format_articles, ':blog_id_blog'=>($object->id_blog === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id_blog))));
   return $affectedRows;
 }
 public function _compiled_delete ($id_blog){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog where id_blog '.($id_blog === null ? 'IS' : '=').'  '.($id_blog === null ? 'NULL' : ':blog_id_blog').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blog_id_blog'=>($id_blog === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_blog))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_blog'=>array('id_blog', 'autoincrement','blog','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'id_ctpt'=>array('id_ctpt', 'int','blog','%s'), 'logo_blog'=>array('logo_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'style_blog_file'=>array('style_blog_file', 'integer','blog','%s'), 'is_public'=>array('is_public', 'integer','blog','%s'), 'has_comments_activated'=>array('has_comments_activated', 'integer','blog','%s'), 'type_moderation_comments'=>array('type_moderation_comments', 'string','blog','%s'), 'default_format_articles'=>array('default_format_articles', 'string','blog','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_blog blog';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_blog'=>array('id_blog', 'autoincrement','blog','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'id_ctpt'=>array('id_ctpt', 'int','blog','%s'), 'logo_blog'=>array('logo_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'style_blog_file'=>array('style_blog_file', 'integer','blog','%s'), 'is_public'=>array('is_public', 'integer','blog','%s'), 'has_comments_activated'=>array('has_comments_activated', 'integer','blog','%s'), 'type_moderation_comments'=>array('type_moderation_comments', 'string','blog','%s'), 'default_format_articles'=>array('default_format_articles', 'string','blog','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
public function getFieldsDescription() {
$fields = array (
  'id_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id_blog',
     'fieldName' => 'id_blog',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.id_blog',
     'caption' => NULL,
     'isPK' => true,
     'isFK' => false,
     'type' => 'autoincrement',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'name_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'name_blog',
     'fieldName' => 'name_blog',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.name_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'id_ctpt' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'id_ctpt',
     'fieldName' => 'id_ctpt',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.id_ctpt',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '20',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'logo_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'logo_blog',
     'fieldName' => 'logo_blog',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blog.fields.logo_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'url_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'url_blog',
     'fieldName' => 'url_blog',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.url_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'style_blog_file' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'style_blog_file',
     'fieldName' => 'style_blog_file',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.style_blog_file',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'is_public' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'is_public',
     'fieldName' => 'is_public',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.is_public',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'has_comments_activated' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'has_comments_activated',
     'fieldName' => 'has_comments_activated',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.has_comments_activated',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'type_moderation_comments' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'type_moderation_comments',
     'fieldName' => 'type_moderation_comments',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.type_moderation_comments',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '5',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'default_format_articles' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'default_format_articles',
     'fieldName' => 'default_format_articles',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blog.fields.type_moderation_comments',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '10',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'blog|blog';return $daoId;
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