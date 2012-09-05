<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
*/

class ActionGroupDefault extends CopixActionGroup
{
    public function beforeAction ($actionName)
    {
      CopixHTMLHeader::addJSLink (_resource('js/iconito/module_cahierdetextes.js'));

      // Contrôle d'accès au module
    if (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId', _request('id', null))) < PROFILE_CCV_READ) {

      return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
    }
    // Le responsable doit disposer de l'ID de l'élève
    elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId', _request('id', null))) == PROFILE_CCV_READ) {

      if ($actionName == "processVoirTravaux" && is_null($eleve = _request('eleve', null))) {

        $myNode = CopixSession::get('myNode');
        $eleve = $myNode['type'] == "USER_ELE" ? $myNode['id'] : null;
      } else {

        $eleve = _request('eleve', null);
      }

      if (is_null($eleve) && $actionName != 'go') {

        return CopixActionGroup::process ('generictools|Messages::getError',
                array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }
    }
    }

    /**
     * Affichage principal
     */
    public function processVoirTravaux ()
    {
      $ppo = new CopixPPO ();

      // Récupération ID du cahier de textes
      if (is_null($ppo->cahierId = _request ('cahierId'))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      $myNode = CopixSession::get('myNode');

    // Récupération des paramètres
    $ppo->jour        = _request ('jour', date('d'));
      $ppo->mois        = _request ('mois', date('m'));
      $ppo->annee       = _request ('annee', date('Y'));
      $ppo->msgSuccess  = _request ('msgSuccess', false);
      $ppo->eleve       = _request ('eleve', $myNode['type'] == "USER_ELE" ? $myNode['id'] : null);

      if (_request ('save', false) && !$ppo->msgSuccess) {

        $ppo->msgSuccess = CopixI18N::get ('classeur|classeur.message.confirmUploadLocker');
      }

      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->dateSelectionnee  = $ppo->annee.$ppo->mois.$ppo->jour;

      $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'voir_travaux.tpl');
    }

    /**
     * Affichage des travaux sous forme de liste - * Enseignant / Responsable *
     */
    public function processVoirListeTravaux ()
    {
      $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId  = _request ('cahierId'))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }
      // L'accès admin est nécessaire pour cette vue
      elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId')) > PROFILE_CCV_READ
        && Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId')) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

    // Récupération des paramètres
    $ppo->jour     = _request ('jour', date('d'));
      $ppo->mois     = _request ('mois', date('m'));
      $ppo->annee    = _request ('annee', date('Y'));
    $ppo->nbJours  = _request ('nb_jours', 10);
      $ppo->dateDeb  = _request ('date_deb', $ppo->jour.'/'.$ppo->mois.'/'.$ppo->annee);
      $ppo->eleve    = _request ('eleve', null);

      $ppo->choixNbJours  = array(10, 20, 30, 40, 50);

    // Récupération des travaux suivant le type de l'utilisateur courant
      $travailDAO     = _ioDAO ('cahierdetextes|cahierdetextestravail');
      $cahierInfos    = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->estAdmin  = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) >= PROFILE_CCV_PUBLISH ? true : false;
    $nodeId         = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;

      // Enseignant
      if ($ppo->estAdmin) {

        $ppo->travaux = $travailDAO->findByClasseDateEtIntervalleParJourEtType($nodeId, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours);
      }
      // Responsable
      elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {

      $ppo->travaux = $travailDAO->findByEleveDateEtIntervalleParJourEtType($ppo->eleve, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours);
    }
    // Elève
      else {

        $ppo->travaux = $travailDAO->findByEleveDateEtIntervalleParJourEtType(_currentUser()->getExtra('id'), CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours);
      }

      $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'voir_liste_travaux.tpl');
    }

    /**
     * Affichage des travaux avec filtrage par domaine - * Enseignant / Responsable *
     */
    public function processVoirTravauxParDomaine ()
    {
      $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId  = _request ('cahierId'))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }
      // L'accès admin est nécessaire pour cette vue
      elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId')) > PROFILE_CCV_READ
        && Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId')) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
      $ppo->mois    = _request ('mois', date('m'));
      $ppo->annee   = _request ('annee', date('Y'));
      $ppo->nbJours = _request ('nb_jours', 10);
      $ppo->dateDeb = _request ('date_deb', $ppo->jour.'/'.$ppo->mois.'/'.$ppo->annee);
      $ppo->domaine = _request ('domaine', null);
      $ppo->eleve   = _request ('eleve', null);

      $ppo->choixNbJours  = array(10, 20, 30, 40, 50);
      $cahierInfos        = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->estAdmin      = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) >= PROFILE_CCV_PUBLISH ? true : false;
    $nodeId             = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;

      // Récupération des domaines
      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      $domaines   = $domaineDAO->findByClasse($nodeId);

      $ppo->idsDomaine  = array('');
      $ppo->nomsDomaine = array(CopixI18N::get ('cahierdetextes|cahierdetextes.message.ALL'));
      $ppo->correspondanceDomaines = array();

      foreach($domaines as $domaine) {

        $ppo->idsDomaine[]  = $domaine->id;
        $ppo->nomsDomaine[] = $domaine->nom;
        $ppo->correspondanceDomaines[$domaine->id] = $domaine->nom;
      }

      // Récupération des travaux suivant le type de l'utilisateur courant
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if ($ppo->estAdmin) {

        $ppo->travaux = $travailDAO->findByClasseDateIntervalleEtDomaineParDomaineEtType($nodeId, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {

        $ppo->travaux = $travailDAO->findByEleveDateIntervalleEtDomaineParDomaineEtType($ppo->eleve, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
      }

      $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'voir_travaux_par_domaine.tpl');
    }

    /**
     * Affichage des élèves concernés par un travail - * Enseignant *
     */
    public function processVoirConcernesParTravail ()
    {
      $ppo = new CopixPPO ();
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');

      if (is_null($cahierId = _request('cahierId', null)) || !$ppo->travail = $travailDAO->get (_request('travailId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des élèves liés au travail
      $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
      $ppo->eleves = $travail2eleveDAO->findElevesParTravail($ppo->travail->id);

      return _arPPO ($ppo, array ('template' => 'voir_concernes_par_travail.tpl', 'mainTemplate' => 'main|main_fancy.php'));
    }

    /**
     * Action permettant à un élève de rendre un travail
     */
    public function processRendreTravail ()
    {
      $ppo = new CopixPPO ();

    if (is_null($ppo->cahierId = _request ('cahierId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
          array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if (!$ppo->travail = $travailDAO->get (_request ('travailId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
          array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
      $studentIds = $travail2eleveDAO->findEleveIdsParTravail ($ppo->travail->id);
      if (!$ppo->travail->a_rendre
        || _currentUser()->getExtra('type') != 'USER_ELE' || !in_array (_currentUser()->getExtra('id'), $studentIds)) {

        return CopixActionGroup::process ('generictools|Messages::getError',
          array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      $ppo->error    = _request ('error');

      // Récupération du dossier où rendre le travail
      $classeurDAO  = _ioDAO ('classeur|classeur');
      $dossierDAO   = _ioDAO ('classeur|classeurdossier');

      if ($dossier = $dossierDAO->get ($ppo->travail->dossier_id)) {

        $classeur = $classeurDAO->get ($dossier->classeur_id);
      }

      if (is_null ($ppo->travail->dossier_id) || !$dossier->casier) {

        // Récupération du classeur
        $cahierInfos = Kernel::getModParent ('MOD_CAHIERDETEXTES', $ppo->cahierId);
        $mods = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
      $modClasseur = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR');
      $classeurId = $modClasseur[0]->module_id;

      $classeur = $classeurDAO->get ($classeurId);
      $dossier = $dossierDAO->getCasier ($classeurId);
    }

    if (CopixRequest::isMethod ('post')) {

      // Contrôle upload du fichier
      if (is_uploaded_file ($_FILES['fichier']['tmp_name'])) {

        $file = $_FILES['fichier']['tmp_name'];
        $name = $_FILES['fichier']['name'];

        _classInclude ('classeur|classeurService');
        classeurService::uploadFile ($file, $name, $classeur, $dossier);
        classeurService::sendLockerUploadConfirmation ($name);

        // Sauvegarde de la date de rendu du travail
        $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        if ($suivi = $travail2eleveDAO->getByTravailAndEleve ($ppo->travail->id, _currentUser()->getExtra('id'))) {

          $suivi->rendu_le = date('Y-m-d H:i:s');

          $travail2eleveDAO->update ($suivi);
        } else {

          $suivi = _record ('cahierdetextes|cahierdetextestravail2eleve');

          $suivi->travail_id = $ppo->travail->id;
          $suivi->eleve_id = _currentUser()->getExtra('id');
          $suivi->rendu_le = date('Y-m-d H:i:s');

          $travail2eleveDAO->insert ($suivi);
        }

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'annee' => substr($ppo->travail->date_realisation, 0, 4), 'mois' => substr($ppo->travail->date_realisation, 4, 2), 'jour' => substr($ppo->travail->date_realisation, 6, 2), 'save' => 1)));
      } else {

        $ppo->erreur = CopixI18N::get ('classeur|classeur.error.noFiles');

        return _arPPO ($ppo, array ('template' => 'rendre_travail.tpl'));
      }
    }

    return _arPPO ($ppo, array ('template' => 'rendre_travail.tpl', 'mainTemplate' => 'main|main_fancy.php'));
    }

    /**
     * Gestion d'un domaine - * Enseignant *
     */
    public function processGererDomaines ()
    {
      $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId = _request('cahierId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }
      // ACTION PROTEGEE : l'utilisateur doit être enseignant de la classe
      elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

    // Récupération des paramètres
    $nomDomaine       = _request ('nom', null);
      $ppo->jour        = _request ('jour', date('d'));
      $ppo->mois        = _request ('mois', date('m'));
      $ppo->annee       = _request ('annee', date('Y'));
    $ppo->msgSuccess  = false;

    // Mode edition ?
      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      if (is_null($domaineId = _request('domaineId', null))) {

        $ppo->domaine = _record ('cahierdetextes|cahierdetextesdomaine');
      } else {

        $ppo->domaine = $domaineDAO->get($domaineId);
      }

    if (CopixRequest::isMethod ('post')) {

      $cahierInfos  = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);

      $ppo->domaine = _record ('cahierdetextes|cahierdetextesdomaine');

      $ppo->domaine->id        = _request ('domaineId', null);
      $ppo->domaine->nom       = trim($nomDomaine);
      $ppo->domaine->classe_id = $cahierInfos[0]->node_id;

      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->domaine->nom == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noName');
      }

      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      if ($domaineDAO->getByClasseEtNom($ppo->domaine->classe_id, $ppo->domaine->nom)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes.error.domainExist', array($ppo->domaine->nom));
      }

      if (!empty ($ppo->erreurs)) {

        return _arPPO ($ppo, 'gerer_domaines.tpl');
      }

      // Création
      if ($ppo->domaine->id == '') {

        $domaineDAO->insert ($ppo->domaine);
      }
      // Mise à jour
      else {

        $domaineDAO->update($ppo->domaine);
      }

      $ppo->msgSuccess = CopixI18N::get ('cahierdetextes|cahierdetextes.message.success');

      return _arRedirect (CopixUrl::get ('cahierdetextes||gererDomaines', array('cahierId' => $ppo->cahierId, 'jour' => $ppo->jour, 'mois' => $ppo->mois, 'annee' => $ppo->annee, 'msgSuccess' => $ppo->msgSuccess)));
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'gerer_domaines.tpl');
    }

    /**
     * Suppression d'un domaine - * Enseignant *
     */
    public function processSupprimerDomaine ()
    {
      $ppo = new CopixPPO ();
      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');

      if (is_null($ppo->cahierId = _request('cahierId', null)) || !$domaine = $domaineDAO->get (_request('domaineId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des paramètres
      $ppo->jour  = _request ('jour', date('d'));
      $ppo->mois  = _request ('mois', date('m'));
      $ppo->annee = _request ('annee', date('Y'));

    $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
    if (count($travailDAO->findByDomaine($domaine->id)) > 0) {

      $ppo->erreurs = array ();
      $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.domainNotEmpty');
    } else {

      $domaineDAO->delete ($domaine->id);
      $ppo->msgSuccess = CopixI18N::get ('cahierdetextes|cahierdetextes.message.success');
    }

    return _arPPO ($ppo, 'gerer_domaines.tpl');
    }

    /**
     * Edition d'un travail - * Enseignant *
     */
    public function processEditerTravail ()
    {
      $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId = _request('cahierId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des paramètres
      $ppo->jour        = _request ('jour', date('d'));
      $ppo->mois        = _request ('mois', date('m'));
      $ppo->annee       = _request ('annee', date('Y'));
      $ppo->msgSuccess  = _request ('msgSuccess', false);
      $ppo->vue         = _request ('vue', null);

      $ppo->dateSelectionnee  = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $cahierInfos            = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->nodeInfos         = array('type' => $cahierInfos[0]->module_type, 'id' => $cahierInfos[0]->module_id);

      $ppo->format            = CopixConfig::get('cahierdetextes|format_par_defaut');
      $ppo->nombreMaxVueRadio = CopixConfig::get('cahierdetextes|nombre_max_vue_radio');

      // Récupération des domaines disponibles
      $ppo->idsDomaine  = array();
      $ppo->nomsDomaine = array();

      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      $domaines   = $domaineDAO->findByClasse($cahierInfos[0]->node_id);
      if (count($domaines) > $ppo->nombreMaxVueRadio) {

        $ppo->idsDomaine[] = '';
        $ppo->nomsDomaine[] = '';
      }

      foreach($domaines as $domaine) {

        $ppo->idsDomaine[]  = $domaine->id;
        $ppo->nomsDomaine[] = $domaine->nom;
      }

      $fichierMalleDAO        = _ioDAO('malle|malle_files');
    $fichierClasseurDAO     = _ioDAO('classeur|classeurfichier');

      // Mode edition ?
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if (is_null($travailId = _request('travailId', null))) {

        $ppo->travail = _record ('cahierdetextes|cahierdetextestravail');

        $ppo->travail->a_faire    = _request ('a_faire', 0);
        $ppo->travail->domaine_id = _request ('domaineId', null);

        // Travail à faire
        //  - Par défaut : date de réalisation = date de création + 1
        //  - Vue liste : date de réalisation = date sélectionnée / date de création = date du jour
        if ($ppo->travail->a_faire) {

          $dateJour = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
          if ($ppo->vue == 'liste' && ($ppo->dateSelectionnee > $dateJour)) {

            $ppo->travail->date_creation = date('d/m/Y');
            $ppo->travail->date_realisation = date('d/m/Y', $ppo->dateSelectionnee);
          } else {

            $jourSuivant = strtotime("+1 day", $ppo->dateSelectionnee);
            $ppo->travail->date_realisation = date('d/m/Y', $jourSuivant);
          }
        }
      } else {

        $ppo->travail = $travailDAO->get($travailId);
        $ppo->travail->date_creation = CopixDateTime::yyyymmddToDate($ppo->travail->date_creation);
        $ppo->travail->date_realisation = CopixDateTime::yyyymmddToDate($ppo->travail->date_realisation);

        // Récupération des élèves
        $travail2eleveDAO   = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        $elevesSelectionnes = $travail2eleveDAO->findElevesParTravail ($ppo->travail->id);
        $ppo->elevesSelectionnes = array();
        foreach($elevesSelectionnes as $eleve) {

          $ppo->elevesSelectionnes[] = $eleve->idEleve;
        }

        // Récupération des fichiers
        $travail2fichiersDAO    = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
        $travail2fichiers       = $travail2fichiersDAO->retrieveByTravail ($ppo->travail->id);
        $ppo->fichiers          = array();
        $fichiers               = array();
      foreach($travail2fichiers as $travail2fichier) {

        if ($travail2fichier->module_file == 'MOD_MALLE') {

          if ($fichier = $fichierMalleDAO->get($travail2fichier->file_id)) {

            $ppo->fichiers[] = array('type' => $travail2fichier->module_file, 'id' => $travail2fichier->file_id, 'nom' => $fichier->nom);
          }
        } elseif ($travail2fichier->module_file == 'MOD_CLASSEUR') {

          if ($fichier = $fichierClasseurDAO->get ($travail2fichier->file_id)) {

            $ppo->fichiers[] = array('type' => $travail2fichier->module_file, 'id' => $travail2fichier->file_id, 'nom' => $fichier);
          }
        }
      }
      }

      if (CopixRequest::isMethod ('post')) {

      $ppo->travail->domaine_id        = _request ('travail_domaine_id', null);
      $ppo->travail->a_faire           = _request ('a_faire', 0);
      $ppo->travail->date_creation     = CopixDateTime::dateToyyyymmdd(_request ('travail_date_creation', null));
      $ppo->travail->date_realisation  = CopixDateTime::dateToyyyymmdd(_request ('travail_date_realisation', null));
      $ppo->travail->description       = _request ('travail_description', null);
      $ppo->travail->a_rendre          = _request ('travail_a_rendre', 0);
      $ppo->travail->supprime          = 0;
      $ppo->elevesSelectionnes         = _request ('eleves', array());
      $ppo->fichiers                   = _request ('travail_fichiers', array());
      $ppo->travail_redirection        = _request ('travail_redirection', null);

      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->travail->date_creation == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noCreationDate');
      }
      if ($ppo->travail->a_faire && $ppo->travail->date_realisation == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noRealisationDate');
      }
      if (!is_null($ppo->travail->date_realisation) && ($ppo->travail->date_realisation < $ppo->travail->date_creation)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongRealisationDate');
      }
      if ($ppo->travail->domaine_id == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noDomain');
      }
      if ($ppo->travail->description == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noDescription');
      }
      if (empty($ppo->elevesSelectionnes)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noStudents');
      }
      if (!empty($ppo->fichiers)) {

        $ppo->fichiers = array_unique($ppo->fichiers);

        // Récupération de l'identifiant de la malle du node
        $mods = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
        if ($malle = Kernel::filterModuleList ($mods, 'MOD_MALLE')) {

          $malleId = $malle[0]->module_id;
        }

        // Récupération des identifiants de classeur
        $classeurIds = array();

        // Classeur du node
        $mods = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
        if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {

          $classeurIds[] = $classeur[0]->module_id;
        }
        // Classeur personnel
        $mods = Kernel::getModEnabled (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {

          $classeurIds[] = $classeur[0]->module_id;
        }

        // On détermine s'il s'agit de documents de la malle ou du classeur
        foreach ($ppo->fichiers as $fichierInfos) {

          $fichierInfos = explode('-', $fichierInfos);
          if ($fichierInfos[0] == 'MOD_MALLE') {

            // Erreur : le fichier n'appartient pas à la malle du node
            if (!$fichierMalleDAO->isFileOfMalle($fichierInfos[1], $malleId)) {

              $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
              break;
            } else {

              $fichier = $fichierMalleDAO->get ($fichierInfos[1]);
              $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier->nom);
            }
          } elseif ($fichierInfos[0] == 'MOD_CLASSEUR') {

            $fichier = $fichierClasseurDAO->get ($fichierInfos[1]);

            // Erreur : le fichier n'appartient pas aux classeurs disponible à l'utilisateur
            if (!in_array($fichier->classeur_id, $classeurIds)) {

              $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
              break;
            } else {

              $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier);
            }
          }
        }
      }

      if (!empty ($ppo->erreurs)) {

        $ppo->travail->date_creation     = _request ('travail_date_creation', null);
        $ppo->travail->date_realisation  = _request ('travail_date_realisation', null);

        if (isset($fichiers)) {

          $ppo->fichiers = $fichiers;
        }

        $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, 'editer_travail.tpl');
      }

      // Récupération de la liste des modules activés
      $mods   = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);

      // Création du classeur pour permettre aux élèves de rendre leur travail (si mod activé)
        if ($ppo->travail->a_rendre) {

          $dossierDAO = _ioDAO ('classeur|classeurdossier');
          if (is_null ($ppo->travail->dossier_id) || !$dossierDAO->get ($ppo->travail->dossier_id)) {

            $classeur = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR');
            if ($classeur) {

            // Récupération du casier de la classe
            $casier = $dossierDAO->getCasier($classeur[0]->module_id);
            if ($casier) {

              _classInclude('classeur|classeurService');
              $dossier = _record ('classeur|classeurdossier');

              $dossier->nb_dossiers     = 0;
              $dossier->nb_fichiers     = 0;
              $dossier->taille          = 0;
              $dossier->classeur_id     = $classeur[0]->module_id;
              $dossier->parent_id       = $casier->id;
              $dossier->cle             = classeurService::createKey ();
              $dossier->date_creation   = date('Y-m-d H:i:s');
              $dossier->user_type       = _currentUser()->getExtra('type');
              $dossier->user_id         = _currentUser()->getExtra('id');
              $dossier->casier          = 1;

              // Le nom du casier pour un travail à rendre doit être unique
              $dossier->nom = CopixDateTime::yyyymmddToDate ($ppo->travail->date_realisation).' '.$ppo->nomsDomaine[array_search ($ppo->travail->domaine_id, $ppo->idsDomaine)];

              $cpt = '';
              $nomDossier = $dossier->nom;
              while (count($dossierDAO->getByNom ($dossier->nom)) > 0) {

                if ($cpt == '') {

                  $cpt = 2;
                } else {

                  $cpt++;
                }

                $dossier->nom = $nomDossier.' '.$cpt;
              }

              $dossierDAO->insert ($dossier);

              $ppo->travail->dossier_id = $dossier->id;
            }
            }
          }
        }

      // Création
      if ($ppo->travail->id == '') {

        // Insertion de l'enregistrement "travail"
        $travailDAO->insert ($ppo->travail);
      }
      // Mise à jour
      else {

        // Mise à jour de l'enregistrement "travail"
        $travailDAO->update($ppo->travail);

        // Suppression des relations travail - eleves existantes
        $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        $travail2eleveDAO->deleteByTravail($ppo->travail->id);

        // Suppression des relations travail - fichiers existants
        $travail2fichierDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
        $travail2fichierDAO->deleteByTravail($ppo->travail->id);
      }

      // Insertion des liens "travail > eleve"
      $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
      foreach($ppo->elevesSelectionnes as $eleveId) {

        $travail2eleve = _record ('cahierdetextes|cahierdetextestravail2eleve');

        $travail2eleve->travail_id   = $ppo->travail->id;
        $travail2eleve->eleve_id     = $eleveId;

        $travail2eleveDAO->insert($travail2eleve);
      }

      // Insertion des liens "travail > fichiers"
      $travail2fichierDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
      if (!empty($fichiers)) {

        foreach($fichiers as $fichier) {

          $travail2fichier = _record ('cahierdetextes|cahierdetextestravail2files');

          $travail2fichier->travail_id  = $ppo->travail->id;
          $travail2fichier->module_file = $fichier['type'];
          $travail2fichier->file_id     = $fichier['id'];

          $travail2fichierDAO->insert($travail2fichier);
        }
      }

      // Insertion de l'événement dans l'agenda (si mod activé)
        $agenda = Kernel::filterModuleList ($mods, 'MOD_AGENDA');
        if ($agenda) {

          $agendaWorkDAO = _ioDAO ('agenda|work');

        if (!$agendaWorkDAO->get($ppo->travail->id, $agenda[0]->module_id)) {

          $agenda2work = _record ('agenda|work');

          $agenda2work->travail_id  = $ppo->travail->id;
          $agenda2work->agenda_id   = $agenda[0]->module_id;

          $agendaWorkDAO->insert ($agenda2work);
        }
        }

      $ppo->msgSuccess = CopixI18N::get ('cahierdetextes|cahierdetextes.message.success');

      // Redirection
      switch ($ppo->travail_redirection) {
        case 0:
          switch ($ppo->vue) {
            case "jour":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess)));
              break;
            case "liste":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirListeTravaux', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess)));
              break;
            case "domaine":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravauxParDomaine', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess)));
              break;
            default:
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess)));
              break;
          }
          break;
        case 1:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess, 'vue' => $ppo->vue)));
          break;
        case 2:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess, 'vue' => $ppo->vue, 'a_faire' => 1)));
          break;
        default:
          return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'msgSuccess' => $ppo->msgSuccess, 'vue' => $ppo->vue)));
          break;
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'editer_travail.tpl');
    }

    /**
     * Suppression d'un travail - * Enseignant *
     */
    public function processSupprimerTravail ()
    {
      $ppo = new CopixPPO ();
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');

      if (is_null($cahierId = _request('cahierId', null)) || !$travail = $travailDAO->get (_request('travailId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

    // Suppression des relations travail - eleves existantes
    $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
    $travail2eleveDAO->deleteByTravail($travail->id);

    // Suppression des relations travail - fichiers existantes
    $travail2fichierDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
    $travail2fichierDAO->deleteByTravail($travail->id);

    // Suppression du lien agenda
    $agendaWorkDAO = _ioDAO('agenda|work');
    $agendaWorkDAO->deleteByTravail($travail->id);

    // Suppression du travail
    $travailDAO->delete($travail->id);

    // Redirection
    $vue = _request('vue', null);
    switch($vue) {
      case "jour":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
        break;
      case "liste":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirListeTravaux', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
        break;
      case "domaine":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravauxParDomaine', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
        break;
      default:
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
        break;
    }
    }

    /**
     * Affichage des memos
     */
    public function processVoirMemos ()
    {
      $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId  = _request ('cahierId'))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

    // Récupération des paramètres
    $ppo->jour        = _request ('jour', date('d'));
      $ppo->mois        = _request ('mois', date('m'));
      $ppo->annee       = _request ('annee', date('Y'));
      $ppo->msgSuccess  = _request ('msgSuccess', false);
      $ppo->eleve       = _request ('eleve', null);

      $time                   = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
    $cahierInfos            = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->estAdmin          = $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH ? true : false;
    $ppo->nodeId            = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;
    $ppo->nodeType          = isset($cahierInfos[0]) ? $cahierInfos[0]->node_type : null;

      // Récupération des mémos suivant le type de l'utilisateur courant
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
      if ($ppo->estAdmin) {

        $memos = $memoDAO->findByClasse($ppo->nodeId);
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {

        $memos = $memoDAO->findByEleve($ppo->eleve);
      } else {

        $memos = $memoDAO->findByEleve(_currentUser()->getExtra('id'));
      }

    // Pager
    if (count($memos) > CopixConfig::get('cahierdetextes|nombre_max_memos')) {

      require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

      $params = array(
        'perPage'    => intval(CopixConfig::get('cahierdetextes|nombre_max_memos')),
        'delta'      => 5,
        'recordSet'  => $memos,
        'template'   => '|pager.tpl'
      );

      $pager = CopixPager::Load($params);
      $ppo->pager = $pager->GetMultipage();
      $ppo->memos = $pager->data;
    } else {

      $ppo->memos = $memos;
    }

      if (CopixRequest::isMethod ('post')) {

        $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
        if (is_null($memo2eleve = $memo2eleveDAO->get(_request('memoId', null), $ppo->eleve))
          || !Kernel::isParentOfEleve($ppo->eleve)) {

          return CopixActionGroup::process ('generictools|Messages::getError',
                array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
        }

      $memo2eleve->signe_le    = date('Ymd');
      $memo2eleve->commentaire = _request('commentaire', null);

      $memo2eleveDAO->update($memo2eleve);

      if ($ppo->niveauUtilisateur == PROFILE_CCV_READ) {

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $ppo->cahierId, 'eleve' => $ppo->eleve)));
      } else {

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $ppo->cahierId)));
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'voir_memos.tpl');
    }

  /**
     * Edition d'un mémo - * Enseignant *
     */
    public function processEditerMemo ()
    {
    $ppo = new CopixPPO ();

      if (is_null($ppo->cahierId = _request('cahierId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des paramètres
      $ppo->jour        = _request ('jour', date('d'));
      $ppo->mois        = _request ('mois', date('m'));
      $ppo->annee       = _request ('annee', date('Y'));
      $ppo->msgSuccess  = _request ('msgSuccess', false);

      $ppo->dateSelectionnee  = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
      $ppo->format            = CopixConfig::get('cahierdetextes|format_par_defaut');
      $cahierInfos            = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->nodeInfos         = array('type' => $cahierInfos[0]->module_type, 'id' => $cahierInfos[0]->module_id);

      if (is_null($memoId = _request('memoId', null))) {

        $ppo->memo = _record ('cahierdetextes|cahierdetextesmemo');
      } else {

        $memoDAO    = _ioDAO ('cahierdetextes|cahierdetextesmemo');
        $ppo->memo  = $memoDAO->get($memoId);

        $ppo->memo->date_creation      = CopixDateTime::yyyymmddToDate($ppo->memo->date_creation);
        $ppo->memo->date_validite      = CopixDateTime::yyyymmddToDate($ppo->memo->date_validite);
        $ppo->memo->date_max_signature = CopixDateTime::yyyymmddToDate($ppo->memo->date_max_signature);

        // Récupération des élèves liés au mémo
        $memo2eleveDAO            = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
        $ppo->elevesSelectionnes  = $memo2eleveDAO->findElevesParMemo ($ppo->memo->id);

        // Récupération des fichiers liés au mémo
        $fichierMalleDAO     = _ioDAO('malle|malle_files');
      $fichierClasseurDAO  = _ioDAO('classeur|classeurfichier');

        $memo2fichiersDAO    = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
        $memo2fichiers       = $memo2fichiersDAO->retrieveByMemo ($ppo->memo->id);
        $ppo->fichiers       = array();
        $fichiers            = array();
      foreach($memo2fichiers as $memo2fichier) {

        if ($memo2fichier->module_file == 'MOD_MALLE') {

          if ($fichier = $fichierMalleDAO->get($memo2fichier->file_id)) {

            $ppo->fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier->nom);
          }
        } elseif ($memo2fichier->module_file == 'MOD_CLASSEUR') {

          if ($fichier = $fichierClasseurDAO->get ($memo2fichier->file_id)) {

            $ppo->fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier);
          }
        }
      }
      }

      if (CopixRequest::isMethod ('post')) {

        $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);

        $ppo->memo->classe_id           = $cahierInfos[0]->node_id;
      $ppo->memo->date_creation       = CopixDateTime::dateToyyyymmdd(_request ('memo_date_creation', null));
      $ppo->memo->date_validite       = CopixDateTime::dateToyyyymmdd(_request ('memo_date_validite', null));
      $ppo->memo->message             = _request ('memo_message', null);
      $ppo->memo->avec_signature      = _request ('memo_avec_signature', 0);
      $ppo->memo->date_max_signature  = CopixDateTime::dateToyyyymmdd(_request ('memo_date_max_signature', null));
      $ppo->memo->supprime            = 0;
      $ppo->elevesSelectionnes        = _request ('eleves', array());
      $ppo->fichiers                  = _request ('memo_fichiers', array());

      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->memo->date_creation == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noCreationDate');
      }
      if ($ppo->memo->date_validite == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noValidityDate');
      }
      if (!is_null($ppo->memo->date_validite)
        && ($ppo->memo->date_validite < $ppo->memo->date_creation)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongValidityDate');
      }
      if ($ppo->memo->message == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noContent');
      }
      if ($ppo->memo->avec_signature && $ppo->memo->date_max_signature == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noSignatureDate');
      }
      if ((!is_null($ppo->memo->date_max_signature) && !is_null($ppo->memo->date_validite))
        && ($ppo->memo->date_max_signature > $ppo->memo->date_validite || $ppo->memo->date_max_signature < $ppo->memo->date_creation)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongMaxSignatureDate');
      }
      if (empty($ppo->elevesSelectionnes)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noStudents');
      }
      if (!empty($ppo->fichiers)) {

        $ppo->fichiers = array_unique($ppo->fichiers);

        // Récupération de l'identifiant de la malle du node
        $mods = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
        if ($malle = Kernel::filterModuleList ($mods, 'MOD_MALLE')) {

          $malleId = $malle[0]->module_id;
        }

        // Récupération des identifiants de classeur
        $classeurIds = array();

        // Classeur du node
        $mods = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
        if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {

          $classeurIds[] = $classeur[0]->module_id;
        }
        // Classeur personnel
        $mods = Kernel::getModEnabled (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {

          $classeurIds[] = $classeur[0]->module_id;
        }

        // On détermine s'il s'agit de documents de la malle ou du classeur
        foreach ($ppo->fichiers as $fichierInfos) {

          $fichierInfos = explode('-', $fichierInfos);
          if ($fichierInfos[0] == 'MOD_MALLE') {

            // Erreur : le fichier n'appartient pas à la malle du node
            if (!$fichierMalleDAO->isFileOfMalle($fichierInfos[1], $malleId)) {

              $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
              break;
            } else {

              $fichier = $fichierMalleDAO->get ($fichierInfos[1]);
              $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier->nom);
            }
          } elseif ($fichierInfos[0] == 'MOD_CLASSEUR') {

            $fichier = $fichierClasseurDAO->get ($fichierInfos[1]);

            // Erreur : le fichier n'appartient pas aux classeurs disponible à l'utilisateur
            if (!in_array($fichier->classeur_id, $classeurIds)) {

              $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
              break;
            } else {

              $fichiers[] = array('type' => $fichierInfos[0], 'id' => $fichierInfos[1], 'nom' => $fichier);
            }
          }
        }
      }

      if (!empty ($ppo->erreurs)) {

        $ppo->memo->date_creation       = _request ('memo_date_creation', null);
        $ppo->memo->date_validite       = _request ('memo_date_validite', null);
        $ppo->memo->date_max_signature  = _request ('memo_date_max_signature', null);

        if (isset($fichiers)) {

          $ppo->fichiers = $fichiers;
        }

        $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, 'editer_memo.tpl');
      }

      $memoDAO          = _ioDAO ('cahierdetextes|cahierdetextesmemo');
      $memo2eleveDAO    = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
      $memo2fichierDAO  = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');

      // Création
      if ($ppo->memo->id == '') {

        // Insertion de l'enregistrement "memo"
        $memoDAO->insert ($ppo->memo);
      }
      // Mise à jour
      else {

        // Mise à jour de l'enregistrement "memo"
        $memoDAO->update($ppo->memo);

        // Suppression des relations memo - eleves existantes
        $memo2eleveDAO->deleteByMemo($ppo->memo->id);

        // Suppression des relations memo - fichiers existantes
        $memo2fichierDAO->deleteByMemo($ppo->memo->id);
      }

      // Insertion des nouveaux liens memo > eleve
      foreach($ppo->elevesSelectionnes as $eleveId) {

        $memo2eleve = _record ('cahierdetextes|cahierdetextesmemo2eleve');

        $memo2eleve->memo_id   = $ppo->memo->id;
        $memo2eleve->eleve_id  = $eleveId;

        $memo2eleveDAO->insert($memo2eleve);
      }

      // Insertion des liens "mémo > fichiers"
      if (!empty($fichiers)) {

        foreach($fichiers as $fichier) {

          $memo2fichier = _record ('cahierdetextes|cahierdetextesmemo2files');

          $memo2fichier->memo_id      = $ppo->memo->id;
          $memo2fichier->module_file  = $fichier['type'];
          $memo2fichier->file_id      = $fichier['id'];

          $memo2fichierDAO->insert($memo2fichier);
        }
      }

      return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $ppo->cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
      }

      $modParentInfo = Kernel::getModParentInfo('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, 'editer_memo.tpl');
  }

  /**
     * Suppression d'un mémo - * Enseignant *
     */
    public function processSupprimerMemo ()
    {
      $ppo = new CopixPPO ();
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');

      if (is_null($cahierId = _request('cahierId', null)) || !$memo = $memoDAO->get (_request('memoId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

    // Suppression des relations mémo - eleves existantes
    $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
    $memo2eleveDAO->deleteByMemo($memo->id);

    // Suppression des relations mémo - fichiers existantes
    $memo2fichierDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
    $memo2fichierDAO->deleteByMemo($memo->id);

    // Suppression du mémos
    $memoDAO->delete($memo->id);

    return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $cahierId, 'msgSuccess' => CopixI18N::get ('cahierdetextes|cahierdetextes.message.success'))));
    }

    /**
     * Affichage pour impression d'un mémo - * Enseignant *
     */
    public function processImprMemo ()
    {
      $ppo = new CopixPPO ();
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');

      if (is_null($cahierId = _request('cahierId', null)) || !$ppo->memo = $memoDAO->get (_request('memoId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des paramètres
      $ppo->cahierId  = _request ('cahierId', null);
      $ppo->jour      = _request ('jour', date('d'));
      $ppo->mois      = _request ('mois', date('m'));
      $ppo->annee     = _request ('annee', date('Y'));

      // Récupération du nombre d'exemplaires nécessaires (nombre d'élèves concernés)
      $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
      $ppo->count    = $memo2eleveDAO->retrieveNombreElevesConcernesParMemo($ppo->memo->id);

      return _arPPO ($ppo, 'impr_memo.tpl');
    }

    /**
     * Affichage du suivi d'un mémo (élèves concernés & signatures) - * Enseignant *
     */
    public function processSuiviMemo ()
    {
      $ppo = new CopixPPO ();
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');

      if (is_null($cahierId = _request('cahierId', null)) || !$ppo->memo = $memoDAO->get (_request('memoId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
      }

      // Récupération des élèves liés au mémo
      $memo2eleveDAO  = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
      $ppo->suivis    = $memo2eleveDAO->findSuiviElevesParMemo($ppo->memo->id);

      return _arPPO ($ppo, array ('template' => 'suivi_memo.tpl', 'mainTemplate' => 'main|main_fancy.php'));
    }

  public function go ()
  {
    $myNode = CopixSession::get('myNode');
    if (!is_null($id = _request ('id', null))) {

      if ($myNode['type'] == 'USER_ELE') {

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $id, 'eleve' => $myNode['id'])));
      } else {

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $id)));
      }
    }
    }
}