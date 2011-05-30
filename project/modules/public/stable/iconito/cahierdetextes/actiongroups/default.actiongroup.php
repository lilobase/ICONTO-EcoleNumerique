<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/

class ActionGroupDefault extends enicActionGroup {

	public function beforeAction ($actionName) {
	  
    $this->addJs('js/iconito/module_cahierdetextes.js');
	
	  // Contrôle d'accès au module
    if (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId', _request('id', null))) < PROFILE_CCV_READ) {
      
      return CopixActionGroup::process ('genericTools|Messages::getError',
 	     array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
    }
    
    // Le responsable doit disposer de l'ID de l'élève
    if (Kernel::getLevel('MOD_CAHIERDETEXTES', _request ('cahierId', _request('id', null))) == PROFILE_CCV_READ) {
      
      if ($actionName == "processVoirTravaux" && is_null($eleve = _request('eleve', null))) {
        
        $myNode = CopixSession::get('myNode');
        $eleve = $myNode['type'] == "USER_ELE" ? $myNode['id'] : null;
      }
      else {
        
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
	public function processVoirTravaux () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération ID du cahier de textes
	  if (is_null($ppo->cahierId = _request ('cahierId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  
	  $myNode = CopixSession::get('myNode');

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', false);
  	$ppo->eleve   = _request ('eleve', $myNode['type'] == "USER_ELE" ? $myNode['id'] : null);
  	
  	$ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
  	$ppo->dateSelectionnee  = $ppo->annee.$ppo->mois.$ppo->jour;
	  
	  return _arPPO ($ppo, 'voir_travaux.tpl');
	}
	
	/**
	 * Affichage des travaux sous forme de liste - * Enseignant / Responsable *
	 */
	public function processVoirListeTravaux () {
	  
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
	  
	  return _arPPO ($ppo, 'voir_liste_travaux.tpl');
	}
	
	/**
	 * Affichage des travaux avec filtrage par domaine - * Enseignant / Responsable *
	 */
	public function processVoirTravauxParDomaine () {
	  
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
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {
	    
	    $ppo->travaux = $travailDAO->findByEleveDateIntervalleEtDomaineParDomaineEtType($ppo->eleve, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
	  }
	  else {
	    
	    $ppo->travaux = $travailDAO->findByEleveDateIntervalleEtDomaineParDomaineEtType(_currentUser()->getExtra('id'), CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
	  }
	  
	  return _arPPO ($ppo, 'voir_travaux_par_domaine.tpl');
	}
	
	/**
	 * Affichage des élèves concernés par un travail - * Enseignant *
	 */
	public function processVoirConcernesParTravail () {
	  
	  $ppo = new CopixPPO ();
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  
	  if (is_null($cahierId = _request('cahierId', null)) || !$travail = $travailDAO->get (_request('travailId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération des élèves liés au travail
	  $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
	  $ppo->eleves = $travail2eleveDAO->findElevesParTravail($travail->id);

	  return _arPPO ($ppo, array ('template' => 'voir_concernes_par_travail.tpl', 'mainTemplate' => 'main|main_fancy.php'));
	}
	
	/**
	 * Gestion d'un domaine - * Enseignant *
	 */
	public function processGererDomaines () {
	  
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
    $nomDomaine   = _request ('nom', null);
	  $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
    $ppo->success = false;
    
    // Mode edition ?
  	$domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
  	if (is_null($domaineId = _request('domaineId', null))) {

  	  $ppo->domaine = _record ('cahierdetextes|cahierdetextesdomaine');
  	}
  	else {
  	  
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

      $ppo->success = true;
      
      return _arRedirect (CopixUrl::get ('cahierdetextes||gererDomaines', array('cahierId' => $ppo->cahierId, 'jour' => $ppo->jour, 'mois' => $ppo->mois, 'annee' => $ppo->annee, 'success' => $ppo->success)));
    } 
	  
	  return _arPPO ($ppo, 'gerer_domaines.tpl');
	}
	
	/**
	 * Suppression d'un domaine - * Enseignant *
	 */
	public function processSupprimerDomaine () {
	  
	  $ppo = new CopixPPO ();
	  $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
	  
	  if (is_null($ppo->cahierId = _request('cahierId', null)) || !$domaine = $domaineDAO->get (_request('domaineId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {
	    
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
    }
    else {
      
      $domaineDAO->delete ($domaine->id);
      $ppo->success = true;
    }
    
    return _arPPO ($ppo, 'gerer_domaines.tpl');
	}
	
	/**
	 * Edition d'un travail - * Enseignant *
	 */
	public function processEditerTravail () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->cahierId = _request('cahierId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }   
	  
	  // Récupération des paramètres
	  $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	$ppo->vue     = _request ('vue', null);
  	
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
  	    }
  	    else {
  	      
  	      $jourSuivant = strtotime("+1 day", $ppo->dateSelectionnee);
      	  $ppo->travail->date_realisation = date('d/m/Y', $jourSuivant);
  	    }
    	}
  	}
  	else {
  	  
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
  	  $filesDAO               = _ioDAO('malle|malle_files');
  	  $travail2fichiersDAO    = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
  	  $travail2fichiers       = $travail2fichiersDAO->retrieveByTravail ($ppo->travail->id);
  	  $ppo->fichiers          = array();
      foreach($travail2fichiers as $travail2fichier) {

        if ($fichier = $filesDAO->get($travail2fichier->file_id)) {
          
          $ppo->fichiers[$fichier->id] = $fichier->nom;
        }
      }
  	}
  	
	  if (CopixRequest::isMethod ('post')) {
      
      $ppo->travail->domaine_id        = _request ('travail_domaine_id', null);
      $ppo->travail->a_faire           = _request ('a_faire', null);
      $ppo->travail->date_creation     = CopixDateTime::dateToyyyymmdd(_request ('travail_date_creation', null));
      $ppo->travail->date_realisation  = CopixDateTime::dateToyyyymmdd(_request ('travail_date_realisation', null));
      $ppo->travail->description       = _request ('travail_description', null);
      $ppo->travail->supprime          = 0;
      $ppo->elevesSelectionnes         = _request ('eleves', array());
      $ppo->fichiers                   = _request ('travail_fichiers', array());
      $ppo->travail_redirection        = _request ('travail_redirection', null);
      
      // Malle
      $mods   = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
      $malle  = Kernel::filterModuleList ($mods, 'MOD_MALLE');
      if ($malle) {

        $malleId = $malle[0]->module_id;
      }
      
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
        $fichiersDAO = _ioDAO('malle|malle_files');
        foreach($ppo->fichiers as $fichierId) {

          if (!$fichiersDAO->isFileOfMalle($fichierId, $malleId)) {

            $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
            break;
          }
        }
      }

      if (!empty ($ppo->erreurs)) {
        
        $ppo->travail->date_creation     = _request ('travail_date_creation', null);
        $ppo->travail->date_realisation  = _request ('travail_date_realisation', null);

        $fichiersId = $ppo->fichiers;
        $ppo->fichiers = array();
        foreach($fichiersId as $fichierId) {

          if ($fichier = $fichiersDAO->get($fichierId)) {
            
            $ppo->fichiers[$fichierId] = $fichier->nom;
          }
        }
        
        return _arPPO ($ppo, 'editer_travail.tpl');
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
      foreach($ppo->fichiers as $fichierId) {
      
        $travail2fichier = _record ('cahierdetextes|cahierdetextestravail2files');

        $travail2fichier->travail_id  = $ppo->travail->id;
        $travail2fichier->file_id     = $fichierId;

        $travail2fichierDAO->insert($travail2fichier);
      }
      
      // Insertion de l'événement dans l'agenda (si mod activé)
      $mods   = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
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
      
      $ppo->success = true;
      
      // Redirection
      switch($ppo->travail_redirection) {
        case 0:
          switch($ppo->vue) {
            case "jour":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success)));
              break;
            case "liste":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirListeTravaux', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success)));
              break;
            case "domaine":
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravauxParDomaine', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success)));
              break;
            default:
              return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success)));
              break;
          }
          break;
        case 1:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success, 'vue' => $ppo->vue)));
          break;
        case 2:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success, 'vue' => $ppo->vue, 'a_faire' => 1)));
          break;
        default:
          return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $ppo->cahierId, 'success' => $ppo->success, 'vue' => $ppo->vue)));
          break;
      }
    }
	  
	  return _arPPO ($ppo, 'editer_travail.tpl');
	}
	
	/**
	 * Suppression d'un travail - * Enseignant *
	 */
	public function processSupprimerTravail () {
	  
	  $ppo = new CopixPPO ();
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  
	  if (is_null($cahierId = _request('cahierId', null)) || !$travail = $travailDAO->get (_request('travailId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
	    
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
    if ($travail->a_faire) {
      
      $agendaWorkDAO = _ioDAO('agenda|work');
      $agendaWorkDAO->deleteByTravail($travail->id);
    }
    else {
      
      $agendaEventDAO = _ioDAO('agenda|event');
      $agendaEventDAO->delete($travail->event_id);
    }
    
    // Suppression du travail
    $travailDAO->delete($travail->id);

    // Redirection
    $vue = _request('vue', null);
    switch($vue) {
      case "jour":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $cahierId, 'success' => true)));
        break;
      case "liste":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirListeTravaux', array('cahierId' => $cahierId, 'success' => true)));
        break;
      case "domaine":
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravauxParDomaine', array('cahierId' => $cahierId, 'success' => true)));
        break;
      default:
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $cahierId, 'success' => true)));
        break;
    }
	}
	
	/**
	 * Affichage des memos
	 */
	public function processVoirMemos () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->cahierId  = _request ('cahierId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	$ppo->eleve   = _request ('eleve', null);
  	
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
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {
	    
	    $memos = $memoDAO->findByEleve($ppo->eleve);
	  }
	  else {
	    
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
    }
    else {
      
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
      }
      else {
        
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $ppo->cahierId)));
      }
    }
	  
	  return _arPPO ($ppo, 'voir_memos.tpl');
	}
  
  /**
	 * Edition d'un mémo - * Enseignant *
	 */
	public function processEditerMemo () {
    
    $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->cahierId = _request('cahierId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) < PROFILE_CCV_PUBLISH) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération des paramètres
	  $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	
  	$ppo->dateSelectionnee  = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
  	$ppo->format            = CopixConfig::get('cahierdetextes|format_par_defaut');
  	$cahierInfos            = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
	  $ppo->nodeInfos         = array('type' => $cahierInfos[0]->module_type, 'id' => $cahierInfos[0]->module_id);
  	
  	if (is_null($memoId = _request('memoId', null))) {
	  
	    $ppo->memo = _record ('cahierdetextes|cahierdetextesmemo');
	  }
	  else {
	    
	    $memoDAO    = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	    $ppo->memo  = $memoDAO->get($memoId);
	    
	    $ppo->memo->date_creation      = CopixDateTime::yyyymmddToDate($ppo->memo->date_creation);
  	  $ppo->memo->date_validite      = CopixDateTime::yyyymmddToDate($ppo->memo->date_validite);
  	  $ppo->memo->date_max_signature = CopixDateTime::yyyymmddToDate($ppo->memo->date_max_signature);
	    
	    // Récupération des élèves liés au mémo
	    $memo2eleveDAO            = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
  	  $ppo->elevesSelectionnes  = $memo2eleveDAO->findElevesParMemo ($ppo->memo->id);
  	  
  	  // Récupération des fichiers liés au mémo
  	  $filesDAO            = _ioDAO('malle|malle_files');
  	  $memo2fichiersDAO    = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
  	  $memo2fichiers       = $memo2fichiersDAO->retrieveByMemo ($ppo->memo->id);
  	  $ppo->fichiers       = array();
      foreach($memo2fichiers as $memo2fichier) {

        if ($fichier = $filesDAO->get($memo2fichier->file_id)) {
          
          $ppo->fichiers[$fichier->id] = $fichier->nom;
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
      
      // Malle
      $mods   = Kernel::getModEnabled ($cahierInfos[0]->node_type, $cahierInfos[0]->node_id);
      $malle  = Kernel::filterModuleList ($mods, 'MOD_MALLE');
      if ($malle) {

        $malleId = $malle[0]->module_id;
      }
      
      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->memo->date_creation == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noCreationDate');
      }
      if ($ppo->memo->date_validite == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noRealisationDate');
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
        $fichiersDAO = _ioDAO('malle|malle_files');
        foreach($ppo->fichiers as $fichierId) {

          if (!$fichiersDAO->isFileOfMalle($fichierId, $malleId)) {

            $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile');
            break;
          }
        }
      }
      
      if (!empty ($ppo->erreurs)) {
        
        $ppo->memo->date_creation       = _request ('memo_date_creation', null);
        $ppo->memo->date_validite       = _request ('memo_date_validite', null);
        $ppo->memo->date_max_signature  = _request ('memo_date_max_signature', null);
        
        $fichiersId = $ppo->fichiers;
        $ppo->fichiers = array();
        foreach($fichiersId as $fichierId) {

          if ($fichier = $fichiersDAO->get($fichierId)) {
            
            $ppo->fichiers[$fichierId] = $fichier->nom;
          }
        }
        
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
      foreach($ppo->fichiers as $fichierId) {
      
        $memo2fichier = _record ('cahierdetextes|cahierdetextesmemo2files');

        $memo2fichier->memo_id  = $ppo->memo->id;
        $memo2fichier->file_id     = $fichierId;

        $memo2fichierDAO->insert($memo2fichier);
      }

      return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $ppo->cahierId, 'success' => true)));
	  }
  	
  	return _arPPO ($ppo, 'editer_memo.tpl');
  }
  
  /**
	 * Suppression d'un mémo - * Enseignant *
	 */
	public function processSupprimerMemo () {
	  
	  $ppo = new CopixPPO ();
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  
	  if (is_null($cahierId = _request('cahierId', null)) || !$memo = $memoDAO->get (_request('memoId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
	    
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
    
    return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('cahierId' => $cahierId, 'success' => true)));
	}
	
	/**
	 * Affichage pour impression d'un mémo - * Enseignant *
	 */
	public function processImprMemo () {
	  
	  $ppo = new CopixPPO ();
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  
	  if (is_null($cahierId = _request('cahierId', null)) || !$ppo->memo = $memoDAO->get (_request('memoId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération du nombre d'exemplaires nécessaires (nombre d'élèves concernés)
	  $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
	  $ppo->count    = $memo2eleveDAO->retrieveNombreElevesConcernesParMemo($ppo->memo->id);

	  return _arPPO ($ppo, 'impr_memo.tpl');
	}
	
	/**
	 * Affichage du suivi d'un mémo (élèves concernés & signatures) - * Enseignant *
	 */
	public function processSuiviMemo () {
	  
	  $ppo = new CopixPPO ();
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  
	  if (is_null($cahierId = _request('cahierId', null)) || !$ppo->memo = $memoDAO->get (_request('memoId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $cahierId) < PROFILE_CCV_PUBLISH) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération des élèves liés au mémo
	  $memo2eleveDAO  = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
	  $ppo->suivis    = $memo2eleveDAO->findSuiviElevesParMemo($ppo->memo->id);

	  return _arPPO ($ppo, array ('template' => 'suivi_memo.tpl', 'mainTemplate' => 'main|main_fancy.php'));
	}
  
  public function go () {
    
    $myNode = CopixSession::get('myNode'); 
    if (!is_null($id = _request ('id', null))) {
      
      if ($myNode['type'] == 'USER_ELE') {

        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $id, 'eleve' => $myNode['id'])));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('cahierId' => $id)));
      }
    }
	}
}