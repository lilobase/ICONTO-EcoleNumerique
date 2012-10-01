<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseurDossier
{
  public function __toString ()
  {
        return $this->nom;
    }

    /**
   * Retourne le path complet du dossier
   * sous la forme : /Classeur/Dossier1/Dossier2/...
   *
   * @param bool  $withStripText  StripText du path
   *
   * @return string
   */
    public function getPath ($withStripText = true)
    {
      $paths = array();

      $classeurDAO = _ioDAO('classeur|classeur');
      $dossierDAO = _ioDAO('classeur|classeurdossier');

      $paths[] = $withStripText ? Kernel::stripText($this->nom) : $this->nom;
      $dossier = $this;

      while ($dossier->parent_id != 0) {

        $dossier = $dossierDAO->get($dossier->parent_id);
        $paths[] = $withStripText ? Kernel::stripText($dossier->nom) : $dossier->nom;
      }

      $classeur = $classeurDAO->get($dossier->classeur_id);
      $paths[] = $withStripText ? Kernel::stripText($classeur->titre) : $classeur->titre;

      return '/'.implode('/', array_reverse($paths)).'/';
    }

    /**
   * Indique si le dossier a des sous-dossiers
   *
   * @param bool  $withLockers Prendre en compte les dossiers du type "casier"
   *
   * @return bool
   */
    public function hasSousDossiers ($withLockers = true)
    {
      $dossierDAO = _ioDAO('classeur|classeurdossier');

      return count($dossierDAO->getEnfantsDirects($this->classeur_id, $this->id, $withLockers)->fetchAll()) > 0 ? true : false;
    }

    /**
   * Indique s'il s'agit du casier principal du classeur
   *
   * @return bool
   */
    public function isCasierPrincipal ()
    {
      return $this->casier && $this->parent_id == 0;
    }
}

class DAOClasseurDossier
{
    /**
   * Retourne le casier principal d'un classeur
   *
   * @param string   $nom   Nom du classeur
   *
   * @return CopixDAORecordIterator
   */
    public function getByNom ($nom)
    {
      $criteria = _daoSp ();
        $criteria->addCondition ('nom', '=', $nom);

        return $this->findBy ($criteria);
    }

    /**
   * Retourne le casier principal d'un classeur
   *
   * @param int   $idClasseur   Identifiant du classeur
   *
   * @return DAORecordClasseur_dossier or false
   */
    public function getCasier ($idClasseur)
    {
      $criteria = _daoSp ();
      $criteria->addCondition ('classeur_id', '=', $idClasseur);
      $criteria->addCondition ('parent_id', '=', 0);
    $results = $this->findBy ($criteria);

    return isset ($results[0]) ? $results[0] : false;
    }


  /**
   * Retourne les dossiers directs du classeur ou dossier donné
   *
   * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   * @param bool  $withLockers  Récupère également les dossiers de type "casier"
   * @param array $tri          Tableau spécifiant la colonne et la direction à utiliser pour le tri
   *
   * @return CopixDAORecordIterator
   */
  public function getEnfantsDirects ($idClasseur, $idDossier = null, $withLockers = true, $tri = array())
  {
    $criteria = _daoSp ();
        $criteria->addCondition ('classeur_id', '=', $idClasseur);
        if (!is_null ($idDossier)) {

          $criteria->addCondition ('parent_id', '=', $idDossier);
        } else {

          $criteria->addCondition ('parent_id', '=', 0);
        }

        if (!$withLockers) {

          $criteria->addCondition ('casier', '=', 0);
        }

        if (!empty($tri)) {

          $criteria->orderBy(array($tri['colonne'], $tri['direction']));
        } else {

          $criteria->orderBy(array('nom', 'ASC'));
        }

        return $this->findBy ($criteria);
  }

  /**
   * Retourne les contenus (dossiers / fichiers) d'un classeur / dossier
   *
   * @param int     $idClasseur Identifiant du classeur
   * @param int     $idDossier  Identifiant du dossier
   * @param array   $tri        Paramètres du tri
   *
   * @return array
   */
  public function getContenus ($idClasseur, $idDossier = null, $tri = array())
  {
    $toReturn = array();

    $sql = 'SELECT id, id AS dossier_id, parent_id AS parent_id, D.nom AS titre, D.nom AS fichier, nb_dossiers, nb_fichiers, taille, "---" AS type, casier, date_creation AS date, D.user_type, D.user_id, "" AS origine, "dossier" AS content_type'
        . ' FROM module_classeur_dossier D'
        . ' WHERE module_classeur_id = :idClasseur';
    if (!is_null($idDossier)) {

      $sql .= ' AND parent_id = :idDossier';
    }

    $sql .= ' UNION';

    $sql .= ' SELECT F.id, module_classeur_dossier_id AS dossier_id, module_classeur_dossier_id AS parent_id, titre, fichier, "" AS nb_dossiers, "" AS nb_fichiers, taille, type, "" AS casier, date_upload AS date, F.user_type, F.user_id,'
        . ' CASE F.user_type
                WHEN "USER_ELE" THEN CONCAT(kernel_bu_eleve.prenom1, " ", kernel_bu_eleve.nom)
                WHEN "USER_RES" THEN CONCAT(kernel_bu_responsable.prenom1, " ", kernel_bu_responsable.nom)
                WHEN "USER_ENS" THEN CONCAT(kernel_bu_personnel.prenom1, " ", kernel_bu_personnel.nom)
                WHEN "USER_VIL" THEN CONCAT(kernel_bu_personnel.prenom1, " ", kernel_bu_personnel.nom)
                WHEN "USER_EXT" THEN CONCAT(kernel_ext_user.prenom, " ", kernel_ext_user.nom)
                ELSE "inconnu"
            END AS origine,'
        . ' "fichier" AS content_type'
        . ' FROM module_classeur_fichier F'

        . ' LEFT JOIN kernel_bu_eleve ON kernel_bu_eleve.idEleve=F.user_id'
        . ' LEFT JOIN kernel_bu_responsable ON kernel_bu_responsable.numero=F.user_id'
        . ' LEFT JOIN kernel_bu_personnel ON kernel_bu_personnel.numero=F.user_id'
        . ' LEFT JOIN kernel_ext_user ON kernel_ext_user.id=F.user_id'

        . ' WHERE module_classeur_id = :idClasseur';
    if (!is_null($idDossier)) {

      $sql .= ' AND module_classeur_dossier_id = :idDossier';
    }

    if (!empty($tri)) {

      $sql .= ' ORDER BY '.$tri['colonne'].' '.$tri['direction'];
    } else {

      $sql .= ' ORDER BY titre ASC';
    }

    // Ajout d'un champ lien pour les favoris
    $results = _doQuery($sql, array (':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
    foreach ($results as $key => $result) {
      if ($result->content_type == 'fichier'
        && substr($result->fichier, -4) == '.web') {

        $result->lien = ClasseurService::getFavoriteLink ($result->id);
      }

      $toReturn[] = $result;
    }

    return $toReturn;
  }

    /**
     * Renvoie le nombre de sous-répertoires contenus dans un répertoire
     *
     * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   *
   * @return int
     */
    public function getNombreEnfantsDirects ($idClasseur, $idDossier)
    {
        $results = array();

        $sql = 'SELECT COUNT(id) AS nb_dossiers'
            . ' FROM module_classeur_dossier'
            . ' WHERE module_classeur_id = :idClasseur'
            . ' AND parent_id = :idDossier';

      $results = _doQuery($sql, array(':idClasseur' => $idClasseur, ':idDossier' => $idDossier));

      return $results[0] ? $results[0]->nb_dossiers : 0;
    }
}