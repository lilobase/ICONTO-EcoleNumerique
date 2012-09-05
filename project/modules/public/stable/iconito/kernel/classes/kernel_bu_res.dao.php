<?php

/**
 * Surcharge de la DAO Kernel_bu_res
 *
 * @package Iconito
 * @subpackage Kernel
 */

class DAORecordKernel_bu_res
{
  protected $_loginAccount = null;

  public function getLoginAccount ()
  {
    if (is_null ($this->_loginAccount)) {

      $dbUserDAO = _ioDAO ('kernel|kernel_copixuser');
      $this->_loginAccount = $dbUserDAO->getUserByBuIdAndBuType ($this->res_numero, 'USER_RES')->login_dbuser;
    }

    return $this->_loginAccount;
  }
}

class DAOKernel_bu_res
{
    /**
     * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $classe Id de la classe
     * @return mixed Objet DAO
     */
    public function getParentsInClasse ($classe)
    {
      $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_dbuser AS login, LI.bu_type, LI.bu_id, R.id_sexe AS sexe FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_affectation EA, kernel_link_bu2user LI, dbuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND EA.classe=".$classe." AND EA.current = 1 ORDER BY R.nom, R.prenom1";
        return _doQuery($query);
    }

    /**
     * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $ecole Id de l'école
     * @return mixed Objet DAO
     */
    public function getParentsInEcole ($ecole)
    {
      $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_link_bu2user LI, dbuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND EA.etablissement=".$ecole." ORDER BY R.nom, R.prenom1";
        return _doQuery($query);
    }

    /**
     * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $ville Id de la ville
     * @return mixed Objet DAO
     */
    public function getParentsInVille ($ville)
    {
      $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_bu_ecole E, kernel_link_bu2user LI, dbuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND EA.etablissement=E.numero AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND E.id_ville=".$ville." ORDER BY R.nom, R.prenom1";

        return _doQuery($query);
    }


    /**
     * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école d'une ville d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $grville Id du groupe de villes
     * @return mixed Objet DAO
     */
    public function getParentsInGrville ($grville)
    {
        $sqlPlus = '';
        if ( Kernel::getKernelLimits('ville') )
            $sqlPlus .= ' AND V.id_vi IN ('.Kernel::getKernelLimits('ville').')';
      $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_bu_ecole E, kernel_bu_ville V, kernel_link_bu2user LI, dbuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND EA.etablissement=E.numero AND E.id_ville=V.id_vi AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND  V.id_grville=".$grville.$sqlPlus." ORDER BY R.nom, R.prenom1";

        return _doQuery($query);
    }

    /**
     * Retourne les responsables d'un élève
     *
     * @param int $studentId Id de l'élève
     */
    public function getByStudent ($studentId)
    {
      $sql = str_replace('FROM', ', dbuser.login_dbuser AS login, kernel_bu_lien_parental.parente as link FROM', $this->_selectQuery) . ', kernel_bu_responsables, kernel_link_bu2user, dbuser, kernel_bu_lien_parental'
             . ' WHERE kernel_bu_responsable.numero=kernel_bu_responsables.id_responsable'
             . ' AND kernel_bu_responsables.id_par=kernel_bu_lien_parental.id_pa'
             . ' AND kernel_link_bu2user.user_id=dbuser.id_dbuser'
              . ' AND kernel_link_bu2user.bu_type="USER_RES"'
              . ' AND kernel_link_bu2user.bu_id=kernel_bu_responsable.numero'
             . ' AND kernel_bu_responsables.id_beneficiaire=:id';

        return _doQuery ($sql, array (':id' => $studentId));
    }

    /**
     * Retourne un responsable à partir du login de son compte
     *
     * @param string $login
     */
    public function getByLogin ($login)
    {
      $sql = $this->_selectQuery . ', kernel_link_bu2user, dbuser'
             . ' WHERE dbuser.login_dbuser=:login'
             . ' AND kernel_link_bu2user.user_id=dbuser.id_dbuser'
             . ' AND kernel_link_bu2user.bu_type="USER_RES"'
             . ' AND kernel_link_bu2user.bu_id=kernel_bu_responsable.numero';

        $results = _doQuery($sql, array (':login' => $login));

       return isset ($results[0]) ? $results[0] : false;
    }

    /**
   * Indique si un responsable est parent de l'élève indiqué
   *
   * @param int $parentId
   * @param int $studentId
   *
   * @return boolean
   */
    public function isParentOfStudent ($parentId, $studentId)
    {
      $sql = $this->_selectQuery . ', kernel_bu_responsables'
             . ' WHERE kernel_bu_responsable.numero=kernel_bu_responsables.id_responsable'
             . ' AND kernel_bu_responsables.id_beneficiaire=:studentId'
             . ' AND kernel_bu_responsable.numero=:parentId';

      $results = _doQuery($sql, array (':studentId' => $studentId, ':parentId' => $parentId));

       return isset ($results[0]) ? true : false;
    }
}