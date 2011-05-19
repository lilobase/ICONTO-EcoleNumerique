<?php

class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction () {
    
    // Contrôle d'accès au module
    $nid = _request ('nid');
    if (!Kernel::isEnseignantOfClasse($nid)
     && !Kernel::isEleveOfClasse($nid)
     && !Kernel::isParentOfEleve($nid)) {
       
       return CopixActionGroup::process ('genericTools|Messages::getError', 
 	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
     }
    
		CopixHTMLHeader::addCSSLink (_resource ('styles/module_cahierdetextes.css'));
	}
	
	/**
	 * Action par défaut => redirige vers voirTravaux
	 */
	public function processDefault () {

	  return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux'));
	}
	
	/**
	 * Affichage principal
	 */
	public function processVoirTravaux () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération ID node
	  //    * Pour un eleve ou un enseignant, cet ID correspond à l'identifiant de la classe
	  //    * Pour un parent, cet ID correspond à l'identifiant de l'élève
	  if (is_null($ppo->nid  = _request ('nid'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', false);
  	
  	setlocale(LC_TIME, 'fr_FR');
  	$dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
    $ppo->titre = strftime('%A %d %B %Y', $dateSelectionnee);  
	  
	  return _arPPO ($ppo, 'voir_travaux.tpl');
	}
	
	/**
	 * Affichage des travaux sous forme de liste
	 */
	public function processVoirListeTravaux () {
	  
	  $ppo = new CopixPPO ();

	  if (is_null($ppo->nid  = _request ('nid'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::isEleve()) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $ppo->jour     = _request ('jour', date('d'));
  	$ppo->mois     = _request ('mois', date('m'));
  	$ppo->annee    = _request ('annee', date('Y'));
    $ppo->nbJours  = _request ('nb_jours', 10);
  	$ppo->dateDeb  = _request ('date_deb', $ppo->jour.'/'.$ppo->mois.'/'.$ppo->annee);
  	
  	$ppo->choixNbJours    = array(10, 20, 30, 40, 50);
  	$ppo->typeUtilisateur = _currentUser()->getExtra('type');

  	$travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
  	if ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->travaux = $travailDAO->findByEleveDateEtIntervalleParJourEtType($ppo->nid, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->travaux = $travailDAO->findByClasseDateEtIntervalleParJourEtType($ppo->nid, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours);
	  }
	  
	  return _arPPO ($ppo, 'voir_liste_travaux.tpl');
	}
	
	/**
	 * Affichage des travaux avec filtrage par domaine
	 */
	public function processVoirTravauxParDomaine () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid  = _request ('nid'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->nbJours = _request ('nb_jours', 10);
  	$ppo->dateDeb = _request ('date_deb', $ppo->jour.'/'.$ppo->mois.'/'.$ppo->annee);
  	$ppo->domaine = _request ('domaine', null);
  	
  	$ppo->choixNbJours    = array(10, 20, 30, 40, 50);
  	$ppo->typeUtilisateur = _currentUser()->getExtra('type');
  	
  	// Récupération des domaines
  	$domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
	  $domaines   = $domaineDAO->findByClasse($ppo->nid);

	  $ppo->idsDomaine  = array('');
	  $ppo->nomsDomaine = array(CopixI18N::get ('cahierdetextes|cahierdetextes.message.ALL'));
	  
	  foreach($domaines as $domaine) {
	    
	    $ppo->idsDomaine[]  = $domaine->id; 
	    $ppo->nomsDomaine[] = $domaine->nom;
	  }
	  
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
  	if ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->travaux = $travailDAO->findByEleveDateIntervalleEtDomaineParDomaineEtType($ppo->nid, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->travaux = $travailDAO->findByClasseDateIntervalleEtDomaineParDomaineEtType($ppo->nid, CopixDateTime::dateToyyyymmdd($ppo->dateDeb), $ppo->nbJours, $ppo->domaine);
	  }
	  
	  return _arPPO ($ppo, 'voir_travaux_par_domaine.tpl');
	}
	
	public function processVoirConcernesParTravail () {
	  
	  $ppo = new CopixPPO ();
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  
	  if (is_null($nid = _request('nid', null)) || !$travail = $travailDAO->get (_request('travailId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
	  $ppo->eleves = $travail2eleveDAO->findElevesParTravail($travail->id);

	  return _arPPO ($ppo, 'voir_concernes_par_travail.tpl');
	}
	
	/**
	 * Gestion d'un domaine - * Enseignant *
	 */
	public function processGererDomaines () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  // ACTION PROTEGEE : l'utilisateur doit être enseignant de la classe
	  elseif (!Kernel::isEnseignantOfClasse($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $nomDomaine = _request ('nom', null);
	  $ppo->jour  = _request ('jour', date('d'));
  	$ppo->mois  = _request ('mois', date('m'));
  	$ppo->annee = _request ('annee', date('Y'));
    $ppo->success = false;

    if (CopixRequest::isMethod ('post')) {
      
      $ppo->domaine = _record ('cahierdetextes|cahierdetextesdomaine');
      
      $ppo->domaine->nom       = trim($nomDomaine);
      $ppo->domaine->classe_id = $ppo->nid;
      
      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->domaine->nom == '') {
        
        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noName');
      }
      $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      if ($domaineDAO->getByNom($ppo->domaine->nom)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes.error.domainExist', array($ppo->domaine->nom));
      }
      
      if (!empty ($ppo->erreurs)) {

        return _arPPO ($ppo, 'gerer_domaines.tpl');
      }
        
      $domaineDAO->insert ($ppo->domaine);
      $ppo->success = true;
    } 
	  
	  return _arPPO ($ppo, 'gerer_domaines.tpl');
	}
	
	public function processSupprimerDomaine () {
	  
	  $ppo = new CopixPPO ();
	  $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
	  
	  if (is_null($ppo->nid = _request('nid', null)) || !$domaine = $domaineDAO->get (_request('domain_id', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }

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
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }   
	  
	  // Récupération des paramètres
	  $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	
  	$ppo->dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
	  
	  $ppo->format            = CopixConfig::get('cahierdetextes|format_par_defaut');
	  $ppo->nombreMaxVueRadio = CopixConfig::get('cahierdetextes|nombre_max_vue_radio');
	  
	  $domaineDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
	  $domaines   = $domaineDAO->findByClasse($ppo->nid);

	  $ppo->idsDomaine  = array();
	  $ppo->nomsDomaine = array();
	  
	  foreach($domaines as $domaine) {
	    
	    $ppo->idsDomaine[]  = $domaine->id; 
	    $ppo->nomsDomaine[] = $domaine->nom;
	  }
  	
  	// Mode edition ?
  	$travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
  	if (is_null($travailId = _request('travailId', null))) {

  	  $ppo->travail = _record ('cahierdetextes|cahierdetextestravail');
  	  
  	  $ppo->travail->a_faire  = _request ('a_faire', 0);
    	// Travail à faire, par défaut date de réalisation = date de création + 1
    	if ($ppo->travail->a_faire) {
    	  
    	  $jourSuivant = strtotime("+1 day", $ppo->dateSelectionnee);
    	  $ppo->travail->date_realisation = date('d/m/Y', $jourSuivant);
  	  }
  	}
  	else {
  	  
  	  $ppo->travail = $travailDAO->get($travailId);
  	  $ppo->travail->date_creation = CopixDateTime::yyyymmddToDate($ppo->travail->date_creation);
  	  $ppo->travail->date_realisation = CopixDateTime::yyyymmddToDate($ppo->travail->date_realisation);
  	  
  	  $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
  	  $ppo->elevesSelectionnes = $travail2eleveDAO->findElevesParTravail ($ppo->travail->id);
  	}

	  if (CopixRequest::isMethod ('post')) {
      
      $ppo->travail->domaine_id        = _request ('travail_domaine_id', null);
      $ppo->travail->a_faire           = _request ('a_faire', null);
      $ppo->travail->date_creation     = CopixDateTime::dateToyyyymmdd(_request ('travail_date_creation', null));
      $ppo->travail->date_realisation  = CopixDateTime::dateToyyyymmdd(_request ('travail_date_realisation', null));
      $ppo->travail->description       = _request ('travail_description', null);
      $ppo->travail->supprime          = 0;
      $ppo->elevesSelectionnes         = _request ('eleves', array());
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

      if (!empty ($ppo->erreurs)) {
        
        $ppo->travail->date_creation     = _request ('travail_date_creation', null);
        $ppo->travail->date_realisation  = _request ('travail_date_realisation', null);
        
        return _arPPO ($ppo, 'editer_travail.tpl');
      }
      
      // Create
      if ($ppo->travail->id == '') {
        
        // Insertion de l'enregistrement "travail"
        $travailDAO->insert ($ppo->travail);

        // Insertion des liens "travail > eleve"
        $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        foreach($ppo->elevesSelectionnes as $eleveId) {

          $travail2eleve = _record ('cahierdetextes|cahierdetextestravail2eleve');

          $travail2eleve->travail_id   = $ppo->travail->id;
          $travail2eleve->eleve_id     = $eleveId;

          $travail2eleveDAO->insert($travail2eleve);
        }
      }
      // Update
      else {
        
        // Mise à jour de l'enregistrement "travail"
        $travailDAO->update($ppo->travail);
        
        // Suppression des relations travail - eleves existantes
        $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        $travail2eleveDAO->deleteByTravail($ppo->travail->id);
        
        // Insertion des nouveaux liens "travail > eleve"
        foreach($ppo->elevesSelectionnes as $eleveId) {

          $travail2eleve = _record ('cahierdetextes|cahierdetextestravail2eleve');

          $travail2eleve->travail_id   = $ppo->travail->id;
          $travail2eleve->eleve_id     = $eleveId;

          $travail2eleveDAO->insert($travail2eleve);
        }
      }
      
      $ppo->success = true;
      
      // Redirection
      switch($ppo->travail_redirection) {
        case 0:
          return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('nid' => $ppo->nid, 'success' => $ppo->success)));
          break;
        case 1:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('nid' => $ppo->nid, 'success' => $ppo->success)));
          break;
        case 2:
          return _arRedirect (CopixUrl::get ('cahierdetextes||editerTravail', array('nid' => $ppo->nid, 'success' => $ppo->success, 'a_faire' => 1)));
          break;
        default:
          return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('nid' => $ppo->nid, 'success' => $ppo->success)));
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
	  
	  if (is_null($nid = _request('nid', null)) || !$travail = $travailDAO->get (_request('travailId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
    
    // Suppression des relations travail - eleves existantes
    $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
    $travail2eleveDAO->deleteByTravail($travail->id);
    
    // Suppression du travail
    $travailDAO->delete($travail->id);
    
    return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('nid' => $nid)));
	}
	
	/**
	 * Affichage des memos
	 */
	public function processVoirMemos () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid  = _request ('nid'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }

    // Récupération des paramètres
    $ppo->jour    = _request ('jour', date('d'));
  	$ppo->mois    = _request ('mois', date('m'));
  	$ppo->annee   = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	
  	$time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
  	$ppo->typeUtilisateur = _currentUser()->getExtra('type');
	  
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  if ($ppo->typeUtilisateur == 'USER_ELE') {
	    
	    $ppo->memos = $memoDAO->findByEleve(_currentUser()->getExtra('id'));
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->memos = $memoDAO->findByEleve($ppo->nid);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->memos = $memoDAO->findByClasse($ppo->nid);
	  }
	  
	  if (CopixRequest::isMethod ('post')) {
	    
	    $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
	    if (is_null($memo2eleve = $memo2eleveDAO->get(_request('memoId', null), $ppo->nid)) 
	      || !Kernel::isParentOfEleve($ppo->nid)) {
	      
	      return CopixActionGroup::process ('generictools|Messages::getError',
    			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	    }
      
      $memo2eleve->signe_le    = date('Ymd');
      $memo2eleve->commentaire = _request('commentaire', null);
      
      $memo2eleveDAO->update($memo2eleve);
      
      return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('nid' => $ppo->nid)));
    }
	  
	  return _arPPO ($ppo, 'voir_memos.tpl');
	}
  
  /**
	 * Edition d'un mémo - * Enseignant *
	 */
	public function processEditerMemo () {
    
    $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération des paramètres
	  $ppo->jour   = _request ('jour', date('d'));
  	$ppo->mois   = _request ('mois', date('m'));
  	$ppo->annee  = _request ('annee', date('Y'));
  	$ppo->success = _request ('success', null);
  	
  	$ppo->dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
  	$ppo->format = CopixConfig::get('cahierdetextes|format_par_defaut');
  	
  	if (is_null($memoId = _request('memoId', null))) {
	  
	    $ppo->memo = _record ('cahierdetextes|cahierdetextesmemo');
	  }
	  else {
	    
	    $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	    $ppo->memo = $memoDAO->get($memoId);
	    
	    $ppo->memo->date_creation      = CopixDateTime::yyyymmddToDate($ppo->memo->date_creation);
  	  $ppo->memo->date_validite      = CopixDateTime::yyyymmddToDate($ppo->memo->date_validite);
  	  $ppo->memo->date_max_signature = CopixDateTime::yyyymmddToDate($ppo->memo->date_max_signature);
	    
	    $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
  	  $ppo->elevesSelectionnes = $memo2eleveDAO->findElevesParMemo ($ppo->memo->id);
	  }
  	
  	if (CopixRequest::isMethod ('post')) {
	  
	    $ppo->memo->classe_id           = $ppo->nid;
      $ppo->memo->date_creation       = CopixDateTime::dateToyyyymmdd(_request ('memo_date_creation', null));
      $ppo->memo->date_validite       = CopixDateTime::dateToyyyymmdd(_request ('memo_date_validite', null));
      $ppo->memo->message             = _request ('memo_message', null);
      $ppo->memo->avec_signature      = _request ('memo_avec_signature', 0);
      $ppo->memo->date_max_signature  = CopixDateTime::dateToyyyymmdd(_request ('memo_date_max_signature', null));
      $ppo->memo->supprime            = 0;
      $ppo->elevesSelectionnes        = _request ('eleves', array());
      
      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->memo->date_creation == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noCreationDate');
      }
      
      if (!is_null($ppo->memo->date_validite) && ($ppo->memo->date_validite < $ppo->memo->date_creation)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongValidityDate');
      }
      
      if ($ppo->memo->message == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noContent');
      }
      if ($ppo->memo->avec_signature && $ppo->memo->date_max_signature == '') {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.noSignatureDate');
      }
      if (!is_null($ppo->memo->date_max_signature) && ($ppo->memo->date_max_signature < $ppo->memo->date_validite)) {

        $ppo->erreurs[] = CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongMaxSignatureDate');
      } 
      
      if (!empty ($ppo->erreurs)) {
        
        $ppo->memo->date_creation       = _request ('memo_date_creation', null);
        $ppo->memo->date_validite       = _request ('memo_date_validite', null);
        $ppo->memo->date_max_signature  = _request ('memo_date_max_signature', null);
        
        return _arPPO ($ppo, 'editer_memo.tpl');
      }
      
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
      $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
      
      if ($ppo->memo->id == '') {

        // Insertion de l'enregistrement "memo"
        $memoDAO->insert ($ppo->memo);

        // Insertion des liens memo > eleve
        foreach($ppo->elevesSelectionnes as $eleveId) {

          $memo2eleve = _record ('cahierdetextes|cahierdetextesmemo2eleve');

          $memo2eleve->memo_id   = $ppo->memo->id;
          $memo2eleve->eleve_id  = $eleveId;

          $memo2eleveDAO->insert($memo2eleve);
        }
      }
      else {
        
        // Mise à jour de l'enregistrement "memo"
        $memoDAO->update($ppo->memo);
        
        // Suppression des relations memo - eleves existantes
        $memo2eleveDAO->deleteByMemo($ppo->memo->id);
        
        // Insertion des nouveaux liens memo > eleve
        foreach($ppo->elevesSelectionnes as $eleveId) {

          $memo2eleve = _record ('cahierdetextes|cahierdetextesmemo2eleve');

          $memo2eleve->memo_id   = $ppo->memo->id;
          $memo2eleve->eleve_id  = $eleveId;

          $memo2eleveDAO->insert($memo2eleve);
        }
      }

      return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('nid' => $ppo->nid, 'success' => true)));
	  }
  	
  	return _arPPO ($ppo, 'editer_memo.tpl');
  }
  
  /**
	 * Suppression d'un mémo - * Enseignant *
	 */
	public function processSupprimerMemo () {
	  
	  $ppo = new CopixPPO ();
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  
	  if (is_null($nid = _request('nid', null)) || !$memo = $memoDAO->get (_request('memoId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
    
    // Suppression des relations mémos - eleves existantes
    $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
    $memo2eleveDAO->deleteByMemo($memo->id);
    
    // Suppression du mémos
    $memoDAO->delete($memo->id);
    
    return _arRedirect (CopixUrl::get ('cahierdetextes||voirMemos', array('nid' => $nid, 'success' => true)));
	}
	
	public function processSuiviMemo () {
	  
	  $ppo = new CopixPPO ();
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  
	  if (is_null($nid = _request('nid', null)) || !$memo = $memoDAO->get (_request('memoId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (!Kernel::isEnseignantOfClasse($nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('')));
	  }
	  
	  $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
	  $ppo->suivis = $memo2eleveDAO->findSuiviElevesParMemo($memo->id);

	  return _arPPO ($ppo, 'suivi_memo.tpl');
	}
  
  public function go () {

    if (!is_null(_request ('id', null))) {
      
      return _arRedirect (CopixUrl::get ('cahierdetextes||voirTravaux', array('nid' => _request ('id', null))));
    }
	}
}