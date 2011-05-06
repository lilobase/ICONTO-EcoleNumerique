<?php

class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction () {
    
		CopixHTMLHeader::addCSSLink (_resource ('styles/module_cahier.css'));
	}
	
	/**
	 * Action par défaut => redirige vers showWorks
	 */
	public function processDefault () {

	  return _arRedirect (CopixUrl::get ('cahier||showWorks'));
	}
	
	/**
	 * Affichage principal
	 */
	public function processShowWorks () {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération ID node (ele_id => user_ele / user_res ou class_id => user_ens)
	  if (is_null($ppo->nid  = _request ('nid'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }

    // Récupération des paramètres
    $ppo->day   = _request ('day', date('d'));
  	$ppo->month = _request ('month', date('m'));
  	$ppo->year  = _request ('year', date('Y'));
    
    $ppo->title = 'DATE JOUR';
    $ppo->userType = _currentUser()->getExtra('type');    
	  
	  return _arPPO ($ppo, 'show_works.tpl');
	}
	
	/**
	 * Création d'un domaine - * Enseignant *
	 */
	public function processManageDomains () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  // ACTION PROTEGEE : l'utilisateur doit être enseignant de la classe
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }

    // Récupération des paramètres
    $domainName   = _request ('nom', null);
    $ppo->save    = null;
    
    // Si formulaire soumis
    if (!is_null($domainName)) {
      
      $ppo->domain = _record ('cahier|cahier_domaine');
      
      $ppo->domain->nom       = trim($domainName);
      $ppo->domain->classe_id = $ppo->nid;
      
      // Traitement des erreurs
      $ppo->errors = array ();

      $domainDAO = _ioDAO ('cahier|cahier_domaine');
      if ($domainDAO->getByName($ppo->domain->nom)) {

        $ppo->errors = CopixI18N::get ('cahier.error.domainexist', array($ppo->domain->nom));
      }
      else {
        
        $domainDAO->insert ($ppo->domain);
        $ppo->save = true; 
      }
    } 
	  
	  return _arPPO ($ppo, 'manage_domains.tpl');
	}
	
	public function processUpdateDomain () {
	  
	  
	}
	
	public function processDeleteDomain () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null)) || is_null($domainId = _request('domain_id', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }
	  
	  $domainDAO = _ioDAO ('cahier|cahier_domaine');
    if ($domain = $domainDAO->get ($domainId)) {
      
      $domainDAO->delete ($domain->id);
    }
	  
	  return _arRedirect (CopixUrl::get ('cahier||manageDomains', array('nid' => $ppo->nid)));
	}
	
	/**
	 * Création d'un travail - * Enseignant *
	 */
	public function processAddWork () {
	  
	  $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }   
	  
	  // Récupération des paramètres
	  $ppo->day   = _request ('day', date('d'));
  	$ppo->month = _request ('month', date('m'));
  	$ppo->year  = _request ('year', date('Y'));
  	$ppo->todo  = _request('todo', null);
  	
  	$ppo->selectedDate = mktime(0, 0, 0, $ppo->month, $ppo->day, $ppo->year);
  	
  	// Travail à faire, par défaut date de réalisation = date de création + 1
  	if ($ppo->todo) {
  	  $nextDay = strtotime("+1 day", $ppo->selectedDate);
  	  $ppo->work->date_realisation = date('d/m/Y', $nextDay);
  	}
	  
	  $ppo->format           = CopixConfig::get('cahier|default_format');
	  $ppo->domainsStyleView = CopixConfig::get('cahier|domains_style_view');
	  
	  $domainDAO = _ioDAO ('cahier|cahier_domaine');
	  $domains   = $domainDAO->findByIdClass($ppo->nid);
	  
	  $ppo->domainIds   = array();
	  $ppo->domainNames = array();
	  
	  foreach($domains as $domain) {
	    
	    $ppo->domainIds[]   = $domain->id; 
	    $ppo->domainNames[] = $domain->nom;
	  }
	  
	  return _arPPO ($ppo, 'add_work.tpl');
	}
	
	/**
	 * Création d'un travail - Validation * Enseignant *
	 */
	public function processValidateAddWork () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }
	  
	  // Récupération des paramètres
    $ppo->todo = _request ('todo', 0);
    
    $ppo->work = _record ('cahier|cahier_travail');

    $ppo->work->domaine_id        = _request ('domain_id', null);
    $ppo->work->a_faire           = $ppo->todo;
    $ppo->work->date_creation     = $ppo->todo ? _request ('work_date_from', null) : _request ('work_date', null);
    $ppo->work->date_realisation  = _request ('work_date_to', null);
    $ppo->work->description       = _request ('work_description', null);
    $ppo->work->supprime          = 0;
    $ppo->selectedStudentIds      = _request ('students', array());
    $ppo->work_after              = _request ('work_after', null);
	  
	  // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->work->date_creation) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nocreationdate');
    }
    if ($ppo->todo && !$ppo->work->date_realisation) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.norealisationdate');
    }
    if (!$ppo->work->domaine_id) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nodomain');
    }
    if (!$ppo->work->description) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nodescription');
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->format           = CopixConfig::get('cahier|default_format');
      $ppo->domainsStyleView = CopixConfig::get('cahier|domains_style_view');
      
      $domainDAO = _ioDAO ('cahier|cahier_domaine');
  	  $domains   = $domainDAO->findByIdClass($ppo->nid);

  	  $ppo->domainIds   = array();
  	  $ppo->domainNames = array();

  	  foreach($domains as $domain) {

  	    $ppo->domainIds[]   = $domain->id; 
  	    $ppo->domainNames[] = $domain->nom;
  	  }
  	  
      return _arPPO ($ppo, 'add_work.tpl');
    }
    
    // Insertion de l'enregistrement "travail"
    $workDAO = _ioDAO ('cahier|cahier_travail');
    $workDAO->insert ($ppo->work);
    
    // Insertion des liens "travail > eleve"
    $work2studentDAO = _ioDAO ('cahier|cahier_travail2eleve');
    foreach($ppo->selectedStudentIds as $studentId) {
      
      $work2student = _record ('cahier|cahier_travail2eleve');
      
      $work2student->travail_id   = $ppo->work->id;
      $work2student->eleve_id     = $studentId;
      
      $work2studentDAO->insert($work2student);
    }
    
    switch($ppo->work_after) {
      case 0:
        return _arRedirect (CopixUrl::get ('cahier||showWorks', array('nid' => $ppo->nid)));
        break;
      case 1:
        return _arRedirect (CopixUrl::get ('cahier||addWork', array('nid' => $ppo->nid)));
        break;
      case 2:
        return _arRedirect (CopixUrl::get ('cahier||addWork', array('todo' => 1, 'nid' => $ppo->nid)));
        break;
      default:
        return _arRedirect (CopixUrl::get ('cahier||showWorks', array('nid' => $ppo->nid)));
        break;
    }
  }
  
  /**
	 * Création d'un mémo - * Enseignant *
	 */
	public function processAddMemo () {
    
    $ppo = new CopixPPO ();
	  
	  if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }
	  
	  // Récupération des paramètres
	  $ppo->day   = _request ('day', date('d'));
  	$ppo->month = _request ('month', date('m'));
  	$ppo->year  = _request ('year', date('Y'));
  	
  	$ppo->selectedDate = mktime(0, 0, 0, $ppo->month, $ppo->day, $ppo->year);
  	
  	$ppo->format = CopixConfig::get('cahier|default_format');
  	
  	return _arPPO ($ppo, 'add_memo.tpl');
  }
  
  /**
	 * Création d'un mémo - Validation * Enseignant *
	 */
	public function processValidateAddMemo () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->nid = _request('nid', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('gestionautonome||showWorks')));
	  }
	  elseif (!Kernel::isEnseignantForIdClass($ppo->nid)) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('cahier||showWorks')));
	  }
    
    // Récupération des paramètres
    $ppo->memo = _record ('cahier|cahier_memo');

    $ppo->memo->classe_id           = $ppo->nid;
    $ppo->memo->date_creation       = _request ('memo_date_from', null);
    $ppo->memo->date_validite       = _request ('memo_date_to', null);
    $ppo->memo->message             = _request ('memo_content', null);
    $ppo->memo->avec_signature      = _request ('memo_with_signature', 0);
    $ppo->memo->date_max_signature  = _request ('memo_date_signature', null);
    $ppo->memo->supprime            = 0;
    $ppo->selectedStudentIds        = _request ('students', array());
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->memo->date_creation) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nocreationdate');
    }
    if (!$ppo->memo->message) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nocontent');
    }
    if ($ppo->memo->avec_signature && !$ppo->memo->date_max_signature) {
      
      $ppo->errors[] = CopixI18N::get ('cahier|cahier.error.nosignaturedate');
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->format = CopixConfig::get('cahier|default_format');
  	  
      return _arPPO ($ppo, 'add_memo.tpl');
    }
    
    // Insertion de l'enregistrement "memo"
    $memoDAO = _ioDAO ('cahier|cahier_memo');
    $memoDAO->insert ($ppo->memo);
    
    // Insertion des liens memo > eleve
    $memo2studentDAO = _ioDAO ('cahier|cahier_memo2eleve');
    foreach($ppo->selectedStudentIds as $studentId) {
      
      $memo2student = _record ('cahier|cahier_memo2eleve');
      
      $memo2student->memo_id   = $ppo->memo->id;
      $memo2student->eleve_id  = $studentId;
      
      $memo2studentDAO->insert($memo2student);
    }
    
    return _arRedirect (CopixUrl::get ('cahier||showWorks', array('nid' => $ppo->nid)));
  }
}