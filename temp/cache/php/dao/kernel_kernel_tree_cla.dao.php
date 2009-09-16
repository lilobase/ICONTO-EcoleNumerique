<?php 
class CompiledDAORecordkernel_tree_cla implements ICopixDAORecord{
 var $cla_id = null;
 var $cla_ecole = null;
 var $cla_nom = null;
 var $cla_annee_scol = null;
 var $cla_is_validee = null;
 var $cla_is_supprimee = null;
 var $eco_numero = null;
 var $eco_type = null;
 var $eco_nom = null;
 var $eco_id_ville = null;
 var $annee_id_as = null;
 var $annee_current = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->cla_id= $dbRecord['cla_id'];
}else{
 $this->cla_id= $dbRecord->cla_id;
} if (is_array ($dbRecord)){
 $this->cla_ecole= $dbRecord['cla_ecole'];
}else{
 $this->cla_ecole= $dbRecord->cla_ecole;
} if (is_array ($dbRecord)){
 $this->cla_nom= $dbRecord['cla_nom'];
}else{
 $this->cla_nom= $dbRecord->cla_nom;
} if (is_array ($dbRecord)){
 $this->cla_annee_scol= $dbRecord['cla_annee_scol'];
}else{
 $this->cla_annee_scol= $dbRecord->cla_annee_scol;
} if (is_array ($dbRecord)){
 $this->cla_is_validee= $dbRecord['cla_is_validee'];
}else{
 $this->cla_is_validee= $dbRecord->cla_is_validee;
} if (is_array ($dbRecord)){
 $this->cla_is_supprimee= $dbRecord['cla_is_supprimee'];
}else{
 $this->cla_is_supprimee= $dbRecord->cla_is_supprimee;
} if (is_array ($dbRecord)){
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
 $this->eco_id_ville= $dbRecord['eco_id_ville'];
}else{
 $this->eco_id_ville= $dbRecord->eco_id_ville;
} if (is_array ($dbRecord)){
 $this->annee_id_as= $dbRecord['annee_id_as'];
}else{
 $this->annee_id_as= $dbRecord->annee_id_as;
} if (is_array ($dbRecord)){
 $this->annee_current= $dbRecord['annee_current'];
}else{
 $this->annee_current= $dbRecord->annee_current;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_tree_cla';return $daoId;
}}

class CompiledDAOkernel_tree_cla implements ICopixDAO  { 
   var $_table='kernel_bu_ecole_classe';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_bu_ecole_classe.id cla_id, kernel_bu_ecole_classe.ecole cla_ecole, kernel_bu_ecole_classe.nom cla_nom, kernel_bu_ecole_classe.annee_scol cla_annee_scol, kernel_bu_ecole_classe.is_validee cla_is_validee, kernel_bu_ecole_classe.is_supprimee cla_is_supprimee, kernel_bu_ecole.numero eco_numero, kernel_bu_ecole.type eco_type, kernel_bu_ecole.nom eco_nom, kernel_bu_ecole.id_ville eco_id_ville, kernel_bu_annee_scolaire.id_as annee_id_as, kernel_bu_annee_scolaire.current annee_current FROM kernel_bu_ecole_classe JOIN kernel_bu_annee_scolaire JOIN kernel_bu_ecole WHERE  kernel_bu_ecole_classe.annee_scol=kernel_bu_annee_scolaire.id_as AND kernel_bu_ecole_classe.ecole=kernel_bu_ecole.numero';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->cla_id) > 0){
   if (! is_numeric ($pRecord->cla_id)){
      $errorObject->addError ('cla_id', _i18n ('copix:dao.errors.numeric','cla_id'));
  }
  }
  if (strlen ($pRecord->cla_ecole) > 0){
   if (! is_numeric ($pRecord->cla_ecole)){
      $errorObject->addError ('cla_ecole', _i18n ('copix:dao.errors.numeric','cla_ecole'));
  }
  }
  if (strlen ($pRecord->cla_annee_scol) > 0){
   if (! is_numeric ($pRecord->cla_annee_scol)){
      $errorObject->addError ('cla_annee_scol', _i18n ('copix:dao.errors.numeric','cla_annee_scol'));
  }
  }
  if (strlen ($pRecord->cla_is_validee) > 0){
   if (! is_numeric ($pRecord->cla_is_validee)){
      $errorObject->addError ('cla_is_validee', _i18n ('copix:dao.errors.numeric','cla_is_validee'));
  }
  }
  if (strlen ($pRecord->cla_is_supprimee) > 0){
   if (! is_numeric ($pRecord->cla_is_supprimee)){
      $errorObject->addError ('cla_is_supprimee', _i18n ('copix:dao.errors.numeric','cla_is_supprimee'));
  }
  }
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
  if (strlen ($pRecord->annee_id_as) > 0){
   if (! is_numeric ($pRecord->annee_id_as)){
      $errorObject->addError ('annee_id_as', _i18n ('copix:dao.errors.numeric','annee_id_as'));
  }
  }
  if (strlen ($pRecord->annee_current) > 0){
   if (! is_numeric ($pRecord->annee_current)){
      $errorObject->addError ('annee_current', _i18n ('copix:dao.errors.numeric','annee_current'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($cla_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' AND kernel_bu_ecole_classe.id '.($cla_id === null ? 'IS' : '=').'  '.($cla_id === null ? 'NULL' : ':kernel_bu_ecole_classe_cla_id').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ecole_classe_cla_id'=>($cla_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $cla_id)))), $this->getDAOId ());
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
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('cla_id'=>array('id', 'integer','kernel_bu_ecole_classe','%s'), 'cla_ecole'=>array('ecole', 'integer','kernel_bu_ecole_classe','%s'), 'cla_nom'=>array('nom', 'string','kernel_bu_ecole_classe','%s'), 'cla_annee_scol'=>array('annee_scol', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_validee'=>array('is_validee', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_supprimee'=>array('is_supprimee', 'integer','kernel_bu_ecole_classe','%s'), 'eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'annee_id_as'=>array('id_as', 'integer','kernel_bu_annee_scolaire','%s'), 'annee_current'=>array('current', 'integer','kernel_bu_annee_scolaire','%s')), $ct);
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
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('cla_id'=>array('id', 'integer','kernel_bu_ecole_classe','%s'), 'cla_ecole'=>array('ecole', 'integer','kernel_bu_ecole_classe','%s'), 'cla_nom'=>array('nom', 'string','kernel_bu_ecole_classe','%s'), 'cla_annee_scol'=>array('annee_scol', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_validee'=>array('is_validee', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_supprimee'=>array('is_supprimee', 'integer','kernel_bu_ecole_classe','%s'), 'eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'annee_id_as'=>array('id_as', 'integer','kernel_bu_annee_scolaire','%s'), 'annee_current'=>array('current', 'integer','kernel_bu_annee_scolaire','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_cla');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_bu_ecole_classe (id,ecole,nom,annee_scol,is_validee,is_supprimee,numero,type,nom,id_ville,id_as,current) VALUES (:cla_id, :cla_ecole, :cla_nom, :cla_annee_scol, :cla_is_validee, :cla_is_supprimee, :eco_numero, :eco_type, :eco_nom, :eco_id_ville, :annee_id_as, :annee_current)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':cla_id'=>$object->cla_id, ':cla_ecole'=>$object->cla_ecole, ':cla_nom'=>$object->cla_nom, ':cla_annee_scol'=>$object->cla_annee_scol, ':cla_is_validee'=>$object->cla_is_validee, ':cla_is_supprimee'=>$object->cla_is_supprimee, ':eco_numero'=>$object->eco_numero, ':eco_type'=>$object->eco_type, ':eco_nom'=>$object->eco_nom, ':eco_id_ville'=>$object->eco_id_ville, ':annee_id_as'=>$object->annee_id_as, ':annee_current'=>$object->annee_current)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_tree_cla');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_bu_ecole_classe SET  ecole= :cla_ecole, nom= :cla_nom, annee_scol= :cla_annee_scol, is_validee= :cla_is_validee, is_supprimee= :cla_is_supprimee where id '.($object->cla_id === null ? 'IS' : '=').'  '.($object->cla_id === null ? 'NULL' : ':kernel_bu_ecole_classe_cla_id').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':cla_ecole'=>$object->cla_ecole, ':cla_nom'=>$object->cla_nom, ':cla_annee_scol'=>$object->cla_annee_scol, ':cla_is_validee'=>$object->cla_is_validee, ':cla_is_supprimee'=>$object->cla_is_supprimee, ':kernel_bu_ecole_classe_cla_id'=>($object->cla_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->cla_id))));
   return $affectedRows;
 }
 public function delete ($cla_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ecole_classe where id '.($cla_id === null ? 'IS' : '=').'  '.($cla_id === null ? 'NULL' : ':kernel_bu_ecole_classe_cla_id').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_ecole_classe_cla_id'=>($cla_id === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $cla_id))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_ecole_classe';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('cla_id'=>array('id', 'integer','kernel_bu_ecole_classe','%s'), 'cla_ecole'=>array('ecole', 'integer','kernel_bu_ecole_classe','%s'), 'cla_nom'=>array('nom', 'string','kernel_bu_ecole_classe','%s'), 'cla_annee_scol'=>array('annee_scol', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_validee'=>array('is_validee', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_supprimee'=>array('is_supprimee', 'integer','kernel_bu_ecole_classe','%s'), 'eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'annee_id_as'=>array('id_as', 'integer','kernel_bu_annee_scolaire','%s'), 'annee_current'=>array('current', 'integer','kernel_bu_annee_scolaire','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_bu_ecole_classe JOIN kernel_bu_annee_scolaire JOIN kernel_bu_ecole WHERE  kernel_bu_ecole_classe.annee_scol=kernel_bu_annee_scolaire.id_as AND kernel_bu_ecole_classe.ecole=kernel_bu_ecole.numero';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('cla_id'=>array('id', 'integer','kernel_bu_ecole_classe','%s'), 'cla_ecole'=>array('ecole', 'integer','kernel_bu_ecole_classe','%s'), 'cla_nom'=>array('nom', 'string','kernel_bu_ecole_classe','%s'), 'cla_annee_scol'=>array('annee_scol', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_validee'=>array('is_validee', 'integer','kernel_bu_ecole_classe','%s'), 'cla_is_supprimee'=>array('is_supprimee', 'integer','kernel_bu_ecole_classe','%s'), 'eco_numero'=>array('numero', 'integer','kernel_bu_ecole','%s'), 'eco_type'=>array('type', 'string','kernel_bu_ecole','%s'), 'eco_nom'=>array('nom', 'string','kernel_bu_ecole','%s'), 'eco_id_ville'=>array('id_ville', 'integer','kernel_bu_ecole','%s'), 'annee_id_as'=>array('id_as', 'integer','kernel_bu_annee_scolaire','%s'), 'annee_current'=>array('current', 'integer','kernel_bu_annee_scolaire','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByEcole ($eco){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_bu_ecole_classe.ecole'.($eco===null ? ' IS ' : ' = ').''.($eco === null ? 'NULL' : intval($eco)).' AND kernel_bu_ecole_classe.is_validee'.('1'===null ? ' IS ' : ' = ').''.('1' === null ? 'NULL' : intval('1')).' AND kernel_bu_ecole_classe.is_supprimee'.('0'===null ? ' IS ' : ' = ').''.('0' === null ? 'NULL' : intval('0')).' AND kernel_bu_annee_scolaire.current'.('1'===null ? ' IS ' : ' = ').''.('1' === null ? 'NULL' : intval('1')).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'cla_id' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_id',
     'fieldName' => 'id',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_id',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'cla_ecole' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_ecole',
     'fieldName' => 'ecole',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_ecole',
     'isPK' => false,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'kernel_bu_ecole',
     'fkFieldName' => 'numero',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'cla_nom' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_nom',
     'fieldName' => 'nom',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_nom',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'cla_annee_scol' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_annee_scol',
     'fieldName' => 'annee_scol',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_annee_scol',
     'isPK' => false,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'kernel_bu_annee_scolaire',
     'fkFieldName' => 'id_as',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'cla_is_validee' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_is_validee',
     'fieldName' => 'is_validee',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_is_validee',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'cla_is_supprimee' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'cla_is_supprimee',
     'fieldName' => 'is_supprimee',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'cla_is_supprimee',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole_classe',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
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
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
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
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_ecole',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
  'annee_id_as' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'annee_id_as',
     'fieldName' => 'id_as',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'annee_id_as',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_annee_scolaire',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
  'annee_current' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'annee_current',
     'fieldName' => 'current',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'annee_current',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_annee_scolaire',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
);return $fields;
}public function getDAOId () {
$daoId = 'kernel|kernel_tree_cla';return $daoId;
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