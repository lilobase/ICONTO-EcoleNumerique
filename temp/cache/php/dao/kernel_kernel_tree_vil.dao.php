<?php 
class CompiledDAORecordkernel_tree_vil implements ICopixDAORecord{
 var $vil_id_vi = null;
 var $vil_nom = null;
 var $vil_canon = null;
 var $vil_id_grville = null;
 var $vil_date_creation = null;
 var $grv_id_grv = null;
 var $grv_nom_groupe = null;
 var $grv_date_creation = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->vil_id_vi= $dbRecord['vil_id_vi'];
}else{
 $this->vil_id_vi= $dbRecord->vil_id_vi;
} if (is_array ($dbRecord)){
 $this->vil_nom= $dbRecord['vil_nom'];
}else{
 $this->vil_nom= $dbRecord->vil_nom;
} if (is_array ($dbRecord)){
 $this->vil_canon= $dbRecord['vil_canon'];
}else{
 $this->vil_canon= $dbRecord->vil_canon;
} if (is_array ($dbRecord)){
 $this->vil_id_grville= $dbRecord['vil_id_grville'];
}else{
 $this->vil_id_grville= $dbRecord->vil_id_grville;
} if (is_array ($dbRecord)){
 $this->vil_date_creation= $dbRecord['vil_date_creation'];
}else{
 $this->vil_date_creation= $dbRecord->vil_date_creation;
} if (is_array ($dbRecord)){
 $this->grv_id_grv= $dbRecord['grv_id_grv'];
}else{
 $this->grv_id_grv= $dbRecord->grv_id_grv;
} if (is_array ($dbRecord)){
 $this->grv_nom_groupe= $dbRecord['grv_nom_groupe'];
}else{
 $this->grv_nom_groupe= $dbRecord->grv_nom_groupe;
} if (is_array ($dbRecord)){
 $this->grv_date_creation= $dbRecord['grv_date_creation'];
}else{
 $this->grv_date_creation= $dbRecord->grv_date_creation;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_tree_vil';return $daoId;
}}

class CompiledDAOkernel_tree_vil implements ICopixDAO  { 
   var $_table='kernel_bu_ville';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_bu_ville.id_vi vil_id_vi, kernel_bu_ville.nom vil_nom, kernel_bu_ville.canon vil_canon, kernel_bu_ville.id_grville vil_id_grville, DATE_FORMAT(kernel_bu_ville.date_creation, \'%Y%m%d\') vil_date_creation, kernel_bu_groupe_villes.id_grv grv_id_grv, kernel_bu_groupe_villes.nom_groupe grv_nom_groupe, DATE_FORMAT(kernel_bu_groupe_villes.date_creation, \'%Y%m%d\') grv_date_creation FROM kernel_bu_ville LEFT JOIN kernel_bu_groupe_villes ON (kernel_bu_ville.id_grville=kernel_bu_groupe_villes.id_grv)';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->vil_id_vi) > 0){
   if (! is_numeric ($pRecord->vil_id_vi)){
      $errorObject->addError ('vil_id_vi', _i18n ('copix:dao.errors.numeric','vil_id_vi'));
  }
  }
  if (strlen ($pRecord->vil_id_grville) > 0){
   if (! is_numeric ($pRecord->vil_id_grville)){
      $errorObject->addError ('vil_id_grville', _i18n ('copix:dao.errors.numeric','vil_id_grville'));
  }
  }
     if (CopixDateTime::yyyymmddToDate ($pRecord->vil_date_creation) === false){
        $errorObject->addError ('vil_date_creation', _i18n ('copix:dao.errors.date','vil_date_creation'));
  }
  if (strlen ($pRecord->grv_id_grv) > 0){
   if (! is_numeric ($pRecord->grv_id_grv)){
      $errorObject->addError ('grv_id_grv', _i18n ('copix:dao.errors.numeric','grv_id_grv'));
  }
  }
     if (CopixDateTime::yyyymmddToDate ($pRecord->grv_date_creation) === false){
        $errorObject->addError ('grv_date_creation', _i18n ('copix:dao.errors.date','grv_date_creation'));
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($vil_id_vi){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE kernel_bu_ville.id_vi '.($vil_id_vi === null ? 'IS' : '=').'  '.($vil_id_vi === null ? 'NULL' : ':kernel_bu_ville_vil_id_vi').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ville_vil_id_vi'=>($vil_id_vi === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $vil_id_vi)))), $this->getDAOId ());
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
         array('vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s'), 'grv_id_grv'=>array('id_grv', 'integer','kernel_bu_groupe_villes','%s'), 'grv_nom_groupe'=>array('nom_groupe', 'string','kernel_bu_groupe_villes','%s'), 'grv_date_creation'=>array('date_creation', 'date','kernel_bu_groupe_villes','%s')), $ct);
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
         array('vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s'), 'grv_id_grv'=>array('id_grv', 'integer','kernel_bu_groupe_villes','%s'), 'grv_nom_groupe'=>array('nom_groupe', 'string','kernel_bu_groupe_villes','%s'), 'grv_date_creation'=>array('date_creation', 'date','kernel_bu_groupe_villes','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_vil');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_bu_ville (id_vi,nom,canon,id_grville,date_creation,id_grv,nom_groupe,date_creation) VALUES (:vil_id_vi, :vil_nom, :vil_canon, :vil_id_grville, :vil_date_creation, :grv_id_grv, :grv_nom_groupe, :grv_date_creation)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':vil_id_vi'=>$object->vil_id_vi, ':vil_nom'=>$object->vil_nom, ':vil_canon'=>$object->vil_canon, ':vil_id_grville'=>$object->vil_id_grville, ':vil_date_creation'=>CopixDateTime::yyyymmddToFormat ($object->vil_date_creation, 'Y-m-d'), ':grv_id_grv'=>$object->grv_id_grv, ':grv_nom_groupe'=>$object->grv_nom_groupe, ':grv_date_creation'=>CopixDateTime::yyyymmddToFormat ($object->grv_date_creation, 'Y-m-d'))));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_vil');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_bu_ville SET  nom= :vil_nom, canon= :vil_canon, id_grville= :vil_id_grville, date_creation= :vil_date_creation where id_vi '.($object->vil_id_vi === null ? 'IS' : '=').'  '.($object->vil_id_vi === null ? 'NULL' : ':kernel_bu_ville_vil_id_vi').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':vil_nom'=>$object->vil_nom, ':vil_canon'=>$object->vil_canon, ':vil_id_grville'=>$object->vil_id_grville, ':vil_date_creation'=>CopixDateTime::yyyymmddToFormat ($object->vil_date_creation, 'Y-m-d'), ':kernel_bu_ville_vil_id_vi'=>($object->vil_id_vi === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->vil_id_vi))));
   return $affectedRows;
 }
 public function delete ($vil_id_vi){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ville where id_vi '.($vil_id_vi === null ? 'IS' : '=').'  '.($vil_id_vi === null ? 'NULL' : ':kernel_bu_ville_vil_id_vi').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ville_vil_id_vi'=>($vil_id_vi === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $vil_id_vi))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ville';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s'), 'grv_id_grv'=>array('id_grv', 'integer','kernel_bu_groupe_villes','%s'), 'grv_nom_groupe'=>array('nom_groupe', 'string','kernel_bu_groupe_villes','%s'), 'grv_date_creation'=>array('date_creation', 'date','kernel_bu_groupe_villes','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_bu_ville LEFT JOIN kernel_bu_groupe_villes ON (kernel_bu_ville.id_grville=kernel_bu_groupe_villes.id_grv)';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s'), 'grv_id_grv'=>array('id_grv', 'integer','kernel_bu_groupe_villes','%s'), 'grv_nom_groupe'=>array('nom_groupe', 'string','kernel_bu_groupe_villes','%s'), 'grv_date_creation'=>array('date_creation', 'date','kernel_bu_groupe_villes','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByGroupeVille ($grv){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_bu_ville.id_grville'.($grv===null ? ' IS ' : ' = ').''.($grv === null ? 'NULL' : intval($grv)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'vil_id_vi' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'vil_id_vi',
     'fieldName' => 'id_vi',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'vil_id_vi',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'vil_nom' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'vil_nom',
     'fieldName' => 'nom',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'vil_nom',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'vil_canon' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'vil_canon',
     'fieldName' => 'canon',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'vil_canon',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'vil_id_grville' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'vil_id_grville',
     'fieldName' => 'id_grville',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'vil_id_grville',
     'isPK' => false,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'kernel_bu_groupe_villes',
     'fkFieldName' => 'id_grv',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'vil_date_creation' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'vil_date_creation',
     'fieldName' => 'date_creation',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'vil_date_creation',
     'isPK' => false,
     'isFK' => false,
     'type' => 'date',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'grv_id_grv' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'grv_id_grv',
     'fieldName' => 'id_grv',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'grv_id_grv',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_groupe_villes',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
  'grv_nom_groupe' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'grv_nom_groupe',
     'fieldName' => 'nom_groupe',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'grv_nom_groupe',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_groupe_villes',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'grv_date_creation' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'grv_date_creation',
     'fieldName' => 'date_creation',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'grv_date_creation',
     'isPK' => false,
     'isFK' => false,
     'type' => 'date',
     'table' => 'kernel_bu_groupe_villes',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'kernel|kernel_tree_vil';return $daoId;
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