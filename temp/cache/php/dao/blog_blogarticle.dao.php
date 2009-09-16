<?php  Copix::RequireOnce ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blogarticle.dao.php');

class CompiledDAORecordblogarticle extends DAORecordblogarticle implements ICopixDAORecord{
 var $id_bact = null;
 var $id_blog = null;
 var $name_blog = null;
 var $url_blog = null;
 var $name_bact = null;
 var $sumary_bact = null;
 var $sumary_html_bact = null;
 var $content_bact = null;
 var $content_html_bact = null;
 var $format_bact = null;
 var $author_bact = null;
 var $date_bact = null;
 var $time_bact = null;
 var $url_bact = null;
 var $sticky_bact = null;
 var $is_online = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->id_bact= $dbRecord['id_bact'];
}else{
 $this->id_bact= $dbRecord->id_bact;
} if (is_array ($dbRecord)){
 $this->id_blog= $dbRecord['id_blog'];
}else{
 $this->id_blog= $dbRecord->id_blog;
} if (is_array ($dbRecord)){
 $this->name_blog= $dbRecord['name_blog'];
}else{
 $this->name_blog= $dbRecord->name_blog;
} if (is_array ($dbRecord)){
 $this->url_blog= $dbRecord['url_blog'];
}else{
 $this->url_blog= $dbRecord->url_blog;
} if (is_array ($dbRecord)){
 $this->name_bact= $dbRecord['name_bact'];
}else{
 $this->name_bact= $dbRecord->name_bact;
} if (is_array ($dbRecord)){
 $this->sumary_bact= $dbRecord['sumary_bact'];
}else{
 $this->sumary_bact= $dbRecord->sumary_bact;
} if (is_array ($dbRecord)){
 $this->sumary_html_bact= $dbRecord['sumary_html_bact'];
}else{
 $this->sumary_html_bact= $dbRecord->sumary_html_bact;
} if (is_array ($dbRecord)){
 $this->content_bact= $dbRecord['content_bact'];
}else{
 $this->content_bact= $dbRecord->content_bact;
} if (is_array ($dbRecord)){
 $this->content_html_bact= $dbRecord['content_html_bact'];
}else{
 $this->content_html_bact= $dbRecord->content_html_bact;
} if (is_array ($dbRecord)){
 $this->format_bact= $dbRecord['format_bact'];
}else{
 $this->format_bact= $dbRecord->format_bact;
} if (is_array ($dbRecord)){
 $this->author_bact= $dbRecord['author_bact'];
}else{
 $this->author_bact= $dbRecord->author_bact;
} if (is_array ($dbRecord)){
 $this->date_bact= $dbRecord['date_bact'];
}else{
 $this->date_bact= $dbRecord->date_bact;
} if (is_array ($dbRecord)){
 $this->time_bact= $dbRecord['time_bact'];
}else{
 $this->time_bact= $dbRecord->time_bact;
} if (is_array ($dbRecord)){
 $this->url_bact= $dbRecord['url_bact'];
}else{
 $this->url_bact= $dbRecord->url_bact;
} if (is_array ($dbRecord)){
 $this->sticky_bact= $dbRecord['sticky_bact'];
}else{
 $this->sticky_bact= $dbRecord->sticky_bact;
} if (is_array ($dbRecord)){
 $this->is_online= $dbRecord['is_online'];
}else{
 $this->is_online= $dbRecord->is_online;
} return $this; }
public function getDAOId () {
$daoId = 'blog|blogarticle';return $daoId;
}}
  Copix::RequireOnce  ('E:\Webs\EcoleNumerique2\trunk\project/modules/public/stable/iconito/blog/classes/blogarticle.dao.php');

class CompiledDAOblogarticle extends DAOblogarticle implements ICopixDAO  { 
   var $_table='module_blog_article';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT blogarticle.id_bact, blogarticle.id_blog, blog.name_blog, blog.url_blog, blogarticle.name_bact, blogarticle.sumary_bact, blogarticle.sumary_html_bact, blogarticle.content_bact, blogarticle.content_html_bact, blogarticle.format_bact, blogarticle.author_bact, blogarticle.date_bact, blogarticle.time_bact, blogarticle.url_bact, blogarticle.sticky_bact, blogarticle.is_online FROM module_blog_article blogarticle LEFT JOIN module_blog blog ON (blogarticle.id_blog=blog.id_blog)';
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
  if ($pRecord->name_bact === null){
    $errorObject->addError ('name_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.name_bact')));
  }
  if (strlen ($pRecord->name_bact) > 100){
      $errorObject->addError ('name_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.name_bact'), 100)));
  }
  if ($pRecord->format_bact === null){
    $errorObject->addError ('format_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.format_bact')));
  }
  if ($pRecord->author_bact === null){
    $errorObject->addError ('author_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.author_bact')));
  }
  if (strlen ($pRecord->author_bact) > 50){
      $errorObject->addError ('author_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.author_bact'), 50)));
  }
  if ($pRecord->date_bact === null){
    $errorObject->addError ('date_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.date_bact')));
  }
  if (strlen ($pRecord->date_bact) > 8){
      $errorObject->addError ('date_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.date_bact'), 8)));
  }
  if ($pRecord->time_bact === null){
    $errorObject->addError ('time_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.time_bact')));
  }
  if (strlen ($pRecord->time_bact) > 5){
      $errorObject->addError ('time_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.time_bact'), 5)));
  }
  if (strlen ($pRecord->url_bact) > 100){
      $errorObject->addError ('url_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.url_bacg'), 100)));
  }
  if ($pRecord->sticky_bact === null){
    $errorObject->addError ('sticky_bact', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.sticky_bact')));
  }
  if (strlen ($pRecord->sticky_bact) > 1){
      $errorObject->addError ('sticky_bact', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.sticky_bact'), 1)));
  }
  if (strlen ($pRecord->sticky_bact) > 0){
   if (! is_numeric ($pRecord->sticky_bact)){
      $errorObject->addError ('sticky_bact', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blogarticle.fields.sticky_bact')));
  }
  }
  if ($pRecord->is_online === null){
    $errorObject->addError ('is_online', _i18n ('copix:dao.errors.required',_i18n ('blog|dao.blogarticle.fields.is_online')));
  }
  if (strlen ($pRecord->is_online) > 1){
      $errorObject->addError ('is_online', _i18n ('copix:dao.errors.sizeLimit',array(_i18n ('blog|dao.blogarticle.fields.is_online'), 1)));
  }
  if (strlen ($pRecord->is_online) > 0){
   if (! is_numeric ($pRecord->is_online)){
      $errorObject->addError ('is_online', _i18n ('copix:dao.errors.numeric',_i18n ('blog|dao.blogarticle.fields.is_online')));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($id_bact){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE blogarticle.id_bact '.($id_bact === null ? 'IS' : '=').'  '.($id_bact === null ? 'NULL' : ':blogarticle_id_bact').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticle_id_bact'=>($id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bact)))), $this->getDAOId ());
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
         array('id_bact'=>array('id_bact', 'autoincrement','blogarticle','%s'), 'id_blog'=>array('id_blog', 'int','blogarticle','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'name_bact'=>array('name_bact', 'string','blogarticle','%s'), 'sumary_bact'=>array('sumary_bact', 'string','blogarticle','%s'), 'sumary_html_bact'=>array('sumary_html_bact', 'string','blogarticle','%s'), 'content_bact'=>array('content_bact', 'string','blogarticle','%s'), 'content_html_bact'=>array('content_html_bact', 'string','blogarticle','%s'), 'format_bact'=>array('format_bact', 'string','blogarticle','%s'), 'author_bact'=>array('author_bact', 'string','blogarticle','%s'), 'date_bact'=>array('date_bact', 'string','blogarticle','%s'), 'time_bact'=>array('time_bact', 'string','blogarticle','%s'), 'url_bact'=>array('url_bact', 'string','blogarticle','%s'), 'sticky_bact'=>array('sticky_bact', 'int','blogarticle','%s'), 'is_online'=>array('is_online', 'int','blogarticle','%s')), $ct);
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
         array('id_bact'=>array('id_bact', 'autoincrement','blogarticle','%s'), 'id_blog'=>array('id_blog', 'int','blogarticle','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'name_bact'=>array('name_bact', 'string','blogarticle','%s'), 'sumary_bact'=>array('sumary_bact', 'string','blogarticle','%s'), 'sumary_html_bact'=>array('sumary_html_bact', 'string','blogarticle','%s'), 'content_bact'=>array('content_bact', 'string','blogarticle','%s'), 'content_html_bact'=>array('content_html_bact', 'string','blogarticle','%s'), 'format_bact'=>array('format_bact', 'string','blogarticle','%s'), 'author_bact'=>array('author_bact', 'string','blogarticle','%s'), 'date_bact'=>array('date_bact', 'string','blogarticle','%s'), 'time_bact'=>array('time_bact', 'string','blogarticle','%s'), 'url_bact'=>array('url_bact', 'string','blogarticle','%s'), 'sticky_bact'=>array('sticky_bact', 'int','blogarticle','%s'), 'is_online'=>array('is_online', 'int','blogarticle','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticle');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
if (($object->id_bact !== null) && $pUseId){    $query = 'INSERT INTO module_blog_article (id_bact,id_blog,name_blog,url_blog,name_bact,sumary_bact,sumary_html_bact,content_bact,content_html_bact,format_bact,author_bact,date_bact,time_bact,url_bact,sticky_bact,is_online) VALUES (:id_bact, :id_blog, :name_blog, :url_blog, :name_bact, :sumary_bact, :sumary_html_bact, :content_bact, :content_html_bact, :format_bact, :author_bact, :date_bact, :time_bact, :url_bact, :sticky_bact, :is_online)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_bact'=>$object->id_bact, ':id_blog'=>$object->id_blog, ':name_blog'=>$object->name_blog, ':url_blog'=>$object->url_blog, ':name_bact'=>$object->name_bact, ':sumary_bact'=>$object->sumary_bact, ':sumary_html_bact'=>$object->sumary_html_bact, ':content_bact'=>$object->content_bact, ':content_html_bact'=>$object->content_html_bact, ':format_bact'=>$object->format_bact, ':author_bact'=>$object->author_bact, ':date_bact'=>$object->date_bact, ':time_bact'=>$object->time_bact, ':url_bact'=>$object->url_bact, ':sticky_bact'=>$object->sticky_bact, ':is_online'=>$object->is_online)));
}else{    $query = 'INSERT INTO module_blog_article (id_blog,name_bact,sumary_bact,sumary_html_bact,content_bact,content_html_bact,format_bact,author_bact,date_bact,time_bact,url_bact,sticky_bact,is_online) VALUES (:id_blog, :name_bact, :sumary_bact, :sumary_html_bact, :content_bact, :content_html_bact, :format_bact, :author_bact, :date_bact, :time_bact, :url_bact, :sticky_bact, :is_online)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_blog'=>$object->id_blog, ':name_bact'=>$object->name_bact, ':sumary_bact'=>$object->sumary_bact, ':sumary_html_bact'=>$object->sumary_html_bact, ':content_bact'=>$object->content_bact, ':content_html_bact'=>$object->content_html_bact, ':format_bact'=>$object->format_bact, ':author_bact'=>$object->author_bact, ':date_bact'=>$object->date_bact, ':time_bact'=>$object->time_bact, ':url_bact'=>$object->url_bact, ':sticky_bact'=>$object->sticky_bact, ':is_online'=>$object->is_online)));
}if (! $pUseId){$object->id_bact= $ct->lastId();}
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('blog|blogarticle');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE module_blog_article SET  id_blog= :id_blog, name_bact= :name_bact, sumary_bact= :sumary_bact, sumary_html_bact= :sumary_html_bact, content_bact= :content_bact, content_html_bact= :content_html_bact, format_bact= :format_bact, author_bact= :author_bact, date_bact= :date_bact, time_bact= :time_bact, url_bact= :url_bact, sticky_bact= :sticky_bact, is_online= :is_online where id_bact '.($object->id_bact === null ? 'IS' : '=').'  '.($object->id_bact === null ? 'NULL' : ':blogarticle_id_bact').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':id_blog'=>$object->id_blog, ':name_bact'=>$object->name_bact, ':sumary_bact'=>$object->sumary_bact, ':sumary_html_bact'=>$object->sumary_html_bact, ':content_bact'=>$object->content_bact, ':content_html_bact'=>$object->content_html_bact, ':format_bact'=>$object->format_bact, ':author_bact'=>$object->author_bact, ':date_bact'=>$object->date_bact, ':time_bact'=>$object->time_bact, ':url_bact'=>$object->url_bact, ':sticky_bact'=>$object->sticky_bact, ':is_online'=>$object->is_online, ':blogarticle_id_bact'=>($object->id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->id_bact))));
   return $affectedRows;
 }
 public function _compiled_delete ($id_bact){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_article where id_bact '.($id_bact === null ? 'IS' : '=').'  '.($id_bact === null ? 'NULL' : ':blogarticle_id_bact').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':blogarticle_id_bact'=>($id_bact === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $id_bact))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM module_blog_article';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bact'=>array('id_bact', 'autoincrement','blogarticle','%s'), 'id_blog'=>array('id_blog', 'int','blogarticle','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'name_bact'=>array('name_bact', 'string','blogarticle','%s'), 'sumary_bact'=>array('sumary_bact', 'string','blogarticle','%s'), 'sumary_html_bact'=>array('sumary_html_bact', 'string','blogarticle','%s'), 'content_bact'=>array('content_bact', 'string','blogarticle','%s'), 'content_html_bact'=>array('content_html_bact', 'string','blogarticle','%s'), 'format_bact'=>array('format_bact', 'string','blogarticle','%s'), 'author_bact'=>array('author_bact', 'string','blogarticle','%s'), 'date_bact'=>array('date_bact', 'string','blogarticle','%s'), 'time_bact'=>array('time_bact', 'string','blogarticle','%s'), 'url_bact'=>array('url_bact', 'string','blogarticle','%s'), 'sticky_bact'=>array('sticky_bact', 'int','blogarticle','%s'), 'is_online'=>array('is_online', 'int','blogarticle','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM module_blog_article blogarticle LEFT JOIN module_blog blog ON (blogarticle.id_blog=blog.id_blog)';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('id_bact'=>array('id_bact', 'autoincrement','blogarticle','%s'), 'id_blog'=>array('id_blog', 'int','blogarticle','%s'), 'name_blog'=>array('name_blog', 'string','blog','%s'), 'url_blog'=>array('url_blog', 'string','blog','%s'), 'name_bact'=>array('name_bact', 'string','blogarticle','%s'), 'sumary_bact'=>array('sumary_bact', 'string','blogarticle','%s'), 'sumary_html_bact'=>array('sumary_html_bact', 'string','blogarticle','%s'), 'content_bact'=>array('content_bact', 'string','blogarticle','%s'), 'content_html_bact'=>array('content_html_bact', 'string','blogarticle','%s'), 'format_bact'=>array('format_bact', 'string','blogarticle','%s'), 'author_bact'=>array('author_bact', 'string','blogarticle','%s'), 'date_bact'=>array('date_bact', 'string','blogarticle','%s'), 'time_bact'=>array('time_bact', 'string','blogarticle','%s'), 'url_bact'=>array('url_bact', 'string','blogarticle','%s'), 'sticky_bact'=>array('sticky_bact', 'int','blogarticle','%s'), 'is_online'=>array('is_online', 'int','blogarticle','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function findByCritere ($id_blog, $word){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE blogarticle.id_blog'.($id_blog===null ? ' IS ' : ' = ').''.($id_blog === null ? 'NULL' : intval($id_blog)).' AND blogarticle.is_online'.('1'===null ? ' IS ' : ' = ').''.('1' === null ? 'NULL' : intval('1')).' AND ( blogarticle.name_bact  like  '. $ct->quote ($word).' OR blogarticle.sumary_bact  like  '. $ct->quote ($word).' OR blogarticle.content_bact  like  '. $ct->quote ($word).') ORDER BY blogarticle.date_bact desc, blogarticle.time_bact desc, blogarticle.id_bact desc';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
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
     'type' => 'autoincrement',
     'table' => 'blogarticle',
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
     'isFK' => true,
     'type' => 'int',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'blog',
     'fkFieldName' => 'id_blog',
     'sequenceName' => '',
     'maxlength' => '20',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'name_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'name_blog',
     'fieldName' => 'name_blog',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blog.fields.name_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'url_blog' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'url_blog',
     'fieldName' => 'url_blog',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blog.fields.url_blog',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blog',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'name_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'name_bact',
     'fieldName' => 'name_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.name_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'sumary_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'sumary_bact',
     'fieldName' => 'sumary_bact',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blogarticle.fields.sumary_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'sumary_html_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'sumary_html_bact',
     'fieldName' => 'sumary_html_bact',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blogarticle.fields.sumary_html_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'content_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'content_bact',
     'fieldName' => 'content_bact',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blogarticle.fields.content_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'content_html_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'content_html_bact',
     'fieldName' => 'content_html_bact',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blogarticle.fields.content_html_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'format_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'format_bact',
     'fieldName' => 'format_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.format_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'author_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'author_bact',
     'fieldName' => 'author_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.author_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '50',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'date_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'date_bact',
     'fieldName' => 'date_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.date_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '8',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'time_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'time_bact',
     'fieldName' => 'time_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.time_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '5',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'url_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'url_bact',
     'fieldName' => 'url_bact',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => 'blog|dao.blogarticle.fields.url_bacg',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '100',
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'sticky_bact' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'sticky_bact',
     'fieldName' => 'sticky_bact',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.sticky_bact',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '1',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'is_online' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'is_online',
     'fieldName' => 'is_online',
     'regExp' => NULL,
     'required' => true,
     'captionI18N' => 'blog|dao.blogarticle.fields.is_online',
     'caption' => NULL,
     'isPK' => false,
     'isFK' => false,
     'type' => 'int',
     'table' => 'blogarticle',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => '1',
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'blog|blogarticle';return $daoId;
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