<?php 
class CompiledDAORecordkernel_bu_personnel_entite implements ICopixDAORecord{
 var $pers_entite_id_per = null;
 var $pers_entite_reference = null;
 var $pers_entite_type_ref = null;
 var $pers_entite_role = null;
 var $pers_numero = null;
 var $pers_nom = null;
 var $pers_nom_jf = null;
 var $pers_prenom1 = null;
 var $pers_civilite = null;
 var $pers_id_sexe = null;
 var $pers_cle_privee = null;
 var $pers_profession = null;
 var $pers_tel_dom = null;
 var $pers_tel_gsm = null;
 var $pers_tel_pro = null;
 var $pers_mel = null;
 var $pers_num_rue = null;
 var $pers_num_seq = null;
 var $pers_adresse1 = null;
 var $pers_adresse2 = null;
 var $pers_code_postal = null;
 var $pers_commune = null;
 public function initFromDBObject ($dbRecord){
 if (is_array ($dbRecord)){
 $this->pers_entite_id_per= $dbRecord['pers_entite_id_per'];
}else{
 $this->pers_entite_id_per= $dbRecord->pers_entite_id_per;
} if (is_array ($dbRecord)){
 $this->pers_entite_reference= $dbRecord['pers_entite_reference'];
}else{
 $this->pers_entite_reference= $dbRecord->pers_entite_reference;
} if (is_array ($dbRecord)){
 $this->pers_entite_type_ref= $dbRecord['pers_entite_type_ref'];
}else{
 $this->pers_entite_type_ref= $dbRecord->pers_entite_type_ref;
} if (is_array ($dbRecord)){
 $this->pers_entite_role= $dbRecord['pers_entite_role'];
}else{
 $this->pers_entite_role= $dbRecord->pers_entite_role;
} if (is_array ($dbRecord)){
 $this->pers_numero= $dbRecord['pers_numero'];
}else{
 $this->pers_numero= $dbRecord->pers_numero;
} if (is_array ($dbRecord)){
 $this->pers_nom= $dbRecord['pers_nom'];
}else{
 $this->pers_nom= $dbRecord->pers_nom;
} if (is_array ($dbRecord)){
 $this->pers_nom_jf= $dbRecord['pers_nom_jf'];
}else{
 $this->pers_nom_jf= $dbRecord->pers_nom_jf;
} if (is_array ($dbRecord)){
 $this->pers_prenom1= $dbRecord['pers_prenom1'];
}else{
 $this->pers_prenom1= $dbRecord->pers_prenom1;
} if (is_array ($dbRecord)){
 $this->pers_civilite= $dbRecord['pers_civilite'];
}else{
 $this->pers_civilite= $dbRecord->pers_civilite;
} if (is_array ($dbRecord)){
 $this->pers_id_sexe= $dbRecord['pers_id_sexe'];
}else{
 $this->pers_id_sexe= $dbRecord->pers_id_sexe;
} if (is_array ($dbRecord)){
 $this->pers_cle_privee= $dbRecord['pers_cle_privee'];
}else{
 $this->pers_cle_privee= $dbRecord->pers_cle_privee;
} if (is_array ($dbRecord)){
 $this->pers_profession= $dbRecord['pers_profession'];
}else{
 $this->pers_profession= $dbRecord->pers_profession;
} if (is_array ($dbRecord)){
 $this->pers_tel_dom= $dbRecord['pers_tel_dom'];
}else{
 $this->pers_tel_dom= $dbRecord->pers_tel_dom;
} if (is_array ($dbRecord)){
 $this->pers_tel_gsm= $dbRecord['pers_tel_gsm'];
}else{
 $this->pers_tel_gsm= $dbRecord->pers_tel_gsm;
} if (is_array ($dbRecord)){
 $this->pers_tel_pro= $dbRecord['pers_tel_pro'];
}else{
 $this->pers_tel_pro= $dbRecord->pers_tel_pro;
} if (is_array ($dbRecord)){
 $this->pers_mel= $dbRecord['pers_mel'];
}else{
 $this->pers_mel= $dbRecord->pers_mel;
} if (is_array ($dbRecord)){
 $this->pers_num_rue= $dbRecord['pers_num_rue'];
}else{
 $this->pers_num_rue= $dbRecord->pers_num_rue;
} if (is_array ($dbRecord)){
 $this->pers_num_seq= $dbRecord['pers_num_seq'];
}else{
 $this->pers_num_seq= $dbRecord->pers_num_seq;
} if (is_array ($dbRecord)){
 $this->pers_adresse1= $dbRecord['pers_adresse1'];
}else{
 $this->pers_adresse1= $dbRecord->pers_adresse1;
} if (is_array ($dbRecord)){
 $this->pers_adresse2= $dbRecord['pers_adresse2'];
}else{
 $this->pers_adresse2= $dbRecord->pers_adresse2;
} if (is_array ($dbRecord)){
 $this->pers_code_postal= $dbRecord['pers_code_postal'];
}else{
 $this->pers_code_postal= $dbRecord->pers_code_postal;
} if (is_array ($dbRecord)){
 $this->pers_commune= $dbRecord['pers_commune'];
}else{
 $this->pers_commune= $dbRecord->pers_commune;
} return $this; }
public function getDAOId () {
$daoId = 'kernel|kernel_bu_personnel_entite';return $daoId;
}}

class CompiledDAOkernel_bu_personnel_entite implements ICopixDAO  { 
   var $_table='kernel_bu_personnel_entite';
   var $_connectionName=null;
   var $_selectQuery;
 public function __construct ($pConnectionName = null) {
  if ($pConnectionName != null) $this->_connectionName = $pConnectionName;  $this->_selectQuery ='SELECT kernel_bu_personnel_entite.id_per pers_entite_id_per, kernel_bu_personnel_entite.reference pers_entite_reference, kernel_bu_personnel_entite.type_ref pers_entite_type_ref, kernel_bu_personnel_entite.role pers_entite_role, kernel_bu_personnel.numero pers_numero, kernel_bu_personnel.nom pers_nom, kernel_bu_personnel.nom_jf pers_nom_jf, kernel_bu_personnel.prenom1 pers_prenom1, kernel_bu_personnel.civilite pers_civilite, kernel_bu_personnel.id_sexe pers_id_sexe, kernel_bu_personnel.cle_privee pers_cle_privee, kernel_bu_personnel.profession pers_profession, kernel_bu_personnel.tel_dom pers_tel_dom, kernel_bu_personnel.tel_gsm pers_tel_gsm, kernel_bu_personnel.tel_pro pers_tel_pro, kernel_bu_personnel.mel pers_mel, kernel_bu_personnel.num_rue pers_num_rue, kernel_bu_personnel.num_seq pers_num_seq, kernel_bu_personnel.adresse1 pers_adresse1, kernel_bu_personnel.adresse2 pers_adresse2, kernel_bu_personnel.code_postal pers_code_postal, kernel_bu_personnel.commune pers_commune FROM kernel_bu_personnel_entite JOIN kernel_bu_personnel WHERE  kernel_bu_personnel_entite.id_per=kernel_bu_personnel.numero';
 }
 public function check ($pRecord){
  $errorObject = new CopixErrorObject ();
  if (strlen ($pRecord->pers_entite_id_per) > 0){
   if (! is_numeric ($pRecord->pers_entite_id_per)){
      $errorObject->addError ('pers_entite_id_per', _i18n ('copix:dao.errors.numeric','pers_entite_id_per'));
  }
  }
  if (strlen ($pRecord->pers_entite_reference) > 0){
   if (! is_numeric ($pRecord->pers_entite_reference)){
      $errorObject->addError ('pers_entite_reference', _i18n ('copix:dao.errors.numeric','pers_entite_reference'));
  }
  }
  if (strlen ($pRecord->pers_entite_role) > 0){
   if (! is_numeric ($pRecord->pers_entite_role)){
      $errorObject->addError ('pers_entite_role', _i18n ('copix:dao.errors.numeric','pers_entite_role'));
  }
  }
  if (strlen ($pRecord->pers_numero) > 0){
   if (! is_numeric ($pRecord->pers_numero)){
      $errorObject->addError ('pers_numero', _i18n ('copix:dao.errors.numeric','pers_numero'));
  }
  }
  if (strlen ($pRecord->pers_id_sexe) > 0){
   if (! is_numeric ($pRecord->pers_id_sexe)){
      $errorObject->addError ('pers_id_sexe', _i18n ('copix:dao.errors.numeric','pers_id_sexe'));
  }
  }
  return $errorObject->isError () ? $errorObject->asArray () : true;
 }
 public function get ($pers_entite_id_per){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery .' AND kernel_bu_personnel_entite.id_per '.($pers_entite_id_per === null ? 'IS' : '=').'  '.($pers_entite_id_per === null ? 'NULL' : ':kernel_bu_personnel_entite_pers_entite_id_per').' ';
    $results = new CopixDAORecordIterator ($ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_personnel_entite_pers_entite_id_per'=>($pers_entite_id_per === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $pers_entite_id_per)))), $this->getDAOId ());
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
         array('pers_entite_id_per'=>array('id_per', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_reference'=>array('reference', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_type_ref'=>array('type_ref', 'string','kernel_bu_personnel_entite','%s'), 'pers_entite_role'=>array('role', 'integer','kernel_bu_personnel_entite','%s'), 'pers_numero'=>array('numero', 'integer','kernel_bu_personnel','%s'), 'pers_nom'=>array('nom', 'string','kernel_bu_personnel','%s'), 'pers_nom_jf'=>array('nom_jf', 'string','kernel_bu_personnel','%s'), 'pers_prenom1'=>array('prenom1', 'string','kernel_bu_personnel','%s'), 'pers_civilite'=>array('civilite', 'string','kernel_bu_personnel','%s'), 'pers_id_sexe'=>array('id_sexe', 'integer','kernel_bu_personnel','%s'), 'pers_cle_privee'=>array('cle_privee', 'string','kernel_bu_personnel','%s'), 'pers_profession'=>array('profession', 'string','kernel_bu_personnel','%s'), 'pers_tel_dom'=>array('tel_dom', 'string','kernel_bu_personnel','%s'), 'pers_tel_gsm'=>array('tel_gsm', 'string','kernel_bu_personnel','%s'), 'pers_tel_pro'=>array('tel_pro', 'string','kernel_bu_personnel','%s'), 'pers_mel'=>array('mel', 'string','kernel_bu_personnel','%s'), 'pers_num_rue'=>array('num_rue', 'string','kernel_bu_personnel','%s'), 'pers_num_seq'=>array('num_seq', 'string','kernel_bu_personnel','%s'), 'pers_adresse1'=>array('adresse1', 'string','kernel_bu_personnel','%s'), 'pers_adresse2'=>array('adresse2', 'string','kernel_bu_personnel','%s'), 'pers_code_postal'=>array('code_postal', 'string','kernel_bu_personnel','%s'), 'pers_commune'=>array('commune', 'string','kernel_bu_personnel','%s')), $ct);
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
         array('pers_entite_id_per'=>array('id_per', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_reference'=>array('reference', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_type_ref'=>array('type_ref', 'string','kernel_bu_personnel_entite','%s'), 'pers_entite_role'=>array('role', 'integer','kernel_bu_personnel_entite','%s'), 'pers_numero'=>array('numero', 'integer','kernel_bu_personnel','%s'), 'pers_nom'=>array('nom', 'string','kernel_bu_personnel','%s'), 'pers_nom_jf'=>array('nom_jf', 'string','kernel_bu_personnel','%s'), 'pers_prenom1'=>array('prenom1', 'string','kernel_bu_personnel','%s'), 'pers_civilite'=>array('civilite', 'string','kernel_bu_personnel','%s'), 'pers_id_sexe'=>array('id_sexe', 'integer','kernel_bu_personnel','%s'), 'pers_cle_privee'=>array('cle_privee', 'string','kernel_bu_personnel','%s'), 'pers_profession'=>array('profession', 'string','kernel_bu_personnel','%s'), 'pers_tel_dom'=>array('tel_dom', 'string','kernel_bu_personnel','%s'), 'pers_tel_gsm'=>array('tel_gsm', 'string','kernel_bu_personnel','%s'), 'pers_tel_pro'=>array('tel_pro', 'string','kernel_bu_personnel','%s'), 'pers_mel'=>array('mel', 'string','kernel_bu_personnel','%s'), 'pers_num_rue'=>array('num_rue', 'string','kernel_bu_personnel','%s'), 'pers_num_seq'=>array('num_seq', 'string','kernel_bu_personnel','%s'), 'pers_adresse1'=>array('adresse1', 'string','kernel_bu_personnel','%s'), 'pers_adresse2'=>array('adresse2', 'string','kernel_bu_personnel','%s'), 'pers_code_postal'=>array('code_postal', 'string','kernel_bu_personnel','%s'), 'pers_commune'=>array('commune', 'string','kernel_bu_personnel','%s')), $ct);
      $query .= $querySql;
    }
    if(count($joins)) return $ct->iDoQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ());
    return new CopixDAORecordIterator ($ct->doQuery ($query, $params, $searchParams->getOffset (), $searchParams->getCount ()), $this->getDAOId ());
 }
 public function insert ($object, $pUseId = false){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_bu_personnel_entite');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'INSERT INTO kernel_bu_personnel_entite (id_per,reference,type_ref,role,numero,nom,nom_jf,prenom1,civilite,id_sexe,cle_privee,profession,tel_dom,tel_gsm,tel_pro,mel,num_rue,num_seq,adresse1,adresse2,code_postal,commune) VALUES (:pers_entite_id_per, :pers_entite_reference, :pers_entite_type_ref, :pers_entite_role, :pers_numero, :pers_nom, :pers_nom_jf, :pers_prenom1, :pers_civilite, :pers_id_sexe, :pers_cle_privee, :pers_profession, :pers_tel_dom, :pers_tel_gsm, :pers_tel_pro, :pers_mel, :pers_num_rue, :pers_num_seq, :pers_adresse1, :pers_adresse2, :pers_code_postal, :pers_commune)';
   $toReturn = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':pers_entite_id_per'=>$object->pers_entite_id_per, ':pers_entite_reference'=>$object->pers_entite_reference, ':pers_entite_type_ref'=>$object->pers_entite_type_ref, ':pers_entite_role'=>$object->pers_entite_role, ':pers_numero'=>$object->pers_numero, ':pers_nom'=>$object->pers_nom, ':pers_nom_jf'=>$object->pers_nom_jf, ':pers_prenom1'=>$object->pers_prenom1, ':pers_civilite'=>$object->pers_civilite, ':pers_id_sexe'=>$object->pers_id_sexe, ':pers_cle_privee'=>$object->pers_cle_privee, ':pers_profession'=>$object->pers_profession, ':pers_tel_dom'=>$object->pers_tel_dom, ':pers_tel_gsm'=>$object->pers_tel_gsm, ':pers_tel_pro'=>$object->pers_tel_pro, ':pers_mel'=>$object->pers_mel, ':pers_num_rue'=>$object->pers_num_rue, ':pers_num_seq'=>$object->pers_num_seq, ':pers_adresse1'=>$object->pers_adresse1, ':pers_adresse2'=>$object->pers_adresse2, ':pers_code_postal'=>$object->pers_code_postal, ':pers_commune'=>$object->pers_commune)));
    return $toReturn;
}
 public function update ($object){
   if (is_array ($object)){
      $tmpRecord = _record ('kernel|kernel_bu_personnel_entite');
      $tmpRecord->initFromDBObject ($object);
      $object = $tmpRecord;
   }
   if (($checkResult = $this->check ($object)) !== true){
		           throw new CopixDAOCheckException ($checkResult, $object);
		        }    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'UPDATE kernel_bu_personnel_entite SET  reference= :pers_entite_reference, type_ref= :pers_entite_type_ref, role= :pers_entite_role where id_per '.($object->pers_entite_id_per === null ? 'IS' : '=').'  '.($object->pers_entite_id_per === null ? 'NULL' : ':kernel_bu_personnel_entite_pers_entite_id_per').' ';
   $affectedRows = $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':pers_entite_reference'=>$object->pers_entite_reference, ':pers_entite_type_ref'=>$object->pers_entite_type_ref, ':pers_entite_role'=>$object->pers_entite_role, ':kernel_bu_personnel_entite_pers_entite_id_per'=>($object->pers_entite_id_per === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $object->pers_entite_id_per))));
   return $affectedRows;
 }
 public function delete ($pers_entite_id_per){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_personnel_entite where id_per '.($pers_entite_id_per === null ? 'IS' : '=').'  '.($pers_entite_id_per === null ? 'NULL' : ':kernel_bu_personnel_entite_pers_entite_id_per').' ';
   return $ct->doQuery ($query, $this->_dirtyClearNullValuesForSelectQueries (array (':kernel_bu_personnel_entite_pers_entite_id_per'=>($pers_entite_id_per === null ? "___COPIX___DELETE___ME___FROM____DAO___QUERIES___" : $pers_entite_id_per))));
 }
 public function deleteBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'DELETE FROM kernel_bu_personnel_entite';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' WHERE ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('pers_entite_id_per'=>array('id_per', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_reference'=>array('reference', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_type_ref'=>array('type_ref', 'string','kernel_bu_personnel_entite','%s'), 'pers_entite_role'=>array('role', 'integer','kernel_bu_personnel_entite','%s'), 'pers_numero'=>array('numero', 'integer','kernel_bu_personnel','%s'), 'pers_nom'=>array('nom', 'string','kernel_bu_personnel','%s'), 'pers_nom_jf'=>array('nom_jf', 'string','kernel_bu_personnel','%s'), 'pers_prenom1'=>array('prenom1', 'string','kernel_bu_personnel','%s'), 'pers_civilite'=>array('civilite', 'string','kernel_bu_personnel','%s'), 'pers_id_sexe'=>array('id_sexe', 'integer','kernel_bu_personnel','%s'), 'pers_cle_privee'=>array('cle_privee', 'string','kernel_bu_personnel','%s'), 'pers_profession'=>array('profession', 'string','kernel_bu_personnel','%s'), 'pers_tel_dom'=>array('tel_dom', 'string','kernel_bu_personnel','%s'), 'pers_tel_gsm'=>array('tel_gsm', 'string','kernel_bu_personnel','%s'), 'pers_tel_pro'=>array('tel_pro', 'string','kernel_bu_personnel','%s'), 'pers_mel'=>array('mel', 'string','kernel_bu_personnel','%s'), 'pers_num_rue'=>array('num_rue', 'string','kernel_bu_personnel','%s'), 'pers_num_seq'=>array('num_seq', 'string','kernel_bu_personnel','%s'), 'pers_adresse1'=>array('adresse1', 'string','kernel_bu_personnel','%s'), 'pers_adresse2'=>array('adresse2', 'string','kernel_bu_personnel','%s'), 'pers_code_postal'=>array('code_postal', 'string','kernel_bu_personnel','%s'), 'pers_commune'=>array('commune', 'string','kernel_bu_personnel','%s')), $ct);
      $query .= $querySql;
    }
    return $ct->doQuery ($query, $params);
 }
 public function countBy ($searchParams){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = 'SELECT COUNT(*) AS "COUNT"  FROM kernel_bu_personnel_entite JOIN kernel_bu_personnel WHERE  kernel_bu_personnel_entite.id_per=kernel_bu_personnel.numero';
    $params = array ();    if (!$searchParams->isEmpty ()){
       $query .= ' AND ';
      list ($querySql, $params) = $searchParams->explainSQL (
         array('pers_entite_id_per'=>array('id_per', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_reference'=>array('reference', 'integer','kernel_bu_personnel_entite','%s'), 'pers_entite_type_ref'=>array('type_ref', 'string','kernel_bu_personnel_entite','%s'), 'pers_entite_role'=>array('role', 'integer','kernel_bu_personnel_entite','%s'), 'pers_numero'=>array('numero', 'integer','kernel_bu_personnel','%s'), 'pers_nom'=>array('nom', 'string','kernel_bu_personnel','%s'), 'pers_nom_jf'=>array('nom_jf', 'string','kernel_bu_personnel','%s'), 'pers_prenom1'=>array('prenom1', 'string','kernel_bu_personnel','%s'), 'pers_civilite'=>array('civilite', 'string','kernel_bu_personnel','%s'), 'pers_id_sexe'=>array('id_sexe', 'integer','kernel_bu_personnel','%s'), 'pers_cle_privee'=>array('cle_privee', 'string','kernel_bu_personnel','%s'), 'pers_profession'=>array('profession', 'string','kernel_bu_personnel','%s'), 'pers_tel_dom'=>array('tel_dom', 'string','kernel_bu_personnel','%s'), 'pers_tel_gsm'=>array('tel_gsm', 'string','kernel_bu_personnel','%s'), 'pers_tel_pro'=>array('tel_pro', 'string','kernel_bu_personnel','%s'), 'pers_mel'=>array('mel', 'string','kernel_bu_personnel','%s'), 'pers_num_rue'=>array('num_rue', 'string','kernel_bu_personnel','%s'), 'pers_num_seq'=>array('num_seq', 'string','kernel_bu_personnel','%s'), 'pers_adresse1'=>array('adresse1', 'string','kernel_bu_personnel','%s'), 'pers_adresse2'=>array('adresse2', 'string','kernel_bu_personnel','%s'), 'pers_code_postal'=>array('code_postal', 'string','kernel_bu_personnel','%s'), 'pers_commune'=>array('commune', 'string','kernel_bu_personnel','%s')), $ct);
      $query .= $querySql;
    }
    $result = $ct->doQuery ($query, $params);
    return $result[0]->COUNT; }
 function getByRef ($ref_type, $ref_id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_bu_personnel_entite.reference'.($ref_id===null ? ' IS ' : ' = ').''.($ref_id === null ? 'NULL' : intval($ref_id)).' AND kernel_bu_personnel_entite.type_ref'.($ref_type===null ? ' IS ' : ' = ').''. $ct->quote ($ref_type).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
 function getById ($id){
    $ct = CopixDB::getConnection ($this->_connectionName);
    $query = $this->_selectQuery.' AND kernel_bu_personnel_entite.id_per'.($id===null ? ' IS ' : ' = ').''.($id === null ? 'NULL' : intval($id)).'';
    return new CopixDAORecordIterator ($ct->doQuery ($query), $this->getDAOId ());
 }
public function getFieldsDescription() {
$fields = array (
  'pers_entite_id_per' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_entite_id_per',
     'fieldName' => 'id_per',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_entite_id_per',
     'isPK' => true,
     'isFK' => true,
     'type' => 'integer',
     'table' => 'kernel_bu_personnel_entite',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => 'kernel_bu_personnel',
     'fkFieldName' => 'numero',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'pers_entite_reference' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_entite_reference',
     'fieldName' => 'reference',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_entite_reference',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_personnel_entite',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'pers_entite_type_ref' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_entite_type_ref',
     'fieldName' => 'type_ref',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_entite_type_ref',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel_entite',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => true,
  )),
  'pers_entite_role' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_entite_role',
     'fieldName' => 'role',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_entite_role',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_personnel_entite',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => '',
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => true,
  )),
  'pers_numero' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_numero',
     'fieldName' => 'numero',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_numero',
     'isPK' => true,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
  'pers_nom' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_nom',
     'fieldName' => 'nom',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_nom',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_nom_jf' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_nom_jf',
     'fieldName' => 'nom_jf',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_nom_jf',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_prenom1' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_prenom1',
     'fieldName' => 'prenom1',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_prenom1',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_civilite' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_civilite',
     'fieldName' => 'civilite',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_civilite',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_id_sexe' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_id_sexe',
     'fieldName' => 'id_sexe',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_id_sexe',
     'isPK' => false,
     'isFK' => false,
     'type' => 'integer',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => false,
     'ofPrimaryTable' => false,
  )),
  'pers_cle_privee' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_cle_privee',
     'fieldName' => 'cle_privee',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_cle_privee',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_profession' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_profession',
     'fieldName' => 'profession',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_profession',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_tel_dom' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_tel_dom',
     'fieldName' => 'tel_dom',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_tel_dom',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_tel_gsm' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_tel_gsm',
     'fieldName' => 'tel_gsm',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_tel_gsm',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_tel_pro' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_tel_pro',
     'fieldName' => 'tel_pro',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_tel_pro',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_mel' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_mel',
     'fieldName' => 'mel',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_mel',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_num_rue' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_num_rue',
     'fieldName' => 'num_rue',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_num_rue',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_num_seq' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_num_seq',
     'fieldName' => 'num_seq',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_num_seq',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_adresse1' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_adresse1',
     'fieldName' => 'adresse1',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_adresse1',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_adresse2' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_adresse2',
     'fieldName' => 'adresse2',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_adresse2',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_code_postal' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_code_postal',
     'fieldName' => 'code_postal',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_code_postal',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
     'selectMotif' => '%s',
     'method' => NULL,
     'fkTable' => NULL,
     'fkFieldName' => NULL,
     'sequenceName' => '',
     'maxlength' => NULL,
     'needsQuotes' => true,
     'ofPrimaryTable' => false,
  )),
  'pers_commune' => 
  CopixPropertyForDAO::__set_state(array(
     'name' => 'pers_commune',
     'fieldName' => 'commune',
     'regExp' => NULL,
     'required' => false,
     'captionI18N' => NULL,
     'caption' => 'pers_commune',
     'isPK' => false,
     'isFK' => false,
     'type' => 'string',
     'table' => 'kernel_bu_personnel',
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
$daoId = 'kernel|kernel_bu_personnel_entite';return $daoId;
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