<?php
/**
* @package  Iconito
* @subpackage Gestionautonome
* @version   $Id: default.actiongroup.php,xxxx $
* @author   xxxxxxx
* @copyright xxxx
* @link     xxxx
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction () {
		
    CopixHTMLHeader::addJSLink (_resource ('js/jquery-1.4.2.min.js'));
    CopixHTMLHeader::addJSLink (_resource ('js/jquery-ui-1.8.custom.min.js'));
    CopixHTMLHeader::addJSLink (_resource ('js/jquery.ui.datepicker-fr.js'));
    CopixHTMLHeader::addCSSLink (_resource ('jquery-ui-theme/jquery-ui-1.8.custom.css'));  
		CopixHTMLHeader::addCSSLink (_resource ('styles/module_gestionautonome.css'));
	}
	
	/**
	 * Action par défaut => redirige vers showTree
	 */
	public function processDefault () {
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree'));
	}
	
	/**
	 * Affichage principal (arbre)
	 */
	public function processShowTree () {

	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = 'Gestion de la structure scolaire';
	  
    // Y a t-il eu des modifications ?
    $ppo->save = _request ('save', null); 
    
    // Sélection de l'onglet courant
    $ppo->tab = _request ('tab', null);
    
    // Récupération de l'année scolaire : si non précisée, récupération de l'année scolaire courante
    if (is_null($ppo->grade = _sessionGet('grade'))) {
      
      $ppo->grade = Kernel::getAnneeScolaireCourante ()->id_as;
    }
    
    // Réaffectation des zones après retour d'action
    $currentInSession = _sessionGet ('current');
    if (!is_null($currentInSession)) {
      
      $ppo->targetId = $currentInSession['node_id'];
      $ppo->targetType = $currentInSession['node_type'];
    }
    
    // Récupération de la liste des années scolaires disponibles pour select
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $grades = $gradesDAO->findAll ();
	  foreach ($grades as $grade) {

	    $ppo->gradesIds[]   = $grade->id_as;
	    $ppo->gradesNames[] = $grade->annee_scolaire;
	  }

		return _arPPO ($ppo, 'show_tree.tpl');
	}
	
	public function processRefreshTree () {
	  
	  $grade = _request ('grade', Kernel::getAnneeScolaireCourante ()->id_as);
	  _sessionSet('grade', $grade);
	  
	  _sessionSet ('current', null);
	  
	  echo CopixZone::process ('gestionautonome|citiesGroup');
	  
	  return _arNone ();
	}
	
	/**
	 * Toggle d'un noeud
	 */
	public function processToggleNode () {

	  $nodeType = _request('node_type');
	  $nodeId = _request('node_id');
	  $showForced = _request('show_forced', false);
	  
	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $this->setNodeStatusInSession ('cities_groups_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|city', array ('cities_group_id' => $nodeId));
	        break;
	      case 'city':
	        $this->setNodeStatusInSession ('cities_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|school', array ('city_id' => $nodeId));
	        break;
	      case 'school':
	        $this->setNodeStatusInSession ('schools_nodes', $nodeId, $showForced);
	        echo CopixZone::process ('gestionautonome|classroom', array ('school_id' => $nodeId));
	        break;
	    }
	  }
	  
	  return _arNone ();
	}
	
  /**
   * Mise en session de l'état de l'arbre
   */ 
	private function setNodeStatusInSession ($type, $nodeId, $showForced) {
	  
	  // Récupération de l'état actuel ou initialisation
    $nodesAr = _sessionGet ($type);
    if (is_null($nodesAr)) {
      
      $nodesAr = array();
    }
      
    if (isset($nodesAr[$nodeId]) && !$showForced) {
      
      unset($nodesAr[$nodeId]);
    }
    else {
      
      $nodesAr[$nodeId] =	$nodeId;
    }
    
    _sessionSet ($type, $nodesAr);
	}
	
	/**
	 * displayPersonsData (Ajax)
	 *
	 * Récupération des personnes du noeud courant.
	 * 
	 */
	public function processDisplayPersonsData () {
	
	  $nodeId   = _request ('node_id', null);
	  $nodeType = _request ('node_type', null);

	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    $typeRef = null;
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $typeRef = 'BU_GRVILLE';
	        break;
	      case 'city':
	        $typeRef = 'BU_VILLE';
	        break;
	      case 'school':
	        $typeRef = 'BU_ECOLE';
	        break;
	      case 'classroom':
	        $typeRef = 'BU_CLASSE';
	    }
	    
		  echo CopixZone::process ('gestionautonome|PersonsData', array ('node_id' => $nodeId, 'node_type' => $typeRef));
    }
    return _arNone ();
	}
	
	/**
	 * updateTreeActions (Ajax)
	 *
	 * Récupération des actions disponibles pour le noeud courant.
	 * 
	 */
	public function processUpdateTreeActions () {

	  $nodeId   = _request ('node_id', null);
	  $nodeType = _request ('node_type', null);

	  if (!is_null($nodeType) && !is_null($nodeId)) {
	    
	    $typeRef = null;
	    switch ($nodeType) {
	      
	      case 'cities-group':
	        $typeRef = 'BU_GRVILLE';
	        break;
	      case 'city':
	        $typeRef = 'BU_VILLE';
	        break;
	      case 'school':
	        $typeRef = 'BU_ECOLE';
	        break;
	      case 'classroom':
	        $typeRef = 'BU_CLASSE';
	    }
	    	    
	    echo CopixZone::process ('gestionautonome|TreeActions', array ('node_id' => $nodeId, 'node_type' => $typeRef));
	    
	    // Stockage de l'état courrant
	    _sessionSet ('current', array('node_type' => $typeRef, 'node_id' => $nodeId));
	  }
		
    return _arNone ();
	}
	
	/**
	 * createCity
	 *
	 * Création d'une ville.
	 * 
	 */
	public function processCreateCity () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->parentId    = _request ('nodeId', null);
	  $ppo->parentType  = _request ('nodeType', null);
	  
	  // La création d'une ville n'est possible qu'à partir d'un groupe de ville
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_GRVILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => 'Création d\'une ville');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'create_city.tpl');
	}
	
	/**
	 * validateCityCreation
	 *
	 * Validation du formulaire de création de ville.
	 * 
	 */
	public function processValidateCityCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->parentId    = _request ('id_parent', null);
	  $ppo->parentType  = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // DAO
	  $cityDAO   = _ioDAO ('kernel|kernel_bu_ville');
	  
    // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => 'Création d\'une ville');

	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs," &raquo; ");    
    
    // Récupération des paramètres
    $cityName  = _request ('nom', null); 
    $ppo->city = _record ('kernel|kernel_bu_ville');

    $ppo->city->nom           = trim ($cityName);
    $ppo->city->canon         = Kernel::createCanon ($cityName);
    $ppo->city->id_grville    = $ppo->parentId;
    $ppo->city->date_creation = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->city->id_grville) {
      
      $ppo->errors[] = 'Une erreur est survenue';
    }
    
    if (!empty ($ppo->errors)) {
      
      return _arPPO ($ppo, 'create_city.tpl');
    }
      
    // Insertion de la ville
    $cityDAO->insert ($ppo->city);
    
    $ppo->nodeId   = $ppo->city->id_vi;
		$ppo->nodeType = 'BU_VILLE';

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
  }
  
  /**
	 * updateCity
	 *
	 * Edition d'une ville.
	 * 
	 */
	public function processUpdateCity () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // DAO
	  $cityDAO   = _ioDAO ('kernel|kernel_bu_ville');
	  
	  // Récupération de la ville
	  $ppo->city = $cityDAO->get ($ppo->nodeId);
	  
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $ppo->city->nom);
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'update_city.tpl');
	}
	
	/**
	 * validateCityUpdate
	 *
	 * Validation du formulaire d'édition de ville.
	 * 
	 */
	public function processValidateCityUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  
	  $ppo->nodeId   = _request ('id_node', null);
		$ppo->nodeType = _request ('type_node', null);
		
	  // Récupération des paramètres
	  if (!$ppo->city = $cityDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $ppo->city->nom);

	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs," &raquo; ");
      
    // Récupération des paramètres
    $cityName = _request ('name', null);
    
    $ppo->city->nom   = trim ($cityName);
    $ppo->city->canon = Kernel::createCanon ($cityName);

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
  	  
      return _arPPO ($ppo, 'update_city.tpl');
    }
      
    $cityDAO->update ($ppo->city);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	/**
	 * deleteVille
	 *
	 * Suppression d'une ville.
	 * 
	 */
	public function processDeleteCity () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  $nodeType = _request ('nodeType', null);
	  
	  // DAO
	  $cityDAO                = _ioDAO ('kernel|kernel_bu_ville');
	  $schoolDAO              = _ioDAO ('kernel|kernel_bu_ecole');
	  $classDAO               = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO          = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO             = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO   = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $studentAdmissionDAO    = _ioDAO ('kernel|kernel_bu_eleve_admission');
	  $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');
	  
	  if ($nodeType != 'BU_VILLE' || !$city = $cityDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	                 
	  // Récupération GRVILLE pour redirect
	  $ppo->nodeId   = $city->id_grville;
	  $ppo->nodeType = 'BU_GRVILLE';
	  
	  // Récupération des écoles de la ville
	  $schools = $schoolDAO->getByCity ($nodeId);
	  
	  foreach ($schools as $school) {

	    // Récupération des classes de la ville
	    $classes = $classDAO->getBySchool ($school->numero);
	    
	    foreach ($classes as $class) {

	      // Récupération des associations classe-niveau
	      $classLevels = $classLevelDAO->getByClass ($class->id);
	      
	      foreach ($classLevels as $classLevel) {
	        
	        // Suppression des associations classe-niveau
  	      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
	      }
	      
	      // Récupération des élèves de la classe
	      $students = $studentDAO->getElevesInClasse ($class->id);
        foreach ($students as $student) {

          // Récupération de l'affectation de l'élève à la classe
          if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->id, $class->id)) {

            $studentAssignmentDAO->delete ($studentAssignment->affect_id);
          }

          // Récupérations des admission de l'élève
      	  if ($admission = $studentAdmissionDAO->getByStudentAndSchool ($student->id, $school->numero)) {

      	    $studentAdmissionDAO->delete ($admission->admission_numero);  
      	  }
      	  
      	  // Récupérations des inscriptions de l'élève
      	  if ($registration = $studentRegistrationDAO->getByStudentAndSchool ($student->id, $school->numero)) {

      	    $studentRegistrationDAO->delete ($registration->numero);  
      	  }
        }
	      
	      // Suppression de la classe
	      $classDAO->delete ($class->id);
	    }
	    
	    // Suppression de l'école
	    $schoolDAO->delete ($school->numero);
	  }
	  
	  // Suppression de la ville 
  	$cityDAO->delete ($city->id_vi);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	/**
	 * createEcole
	 *
	 * Création d'une école.
	 * 
	 */
	public function processCreateSchool () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->parentId    = _request ('parentId', null);
	  $ppo->parentType  = _request ('parentType', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_VILLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
	  
	  $city = _ioDAO ('kernel|kernel_bu_ville')->get ($ppo->parentId);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $ppo->parentId, 'nodeType' => $ppo->parentType)));
	  $breadcrumbs[] = array('txt' => 'Création d\'une école');
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet ($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'create_school.tpl');
	}
	
	/**
	 * validateSchoolCreation
	 *
	 * Validation du formulaire de création d'une école.
	 * 
	 */
	public function processValidateSchoolCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->parentId   = _request ('id_parent', null);
	  $ppo->parentType = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    // DAO
    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
       
    $city = _ioDAO ('kernel|kernel_bu_ville')->get ($ppo->parentId);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $ppo->parentId, 'nodeType' => $ppo->parentType)));
	  $breadcrumbs[] = array('txt' => 'Création d\'une école');

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    // Récupération des paramètres
    $ppo->school = _record ('kernel|kernel_bu_ecole');
    
    $ppo->school->type      = _request ('type', null);
    $ppo->school->nom       = trim (_request ('nom', null));
    $ppo->school->id_ville  = $ppo->parentId;

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');

      return _arPPO ($ppo, 'create_school.tpl');
    }
    
    $schoolDAO->insert ($ppo->school);
    
    $ppo->nodeId    = $ppo->school->numero;
    $ppo->nodeType  = 'BU_ECOLE';
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	/**
	 * updateSchool
	 *
	 * Edition d'une école.
	 * 
	 */
	public function processUpdateSchool () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
    $schoolDAO  = _ioDAO ('kernel|kernel_bu_ecole');
    
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  // Récupération des villes pour select
    $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  $cities  = $cityDAO->findAll ();
	  
    foreach ($cities as $city) {
      
      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }
    
    // Liste des types d'école
	  $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
	  
	  $city = $cityDAO->get ($ppo->school->id_ville);

	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $ppo->school->nom);
	  
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'update_school.tpl');
	}
	
	/**
	 * validateSchoolUpdate
	 *
	 * Validation du formulaire d'édition d'une école.
	 * 
	 */
	public function processValidateSchoolUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  
	  // DAO
	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  
	  // Récupération de l'école
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
 
    $city = _ioDAO ('kernel|kernel_bu_ville')->get ($ppo->school->id_ville);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $ppo->school->nom);

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    // Récupération des paramètres
    $ppo->school->type      = _request ('type', null);
    $ppo->school->nom       = trim (_request ('nom', null));
    $ppo->school->id_ville  = _request ('ville', null);
		
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();

  	  // Récupération des villes pour select
      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
  	  $cities  = $cityDAO->findAll ();

      foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }
      
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
      
      return _arPPO ($ppo, 'update_school.tpl');
    }
      
    $schoolDAO->update ($ppo->school);
    
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processDeleteSchool () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  $nodeType = _request ('nodeType', null);

	  $schoolDAO     = _ioDAO ('kernel|kernel_bu_ecole');
	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO    = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $studentAdmissionDAO = _ioDAO ('kernel|kernel_bu_eleve_admission');
	  
	  if ($nodeType != 'BU_ECOLE' || !$school = $schoolDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération VILLE pour redirect
	  $ppo->nodeId = $school->id_ville;
	  $ppo->nodeType = 'BU_VILLE';
	  
	  // Récupération des classes de l'école
	  $classes = $classDAO->getBySchool ($school->numero);
	    
	  foreach ($classes as $class) {

	    // Récupération de l'association classe-niveau
	    $classLevels = $classLevelDAO->getByClass ($class->id);
	    
	    foreach ($classLevels as $classLevel) {
	      
	      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
	    }
	    
	    $students = $studentDAO->getElevesInClasse ($class->id);
      foreach ($students as $student) {

        // Récupération de l'affectation de l'élève à la classe
        if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->id, $class->id)) {
          
          $studentAssignmentDAO->delete ($studentAssignment->affect_id);
        }
        
        // Récupérations des admission de l'élève
    	  if ($admission = $studentAdmissionDAO->getByStudentAndSchool ($student->id, $school->numero)) {
    	  
    	    $studentAdmissionDAO->delete ($admission->admission_numero);  
    	  }
      }
	      
	    // Suppression de la classe
	    $classDAO->delete ($class->id);
	  }
 
	  // Suppression de l'école
	  $schoolDAO->delete ($school->numero);

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	/**
	 * createClass
	 *
	 * Création d'une classe.
	 * 
	 */
	public function processCreateClass () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->parentId   = _request ('parentId', null);
	  $ppo->parentType = _request ('parentType', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType) || $ppo->parentType != 'BU_ECOLE') {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
		
		// Récupération des niveaux de classe
		$classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');     
    $levels  = $classLevelDAO->findAll ();
    
    $ppo->levelNames = array ();
		$ppo->levelIds   = array ();
		
    foreach ($levels as $level) {
      
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
    
    // Récupération des types de classe
    $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
    $types        = $classTypeDAO->findAll ();
    
    $ppo->typeNames = array ();
    $ppo->typeIds   = array ();
    
    foreach ($types as $type) {
      
      $ppo->typeNames[] = $type->type_classe;
      $ppo->typeIds[]   = $type->id_tycla;
    }

    $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->parentId);
    $city = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero, 'nodeType' => 'BU_ECOLE')));
	  $breadcrumbs[] = array('txt' => 'Création d\'une classe');

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'create_class.tpl');
	}
	
	/**
	 * validateClassCreation
	 *
	 * Validation du formulaire de création d'une classe.
	 * 
	 */
	public function processValidateClassCreation () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->parentId   = _request ('id_parent', null);
	  $ppo->parentType = _request ('type_parent', null);
	  
	  if (is_null ($ppo->parentId) || is_null ($ppo->parentType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    // DAO
    $schoolClassDAO       = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO  = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    
    $ppo->levels  = _request ('levels', null);
    $ppo->type    = _request ('type', null);
   
    $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->parentId);
    $city = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero, 'nodeType' => 'BU_ECOLE')));
	  $breadcrumbs[] = array('txt' => 'Création d\'une classe');

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    // Insertion de la classe
    $ppo->class = _record ('kernel|kernel_bu_ecole_classe');

    $ppo->class->ecole        = $ppo->parentId;
    $ppo->class->nom          = trim (_request ('nom', null));
    $ppo->class->annee_scol   = Kernel::getAnneeScolaireCourante ()->id_as;
    $ppo->class->is_validee   = 1;
    $ppo->class->is_supprimee = 0;

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = 'Saisissez un niveau';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des niveaux de classe
  		$classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');     
      $levels  = $classLevelDAO->findAll ();
      
      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($levels as $level) {

        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }

      // Récupération des types de classe
      $classTypeDAO   = _ioDAO ('kernel_bu_classe_type');
      $types     = $classTypeDAO->findAll ();
      
      $ppo->typeNames = array ();
      $ppo->typeIds   = array ();
      
      foreach ($types as $type) {

        $ppo->typeNames[] = $type->type_classe;
        $ppo->typeIds[]   = $type->id_tycla;
      }

      return _arPPO ($ppo, 'create_class.tpl');
    }

    $schoolClassDAO->insert ($ppo->class);
    
    // Insertion des affectations classe-niveau
    $newSchoolClassLevel = _record ('kernel|kernel_bu_ecole_classe_niveau');

    foreach ($ppo->levels as $level) {
      
      $newSchoolClassLevel->classe = $ppo->class->id;
      $newSchoolClassLevel->niveau = $level;
      $newSchoolClassLevel->type   = $ppo->type;

      $schoolClassLevelDAO->insert ($newSchoolClassLevel);
    }	
    
    $ppo->nodeId   = $ppo->class->id;
		$ppo->nodeType = 'BU_CLASSE';

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processUpdateClass () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération de la classe
	  $classDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $ppo->class = $classDAO->get ($ppo->nodeId);

    // Récupération des niveaux de la classe
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_tree_claniv');
    $schoolClassLevels = $schoolClassLevelDAO->getByClasse ($ppo->nodeId);

    $ppo->levels = array ();
    
    foreach ($schoolClassLevels as $ecn) {
      
      $ppo->levels[] = $ecn->niveau;
      $ppo->type = $ecn->type;
    }

    // Récupération des écoles pour le sélecteur
    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  $schools = $schoolDAO->findAll ();
	  
	  $ppo->schoolNames = array ();
	  $ppo->schoolIds   = array ();
	  
    foreach ($schools as $school) {
      
      $ppo->schoolNames[] = $school->nom;
      $ppo->schoolIds[]  = $school->numero;
    }
    
    // Récupération des niveaux de classe
    $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
	  $levels = $classLevelDAO->findAll ();
	  
	  $ppo->levelNames = array ();
	  $ppo->levelIds   = array ();
	  
    foreach ($levels as $level) {
      
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
    
    // Récupération des types de classe pour le sélecteur
    $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
    $types = $classTypeDAO->findAll ();
    
    $ppo->typeNames = array ();
    $ppo->typeIds   = array ();
    
	  foreach ($types as $type) {
      
      $ppo->typeNames[] = $type->type_classe;
      $ppo->typeIds[]   = $type->id_tycla;
    }
    
    $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->class->ecole);
    $city   = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero, 'nodeType' => 'BU_ECOLE')));
	  $breadcrumbs[] = array('txt' => $ppo->class->nom);

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
		
		return _arPPO ($ppo, 'update_class.tpl');
	}
	
	public function processValidateClassUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  
	  $classDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  
	  // Récupération de l'école
	  if (!$ppo->class = $classDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

    $school = _ioDAO ('kernel|kernel_bu_ecole')->get ($ppo->class->ecole);
    $city   = _ioDAO ('kernel|kernel_bu_ville')->get ($school->id_ville);
    
	  // Breadcrumbs
	  $breadcrumbs   = array();
	  $breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
	  $breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $city->id_vi, 'nodeType' => 'BU_VILLE')));
	  $breadcrumbs[] = array('txt' => $school->nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $school->numero, 'nodeType' => 'BU_ECOLE')));
	  $breadcrumbs[] = array('txt' => $ppo->class->nom);

	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    $ppo->class->ecole  = _request ('ecole', null);
    $ppo->class->nom    = trim (_request ('nom', null));

    // Affectations classe-niveau
    $schoolClassLevel = _record ('kernel|kernel_bu_ecole_classe_niveau');

    $ppo->levels = _request ('niveaux', null);
    $ppo->type   = _request ('type', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = 'Saisissez un niveau';
    }
    
    if (!empty ($ppo->errors)) {

      // Récupération des niveaux de la classe
      $schoolClassLevelDAO = _ioDAO ('kernel|kernel_tree_claniv');
      $schoolClassLevels = $schoolClassLevelDAO->getByClasse ($ppo->nodeId);

      $ppo->levels = array ();
      
      foreach ($schoolClassLevels as $ecn) {

        $ppo->levels[] = $ecn->niveau;
        $ppo->type = $ecn->type;
      }

      // Récupération des écoles pour le sélecteur
      $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
  	  $schools = $schoolDAO->findAll ();
      
      $ppo->schoolNames = array ();
      $ppo->schoolIds   = array ();
      
      foreach ($schools as $school) {

        $ppo->schoolNames[] = $school->nom;
        $ppo->schoolIds[]  = $school->numero;
      }

      // Récupération des niveaux de classe
      $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
  	  $levels = $classLevelDAO->findAll ();

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($levels as $level) {

        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }

      // Récupération des types de classe pour le sélecteur
      $classTypeDAO = _ioDAO ('kernel_bu_classe_type');
      $types = $classTypeDAO->findAll ();

      $ppo->typeNames = array ();
      $ppo->typeIds   = array ();
      
  	  foreach ($types as $type) {

        $ppo->typeNames[] = $type->type_classe;
        $ppo->typeIds[]   = $type->id_tycla;
      }

      return _arPPO ($ppo, 'update_class.tpl');
    }
    
    // Suppression des anciennes affectations
    $oldSchoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->class->id);
    
    foreach ($oldSchoolClassLevels as $oldSchoolClassLevel) {
      
      $schoolClassLevelDAO->delete ($oldSchoolClassLevel->classe, $oldSchoolClassLevel->niveau);
    }
    
    // Insertions des nouvelles
    foreach ($ppo->levels as $level) {
      
      $newSchoolClassLevel->classe = $ppo->class->id;
      $newSchoolClassLevel->niveau = $level;
      $newSchoolClassLevel->type   = $ppo->type;

      $schoolClassLevelDAO->insert ($newSchoolClassLevel);
    }
    
    $classDAO->update ($ppo->class);
		
		$ppo->nodeId   = $ppo->class->id;
		$ppo->nodeType = 'BU_CLASSE';

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processDeleteClass () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  $nodeType = _request ('nodeType', null);

	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  $studentDAO    = _ioDAO ('kernel|kernel_bu_ele');
	  $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');

	  if ($nodeType != 'BU_CLASSE' || !$class = $classDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  } 
	  
	  // Récupération ECOLE pour redirect
	  $ppo->nodeId = $class->ecole;
	  $ppo->nodeType = 'BU_ECOLE';
	  
	  // Récupération de l'association classe-niveau
    $classLevels = $classLevelDAO->getByClass ($class->id);
    
    foreach ($classLevels as $classLevel) {
      
      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
    }
    
    $students = $studentDAO->getElevesInClasse ($nodeId);
    foreach ($students as $student) {
      
      // Récupération de l'affectation de l'élève pour cette classe
      $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($student->ele_idEleve, $nodeId);
      $studentAssignmentDAO->delete ($studentAssignment->affect_id);
    }
    
	  // Suppression de la classe
	  $classDAO->delete ($class->id);
		
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processCreatePersonnel () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _sessionSet ('modules|gestionautonome|createAccount', array ());
	  		
		$ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
		$roleDAO = _ioDAO ('kernel_bu_personnel_role');
    $ppo->roleName = $roleDAO->get ($ppo->role)->nom_role;

	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Création d\'un '.$ppo->roleName);
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");

		return _arPPO ($ppo, 'create_personnel.tpl');
	}
	
	public function processValidatePersonnelCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_parent', null);
	  $ppo->nodeType = _request ('type_parent', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

		_classInclude ('kernel|Tools');
		
		// DAO
    $personnelDAO       = _ioDAO ('kernel|kernel_bu_personnel');
    $personnelEntiteDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
    $dbUserDAO          = _ioDAO ('kernel|kernel_copixuser');
    $dbLinkDAO          = _ioDAO ('kernel|kernel_bu2user2');
    $roleDAO            = _ioDAO ('kernel_bu_personnel_role');
    
    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
    $ppo->roleName = $roleDAO->get ($ppo->role)->nom_role;

	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Création d\'un '.$ppo->roleName);
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");    
        
    // Enregistrement kernel_bu_personnel
    $ppo->personnel = _record ('kernel|kernel_bu_personnel');
                                   
    $ppo->personnel->pers_nom         = trim (_request ('nom', null));
    $ppo->personnel->pers_prenom1     = trim (_request ('prenom1', null));    
    $ppo->personnel->pers_date_nais   = CopixDateTime::dateToyyyymmdd(_request ('date_nais', null));
    $ppo->personnel->pers_id_sexe     = _request ('gender', null);
    
    $ppo->login                  = _request ('login', null);
    $ppo->password               = _request ('password', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->personnel->pers_nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->personnel->pers_prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (!$ppo->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    if (!$ppo->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    if (!Kernel::isLoginAvailable ($ppo->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    if (is_null($ppo->personnel->pers_id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
   
    if (!empty ($ppo->errors)) {
  	  
  	  $ppo->personnel->date_nais = _request ('date_nais', null);
  	   
      return _arPPO ($ppo, 'create_personnel.tpl');
    }
    
    $personnelDAO->insert ($ppo->personnel);
    
    // Récupération du type_user et du type_ref                             
    switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $type_user = 'USER_VIL';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $type_user = 'USER_VIL';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        if ($ppo->role == '3') {
          $type_user = 'USER_ADM';
        }                         
        else {
          $type_user = 'USER_ENS';
        }
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
        $type_user = 'USER_ENS';
      	break;
		}
		
    // Enregistrement dbuser
    $dbuser = _record ('kernel|kernel_copixuser');

    $dbuser->login_dbuser    = $ppo->login;
    $dbuser->password_dbuser = md5 ($ppo->password);
    $dbuser->email_dbuser    = '';
    $dbuser->enabled_dbuser  = 1;
    
    $dbUserDAO->insert ($dbuser);
    
    // Enregistrement kernel_link_bu2user
    $dbLink = _record ('kernel|kernel_bu2user2');

    $dbLink->user_id = $dbuser->id_dbuser;
    $dbLink->bu_type = $type_user;
    $dbLink->bu_id   = $ppo->personnel->pers_numero;
    
    $dbLinkDAO->insert ($dbLink);                                    
    
    // Enregistrement kernel_bu_personnel_entite
    $newPersonnelEntite = _record ('kernel|kernel_bu_personnel_entite');
    
		$newPersonnelEntite->pers_entite_id_per    = $ppo->personnel->pers_numero; 
		$newPersonnelEntite->pers_entite_reference = $ppo->nodeId;
		$newPersonnelEntite->pers_entite_type_ref  = $type_ref;
		$newPersonnelEntite->pers_entite_role      = $ppo->role;
		
		$personnelEntiteDAO->insert ($newPersonnelEntite);
		
		$session = _sessionGet ('modules|gestionautonome|createAccount');
		if (!$session || !is_array ($session)) {
		  
		  $session = array();
		}
		
		$node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
		
		$session[0] = array(
		  'lastname'  => $ppo->personnel->pers_nom,
			'firstname' => $ppo->personnel->pers_prenom1,
			'login'     => $ppo->login,
			'password'  => $ppo->password,
			'bu_type'   => $type_user,
			'bu_id'     => $ppo->personnel->pers_numero,
			'type_nom'  => Kernel::Code2Name($type_user),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
		);
		
		_sessionSet ('modules|gestionautonome|createAccount', $session);
		
		$sessionId = $type_user.'-'.$ppo->personnel->pers_numero;

		$urlReturn = CopixUrl::get ('gestionautonome||showAccountListing', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType));
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}
	
	public function processShowAccountListing () {
	                                                                        
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
	  // Récupération des informations des comptes créés
	  $ppo->sessionDatas = _sessionGet ('modules|gestionautonome|createAccount'); 

    // Récupération du format de sortie demandé
	  if( !_request ('format') || trim (_request ('format')) == '' ) {
	    
			$format = "default";
		} 
		else {
		  
			$format = _request('format');
		} 
		
		// Sortie suivant le format demandé
		$tplResult = & new CopixTpl ();
		$tplResult->assign ('sessionDatas', $ppo->sessionDatas);
		
	  switch ($format) {
			case 'default':
				return _arPPO ($ppo, 'account_listing.tpl');
			case 'html':
			  $result = $tplResult->fetch ('account_listing_html.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'inline', 'content-type'=>CopixMIMETypes::getFromExtension ('.html')));
			  break;
			case 'csv':
			  $result = $tplResult->fetch ('account_listing_csv.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.csv', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.csv')));
			  break;
		}
	}
	
	public function processUpdatePersonnel () {
	
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personnelId   = _request ('personnelId', null);
	  $ppo->type     = _request ('type', null);
	  $ppo->role     = _request ('role', null);
	  
	  // DAO
	  $personnelDAO  = _ioDAO ('kernel|kernel_bu_personnel');
	  $dbuserDAO     = _ioDAO ('kernel|kernel_copixuser');
	  $roleDAO       = _ioDAO ('kernel_bu_personnel_role');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || !$ppo->personnel = $personnelDAO->get ($personnelId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
    $ppo->personnel->pers_date_nais = CopixDateTime::yyyymmddToDate ($ppo->personnel->pers_date_nais);
    
    $ppo->personName = $ppo->personnel->pers_nom.' '.$ppo->personnel->pers_prenom1;
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => $ppo->personName.' ('.Kernel::Code2Name ($ppo->type).')');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	                                                                               
	  // Récupération du compte dbuser
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personnelId, $ppo->type);

		return _arPPO ($ppo, 'update_personnel.tpl');
	}
	
	public function processValidatePersonnelUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  $personnelId   = _request ('id_personnel', null);
	  $ppo->type     = _request ('type', null);
	  
	  $personnelDAO  = _ioDAO ('kernel|kernel_bu_personnel');
	  $dbuserDAO     = _ioDAO ('kernel|kernel_copixuser');
	  
	  if (!$ppo->personnel = $personnelDAO->get ($personnelId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->personName = $ppo->personnel->nom.' '.$ppo->personnel->prenom1;
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => $ppo->personName.' ('.Kernel::Code2Name ($ppo->type).')');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");     

    $ppo->personnel->pers_nom         = trim (_request ('nom', null));
    $ppo->personnel->pers_prenom1     = trim (_request ('prenom1', null));
    $ppo->personnel->pers_date_nais   = _request ('date_nais', null);
    $ppo->personnel->pers_id_sexe     = _request ('gender', null);
    $ppo->personnel->pers_date_nais   = CopixDateTime::dateToyyyymmdd (_request ('date_nais', null)); 
    
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personnelId, $ppo->type);
   
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->personnel->pers_nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->personnel->pers_prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }

    if (!empty ($ppo->errors)) {
      
      $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('0', '1'); 
      
      $ppo->personnel->date_nais = _request ('date_nais', null);
      
      return _arPPO ($ppo, 'update_personnel.tpl');
    }
    
    $personnelDAO->update ($ppo->personnel);
        
    $newPassword = _request ('password', null);
    if ($ppo->account->password_dbuser != md5 ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }
    
    if ($ppo->type == 'USER_ENS' && $ppo->nodeType == 'BU_CLASSE') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'tab' => 1, 'save' => 1)));
    }
    
		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processRemovePersonnel () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personId      = _request ('personnelId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite'); 

    if ($personEntity = $personEntityDAO->get ($personId, $ppo->nodeId, $type_ref)) {

      $personEntityDAO->delete ($personId, $ppo->nodeId, $type_ref);
    }
    
    if ($ppo->nodeType == 'BU_CLASSE') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'tab' => 1, 'save' => 1)));
    }

	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processDeletePersonnel () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $personnelId   = _request ('personnelId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($personnelId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $personnelDAO->delete ($personnelId);

	  $personnelLinkDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
	  $links = $personnelLinkDAO->getById ($personnelId);
	  
	  foreach ($links as $link) {                 
	    
	    $personnelLinkDAO->delete ($link->id_per);
	  }
	  
	  if ($ppo->nodeType == 'BU_CLASSE') {
      
      return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'tab' => 1, 'save' => 1)));
    }
    
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processCreateStudent () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  // Récupération des paramètres
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Remise à zéro des sessions tmp
	  _sessionSet ('modules|gestionautonome|tmpAccount', array ());
	  _sessionSet ('modules|gestionautonome|createAccount', array ());
    
    // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
    $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
    
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
    
    $ppo->genderNames = array ('Garçon', 'Fille');
    $ppo->genderIds = array ('0', '1');
    
    // Compteur responsable
    $ppo->cpt = 1;

	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Création d\'un élève');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
		return _arPPO ($ppo, 'create_student.tpl');
	}
	
	public function processValidateStudentCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";    
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_parent', null);
	  $ppo->nodeType = _request ('type_parent', null);
	  $ppo->level    = _request ('level', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

		_classInclude ('kernel|Tools');
		
    $studentDAO              = _ioDAO ('kernel_bu_eleve');
    $studentRegistrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentAdmissionDAO     = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO    = _ioDAO ('kernel_bu_eleve_affectation');
    $dbuserDAO               = _ioDAO ('kernel|kernel_copixuser'); 
    $dbLinkDAO               = _ioDAO ('kernel_link_bu2user');
    $classDAO                = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO     = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');    
        
    // Création de l'élève
    $ppo->student = _record ('kernel_bu_eleve');
                            
    $ppo->student->numero       = '';                        
    $ppo->student->nom          = trim (_request ('student_lastname', null));
    $ppo->student->prenom1      = trim (_request ('student_firstname', null));
    $ppo->student->id_sexe      = _request ('gender', null);
    $ppo->student->date_nais    = CopixDateTime::dateToyyyymmdd(_request ('student_birthdate', null));
    $ppo->student->flag         = 0;
    $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    $ppo->login                 = _request ('student_login', null);
    $ppo->password              = _request ('student_password', null); 
    
    $ppo->resp_on               = _request ('person_in_charge', null);
    
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Création d\'un élève');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (is_null($ppo->student->id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
    if (!$ppo->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    elseif (!Kernel::isLoginAvailable ($ppo->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    if (!$ppo->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
      
      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level              = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[]  = $level->niveau_court;
        $ppo->levelIds[]    = $level->id_n;
      }
      
      $ppo->genderNames = array ('Garçon', 'Fille');
      $ppo->genderIds = array ('0', '1');
  	   
      $ppo->student->date_nais    = _request('student_birthdate', null);
      
      return _arPPO ($ppo, 'create_student.tpl');
    }

    $studentDAO->insert ($ppo->student);
    
    // Création du compte dbuser
    $dbuser = _record ('kernel|kernel_copixuser');

    $dbuser->login_dbuser    = $ppo->login;
    $dbuser->password_dbuser = md5 ($ppo->password);
    $dbuser->email_dbuser    = '';
    $dbuser->enabled_dbuser  = 1;
    
    $dbuserDAO->insert ($dbuser);
    
    // Création du link bu2user
    $dbLink = _record ('kernel_link_bu2user');

    $dbLink->user_id = $dbuser->id_dbuser;
    $dbLink->bu_type = 'USER_ELE';
    $dbLink->bu_id   = $ppo->student->idEleve;
    
    $dbLinkDAO->insert ($dbLink);
                 
    // Récupération des données nécessaires à l'ajout des enregistrements inscription / adhésion / admission
    $class = $classDAO->get ($ppo->nodeId); 
    $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);
    $classType = $schoolClassLevels[0]->type;
    $schoolId = $class->ecole;
    $currentGradeId = Kernel::getAnneeScolaireCourante ()->id_as;
    
    // Inscription de l'élève dans l'école
    $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');
    
    $studentRegistration->eleve                   = $ppo->student->idEleve;
    $studentRegistration->annee_scol              = $currentGradeId;
    $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentRegistration->etablissement           = $schoolId;
    $studentRegistration->etablissement_refus     = 0;
    $studentRegistration->id_niveau               = $ppo->level;
    $studentRegistration->id_typ_cla              = $classType;
    $studentRegistration->vaccins_aj              = 0;
    $studentRegistration->attente                 = 0;
    $studentRegistration->derogation_dem          = 0; 
    $studentRegistration->temporaire              = 0;
    $studentRegistration->current_inscr           = 1;

    $studentRegistrationDAO->insert ($studentRegistration);

    // Admission de l'élève dans l'école
    $studentAdmission = _record ('kernel_bu_eleve_admission');
    
    $studentAdmission->eleve          = $ppo->student->idEleve;
    $studentAdmission->etablissement  = $schoolId;
    $studentAdmission->annee_scol     = $currentGradeId;
    $studentAdmission->id_niveau      = $ppo->level;
    $studentAdmission->etat_eleve     = 1;
    $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->code_radiation = '';
    $studentAdmission->previsionnel   = '';
    
    $studentAdmissionDAO->insert ($studentAdmission);
    
    // Affectation de l'élève dans les classes
    $studentAssignment = _record ('kernel_bu_eleve_affectation');
    
    $studentAssignment->eleve           = $ppo->student->idEleve;
    $studentAssignment->annee_scol      = $currentGradeId;
    $studentAssignment->classe          = $ppo->nodeId;
    $studentAssignment->niveau          = $ppo->level;
    $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAssignment->current         = 1;
    $studentAssignment->previsionnel_cl = 0;

    $studentAssignmentDAO->insert ($studentAssignment);
    
    $type_user = 'USER_ELE';
    
    $node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
		
		// Mise en session des comptes créés : 0 -> élève / x -> responsables
		$session = _sessionGet ('modules|gestionautonome|createAccount');
		if (!$session || !is_array ($session)) {
		  
		  $session = array();
		}
		
		$session[0] = array(
		  'lastname'  => $ppo->student->nom,
			'firstname' => $ppo->student->prenom1,
			'login'     => $ppo->login,
			'password'  => $ppo->password,
			'bu_type'   => $type_user,
			'bu_id'     => $ppo->student->idEleve,
			'type_nom'  => Kernel::Code2Name($type_user),
			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
		);
		                                                                      
		// Récupérations des infos temporaires en session et ajout aux sessions
		$tmpSession = _sessionGet ('modules|gestionautonome|tmpAccount');                                                                
		if ($tmpSession && is_array ($tmpSession) && !empty ($tmpSession)) {
		  
		  $personDAO       = _ioDAO ('kernel_bu_responsable');
      $personLinkDAO   = _ioDAO ('kernel_bu_responsables');
      
		  if ($personSessions = $tmpSession) {
		    
		    $cptPerson = 1;

  		  foreach ($personSessions as $personSession) {

          // Ajout du responsable en session seulement si la création du dbuser est possible
          if (Kernel::isLoginAvailable ($personSession['login'])) {
            
            // Création du responsable
            $ppo->person = _record ('kernel_bu_responsable');

            $ppo->person->nom        = trim ($personSession['lastname']);
            $ppo->person->prenom1    = trim ($personSession['firstname']);
            $ppo->person->id_sexe    = $personSession['id_sexe'];
            $ppo->res_id_par         = $personSession['id_par'];

    		    $personDAO->insert ($ppo->person);

            // Création de l'association personne->rôle
            $newPersonLink = _record ('kernel_bu_responsables');

        		$newPersonLink->id_beneficiaire   = $ppo->student->idEleve; 
        		$newPersonLink->type_beneficiaire = 'eleve';
        		$newPersonLink->id_responsable    = $ppo->person->numero;
        		$newPersonLink->type              = 'responsable';
        		$newPersonLink->auth_parentale    = '0';
        		$newPersonLink->id_par            = $personSession['id_par'];

        		$personLinkDAO->insert ($newPersonLink);

        		// Création du compte dbuser
            $dbuser = _record ('kernel|kernel_copixuser');

            $dbuser->login_dbuser    = $personSession['login'];
            $dbuser->password_dbuser = md5 ($personSession['password']);
            $dbuser->email_dbuser    = '';
            $dbuser->enabled_dbuser  = 1;

            $dbuserDAO->insert ($dbuser);

            // Création du link bu2user
            $dbLink = _record ('kernel_link_bu2user');

            $dbLink->user_id = $dbuser->id_dbuser;
            $dbLink->bu_type = 'USER_RES';
            $dbLink->bu_id   = $ppo->person->numero;

            $dbLinkDAO->insert ($dbLink);
            
            // Mise en session du responsable
            $session[$cptPerson] = array(
        		  'lastname'  => $personSession['lastname'],
        			'firstname' => $personSession['firstname'],
        			'login'     => $personSession['login'],
        			'password'  => $personSession['password'],
        			'bu_type'   => 'USER_RES',
        			'bu_id'     => $ppo->student->idEleve,
        			'type_nom'  => Kernel::Code2Name('USER_RES'),
        			'node_nom'  => Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'],
        		);

        		$cptPerson++;
          }
        }
		  }
		}
		
		// Mise en session de l'élève et des responsables
		_sessionSet ('modules|gestionautonome|createAccount', $session);
		
		// Remise à zéro des sessions tmp
		_sessionSet ('modules|gestionautonome|tmpAccount', array ());
		
		$sessionId = $type_user.'-'.$ppo->student->idEleve;

		$urlReturn = CopixUrl::get ('gestionautonome||showAccountListing', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType));
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}
	
	public function processUpdateStudent () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  $studentDAO    = _ioDAO ('kernel_bu_eleve');
	  $dbuserDAO     = _ioDAO ('kernel|kernel_copixuser');
	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $schoolDAO     = _ioDAO ('kernel|kernel_bu_ecole');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || !$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    $ppo->student->date_nais = CopixDateTime::yyyymmddToDate ($ppo->student->date_nais);
    
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');
    
    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => $ppo->student->nom);
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
	  $ppo->genderNames = array ('Garçon', 'Fille');
    $ppo->genderIds = array ('0', '1');
	  
	  return _arPPO ($ppo, 'update_student.tpl');
	}
	
	public function processValidateStudentUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";

	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  $studentId     = _request ('id_student', null);
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  $dbuserDAO  = _ioDAO ('kernel|kernel_copixuser');
	  
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

    $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => $ppo->student->nom);
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    $ppo->student->numero       = '';                        
    $ppo->student->nom          = trim (_request ('nom', null));
    $ppo->student->prenom1      = trim (_request ('prenom1', null));
    $ppo->student->id_sexe      = _request ('gender', null);
    $ppo->student->date_nais    = CopixDateTime::dateToyyyymmdd (_request ('date_nais', null));
    $ppo->student->flag         = 0;
    $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
     
    $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    
    if (!empty ($ppo->errors)) {
            
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
      
      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();
      
      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level              = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[]  = $level->niveau_court;
        $ppo->levelIds[]    = $level->id_n;
      }
      
      $ppo->student->date_nais = _request ('date_nais', null);
      
      $ppo->genderNames = array ('Garçon', 'Fille');
      $ppo->genderIds = array ('0', '1');
  	   
      return _arPPO ($ppo, 'update_student.tpl');
    }
      
    $studentDAO->update ($ppo->student);
		
		// Modification du password dbuser si différent
		$newPassword = _request ('password', null);
    if ($ppo->account->password_dbuser != md5 ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }

		return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	
	public function processRemoveStudent () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
    
    // DAO
    $studentAdmissionDAO  = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
    
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId);
	  
	  // Ajout d'un enregistrement de radiation
	  $studentAdmission = _record ('kernel_bu_eleve_admission');
    
    $studentAdmission->eleve          = $studentId;
    $studentAdmission->etablissement  = $nodeInfos['ALL']->cla_ecole;
    $studentAdmission->annee_scol     = Kernel::getAnneeScolaireCourante ()->id_as;
    $studentAdmission->id_niveau      = '';
    $studentAdmission->etat_eleve     = 3;
    $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->code_radiation = '';
    $studentAdmission->previsionnel   = '';
    
    $studentAdmissionDAO->insert ($studentAdmission);
    
    // Récupération de l'affectation de l'élève à la classe pour passage du flag current à 0
    $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $nodeInfos['ALL']->cla_id);
    $studentAssignment->affect_current = 0;
    
    $studentAssignmentDAO->update ($studentAssignment);
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processDeleteStudent () {
	  
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $dbuserDAO        = _ioDAO ('kernel|kernel_copixuser'); 
    $dbLinkDAO        = _ioDAO ('kernel|kernel_bu2user');
	  $studentDAO       = _ioDAO ('kernel_bu_eleve');
	  $assignmentDAO    = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $registrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $admissionDAO     = _ioDAO ('kernel|kernel_bu_eleve_admission');

	  // Récupération et suppression du DbLink et dbuser
	  $dbLink = $dbLinkDAO->getByBUID ('USER_ELE', $studentId);
	  $dbuserDAO->delete ($dbLink[0]->user_id);
	  $dbLinkDAO->delete ($dbLink[0]->user_id, $dbLink[0]->bu_type, $dbLink[0]->bu_id);

	  // Récupération des affectations de l'élève
	  $assignments = $assignmentDAO->getByStudent ($studentId);
	  
	  foreach ($assignments as $assignment) {
	    
	    $assignmentDAO->delete ($assignment->affect_id);
	  }
	  
	  // Récupération des inscriptions de l'élève
	  $registrations = $registrationDAO->getByStudent ($studentId);
	  foreach ($registrations as $registration) {
	    
	    $registrationDAO->delete ($registration->inscr_numero);
	  }

	  // Récupérations des admission de l'élève
	  $admissions = $admissionDAO->getByStudent ($studentId);
	  foreach ($admissions as $admission) {
	    
	    $admissionDAO->delete ($admission->admission_numero);
	  }
	  
	  // Récupération des liens responsables
	  $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  $personInChargeLinks = $res2eleDAO->getByBeneficiaire ('eleve', $studentId);

	  foreach ($personInChargeLinks as $personInChargeLink) {
	    
	    $res2eleDAO->delete ($personInChargeLink->res2ele_id_rel);
	  }
	  
	  // Suppression de l'élève
	  $studentDAO->delete ($studentId);
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
	
	public function processCreatePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO    = _ioDAO ('kernel|kernel_bu_ele'); 
	  $cityDAO       = _ioDAO ('kernel|kernel_bu_ville');  
	  $countryDAO    = _ioDAO ('kernel_bu_pays');  
	  $parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
	  $situationDAO  = _ioDAO ('kernel_bu_situation_familiale');
	  $pcsDAO        = _ioDAO ('kernel_bu_pcs');     
	  $dbuserDAO     = _ioDAO ('kernel|kernel_copixuser');
	  	  
	  // Récupération de l'élève  
	  $ppo->student = $studentDAO->get ($studentId);
	  
    // Récupération des relations
	  $parentLinks = $parentLinkDAO->findAll ();
	  
	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();
	  
	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }
    
    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
    $ppo->studentAccount = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');
	  
		return _arPPO ($ppo, 'create_person_in_charge.tpl');	  
	}
	
	public function processValidatePersonInChargeCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId    = _request ('id_node', null);
	  $ppo->nodeType  = _request ('type_node', null);
	  $ppo->studentId = _request ('id_student', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
    $personDAO       = _ioDAO ('kernel_bu_responsable');
    $personLinkDAO   = _ioDAO ('kernel_bu_responsables');
    $dbuserDAO       = _ioDAO ('kernel|kernel_copixuser');
    $dbLinkDAO       = _ioDAO ('kernel|kernel_bu2user2');  
        
    // Création de la personne
    $ppo->person = _record ('kernel_bu_responsable');
                        
    $ppo->person->nom        = trim (_request ('nom', null));
    $ppo->person->prenom1    = trim (_request ('prenom1', null));
    $ppo->person->id_sexe    = _request('gender', null);
    $ppo->res_id_par         = _request ('id_par', null);
    
    $ppo->login     = _request ('login', null);
    $ppo->password  = _request ('password', null);
       
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->person->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->person->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (is_null($ppo->person->id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
    if (!$ppo->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    elseif (!Kernel::isLoginAvailable ($ppo->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    if (!$ppo->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération de l'élève
  	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
  	  $ppo->student = $studentDAO->get ($ppo->studentId);
  	  
      // Récupération des relations
  		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
  	  $parentLinks = $parentLinkDAO->findAll ();

  	  $ppo->linkNames = array ();
  	  $ppo->linkIds   = array ();

  	  foreach ($parentLinks as $parentLink) {

        $ppo->linkNames[] = $parentLink->parente;
        $ppo->linkIds[]   = $parentLink->id_pa;
      }
      
      $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('0', '1');
      
      return _arPPO ($ppo, 'create_person_in_charge.tpl');
    }
    
    $personDAO->insert ($ppo->person);
    
    // Création du compte dbuser
    $dbuser = _record ('kernel|kernel_copixuser');

    $dbuser->login_dbuser    = $ppo->login;
    $dbuser->password_dbuser = md5 ($ppo->password);
    $dbuser->email_dbuser    = '';
    $dbuser->enabled_dbuser  = 1;
    
    $dbuserDAO->insert ($dbuser);
    
    // Création du link bu2user
    $dbLink = _record ('kernel_link_bu2user');

    $dbLink->user_id = $dbuser->id_dbuser;
    $dbLink->bu_type = 'USER_RES';
    $dbLink->bu_id   = $ppo->person->numero;
    
    $dbLinkDAO->insert ($dbLink);

    // Création de l'association personne->rôle
    $newPersonLink = _record ('kernel_bu_responsables');

		$newPersonLink->id_beneficiaire   = $ppo->studentId; 
		$newPersonLink->type_beneficiaire = 'eleve';
		$newPersonLink->id_responsable    = $ppo->person->numero;
		$newPersonLink->type              = 'responsable';
		$newPersonLink->auth_parentale    = '0';
		$newPersonLink->id_par            = $ppo->res_id_par;
				
		$personLinkDAO->insert ($newPersonLink);
		
		return _arPPO ($ppo, array ('template' => 'create_person_in_charge_success.tpl', 'mainTemplate' => null));  
	}
	
	public function processUpdatePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  $personId      = _request ('personId', null);
	  
	  $personDAO     = _ioDAO ('kernel_bu_responsable');
	  $dbuserDAO     = _ioDAO ('kernel|kernel_copixuser');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($studentId) || !$ppo->person = $personDAO->get ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  // Récupération de l'élève
	  $studentDAO   = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->student = $studentDAO->get ($studentId);

	  $ppo->student_account = $dbuserDAO->getUserByBuIdAndBuType ($studentId, 'USER_ELE');
	  
	  // Récupération du compte du responsable
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personId, 'USER_RES');
	  
    // Récupération des liens parentaux
		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
	  $parentLinks   = $parentLinkDAO->findAll ();
	  
	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();
	  
	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }
    
    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
    // Récupération du lien responsable-élève
    $res2eleDAO   = _ioDAO ('kernel|kernel_bu_res2ele');
    $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($personId, $studentId); 

		return _arPPO ($ppo, 'update_person_in_charge.tpl');
	}
	
	public function processValidatePersonInChargeUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId    = _request ('id_node', null);
	  $ppo->nodeType  = _request ('type_node', null);
	  $ppo->studentId = _request ('id_student', null);
	  $personId       = _request ('id_person', null);
	  
	  $personDAO      = _ioDAO ('kernel_bu_responsable');
	  $dbuserDAO      = _ioDAO ('kernel|kernel_copixuser');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->studentId) || !$ppo->person = $personDAO->get ($personId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération du lien responsable-élève
    $res2eleDAO = _ioDAO ('kernel|kernel_bu_res2ele');
    $ppo->res2ele = $res2eleDAO->getByPersonAndStudent ($personId, $ppo->studentId);

    $ppo->person->nom         = trim (_request ('nom', null));
    $ppo->person->prenom1     = trim (_request ('prenom1', null));
    $ppo->person->date_nais   = _request ('date_nais', null);
    $ppo->person->id_sexe     = _request ('gender', null);
    $res_id_par               = _request ('id_par', null);
    
    // Récupération du compte du responsable
	  $ppo->account = $dbuserDAO->getUserByBuIdAndBuType ($personId, 'USER_RES');
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->person->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->person->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération de l'élève
  	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
  	  $ppo->student = $studentDAO->get ($ppo->studentId);
       
      $dbuserDAO = _ioDAO ('kernel|kernel_copixuser');
  	  $ppo->student_account = $dbuserDAO->getUserByBuIdAndBuType ($ppo->studentId, 'USER_ELE');

      // Récupération des liens parentaux
  		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
  	  $parentLinks = $parentLinkDAO->findAll ();

  	  $ppo->linkNames = array ();
  	  $ppo->linkIds   = array ();

  	  foreach ($parentLinks as $parentLink) {

        $ppo->linkNames[] = $parentLink->parente;
        $ppo->linkIds[]   = $parentLink->id_pa;
      }
      
      $ppo->genderNames = array ('Homme', 'Femme');
      $ppo->genderIds = array ('0', '1');

      return _arPPO ($ppo, 'update_person_in_charge.tpl');
    }
    
    $personDAO->update ($ppo->person);
    
    if ($ppo->res2ele->res2ele_id_par != $res_id_par) {
      
      $ppo->res2ele->res2ele_id_par = $res_id_par;
      $res2eleDAO->update ($ppo->res2ele);
    }
    
    // Modification du password dbuser si différent
		$newPassword = _request ('password', null);
    if ($ppo->account->password_dbuser != md5 ($newPassword)) {
      
      $ppo->account->password_dbuser = md5 ($newPassword);
      $dbuserDAO->update ($ppo->account);
    }

		return _arPPO ($ppo, array ('template' => 'create_person_in_charge_success.tpl', 'mainTemplate' => null));
	}
	
	// AJAX
	public function processRemovePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $personId   = _request ('personId', null);
	  $studentId  = _request ('studentId', null);
	  
	  if (is_null ($personId) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Suppression de l'affectation du responsable
	  $personInChargeLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  $personInChargeLink    = $personInChargeLinkDAO->getByPersonAndStudent ($personId, $studentId);
	  
	  $personInChargeLinkDAO->delete ($personInChargeLink->res2ele_id_rel);
	  
	  // Récupération des responsables de l'élève
	  $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
	  $ppo->persons       = $personsInChargeDAO->getByStudent ($studentId);

    return _arPPO ($ppo, array ('template' => '_persons_in_charge.tpl', 'mainTemplate' => null));
	}
	
	public function processDeletePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $personId   = _request ('personId', null);
	  $studentId  = _request ('studentId', null);
	  
	  if (is_null ($personId) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
    $personInChargeLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  
	  // Récupération des affectations du responsable
	  $assignments = $personInChargeLinkDAO->getByPerson ($personId);
	  
	  foreach ($assignments as $assignment) {
	    
	    $personInChargeLinkDAO->delete ($assignment->res2ele_id_rel);
	  }
	  
	  $personsInChargeDAO->delete ($personId);
	  
	  // Récupération des responsables de l'élève
	  $ppo->persons = $personsInChargeDAO->getByStudent ($studentId);
	  
	  return _arPPO ($ppo, array ('template' => '_persons_in_charge.tpl', 'mainTemplate' => null));
	} 
	
	// AJAX
	public function processPersonInChargeCreation () {

	  $ppo->nodeId          = _request ('nodeId', null);
	  $ppo->nodeType        = _request ('nodeType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  $ppo->person->nom     = _request ('nom', null);
	  $ppo->person->prenom1 = _request ('prenom1', null);
	  $ppo->person->id_sexe = _request ('gender', null);
		$ppo->person->id_par  = _request ('parId', null);
		
	  $ppo->account->login    = _request ('login', null);
	  $ppo->account->password = _request ('password', null);
	  
	  $ppo->cpt = _request('cpt', null);
 
	  // Récupération des relations    
	  $parentLinkDAO = _ioDAO ('kernel_bu_lien_parental'); 
	  $parentLinks = $parentLinkDAO->findAll ();

	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();

	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }

    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');

    $session = _sessionGet ('modules|gestionautonome|tmpAccount');
		if (!$session || !is_array ($session)) {
		  
		  $session = array();
		}
		
		if (!isset ($session)) {
		  
		  $ppo->personsInSession = null;
		}
		else {
		  
		  $ppo->personsInSession = $session;
		}
    
	  // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->person->nom) {
      
      $ppo->errors[] = 'Saisissez un nom';
    }
    if (!$ppo->person->prenom1) {
      
      $ppo->errors[] = 'Saisissez un prénom';
    }
    if (is_null($ppo->person->id_sexe)) {
      
      $ppo->errors[] = 'Saisissez un sexe';
    }
    if (!$ppo->account->login) {
      
      $ppo->errors[] = 'Saisissez un login';
    }
    if (!$ppo->account->password) {
      
      $ppo->errors[] = 'Saisissez un mot de passe';
    }
    elseif (!Kernel::checkPasswordFormat ($ppo->account->password)) {
      
      $ppo->errors[] = 'Format du mot de passe incorrect : au moins 6 caractères dont 1 chiffre';
    }
    if (!Kernel::isLoginAvailable ($ppo->account->login)) {
      
      $ppo->errors[] = 'Login non disponible';
    }
    
    if (!empty ($ppo->errors)) {

      return _arPPO ($ppo, array ('template' => '_create_person_in_charge.tpl', 'mainTemplate' => null));
    }  
    

		$session[$ppo->cpt] = array(
		  'lastname'  => $ppo->person->nom,
			'firstname' => $ppo->person->prenom1,
			'id_par'    => $ppo->person->id_par,
			'id_sexe'   => $ppo->person->id_sexe,
			'login'     => $ppo->account->login,
			'password'  => $ppo->account->password,
		);
		
		_sessionSet ('modules|gestionautonome|tmpAccount', $session);

		$ppo->person  = null;
		$ppo->account = null;
		
		$ppo->personsInSession = $session;
		if (!$ppo->personsInSession || !is_array ($ppo->personsInSession)) {
		  
		  $ppo->personsInSession = array();
		}
		
		if ($ppo->cpt) {

      $ppo->cpt++;                
    }                                
    else {

      $ppo->cpt = 1;
    }
		
		return _arPPO ($ppo, array ('template' => '_create_person_in_charge.tpl', 'mainTemplate' => null));
	}
	
	public function processManageGrades () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  // Récupérations des années scolaires
    $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $ppo->grades = $gradesDAO->findAll ();
	  
	  return _arPPO ($ppo, 'manage_grades.tpl');
	}
	
	public function processCreateGrade () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  return _arPPO ($ppo, 'create_grade.tpl');
	}
	
	public function processValidateGradeCreation () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  
	  $ppo->grade = _record ('kernel_bu_annee_scolaire');

    $dateDebut                  = _request ('dateDebut', null);
    $dateFin                    = _request ('dateFin', null);
                                                            
    $ppo->grade->id_as          = substr($dateDebut, 6, 10);
	  $ppo->grade->annee_scolaire = substr($dateDebut, 6, 10).'-'.substr($dateFin, 6, 10);
	  $ppo->grade->dateDebut      = CopixDateTime::dateToyyyymmdd($dateDebut);
	  $ppo->grade->dateFin        = CopixDateTime::dateToyyyymmdd($dateFin);
	  if (_request ('current', null) == 'on') {
      
      $ppo->grade->current = 1;
    }                    
    else {
      
      $ppo->grade->current = 0;
    }
	  
    if (!$ppo->grade->dateDebut) {
      
      $ppo->errors[] = 'Saisissez une date de début';
    }
    if (!$ppo->grade->dateFin) {
      
      $ppo->errors[] = 'Saisissez une date de fin';
    }
    if ($gradesDAO->get ($ppo->grade->id_as)) {
      
      $ppo->errors[] = 'Cette année scolaire existe déjà';
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->grade->dateDebut    = CopixDateTime::yyyymmddToDate($ppo->grade->dateDebut);
  	  $ppo->grade->dateFin      = CopixDateTime::yyyymmddToDate($ppo->grade->dateFin);
  	  
      return _arPPO ($ppo, 'create_grade.tpl');
    }
    
    if ($ppo->grade->current == 1) {
      
      $currentGrade = Kernel::getAnneeScolaireCourante ();
      $currentGrade->current = 0;
    }
    
    $gradeDAO->insert ($ppo->grade); 
    
    // Récupérations des années scolaires
    $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $ppo->grades = $gradeDAO->findAll ();

	  return _arPPO ($ppo, 'manage_grades.tpl');
	}
	
	public function processSetCurrentGrade () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  $gradeId = _request ('gradeId', null);
	  
	  $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $grades = $gradeDAO->findAll ();
	  
	  foreach ($grades as $grade) {
	    
	    $grade->current = 0;
	    $gradeDAO->update ($grade);
	  }
	  
	  $newCurrentGrade = $gradeDAO->get ($gradeId);
	  $newCurrentGrade->current = 1;
	  $gradeDAO->update ($newCurrentGrade);
	  
	  // Récupérations des années scolaires
	  $ppo->grades = $gradeDAO->findAll ();
	  
	  return _arPPO ($ppo, 'manage_grades.tpl');
	}
	
	public function processUpdateGrade () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');

	  if (!$ppo->grade = $gradeDAO->get (_request ('gradeId', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->grade->dateDebut    = CopixDateTime::yyyymmddToDate($ppo->grade->dateDebut);
	  $ppo->grade->dateFin      = CopixDateTime::yyyymmddToDate($ppo->grade->dateFin);
	  
	  return _arPPO ($ppo, 'update_grade.tpl');
	}
	
	public function processValidateGradeUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  
	  if (!$ppo->grade = $gradeDAO->get (_request ('id_as', null))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

    $dateDebut                  = _request ('dateDebut', null);
    $dateFin                    = _request ('dateFin', null);

	  $ppo->grade->annee_scolaire = substr($dateDebut, 6, 10).'-'.substr($dateFin, 6, 10);
	  $ppo->grade->dateDebut      = CopixDateTime::dateToyyyymmdd($dateDebut);
	  $ppo->grade->dateFin        = CopixDateTime::dateToyyyymmdd($dateFin);
	  if (_request ('current', null) == 'on') {
      
      $ppo->grade->current = 1;
    }                    
    else {
      
      $ppo->grade->current = 0;
    }
	  
    if (!$ppo->grade->dateDebut) {
      
      $ppo->errors[] = 'Saisissez une date de début';
    }
    if (!$ppo->grade->dateFin) {
      
      $ppo->errors[] = 'Saisissez une date de fin';
    }
    if ($ppo->grade->id_as != substr($dateDebut, 6, 10) && $gradeDAO->get ($ppo->grade->id_as)) {
      
      $ppo->errors[] = 'Cette année scolaire existe déjà';
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->grade->dateDebut    = CopixDateTime::yyyymmddToDate($ppo->grade->dateDebut);
  	  $ppo->grade->dateFin      = CopixDateTime::yyyymmddToDate($ppo->grade->dateFin);
  	  
      return _arPPO ($ppo, 'create_grade.tpl');
    }
    
    if ($ppo->grade->current == 1) {
      
      $currentGrade = Kernel::getAnneeScolaireCourante ();
      $currentGrade->current = 0;
    }
    
    $ppo->grade->id_as  = substr($dateDebut, 6, 10);
    
    $gradeDAO->update ($ppo->grade); 
    
    // Récupérations des années scolaires
    $gradeDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $ppo->grades = $gradeDAO->findAll ();

	  return _arPPO ($ppo, 'manage_grades.tpl');
	}
	
	public function processDeleteGrade () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion des années scolaires";
	  
	  $gradeId = _request ('gradeId', null);
	  
	  $gradesDAO = _ioDAO ('kernel_bu_annee_scolaire');
	  $gradesDAO->delete ($gradeId);
	  
	  // Récupérations des années scolaires
	  $ppo->grades = $gradesDAO->findAll ();
	  
	  return _arPPO ($ppo, 'manage_grades.tpl');
	}
	
	public function processAddExistingStudent () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);

	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }                                             
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'un élève existant');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
	  _sessionSet ('gestionautonome|addExisting', array ());                                                                         

	  $ppo->listFilters = array ();

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['id'];
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['id'];
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['ALL']->vil_id_vi;
        $ppo->listFilters['school']    = $nodeInfos['id'];
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';                            
        $ppo->listFilters['groupcity'] = '1';
        $ppo->listFilters['city']      = $nodeInfos['ALL']->eco_id_ville;
        $ppo->listFilters['school']    = $nodeInfos['ALL']->eco_numero;
        $ppo->listFilters['class']     = $nodeInfos['id'];
      	break;
		}
 
		_sessionSet ('gestionautonome|addExisting', $ppo->listFilters);

	  // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processFilterExistingStudents () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');                                                                         
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
    $ppo->listFilters['withAssignment'] = _request ('withAssignment', null);
    $ppo->listFilters['lastname']       = _request ('lastname', null);
    $ppo->listFilters['firstname']      = _request ('firstname', null);
    $ppo->listFilters['groupcity']      = _request ('groupcity', null);
    $ppo->listFilters['city']           = _request ('city', null);
    $ppo->listFilters['school']         = _request ('school', null);
    $ppo->listFilters['class']          = _request ('class', null);
    
    _sessionSet ('gestionautonome|addExisting', $ppo->listFilters);
    
    // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processValidateExistingStudentsAdd () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId      = _request ('id_node', null);
	  $ppo->nodeType    = _request ('type_node', null);
	  $ppo->role        = _request ('role', null);
	  $ppo->studentIds  = _request ('studentIds', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
    $studentRegistrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentAdmissionDAO     = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO    = _ioDAO ('kernel_bu_eleve_affectation');
    $classDAO                = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO     = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
  
	  if (!is_null ($ppo->studentIds)) {
	    
	    foreach ($ppo->studentIds as $studentId) {

        $level = _request ('level-'.$studentId, null);
        
        $class = $classDAO->get ($ppo->nodeId); 
        $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);
        $classType = $schoolClassLevels[0]->type;
        $schoolId = $class->ecole;

        // Inscription de l'élève dans l'école
        $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');

        $studentRegistration->eleve                   = $studentId;
        $studentRegistration->annee_scol              = Kernel::getAnneeScolaireCourante ()->id_as; 
        $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->etablissement           = $schoolId;
        $studentRegistration->etablissement_refus     = 0;
        $studentRegistration->id_niveau               = $level;
        $studentRegistration->id_typ_cla              = $classType;
        $studentRegistration->vaccins_aj              = 0;
        $studentRegistration->attente                 = 0;
        $studentRegistration->derogation_dem          = 0; 
        $studentRegistration->temporaire              = 0;
        $studentRegistration->current_inscr           = 1; 
        
        $studentRegistrationDAO->insert ($studentRegistration);

        // Admission de l'élève dans l'école
        $studentAdmission = _record ('kernel_bu_eleve_admission');

        $studentAdmission->eleve          = $studentId;
        $studentAdmission->etablissement  = $classDAO->get ($ppo->nodeId)->ecole;
        $studentAdmission->annee_scol     = Kernel::getAnneeScolaireCourante ()->id_as;
        $studentAdmission->id_niveau      = $level;
        $studentAdmission->etat_eleve     = 1;
        $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->code_radiation = '';
        $studentAdmission->previsionnel   = '';

        $studentAdmissionDAO->insert ($studentAdmission);

        // Affectation de l'élève dans les classes
        $studentAssignment = _record ('kernel_bu_eleve_affectation');

        $studentAssignment->eleve           = $studentId;
        $studentAssignment->annee_scol      = Kernel::getAnneeScolaireCourante ()->id_as;
        $studentAssignment->classe          = $ppo->nodeId;
        $studentAssignment->niveau          = $level;
        $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAssignment->current         = 1;
        $studentAssignment->previsionnel_cl = 0;

        $studentAssignmentDAO->insert ($studentAssignment);
  	  }
  	  
  	  $ppo->save = 1;
	  }
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
	  // Récupération des niveaux de la classe
    $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');
    
	  $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);
	  
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classSchoolLevels as $classSchoolLevel) {
      
      $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
      $ppo->levelNames[] = $level->niveau_court;
      $ppo->levelIds[]   = $level->id_n;
    }
	  
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->findStudentsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_student.tpl');
	}
	
	public function processAddExistingPersonnel () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }                                             
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
	  _sessionSet ('gestionautonome|addExisting', array ());                                                                         

	  $ppo->listFilters = array ();
	  
	  switch ($ppo->role) {
	    
	    case '1' :
			case '2' :
				$ppo->listFilters['user_type'] = 'USER_ENS';
				break;
			case '3' :
			  $ppo->listFilters['user_type'] = 'USER_ADM';
				break;
			case '4' :
			case '5' :
  		  $ppo->listFilters['user_type'] = 'USER_VIL';
  		  break;
	  }

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['id'];
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['id'];
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
        $ppo->listFilters['groupcity'] = $nodeInfos['ALL']->vil_id_grville;
        $ppo->listFilters['city']      = $nodeInfos['ALL']->vil_id_vi;
        $ppo->listFilters['school']    = $nodeInfos['id'];
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';                            
        $ppo->listFilters['groupcity'] = '1';
        $ppo->listFilters['city']      = $nodeInfos['ALL']->eco_id_ville;
        $ppo->listFilters['school']    = $nodeInfos['ALL']->eco_numero;
        $ppo->listFilters['class']     = $nodeInfos['id'];
      	break;
		}
		
		$ppo->listFilters['withAssignment'] = true;
 
		_sessionSet ('gestionautonome|addExisting', $ppo->listFilters);

	  $personDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}              
	
	public function processFilterExistingPersonnel () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  $ppo->role     = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une personne existante');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
	  
	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');                                                                         
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
    $ppo->listFilters['withAssignment'] = _request ('withAssignment', null);
    $ppo->listFilters['lastname']       = _request ('lastname', null);
    $ppo->listFilters['firstname']      = _request ('firstname', null);
    $ppo->listFilters['groupcity']      = _request ('groupcity', null);
    $ppo->listFilters['city']           = _request ('city', null);
    $ppo->listFilters['school']         = _request ('school', null);
    $ppo->listFilters['class']          = _request ('class', null);
    
    _sessionSet ('gestionautonome|addExisting', $ppo->listFilters);
    
    $personDAO = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);

	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}
	
	public function processValidateExistingPersonsAdd () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId      = _request ('id_node', null);
	  $ppo->nodeType    = _request ('type_node', null);
	  $ppo->role        = _request ('role', null);
	  $ppo->personIds   = _request ('personIds', null); 
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

	  switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $type_ref  = 'CLASSE';
      	break;
		}
	  
	  $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
   
	  if (is_array ($ppo->personIds) && !empty ($ppo->personIds)) {

	    foreach ($ppo->personIds as $personId) {

        if (!$personEntityDAO->getByIdReferenceAndType ($personId, $ppo->nodeId, $type_ref)) {

          // Création de l'association kernel_bu_personnel_entite
          $newPersonEntity = _record ('kernel|kernel_bu_personnel_entite');

          $newPersonEntity->pers_entite_id_per    = $personId;
          $newPersonEntity->pers_entite_reference = $ppo->nodeId;
          $newPersonEntity->pers_entite_type_ref  = $type_ref;
          $newPersonEntity->pers_entite_role      = $ppo->role;

          $personEntityDAO->insert ($newPersonEntity);
        }
  	  }
	  }
	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
	  
	  $personDAO    = _ioDAO ('kernel|kernel_bu_personnel');
	  $ppo->persons = $personDAO->findPersonnelsForAssignment ($ppo->nodeId, $type_ref, $ppo->listFilters);
	  $ppo->save    = 1;

	  return _arPPO ($ppo, 'add_existing_personnel.tpl');
	}
	
	public function processAddMultipleStudents () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('parentId', null);
  	$ppo->nodeType = _request ('parentType', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
  	
  	$nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
  	
  	// RAZ des sessions
  	_sessionSet ('gestionautonome|addMultipleStudents', array ());
  	_sessionSet ('gestionautonome|addMultipleStudents|success', array ());
  	_sessionSet ('gestionautonome|addMultipleStudents|error', array ());
	  
	  return _arPPO ($ppo, 'add_multiple_students.tpl');
	}
	
	public function processValidateMultipleStudentsAdd () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $ppo->nodeId   = _request ('id_parent', null);
  	$ppo->nodeType = _request ('type_parent', null);
  	$liste    = _request ('liste', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
  	
  	$nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");

    if (!is_null ($liste)) {
      
      $lines = explode("\n", nl2br($liste));

      $ppo->students = array ();

      foreach ($lines as $key => $line) {

         $datas = explode (',', $line);

         // Données de l'élève : nom - prénom - sexe - DDN
         if (isset ($datas[0])) {

           $ppo->students[$key]['lastname'] = $datas[0];
         }

         if (isset ($datas[1])) {

           $ppo->students[$key]['firstname'] = $datas[1];
         }

         if (isset ($datas[2])) {

           if (substr($datas[2], 0, 1) == 'M') {

             $ppo->students[$key]['gender'] = 0;
           }
           else {

             $ppo->students[$key]['gender'] = 1;
           }
         }

         if (isset ($datas[3])) {

           $ppo->students[$key]['birthdate'] = $datas[3];
         }

         // Données des responsables : nom - prénom - sexe - relation
         $continue = true;
         $keyPerson=0;
         $cpt = 4;

         while ($continue) {

           if (isset($datas[$cpt])) {

             switch ($cpt - (4*($keyPerson+1))) {

               case 0:
                 $ppo->students[$key]['person'][$keyPerson]['lastname'] = $datas[$cpt];
                 break;
               case 1:       
                 $ppo->students[$key]['person'][$keyPerson]['firstname'] = $datas[$cpt];
                 break; 
               case 2:
                 if (substr($datas[$cpt], 0, 1) == 'M') {

                   $ppo->students[$key]['person'][$keyPerson]['gender'] = 0;
                 }
                 else {

                   $ppo->students[$key]['person'][$keyPerson]['gender'] = 1;
                 }
                 break;
               case 3: 
                 $ppo->students[$key]['person'][$keyPerson]['nom_pa'] = $datas[$cpt];

                 switch (strip_tags(trim($datas[$cpt]))) {
                   case 'MERE':
                     $id_par = 1;
                     break;
                   case 'PERE':
                     $id_par = 2;
                     break;
                   case 'FRERE':
                     $id_par = 3;
                     break;
                   case 'SOEUR':
                     $id_par = 4;
                     break;
                   case 'GRAND-PERE':
                     $id_par = 5;
                     break;
                   case 'GRAND-MERE':
                     $id_par = 6;
                     break;
                   case 'ONCLE':
                     $id_par = 7;
                     break;
                   case 'TANTE':
                     $id_par = 8;
                     break;
                   case 'COLLATERAUX':
                     $id_par = 9;
                     break;
                   case 'TUTEUR':
                     $id_par = 10;
                     break;
                   case 'INCONNU':
                     $id_par = 11;
                     break;
                 }

                 $ppo->students[$key]['person'][$keyPerson]['id_par'] = $id_par;
                 break;
               }
             }
           else {

             $continue = false;
           }

           if (($cpt+1) % 4 == 0) {

             $ppo->students[$key]['person'][$keyPerson]['login']    = Kernel::createLogin (array ('nom' => $ppo->students[$key]['person'][$keyPerson]['lastname'], 'prenom' => $ppo->students[$key]['person'][$keyPerson]['firstname'], 'type' => 'USER_RES'));
             $ppo->students[$key]['person'][$keyPerson]['password'] = Kernel::createPasswd ();
             $keyPerson++;
           }       

           $cpt++; 
         }

         $ppo->students[$key]['login']    = Kernel::createLogin (array ('nom' => $ppo->students[$key]['lastname'], 'prenom' => $ppo->students[$key]['firstname'], 'type' => 'USER_ELE'));
         $ppo->students[$key]['password'] = Kernel::createPasswd ();
      }

      // Mise en session des informations récupérées
      _sessionSet ('gestionautonome|addMultipleStudents', $ppo->students);

      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');

      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();

      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }
    }

	  return _arPPO ($ppo, 'add_multiple_students_listing.tpl');
	}
	
	public function processValidateMultipleStudentsListing () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
  	$ppo->nodeType = _request ('type_node', null);
  	
  	if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }                  
  	
  	// Récupération des informations du formulaire                                             
  	$keys      = _request ('keys', array ());       // Elèves de la liste à créer (checkbox de confirmation)
  	$logins    = _request ('logins', array ());     // Logins des élèves (possibilité de changer le login généré)
  	$passwords = _request ('passwords', array ());  // Passwords des élèves (possibilité de changer le mot de passe généré)
  	$levels    = _request ('levels', array ());     // Niveau des élèves dans la classe
  	
  	$nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Ajout d\'une liste d\'élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
                                  
    // Récupération des élèves déjà créés en session
  	if (!$ppo->studentsSuccess = _sessionGet ('gestionautonome|addMultipleStudents|success')) {
  	  
  	  $ppo->studentsSuccess = array ();
  	}
  	
  	// RAZ des créations en erreur
  	_sessionSet ('gestionautonome|addMultipleStudents|error', array());
  	
  	// DAO 
  	$studentDAO              = _ioDAO ('kernel_bu_eleve');
    $studentRegistrationDAO  = _ioDAO ('kernel|kernel_bu_eleve_inscription');
    $studentAdmissionDAO     = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO    = _ioDAO ('kernel_bu_eleve_affectation');
    $dbuserDAO               = _ioDAO ('kernel|kernel_copixuser'); 
    $dbLinkDAO               = _ioDAO ('kernel_link_bu2user');
    $classDAO                = _ioDAO ('kernel_bu_ecole_classe');
    $personDAO               = _ioDAO ('kernel_bu_responsable');
    $personLinkDAO           = _ioDAO ('kernel_bu_responsables'); 
    $schoolClassLevelDAO     = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
  	
  	$node_infos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, false);
  	
  	// Récupération des élèves en création
    $students = _sessionGet ('gestionautonome|addMultipleStudents');
    
    // Boucle sur les élèves de la liste à créer
    foreach ($keys as $key) {
      
      // La création n'est possible que si le login est disponible
      if (!is_null ($logins[$key]) && !is_null ($passwords[$key]) && Kernel::isLoginAvailable ($logins[$key])) {

        $students[$key]['login']    = $logins[$key];                                              // Récupération des logins pour les élèves en création
        $students[$key]['password'] = $passwords[$key];                                           // Récupération des passwords pour les élèves en création
        $students[$key]['level']    = $levels[$key];                                              // Récupération des niveaux pour les élèves en création 
  			$students[$key]['type_nom'] = Kernel::Code2Name('USER_ELE');
  			$students[$key]['node_nom'] = Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'];
         
        // Création de l'élève
        $ppo->student = _record ('kernel_bu_eleve');

        $ppo->student->numero       = '';                        
        $ppo->student->nom          = $students[$key]['lastname'];
        $ppo->student->prenom1      = $students[$key]['firstname'];
        $ppo->student->id_sexe      = $students[$key]['gender'];             
        $ppo->student->date_nais    = (isset ($students[$key]['birthdate'])) ? CopixDateTime::dateToYYYYMMDD (strip_tags(trim($students[$key]['birthdate']))) : '';
        $ppo->student->flag         = 0;
        $ppo->student->ele_last_update = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
        
        $studentDAO->insert ($ppo->student);
        
        // Création du compte dbuser
        $dbuser = _record ('kernel|kernel_copixuser');
        
        $dbuser->login_dbuser    = $students[$key]['login'];
        $dbuser->password_dbuser = md5 ($students[$key]['password']);
        $dbuser->email_dbuser    = '';
        $dbuser->enabled_dbuser  = 1;
        
        $dbuserDAO->insert ($dbuser);
        
        // Création du link bu2user
        $dbLink = _record ('kernel_link_bu2user');
        
        $dbLink->user_id = $dbuser->id_dbuser;
        $dbLink->bu_type = 'USER_ELE';
        $dbLink->bu_id   = $ppo->student->idEleve;
        
        $dbLinkDAO->insert ($dbLink);

        // Récupération des données nécessaires à l'ajout des enregistrements inscription / adhésion / admission
        $class = $classDAO->get ($ppo->nodeId); 
        $schoolClassLevels = $schoolClassLevelDAO->getByClass ($ppo->nodeId);
        $classType = $schoolClassLevels[0]->type;
        $schoolId = $class->ecole;
        $currentGradeId = Kernel::getAnneeScolaireCourante ()->id_as;
        
        // Inscription de l'élève dans l'école
        $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');
        
        $studentRegistration->eleve                   = $ppo->student->idEleve;
        $studentRegistration->annee_scol              = $currentGradeId;
        $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentRegistration->etablissement           = $schoolId;
        $studentRegistration->etablissement_refus     = 0;
        $studentRegistration->id_niveau               = $students[$key]['level'];
        $studentRegistration->id_typ_cla              = $classType;
        $studentRegistration->vaccins_aj              = 0;
        $studentRegistration->attente                 = 0;
        $studentRegistration->derogation_dem          = 0; 
        $studentRegistration->temporaire              = 0;
        $studentRegistration->current_inscr           = 1; 
        
        $studentRegistrationDAO->insert ($studentRegistration);
        
        // Admission de l'élève dans l'école
        $studentAdmission = _record ('kernel_bu_eleve_admission');
        
        $studentAdmission->eleve          = $ppo->student->idEleve;
        $studentAdmission->etablissement  = $schoolId;
        $studentAdmission->annee_scol     = $currentGradeId;
        $studentAdmission->id_niveau      = $students[$key]['level'];
        $studentAdmission->etat_eleve     = 1;
        $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAdmission->code_radiation = '';
        $studentAdmission->previsionnel   = '';
        
        $studentAdmissionDAO->insert ($studentAdmission);
        
        // Affectation de l'élève dans les classes
        $studentAssignment = _record ('kernel_bu_eleve_affectation');
        
        $studentAssignment->eleve           = $ppo->student->idEleve;
        $studentAssignment->annee_scol      = $currentGradeId;
        $studentAssignment->classe          = $ppo->nodeId;
        $studentAssignment->niveau          = $students[$key]['level'];
        $studentAssignment->dateDebut       = CopixDateTime::timestampToYYYYMMDD (time ());
        $studentAssignment->current         = 1;
        $studentAssignment->previsionnel_cl = 0;
        
        $studentAssignmentDAO->insert ($studentAssignment);
        
        // Récupération des responsables sélectionnés pour l'élève
        $personsKeys      = _request ('person-keys'.$key, array ());
        $personsLogins    = _request ('logins'.$key, array ());        // Récupération des logins des responsables de l'élève
        $personsPasswords = _request ('passwords'.$key, array ());  // Récupération des passwords des responsables de l'élève
        
        foreach ($personsKeys as $personKey) {

          // La création du responsable n'est possible que si le login est disponible
          if (!is_null ($personsLogins[$personKey]) && !is_null ($personsPasswords[$personKey]) && Kernel::isLoginAvailable ($personsLogins[$personKey])) {
            
            $students[$key]['person'][$personKey]['login']    = $personsLogins[$personKey];
            $students[$key]['person'][$personKey]['password'] = $personsPasswords[$personKey];
      			$students[$key]['person'][$personKey]['type_nom'] = Kernel::Code2Name('USER_RES');
      			$students[$key]['person'][$personKey]['node_nom'] = Kernel::Code2Name($ppo->nodeType)." ".$node_infos['nom'];
            
            // Création du responsable
            $ppo->person = _record ('kernel_bu_responsable');

            $ppo->person->nom        = $students[$key]['person'][$personKey]['lastname'];
            $ppo->person->prenom1    = $students[$key]['person'][$personKey]['firstname'];
            $ppo->person->id_sexe    = $students[$key]['person'][$personKey]['gender'];
          
            $personDAO->insert ($ppo->person);

            // Création du compte dbuser
            $dbuser = _record ('kernel|kernel_copixuser');

            $dbuser->login_dbuser    = $students[$key]['person'][$personKey]['login'];
            $dbuser->password_dbuser = md5 ($students[$key]['person'][$personKey]['password']);
            $dbuser->email_dbuser    = '';
            $dbuser->enabled_dbuser  = 1;

            $dbuserDAO->insert ($dbuser);

            // Création du link bu2user
            $dbLink = _record ('kernel_link_bu2user');

            $dbLink->user_id = $dbuser->id_dbuser;
            $dbLink->bu_type = 'USER_RES';
            $dbLink->bu_id   = $ppo->person->numero;

            $dbLinkDAO->insert ($dbLink);

            // Création de l'association personne->rôle
            $newPersonLink = _record ('kernel_bu_responsables');

        		$newPersonLink->id_beneficiaire   = $ppo->student->idEleve; 
        		$newPersonLink->type_beneficiaire = 'eleve';
        		$newPersonLink->id_responsable    = $ppo->person->numero;
        		$newPersonLink->type              = 'responsable';
        		$newPersonLink->auth_parentale    = '0';
        		$newPersonLink->id_par            = $students[$key]['person'][$personKey]['id_par'];

        		$personLinkDAO->insert ($newPersonLink);
      		}
        }
        
        // Récupération de l'élève (et de ses responsables) dans le tableau des élèves créés avec succès
        $ppo->studentsSuccess[] = $students[$key];
      }
      
      // Si le login n'est pas disponible, la création n'est pas possible
      else {
        
        // Ajout de l'élève (et de ses responsables) dans le tableau des créations en erreur
        $ppo->studentsError[] = $students[$key];
      }
    }

    // Si des créations ont bien eu lieu, les mettre en session pour affichage de confirmation 
    if (!empty ($ppo->studentsSuccess)) {
      
      _sessionSet ('gestionautonome|addMultipleStudents|success', $ppo->studentsSuccess);
    }
    
    // Si des créations sont en erreur, les mettre en session pour possibilité de recréation après correction des erreurs
    if (!empty ($ppo->studentsError)) {
      
      _sessionSet ('gestionautonome|addMultipleStudents|error', $ppo->studentsError);
      $ppo->students = $ppo->studentsError;
      
      _sessionSet ('gestionautonome|addMultipleStudents', $ppo->studentsError);
      
      // Récupération des niveaux de la classe
      $classSchoolLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
      $classLevelDAO       = _ioDAO ('kernel_bu_classe_niveau');

      $classSchoolLevels   = $classSchoolLevelDAO->getByClass ($ppo->nodeId);

      $ppo->levelNames = array ();
      $ppo->levelIds   = array ();

      foreach ($classSchoolLevels as $classSchoolLevel) {

        $level             = $classLevelDAO->get ($classSchoolLevel->niveau);
        $ppo->levelNames[] = $level->niveau_court;
        $ppo->levelIds[]   = $level->id_n;
      }
      
      // Flag pour affichage du message d'erreur
      $ppo->error = 1;
      
      return _arPPO ($ppo, 'add_multiple_students_listing.tpl');
    }

    $urlReturn = CopixUrl::get ('gestionautonome||showMultipleAccountsListing', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType));
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}
	
	public function processShowMultipleAccountsListing () {
	                                                                        
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération des informations des comptes créés
	  $ppo->students = _sessionGet ('gestionautonome|addMultipleStudents|success'); 

    // Récupération du format de sortie demandé
	  if( !_request ('format') || trim (_request ('format')) == '' ) {
	    
			$format = "default";
		} 
		else {
		  
			$format = _request('format');
		} 
		
		// Sortie suivant le format demandé
		$tplResult = & new CopixTpl ();
		$tplResult->assign ('sessionDatas', $ppo->students);
		
	  switch ($format) {
			case 'default':
				return _arPPO ($ppo, 'multiple_accounts_listing.tpl');
			case 'html':
			  $result = $tplResult->fetch ('account_listing_html.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.html')));
			  break;
			case 'csv':
			  $result = $tplResult->fetch ('account_listing_csv.tpl');
			  return _arContent ($result, array ('filename'=>'Logins-'.date('YmdHi').'.csv', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.csv')));
			  break;
		}
	}
	
	// AJAX
	public function processGenerateLogin () {
	                              
	  $users_infos ['nom']    = _request ('lastname', null);
	  $users_infos ['prenom'] = _request ('firstname', null); 
	  $users_infos ['type']   = _request ('type', null);
                                             
	  echo Kernel::createLogin ($users_infos); 
	  
	  return _arNone ();
	}
	
	// AJAX
	public function processGeneratePassword () {
	  
	  echo Kernel::createPasswd ();
	  
	  return _arNone ();
	}
	
	public function processRefreshCityFilter () {
	                                  
	  $cityGroupId = _request ('city_group_id', null);
	  if (!is_null ($cityGroupId)) {
	    
	    $groupDAO = _ioDAO ('kernel_bu_groupe_villes');
	    if ($group = $groupDAO->get($cityGroupId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterCity', array('city_group_id' => $cityGroupId));
	    }
	  }
    
    return _arNone ();
	}
	
	public function processRefreshSchoolFilter () {

	  $cityId = _request ('city_id', null);
	  if (!is_null ($cityId)) {
	    
	    $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	    if ($city = $cityDAO->get($cityId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterSchool', array('city_id' => $cityId));
	    }
	  }
	  
    return _arNone ();
	}
	
	public function processRefreshClassFilter () {
	  
	  $schoolId = _request ('school_id', null);
	  if (!is_null ($schoolId)) {
	    
	    $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	    if ($school = $schoolDAO->get($schoolId)) {
	      
	      echo CopixZone::process ('gestionautonome|filterClass', array('school_id' => $schoolId));
	    }
	  }

    return _arNone ();
	}
	
	public function processChangeStudentsAffect () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);
    
    // Breadcrumbs
	  $breadcrumbs      = Kernel::generateBreadcrumbs ($nodeInfos);
	  $breadcrumbs[]    = array('txt' => 'Changer d\'affectation plusieurs élèves');
	  $ppo->breadcrumbs = Kernel::PetitPoucet($breadcrumbs," &raquo; ");
    
    // DAO
    $classLevelDAO = _ioDAO ('kernel_bu_classe_niveau');
    $schoolClassDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $schoolClassLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
    
    // ID de l'école
    $schoolId = $nodeInfos['ALL']->cla_ecole;                                               
    
    // Récupération des classes de l'école
    $classes = $schoolClassDAO->getBySchool ($schoolId);
    
    $ppo->levelNames = array ();
    $ppo->levelIds   = array ();
    
    foreach ($classes as $class) {
      
      $schoolClassLevels = $schoolClassLevelDAO->getByClass ($class->id);

      foreach ($schoolClassLevels as $schoolClassLevel) {

        $level             = $classLevelDAO->get ($schoolClassLevel->niveau);

        $ppo->levelNames[] = $level->niveau_court.' - '.$class->nom;
        $ppo->levelIds[]   = $level->id_n.'-'.$class->id;
      }
    }
    
    $ppo->levelNames[] = '-- quitte la classe (sans affectation définie) --';
    $ppo->levelIds[] = '0-0';
                    
	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->students = $studentDAO->getStudentsByClass ($ppo->nodeId);

	  return _arPPO ($ppo, 'change_students_affect.tpl');
	}
	
	public function processValidateChangeStudentsAffect () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // DAO
	  $studentAdmissionDAO  = _ioDAO ('kernel_bu_eleve_admission');
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect'); 
	  
	  // Récupération des informations du noeud
	  $nodeInfos = Kernel::getNodeInfo ($ppo->nodeType, $ppo->nodeId, true);

	  // ID de l'école
    $schoolId = $nodeInfos['ALL']->cla_ecole;
	  
	  $students   = _request ('students', null);
	  $newAffects = _request ('newAffects', null);
	  
	  foreach ($newAffects as $key => $newAffect) {
	    
	    // Si l'affectation change 
	    if ($newAffect != "") {
	    
	      // Récupération de l'élève
	      $studentId = $students[$key];                          
	      
	      // Récupération des données de la nouvelle affectation : classe et niveau
  	    $datas = explode ('-', $newAffect);
  	    $level = $datas[0];
  	    $class = $datas[1];

        // Si option : -- quitte la classe (sans affectation définie) --
  	    if ($class == 0 && $level == 0) {
  	      
  	      // Ajout d'un enregistrement de radiation
      	  $studentAdmission = _record ('kernel_bu_eleve_admission');

          $studentAdmission->eleve          = $studentId;
          $studentAdmission->etablissement  = $schoolId;
          $studentAdmission->annee_scol     = Kernel::getAnneeScolaireCourante ()->id_as;
          $studentAdmission->id_niveau      = '';
          $studentAdmission->etat_eleve     = 3;
          $studentAdmission->date           = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentAdmission->date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
          $studentAdmission->code_radiation = '';
          $studentAdmission->previsionnel   = '';

          $studentAdmissionDAO->insert ($studentAdmission);

          // Passage du flag de l'affectation de l'élève à 0
          $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $ppo->nodeId);
          if ($studentAssignment) {                            
  	        
  	        $studentAssignment->affect_current = 0;
            $studentAssignmentDAO->update ($studentAssignment);
  	      }
  	    }
  	    else {
  	      
  	      // Passage du flag de l'affectation de l'élève à 0 
  	      $studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $ppo->nodeId);
  	      if ($studentAssignment) {                            
  	        
  	        $studentAssignment->affect_current = 0;
            $studentAssignmentDAO->update ($studentAssignment);
  	      }
          
          // Affectation de l'élève dans sa nouvelle classe
          $newStudentAssignment = _record ('kernel|kernel_bu_ele_affect');

          $newStudentAssignment->affect_eleve       = $studentId;
          $newStudentAssignment->affect_annee_scol  = Kernel::getAnneeScolaireCourante ()->id_as;
          $newStudentAssignment->affect_classe      = $class;
          $newStudentAssignment->affect_niveau      = $level;
          $newStudentAssignment->affect_dateDebut   = CopixDateTime::timestampToYYYYMMDD (time ());
          $newStudentAssignment->affect_current     = 1;
          
          $studentAssignmentDAO->insert ($newStudentAssignment);
  	    }  
	    }
	  }
	  
	  return _arRedirect (CopixUrl::get ('gestionautonome||showTree', array ('nodeId' => $ppo->nodeId, 'nodeType' => $ppo->nodeType, 'save' => 1)));
	}
}