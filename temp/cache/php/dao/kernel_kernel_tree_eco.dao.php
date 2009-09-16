<?php 
class CompiledDAORecordkernel_tree_eco implements ICopixDAORecord{
 var $eco_numero = null;
 var $eco_type = null;
 var $eco_nom = null;
 var $eco_num_rue = null;
 var $eco_num_seq = null;
 var $eco_adresse1 = null;
 var $eco_adresse2 = null;
 var $eco_code_postal = null;
 var $eco_commune = null;
 var $eco_tel = null;
 var $eco_web = null;
 var $eco_mail = null;
 var $eco_id_ville = null;
 var $vil_id_vi = null;
 var $vil_nom = null;
 var $vil_canon = null;
 var $vil_id_grville = null;
 var $vil_date_creation = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->eco_numero= $dbRecord['eco_numero'];
}else{
 $this->eco_numero= $dbRecord->eco_numero;
} if (is_array ($dbRecord)){
 $this->eco_type= $dbRecord['eco_type'];
}else{
 $this->eco_type= $dbRecord->eco_type;
} if (is_array ($dbRecord)){
 $this->eco_nom= $dbRecord['eco_nom'];
}else{
 $this->eco_nom= $dbRecord->eco_nom;
} if (is_array ($dbRecord)){
 $this->eco_num_rue= $dbRecord['eco_num_rue'];
}else{
 $this->eco_num_rue= $dbRecord->eco_num_rue;
} if (is_array ($dbRecord)){
 $this->eco_num_seq= $dbRecord['eco_num_seq'];
}else{
 $this->eco_num_seq= $dbRecord->eco_num_seq;
} if (is_array ($dbRecord)){
 $this->eco_adresse1= $dbRecord['eco_adresse1'];
}else{
 $this->eco_adresse1= $dbRecord->eco_adresse1;
} if (is_array ($dbRecord)){
 $this->eco_adresse2= $dbRecord['eco_adresse2'];
}else{
 $this->eco_adresse2= $dbRecord->eco_adresse2;
} if (is_array ($dbRecord)){
 $this->eco_code_postal= $dbRecord['eco_code_postal'];
}else{
 $this->eco_code_postal= $dbRecord->eco_code_postal;
} if (is_array ($dbRecord)){
 $this->eco_commune= $dbRecord['eco_commune'];
}else{
 $this->eco_commune= $dbRecord->eco_commune;
} if (is_array ($dbRecord)){
 $this->eco_tel= $dbRecord['eco_tel'];
}else{
 $this->eco_tel= $dbRecord->eco_tel;
} if (is_array ($dbRecord)){
 $this->eco_web= $dbRecord['eco_web'];
}else{
 $this->eco_web= $dbRecord->eco_web;
} if (is_array ($dbRecord)){
 $this->eco_mail= $dbRecord['eco_mail'];
}else{
 $this->eco_mail= $dbRecord->eco_mail;
} if (is_array ($dbRecord)){
 $this->eco_id_ville= $dbRecord['eco_id_ville'];
}else{
 $this->eco_id_ville= $dbRecord->eco_id_ville;
} if (is_array ($dbRecord)){
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
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_tree_eco';return $daoId;
}}

class CompiledDAOkernel_tree_eco implements ICopixDAO  { 
   var $_table='kernel_bu_ecole';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_bu_ecole.numero eco_numero, kernel_bu_ecole.type eco_type, kernel_bu_ecole.nom eco_nom, kernel_bu_ecole.num_rue eco_num_rue, kernel_bu_ecole.num_seq eco_num_seq, kernel_bu_ecole.adresse1 eco_adresse1, kernel_bu_ecole.adresse2 eco_adresse2, kernel_bu_ecole.code_postal eco_code_postal, kernel_bu_ecole.commune eco_commune, kernel_bu_ecole.tel eco_tel, kernel_bu_ecole.web eco_web, kernel_bu_ecole.mail eco_mail, kernel_bu_ecole.id_ville eco_id_ville, kernel_bu_ville.id_vi vil_id_vi, kernel_bu_ville.nom vil_nom, kernel_bu_ville.canon vil_canon, kernel_bu_ville.id_grville vil_id_grville, DATE_FORMAT(kernel_bu_ville.date_creation, \'%Y%m%d\') vil_date_creation FROM kernel_bu_ecole LEFT JOIN kernel_bu_ville ON (kernel_bu_ecole.id_ville=kernel_bu_ville.id_vi)';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->eco_numero) > 0){
   if (! is_numeric ($pRecord->eco_numero)){
      $errorObject->addError ('eco_numero', _i18n ('copix:dao.errors.numeric','eco_numero'));
  }
  }
  if (strlen ($pRecord->eco_id_ville) > 0){
   if (! is_numeric ($pRecord->eco_id_ville)){
      $errorObject->addError ('eco_id_ville', _i18n ('copix:dao.errors.numeric','eco_id_ville'));
  }
  }
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
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($eco_numero){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' WHERE kernel_bu_ecole.numero '.($eco_numero === null ? 'IS' : '=').'  '.($eco_numero === null ? 'NULL' : ':kernel_bu_ecole_eco_numero').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ecole_eco_numero'=>($eco_numero === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $eco_numero)))), $this->getDAOId ());
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
         array('eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_num_rue'=>array('num_rue', 'string','kernel_bu_ecole','%s'), 'eco_num_seq'=>array('num_seq', 'string','kernel_bu_ecole','%s'), 'eco_adresse1'=>array('adresse1', 'string','kernel_bu_ecole','%s'), 'eco_adresse2'=>array('adresse2', 'string','kernel_bu_ecole','%s'), 'eco_code_postal'=>array('code_postal', 'string','kernel_bu_ecole','%s'), 'eco_commune'=>array('commune', 'string','kernel_bu_ecole','%s'), 'eco_tel'=>array('tel', 'string','kernel_bu_ecole','%s'), 'eco_web'=>array('web', 'string','kernel_bu_ecole','%s'), 'eco_mail'=>array('mail', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s')), $ct);
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
         array('eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_num_rue'=>array('num_rue', 'string','kernel_bu_ecole','%s'), 'eco_num_seq'=>array('num_seq', 'string','kernel_bu_ecole','%s'), 'eco_adresse1'=>array('adresse1', 'string','kernel_bu_ecole','%s'), 'eco_adresse2'=>array('adresse2', 'string','kernel_bu_ecole','%s'), 'eco_code_postal'=>array('code_postal', 'string','kernel_bu_ecole','%s'), 'eco_commune'=>array('commune', 'string','kernel_bu_ecole','%s'), 'eco_tel'=>array('tel', 'string','kernel_bu_ecole','%s'), 'eco_web'=>array('web', 'string','kernel_bu_ecole','%s'), 'eco_mail'=>array('mail', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_eco');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_bu_ecole (numero,type,nom,num_rue,num_seq,adresse1,adresse2,code_postal,commune,tel,web,mail,id_ville,id_vi,nom,canon,id_grville,date_creation) VALUES (:eco_numero, :eco_type, :eco_nom, :eco_num_rue, :eco_num_seq, :eco_adresse1, :eco_adresse2, :eco_code_postal, :eco_commune, :eco_tel, :eco_web, :eco_mail, :eco_id_ville, :vil_id_vi, :vil_nom, :vil_canon, :vil_id_grville, :vil_date_creation)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':eco_numero'=>$object->eco_numero, ':eco_type'=>$object->eco_type, ':eco_nom'=>$object->eco_nom, ':eco_num_rue'=>$object->eco_num_rue, ':eco_num_seq'=>$object->eco_num_seq, ':eco_adresse1'=>$object->eco_adresse1, ':eco_adresse2'=>$object->eco_adresse2, ':eco_code_postal'=>$object->eco_code_postal, ':eco_commune'=>$object->eco_commune, ':eco_tel'=>$object->eco_tel, ':eco_web'=>$object->eco_web, ':eco_mail'=>$object->eco_mail, ':eco_id_ville'=>$object->eco_id_ville, ':vil_id_vi'=>$object->vil_id_vi, ':vil_nom'=>$object->vil_nom, ':vil_canon'=>$object->vil_canon, ':vil_id_grville'=>$object->vil_id_grville, ':vil_date_creation'=>CopixDateTime::yyyymmddToFormat ($object->vil_date_creation, 'Y-m-d'))));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_eco');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_bu_ecole SET  type= :eco_type, nom= :eco_nom, num_rue= :eco_num_rue, num_seq= :eco_num_seq, adresse1= :eco_adresse1, adresse2= :eco_adresse2, code_postal= :eco_code_postal, commune= :eco_commune, tel= :eco_tel, web= :eco_web, mail= :eco_mail, id_ville= :eco_id_ville where numero '.($object->eco_numero === null ? 'IS' : '=').'  '.($object->eco_numero === null ? 'NULL' : ':kernel_bu_ecole_eco_numero').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':eco_type'=>$object->eco_type, ':eco_nom'=>$object->eco_nom, ':eco_num_rue'=>$object->eco_num_rue, ':eco_num_seq'=>$object->eco_num_seq, ':eco_adresse1'=>$object->eco_adresse1, ':eco_adresse2'=>$object->eco_adresse2, ':eco_code_postal'=>$object->eco_code_postal, ':eco_commune'=>$object->eco_commune, ':eco_tel'=>$object->eco_tel, ':eco_web'=>$object->eco_web, ':eco_mail'=>$object->eco_mail, ':eco_id_ville'=>$object->eco_id_ville, ':kernel_bu_ecole_eco_numero'=>($object->eco_numero === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->eco_numero))));
   return $affectedRows;
 }
 public function delete ($eco_numero){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ecole where numero '.($eco_numero === null ? 'IS' : '=').'  '.($eco_numero === null ? 'NULL' : ':kernel_bu_ecole_eco_numero').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ecole_eco_numero'=>($eco_numero === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $eco_numero))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ecole';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_num_rue'=>array('num_rue', 'string','kernel_bu_ecole','%s'), 'eco_num_seq'=>array('num_seq', 'string','kernel_bu_ecole','%s'), 'eco_adresse1'=>array('adresse1', 'string','kernel_bu_ecole','%s'), 'eco_adresse2'=>array('adresse2', 'string','kernel_bu_ecole','%s'), 'eco_code_postal'=>array('code_postal', 'string','kernel_bu_ecole','%s'), 'eco_commune'=>array('commune', 'string','kernel_bu_ecole','%s'), 'eco_tel'=>array('tel', 'string','kernel_bu_ecole','%s'), 'eco_web'=>array('web', 'string','kernel_bu_ecole','%s'), 'eco_mail'=>array('mail', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_bu_ecole LEFT JOIN kernel_bu_ville ON (kernel_bu_ecole.id_ville=kernel_bu_ville.id_vi)';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_num_rue'=>array('num_rue', 'string','kernel_bu_ecole','%s'), 'eco_num_seq'=>array('num_seq', 'string','kernel_bu_ecole','%s'), 'eco_adresse1'=>array('adresse1', 'string','kernel_bu_ecole','%s'), 'eco_adresse2'=>array('adresse2', 'string','kernel_bu_ecole','%s'), 'eco_code_postal'=>array('code_postal', 'string','kernel_bu_ecole','%s'), 'eco_commune'=>array('commune', 'string','kernel_bu_ecole','%s'), 'eco_tel'=>array('tel', 'string','kernel_bu_ecole','%s'), 'eco_web'=>array('web', 'string','kernel_bu_ecole','%s'), 'eco_mail'=>array('mail', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'vil_id_vi'=>array('id_vi', 'integer','kernel_bu_ville','%s'), 'vil_nom'=>array('nom', 'string','kernel_bu_ville','%s'), 'vil_canon'=>array('canon', 'string','kernel_bu_ville','%s'), 'vil_id_grville'=>array('id_grville', 'integer','kernel_bu_ville','%s'), 'vil_date_creation'=>array('date_creation', 'date','kernel_bu_ville','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByVille ($vil){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' WHERE kernel_bu_ecole.id_ville'.($vil===null ? ' IS ' : ' = ').''.($vil === null ? 'NULL' : intval($vil)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'eco_numero' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_numero',
     'fieldName' => 'numero',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_numero',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'eco_type' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_type',
     'fieldName' => 'type',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_type',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_nom' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_nom',
     'fieldName' => 'nom',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_nom',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_num_rue' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_num_rue',
     'fieldName' => 'num_rue',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_num_rue',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_num_seq' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_num_seq',
     'fieldName' => 'num_seq',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_num_seq',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_adresse1' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_adresse1',
     'fieldName' => 'adresse1',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_adresse1',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_adresse2' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_adresse2',
     'fieldName' => 'adresse2',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_adresse2',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_code_postal' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_code_postal',
     'fieldName' => 'code_postal',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_code_postal',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_commune' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_commune',
     'fieldName' => 'commune',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_commune',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_tel' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_tel',
     'fieldName' => 'tel',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_tel',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_web' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_web',
     'fieldName' => 'web',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_web',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_mail' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_mail',
     'fieldName' => 'mail',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_mail',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'eco_id_ville' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'eco_id_ville',
     'fieldName' => 'id_ville',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'eco_id_ville',
     'isPK' => false,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'kernel_bu_ville',
     'fkFieldName' => 'id_vi',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
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
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ville',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'kernel|kernel_tree_eco';return $daoId;
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