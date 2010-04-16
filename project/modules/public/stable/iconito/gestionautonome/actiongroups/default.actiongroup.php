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

/**
 * @author	xxxxx
 */
class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
		
		CopixHTMLHeader::addCSSLink (_resource ('styles/module_gestionautonome.css'));
	}
	
	public function processShowTree () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération du noeud ROOT
	  $groupcity = Kernel::getNodeChilds ('ROOT', 0);
	  $groupcity = Kernel::filterNodeList ($groupcity, 'BU_GRVILLE');
    
    $ppo->root = $groupcity[0];
    
    // Y a t-il eu des modifications ?
    $ppo->save = _request ('save', null);
    
    // Récupération du noeud cible (noeud courant)
    $ppo->targetId   = _request ('nodeId');
    $ppo->targetType = _request ('nodeType');

		return _arPPO ($ppo, 'show_tree.tpl'); 
	}
	
	/**
	 * displayPersonsData (Ajax)
	 *
	 * Récupération des personnes du noeud courant.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processDisplayPersonsData () {

	  $ppo = new CopixPPO ();                                       
	  
	  $id   = _request ('nodeId', null);
	  $type = _request ('nodeType', null);
	  
		echo CopixZone::process ('gestionautonome|PersonsData', array ('nodeId' => $id, 'nodeType' => $type));
    
    return _arNone ();
	}
	
	/**
	 * updateTreeActions (Ajax)
	 *
	 * Récupération des actions disponibles pour le noeud courant.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processUpdateTreeActions () {

	  $ppo = new CopixPPO ();
	  
	  $id   = _request ('nodeId', null);
	  $type = _request ('nodeType', null);
	  
		echo CopixZone::process ('gestionautonome|TreeActions', array ('nodeId' => $id, 'nodeType' => $type));
    
    return _arNone ();
	}
	
	/**
	 * createCity
	 *
	 * Création d'une ville.
	 * @author	xxxxx
	 * @since	xxxx
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
		
		return _arPPO ($ppo, 'create_city.tpl');
	}
	
	/**
	 * validateCityCreation
	 *
	 * Validation du formulaire de création de ville.
	 * @author	xxxxx
	 * @since	xxxx
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
	  
		_classInclude ('kernel|Tools');
    $cityName = _request ('nom', null);
        
    $cityDAO = _ioDAO ('kernel_bu_ville');
        
    $ppo->city = _record ('kernel_bu_ville');
    
    $ppo->city->nom           = Tools::capitalizeFirst (trim ($cityName));
    $ppo->city->canon         = strtolower (trim ($cityName));
    $ppo->city->id_grville    = $ppo->parentId;
    $ppo->city->date_creation = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->city->id_grville) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.unknownError');
    }
    
    if (!empty ($ppo->errors)) {
      
      return _arPPO ($ppo, 'create_city.tpl');
    }
      
    // Insertion de la ville
    $cityDAO->insert ($ppo->city);
    
    $ppo->nodeId   = $ppo->city->id_vi;
		$ppo->nodeType = 'BU_VILLE';

		return _arPPO ($ppo, array ('template' => 'create_success.tpl', 'mainTemplate' => null));
  }
  
  /**
	 * updateCity
	 *
	 * Edition d'une ville.
	 * @author	xxxxx
	 * @since	xxxx
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
	  
	  // Récupération de la ville
	  $cityDAO   = _ioDAO ('kernel_bu_ville');
	  $ppo->city = $cityDAO->get ($ppo->nodeId);
		
		return _arPPO ($ppo, 'update_city.tpl');
	}
	
	/**
	 * validateCityUpdate
	 *
	 * Validation du formulaire d'édition de ville.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processValidateCityUpdate () {
	  
	  $ppo = new CopixPPO ();
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  $cityDAO = _ioDAO ('kernel_bu_ville');
	  
	  $ppo->nodeId   = _request ('id_node', null);
		$ppo->nodeType = _request ('type_node', null);
		
	  // Récupération des paramètres
	  if (!$ppo->city = $cityDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

    _classInclude ('kernel|Tools');
      
    $cityName = _request ('name', null);
    
    $ppo->city->nom   = Tools::capitalizeFirst (trim ($cityName));
    $ppo->city->canon = strtolower (trim ($cityName));

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->city->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    
    if (!empty ($ppo->errors)) {
      
      return _arPPO ($ppo, 'update_city.tpl');
    }
      
    $cityDAO->update ($ppo->city);
		
		return _arPPO ($ppo, array ('template' => 'update_success.tpl', 'mainTemplate' => null));
	}
	
	/**
	 * deleteVille
	 *
	 * Suppression d'une ville.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processDeleteCity () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  $nodeType = _request ('nodeType', null);
	  
	  $cityDAO       = _ioDAO ('kernel_bu_ville');
	  $schoolDAO     = _ioDAO ('kernel|kernel_bu_ecole');
	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');
	  
	  if ($nodeType != 'BU_VILLE' || !$city = $cityDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
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
	      
	      // Suppression de la classe
	      $classDAO->delete ($class->id);
	    }
	    
	    // Suppression de l'école
	    $schoolDAO->delete ($school->numero);
	  }
	  
	  // Suppression de la ville 
  	$cityDAO->delete ($city->id_vi);
		
		return _arPPO ($ppo, 'update_success.tpl');
	}
	
	/**
	 * createEcole
	 *
	 * Création d'une école.
	 * @author	xxxxx
	 * @since	xxxx
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
		
		return _arPPO ($ppo, 'create_school.tpl');
	}
	
	/**
	 * validateSchoolCreation
	 *
	 * Validation du formulaire de création d'une école.
	 * @author	xxxxx
	 * @since	xxxx
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
	  
		_classInclude ('kernel|Tools');
		
    $schoolDAO = _ioDAO ('kernel_bu_ecole');
        
    $ppo->school = _record ('kernel_bu_ecole');
    
    $ppo->school->RNE                 = _request ('RNE', null);
    $ppo->school->code_ecole_vaccination = '';
    $ppo->school->type                = _request ('type', null);
    $ppo->school->nom                 = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->school->num_rue             = _request ('num_rue', null);
    $ppo->school->adresse1            = _request ('adresse1', null);
    
    if (is_null ($ppo->school->adresse2 = _request ('adresse2', null))) {
      
      $ppo->school->adresse2 = '';
    }
    else {
      
      $ppo->school->adresse2 = _request ('adresse2', null);
    }
    
    $ppo->school->code_postal         = _request ('code_postal', null);
    $ppo->school->commune             = _request ('commune', null);
    $ppo->school->tel                 = _request ('tel', null);
    $ppo->school->web                 = _request ('web', null);
    $ppo->school->mail                = _request ('mail', null);
    $ppo->school->numordre            = 0;
    $ppo->school->num_plan_interactif = 0;
    $ppo->school->id_ville            = $ppo->parentId;

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->RNE) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeRNE');
    }
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->school->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->school->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->school->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->school->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    if (!$ppo->school->tel) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typePhone');
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
      
      return _arPPO ($ppo, 'create_school.tpl');
    }
    
    $schoolDAO->insert ($ppo->school);
    
    $ppo->nodeId    = $ppo->school->numero;
    $ppo->nodeType  = 'BU_ECOLE';
		
		return _arPPO ($ppo, array ('template' => 'create_success.tpl', 'mainTemplate' => null));
	}
	
	/**
	 * updateSchool
	 *
	 * Edition d'une école.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processUpdateSchool () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  
    $schoolDAO  = _ioDAO ('kernel_bu_ecole');
    
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  // Récupération des villes pour select
    $cityDAO = _ioDAO ('kernel_bu_ville');
	  $cities  = $cityDAO->findAll ();
	  
    foreach ($cities as $city) {
      
      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }
    
    // Liste des types d'école
	  $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
		
		return _arPPO ($ppo, 'update_school.tpl');
	}
	
	/**
	 * validateSchoolUpdate
	 *
	 * Validation du formulaire d'édition d'une école.
	 * @author	xxxxx
	 * @since	xxxx
	 * 
	 */
	public function processValidateSchoolUpdate () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  
	  $schoolDAO = _ioDAO ('kernel_bu_ecole');
	  
	  // Récupération de l'école
	  if (!$ppo->school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  _classInclude ('kernel|Tools');
                         
    $ppo->school->RNE          = _request ('RNE', null);
    $ppo->school->code_ecole_vaccination = '';
    $ppo->school->type         = _request ('type', null);
    $ppo->school->nom          = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->school->num_rue      = _request ('num_rue', null);
    $ppo->school->adresse1     = _request ('adresse1', null);
    if (is_null ($ppo->school->adresse2 = _request ('adresse2', null))) {
      
      $ppo->school->adresse2 = '';
    }
    else {
      
      $ppo->school->adresse2 = _request ('adresse2', null);
    }
    $ppo->school->code_postal  = _request ('code_postal', null);
    $ppo->school->commune      = _request ('commune', null);
    $ppo->school->tel          = _request ('tel', null);
    $ppo->school->web          = _request ('web', null);
    $ppo->school->mail         = _request ('mail', null);
    $ppo->school->numordre            = 0;
    $ppo->school->num_plan_interactif = 0;
    $ppo->school->id_ville     = _request ('ville', null);
		
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->school->RNE) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeRNE');
    }
    if (!$ppo->school->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->school->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->school->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->school->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->school->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    if (!$ppo->school->tel) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typePhone');
    }
    
    if (!empty ($ppo->errors)) {
      
      $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();

  	  // Récupération des villes pour select
      $cityDAO = _ioDAO ('kernel_bu_ville');
  	  $cities  = $cityDAO->findAll ();

      foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }
      
      $ppo->types = array ('Maternelle', 'Elémentaire', 'Primaire');
      
      return _arPPO ($ppo, 'update_school.tpl');
    }
      
    $schoolDAO->update ($ppo->school);
    
		return _arPPO ($ppo, array ('template' => 'update_success.tpl', 'mainTemplate' => null));
	}
	
	public function processDeleteSchool () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);

	  $schoolDAO     = _ioDAO ('kernel_bu_ecole');
	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');

	  if ($ppo->nodeType != 'BU_ECOLE' || !$school = $schoolDAO->get ($ppo->nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération des classes de l'école
	  $classes = $classDAO->getBySchool ($school->numero);
	    
	  foreach ($classes as $class) {

	    // Récupération de l'association classe-niveau
	    $classLevels = $classLevelDAO->getByClass ($class->id);
	    
	    foreach ($classLevels as $classLevel) {
	      
	      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
	    }
	      
	    // Suppression de la classe
	    $classDAO->delete ($class->id);
	  }
	    
	  // Suppression de l'école
	  $schoolDAO->delete ($school->numero);

		return _arPPO ($ppo, 'update_success.tpl');
	}
	
	/**
	 * createClass
	 *
	 * Création d'une classe.
	 * @author	xxxxx
	 * @since	xxxx
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
	
	/**
	 * validateClassCreation
	 *
	 * Validation du formulaire de création d'une classe.
	 * @author	xxxxx
	 * @since	xxxx
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

		_classInclude ('kernel|Tools');
		
    $schoolClassDAO       = _ioDAO ('kernel_bu_ecole_classe');
    $schoolClassLevelDAO  = _ioDAO ('kernel_bu_ecole_classe_niveau');
    
    $ppo->levels  = _request ('levels', null);
    $ppo->type    = _request ('type', null);
   
    // Insertion de la classe
    $ppo->class = _record ('kernel_bu_ecole_classe');

    $ppo->class->ecole        = $ppo->parentId;
    $ppo->class->nom          = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->class->annee_scol   = Kernel::getAnneeScolaireCourante ()->id_as;
    $ppo->class->is_validee   = 1;
    $ppo->class->is_supprimee = 0;

    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.selectLevel');
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
    $newSchoolClassLevel = _record ('kernel_bu_ecole_classe_niveau');

    foreach ($ppo->levels as $level) {
      
      $newSchoolClassLevel->classe = $ppo->class->id;
      $newSchoolClassLevel->niveau = $level;
      $newSchoolClassLevel->type   = $ppo->type;

      $schoolClassLevelDAO->insert ($newSchoolClassLevel);
    }	
    
    $ppo->nodeId   = $ppo->class->id;
		$ppo->nodeType = 'BU_CLASSE';
		
		return _arPPO ($ppo, array ('template' => 'create_success.tpl', 'mainTemplate' => null));
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
	  $classDAO = _ioDAO ('kernel_bu_ecole_classe');
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
    $schoolDAO = _ioDAO ('kernel_bu_ecole');
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
	  
	  _classInclude ('kernel|Tools');
                         
    $ppo->class->ecole  = _request ('ecole', null);
    $ppo->class->nom    = Tools::capitalizeFirst (trim (_request ('nom', null)));

    // Affectations classe-niveau
    $schoolClassLevel = _record ('kernel|kernel_bu_ecole_classe_niveau');

    $ppo->levels = _request ('niveaux', null);
    $ppo->type   = _request ('type', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->class->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    
    if (!$ppo->levels) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.selectLevel');
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
      $schoolDAO = _ioDAO ('kernel_bu_ecole');
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
		
		$ppo->targetId   = $ppo->class->id;
		$ppo->targetType = 'BU_CLASSE';

		return _arPPO ($ppo, array ('template' => 'update_success.tpl', 'mainTemplate' => null));
	}
	
	public function processDeleteClass () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $nodeId   = _request ('nodeId', null);
	  $nodeType = _request ('nodeType', null);

	  $classDAO      = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $classLevelDAO = _ioDAO ('kernel|kernel_bu_ecole_classe_niveau');

	  if ($nodeType != 'BU_CLASSE' || !$class = $classDAO->get ($nodeId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Récupération de l'association classe-niveau
    $classLevels = $classLevelDAO->getByClass ($class->id);
    
    foreach ($classLevels as $classLevel) {
      
      $classLevelDAO->delete ($classLevel->classe, $classLevel->niveau);
    }
	  
	  // Suppression de la classe
	  $classDAO->delete ($class->id);
		
		return _arPPO ($ppo, 'update_success.tpl');
	}
	
	public function processCreatePersonnel () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('parentId', null);
	  $ppo->nodeType = _request ('parentType', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
		
		// Récupération des villes
		$cityDAO = _ioDAO ('kernel_bu_ville');
	  $cities = $cityDAO->findAll ();
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  foreach ($cities as $city) {

      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }

	  // Récupération des pays
		$countryDAO = _ioDAO ('kernel_bu_pays');
	  $countries = $countryDAO->findAll ();
	  
	  $ppo->countryNames = array ();
	  $ppo->countryIds   = array ();
	  
	  foreach ($countries as $country) {

      $ppo->countryNames[] = $country->pays;
      $ppo->countryIds[]   = $country->id;
    }
    
    // Civilités
    $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
	  
	  // Récupération du type de compte é créer
	  $ppo->role = _request ('role', null);

		return _arPPO ($ppo, 'create_personnel.tpl');
	}
	
	public function processValidatePersonnelCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_parent', null);
	  $ppo->nodeType = _request ('type_parent', null);
	  $ppo->role       = _request ('role', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->role)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

		_classInclude ('kernel|Tools');
		
    $personnelDAO       = _ioDAO ('kernel_bu_personnel');
    $personnelEntiteDAO = _ioDAO ('kernel_bu_personnel_entite');    
        
    // Création de la personne
    $ppo->personnel = _record ('kernel_bu_personnel');
                            
    $ppo->personnel->nom         = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->personnel->nom_jf      = Tools::capitalizeFirst (trim (_request ('nom_jf', null)));
    $ppo->personnel->prenom1     = Tools::capitalizeFirst (trim (_request ('prenom1', null)));
    $ppo->personnel->civilite    = _request ('civilite', null);
    
    if ($ppo->personnel->civilite == 'Monsieur') {
      
      $ppo->personnel->id_sexe = 1;
    }
    else {
      
      $ppo->personnel->id_sexe = 2;
    }
    
    $ppo->personnel->date_nais    = _request ('date_nais', null);
    $ppo->personnel->cle_privee   = _request ('cle_privee', null);
    $ppo->personnel->profession   = _request ('profession', null);
    $ppo->personnel->tel_dom      = _request ('tel_dom', null);
    $ppo->personnel->tel_gsm      = _request ('tel_gsm', null);
    $ppo->personnel->tel_pro      = _request ('tel_pro', null);
    $ppo->personnel->num_poste    = _request ('num_poste', null);
    $ppo->personnel->mel          = _request ('mel', null);
    $ppo->personnel->mel_pro      = _request ('mel_pro', null);
    $ppo->personnel->num_rue      = _request ('num_rue', null);
    $ppo->personnel->adresse1     = _request ('adresse1', null);
    $ppo->personnel->adresse2     = _request ('adresse2', null);
    $ppo->personnel->code_postal  = _request ('code_postal', null);
    $ppo->personnel->commune      = _request ('commune', null);
    $ppo->personnel->id_ville     = _request ('ville', null);
    $ppo->personnel->pays         = _request ('pays', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->personnel->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->personnel->prenom1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeFirstName');
    }
    if (!$ppo->personnel->cle_privee) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typePrivateKey');
    }
    if (!$ppo->personnel->num_poste) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeNumber');
    }
    if (!$ppo->personnel->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->personnel->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->personnel->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->personnel->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des villes
  		$cityDAO = _ioDAO ('kernel_bu_ville');
  	  $cities = $cityDAO->findAll ();
  	  
  	  $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();
  	  
  	  foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }

  	  // Récupération des pays
  		$countryDAO = _ioDAO ('kernel_bu_pays');
  	  $countries = $countryDAO->findAll ();
  	  
  	  $ppo->countryNames = array ();
  	  $ppo->countryIds   = array ();
  	  
  	  foreach ($countries as $country) {

        $ppo->countryNames[] = $country->pays;
        $ppo->countryIds[]   = $country->id;
      }
      
      // Civilités
      $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
  	   
      return _arPPO ($ppo, 'create_personnel.tpl');
    }
    
    $personnelDAO->insert ($ppo->personnel);
    
    // Création de l'association personne->rôle
    $newPersonnelEntite = _record ('kernel_bu_personnel_entite');
    
		$newPersonnelEntite->id_per    = $ppo->personnel->numero; 
		$newPersonnelEntite->reference = $ppo->nodeId;
		switch ($ppo->nodeType) {
			case 'BU_GRVILLE' :
        $newPersonnelEntite->type_ref  = 'GVILLE';
				break;
		  case 'BU_VILLE' :
        $newPersonnelEntite->type_ref  = 'VILLE';
  			break;
  		case 'BU_ECOLE' :
        $newPersonnelEntite->type_ref  = 'ECOLE';
    		break;		
    	case 'BU_CLASSE' :
        $newPersonnelEntite->type_ref  = 'CLASSE';
      	break;
		}
		$newPersonnelEntite->role = $ppo->role;
		
		$personnelEntiteDAO->insert ($newPersonnelEntite);
		
		return _arPPO ($ppo, array ('template' => 'create_success.tpl', 'mainTemplate' => null));
	}
	
	public function processUpdatePersonnel () {
	
	
	}
	
	public function processValidatePersonnelUpdate () {
	  
	  
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
	  
	  return _arPPO ($ppo, 'update_success.tpl');
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
	  
	  // Récupération des villes
		$cityDAO = _ioDAO ('kernel_bu_ville');
	  $cities  = $cityDAO->findAll ();
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  foreach ($cities as $city) {

      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }

	  // Récupération des pays
		$countryDAO = _ioDAO ('kernel_bu_pays');
	  $countries = $countryDAO->findAll ();
	  
	  $ppo->countryNames = array ();
	  $ppo->countryIds   = array ();
	  
	  foreach ($countries as $country) {

      $ppo->countryNames[] = $country->pays;
      $ppo->countryIds[]   = $country->id;
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
    
    // Civilités
    $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
	  
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
		
    $studentDAO       = _ioDAO ('kernel_bu_eleve');
    $studentClassDAO  = _ioDAO ('kernel_bu_eleve_affectation');    
        
    // Création de l'élève
    $ppo->student = _record ('kernel_bu_eleve');
                            
    $ppo->student->numero       = '';                        
    $ppo->student->nom          = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->student->prenom1      = Tools::capitalizeFirst (trim (_request ('prenom1', null)));
    $ppo->student->civilite     = _request ('civilite', null);
    if ($ppo->student->civilite == 'Monsieur') {
      
      $ppo->student->id_sexe = 1;
    }
    else {
      
      $ppo->student->id_sexe = 2;
    }
    $ppo->student->num_rue            = _request ('num_rue', null);
    $ppo->student->adresse1           = _request ('adresse1', null);
    $ppo->student->code_postal        = _request ('code_postal', null);
    $ppo->student->commune            = _request ('commune', null);
    $ppo->student->id_ville           = _request ('ville', null);
    $ppo->student->pays               = _request ('pays', null);
    $ppo->student->hors_scol          = _request ('hors_scol', null);
    $ppo->student->ele_last_update    = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    $ppo->student->flag               = 0;
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeFirstName');
    }
    if (!$ppo->student->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->student->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->student->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->student->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des villes
  		$cityDAO = _ioDAO ('kernel_bu_ville');
  	  $cities = $cityDAO->findAll ();
  	  
  	  $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();
  	  
  	  foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }

  	  // Récupération des pays
  		$countryDAO = _ioDAO ('kernel_bu_pays');
  	  $countries = $countryDAO->findAll ();
  	  
  	  $ppo->countryNames = array ();
  	  $ppo->countryIds   = array ();
  	  
  	  foreach ($countries as $country) {

        $ppo->countryNames[] = $country->pays;
        $ppo->countryIds[]   = $country->id;
      }
      
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
      
      // Civilités
      $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
  	   
      return _arPPO ($ppo, 'create_student.tpl');
    }

    $studentDAO->insert ($ppo->student);
    
    
    // Création de l'affectation de l'élève
    $ppo->studentAssignment = _record ('kernel_bu_eleve_affectation');
    
    $ppo->studentAssignment->eleve = $ppo->student->idEleve;
    $ppo->studentAssignment->annee_scol = Kernel::getAnneeScolaireCourante ()->id_as;
    $ppo->studentAssignment->classe = $ppo->nodeId;
    $ppo->studentAssignment->niveau = $ppo->level;
    $ppo->studentAssignment->dateDebut = CopixDateTime::timestampToYYYYMMDD (time ());
    $ppo->studentAssignment->current = 1;
    $ppo->studentAssignment->previsionnel_cl = 0;

    $studentClassDAO->insert ($ppo->studentAssignment);
    
		return _arPPO ($ppo, array ('template' => 'create_success.tpl', 'mainTemplate' => null));
	}
	
	public function processUpdateStudent () {
	  
	  $ppo = new CopixPPO ();                                       

	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('nodeId', null);
	  $ppo->nodeType = _request ('nodeType', null);
	  $studentId     = _request ('studentId', null);
	  
	  $studentDAO = _ioDAO ('kernel_bu_eleve');
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || !$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
    // Récupération des villes
		$cityDAO = _ioDAO ('kernel_bu_ville');
	  $cities  = $cityDAO->findAll ();
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  foreach ($cities as $city) {

      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }

	  // Récupération des pays
		$countryDAO = _ioDAO ('kernel_bu_pays');
	  $countries = $countryDAO->findAll ();
	  
	  $ppo->countryNames = array ();
	  $ppo->countryIds   = array ();
	  
	  foreach ($countries as $country) {

      $ppo->countryNames[] = $country->pays;
      $ppo->countryIds[]   = $country->id;
    }
    
    // Civilités
    $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
	  
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
	  
	  if (!$ppo->student = $studentDAO->get ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

    _classInclude ('kernel|Tools');
         
    $ppo->student->numero       = '';                        
    $ppo->student->nom          = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->student->prenom1      = Tools::capitalizeFirst (trim (_request ('prenom1', null)));
    $ppo->student->civilite     = _request ('civilite', null);
    if ($ppo->student->civilite == 'Monsieur') {
      
      $ppo->student->id_sexe = 1;
    }
    else {
      
      $ppo->student->id_sexe = 2;
    }
    $ppo->student->num_rue            = _request ('num_rue', null);
    $ppo->student->adresse1           = _request ('adresse1', null);
    $ppo->student->code_postal        = _request ('code_postal', null);
    $ppo->student->commune            = _request ('commune', null);
    $ppo->student->id_ville           = _request ('ville', null);
    $ppo->student->pays               = _request ('pays', null);
    $ppo->student->hors_scol          = _request ('hors_scol', null);
    $ppo->student->ele_last_update    = CopixDateTime::timestampToYYYYMMDDHHIISS (time ());
    $ppo->student->flag               = 0;
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->student->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->student->prenom1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeFirstName');
    }
    if (!$ppo->student->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->student->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->student->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->student->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération des villes
  		$cityDAO = _ioDAO ('kernel_bu_ville');
  	  $cities = $cityDAO->findAll ();
  	  
  	  $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();
  	  
  	  foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }

  	  // Récupération des pays
  		$countryDAO = _ioDAO ('kernel_bu_pays');
  	  $countries = $countryDAO->findAll ();
  	  
  	  $ppo->countryNames = array ();
  	  $ppo->countryIds   = array ();
  	  
  	  foreach ($countries as $country) {

        $ppo->countryNames[] = $country->pays;
        $ppo->countryIds[]   = $country->id;
      }
      
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
      
      // Civilités
      $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
  	   
      return _arPPO ($ppo, 'update_student.tpl');
    }
      
    $studentDAO->update ($ppo->student);
		
		$ppo->targetId   = $ppo->nodeId;
		$ppo->targetType = $ppo->nodeType;

		return _arPPO ($ppo, array ('template' => 'update_success.tpl', 'mainTemplate' => null));
	}
	
	
	public function processRemoveStudent () {
	  
	  $ppo->targetId   = _request ('nodeId', null);
	  $ppo->targetType = _request ('nodeType', null);
	  $studentId       = _request ('studentId', null);
	  
	  if (is_null ($ppo->targetId) || is_null ($ppo->targetType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $assignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
	  $assignment    = $assignmentDAO->getByStudentAndClass ($studentId, $ppo->targetId);
	  
	  $assignmentDAO->delete ($assignment->affect_id);
	  
	  return _arPPO ($ppo, 'update_success.tpl');
	}
	
	public function processDeleteStudent () {
	  
	  $ppo->targetId   = _request ('nodeId', null);
	  $ppo->targetType = _request ('nodeType', null);
	  $studentId       = _request ('studentId', null);
	  
	  if (is_null ($ppo->targetId) || is_null ($ppo->targetType) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  $studentDAO    = _ioDAO ('kernel_bu_eleve');
	  $assignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
	  
	  // Récupération des affectations de l'élève
	  $assignments = $assignmentDAO->getByStudent ($studentId);
	  
	  foreach ($assignments as $assignment) {
	    
	    $assignmentDAO->delete ($assignment->affect_id);
	  }
	  
	  $studentDAO->delete ($studentId);
	  
	  return _arPPO ($ppo, 'update_success.tpl');
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
	  
	  // Récupération de l'élève
	  $studentDAO   = _ioDAO ('kernel|kernel_bu_ele');
	  $ppo->student = $studentDAO->get ($studentId);
	  
	  // Récupération des villes
		$cityDAO = _ioDAO ('kernel_bu_ville');
	  $cities  = $cityDAO->findAll ();
	  
	  $ppo->cityNames = array ();
	  $ppo->cityIds   = array ();
	  
	  foreach ($cities as $city) {

      $ppo->cityNames[] = $city->nom;
      $ppo->cityIds[]   = $city->id_vi;
    }

	  // Récupération des pays
		$countryDAO = _ioDAO ('kernel_bu_pays');
	  $countries = $countryDAO->findAll ();
	  
	  $ppo->countryNames = array ();
	  $ppo->countryIds   = array ();
	  
	  foreach ($countries as $country) {

      $ppo->countryNames[] = $country->pays;
      $ppo->countryIds[]   = $country->id;
    }
    
    // Récupération des pays
		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
	  $parentLinks = $parentLinkDAO->findAll ();
	  
	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();
	  
	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }
    
    // Récupération des situations familliales
    $situationDAO = _ioDAO ('kernel_bu_situation_familiale');
	  $situations = $situationDAO->findAll ();
	  
	  $ppo->situationNames = array ();
	  $ppo->situationIds   = array ();

	  foreach ($situations as $situation) {

      $ppo->situationNames[] = $situation->situation;
      $ppo->situationIds[]   = $situation->id_sf;
    }
    
    // Récupération des pcs
    $pcsDAO = _ioDAO ('kernel_bu_pcs');
	  $allPcs = $pcsDAO->findAll ();
	  
	  $ppo->pcsIds   = array ();
	  $ppo->pcsNames = array ();
	  
	  foreach ($allPcs as $pcs) {

      $ppo->pcsNames[] = $pcs->pcs;
      $ppo->pcsIds[]   = $pcs->id_p;
    }
    
    // Civilités
    $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
	  
		return _arPPO ($ppo, 'create_person_in_charge.tpl');	  
	}
	
	public function processValidatePersonInChargeCreation () {
	  
	  $ppo = new CopixPPO (); 
	  
	  $ppo->TITLE_PAGE = "Gestion de la structure scolaire";
	  
	  // Récupération des paramètres
	  $ppo->nodeId   = _request ('id_node', null);
	  $ppo->nodeType = _request ('type_node', null);
	  $ppo->studentId     = _request ('id_student', null);
	  
	  if (is_null ($ppo->nodeId) || is_null ($ppo->nodeType) || is_null ($ppo->studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }

		_classInclude ('kernel|Tools');
		
    $personDAO       = _ioDAO ('kernel_bu_responsable');
    $personLinkDAO   = _ioDAO ('kernel_bu_responsables');    
        
    // Création de la personne
    $ppo->person = _record ('kernel_bu_responsable');
                        
    $ppo->person->nom         = Tools::capitalizeFirst (trim (_request ('nom', null)));
    $ppo->person->nom_jf      = Tools::capitalizeFirst (trim (_request ('nom_jf', null)));
    $ppo->person->prenom1     = Tools::capitalizeFirst (trim (_request ('prenom1', null)));
    $ppo->person->civilite    = _request ('civilite', null);
                  
    if ($ppo->person->civilite == 'Monsieur') {
      
      $ppo->person->id_sexe = 1;
    }
    else {
      
      $ppo->person->id_sexe = 2;
    }
    
    $ppo->person->date_nais    = _request ('date_nais', null);
    $ppo->person->profession   = _request ('profession', null);
    $ppo->person->tel_dom      = _request ('tel_dom', null);
    $ppo->person->tel_gsm      = _request ('tel_gsm', null);
    $ppo->person->tel_pro      = _request ('tel_pro', null);
    $ppo->person->num_poste    = _request ('num_poste', null);
    $ppo->person->mel          = _request ('mel', null);
    $ppo->person->mel_pro      = _request ('mel_pro', null);
    $ppo->person->num_rue      = _request ('num_rue', null);
    $ppo->person->adresse1     = _request ('adresse1', null);
    $ppo->person->adresse2     = _request ('adresse2', null);
    $ppo->person->code_postal  = _request ('code_postal', null);
    $ppo->person->commune      = _request ('commune', null);
    $ppo->person->id_ville     = _request ('ville', null);
    $ppo->person->pays         = _request ('pays', null);
    $ppo->person->id_pcs       = _request ('id_pcs', null);
    $ppo->person->id_fam       = _request ('id_fam', null);
    $ppo->person->num_seq      = '';
    $ppo->res_id_par           = _request ('id_par', null);
    
    // Traitement des erreurs
    $ppo->errors = array ();
    
    if (!$ppo->person->nom) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeName');
    }
    if (!$ppo->person->prenom1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeFirstName');
    }
    if (!$ppo->person->num_poste) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeNumber');
    }
    if (!$ppo->person->num_rue) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeStreetNumber');
    }
    if (!$ppo->person->adresse1) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeAddress');
    }
    if (!$ppo->person->code_postal) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeZipCode');
    }
    if (!$ppo->person->commune) {
      
      $ppo->errors[] = CopixI18N::get ('gestionautonome.error.typeCommune');
    }
    
    if (!empty ($ppo->errors)) {
      
      // Récupération de l'élève
  	  $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
  	  $ppo->student = $studentDAO->get ($ppo->studentId);
  	  
      // Récupération des villes
  		$cityDAO = _ioDAO ('kernel_bu_ville');
  	  $cities  = $cityDAO->findAll ();

  	  $ppo->cityNames = array ();
  	  $ppo->cityIds   = array ();

  	  foreach ($cities as $city) {

        $ppo->cityNames[] = $city->nom;
        $ppo->cityIds[]   = $city->id_vi;
      }

  	  // Récupération des pays
  		$countryDAO = _ioDAO ('kernel_bu_pays');
  	  $countries = $countryDAO->findAll ();

  	  $ppo->countryNames = array ();
  	  $ppo->countryIds   = array ();

  	  foreach ($countries as $country) {

        $ppo->countryNames[] = $country->pays;
        $ppo->countryIds[]   = $country->id;
      }

      // Récupération des pays
  		$parentLinkDAO = _ioDAO ('kernel_bu_lien_parental');
  	  $parentLinks = $parentLinkDAO->findAll ();

  	  $ppo->linkNames = array ();
  	  $ppo->linkIds   = array ();

  	  foreach ($parentLinks as $parentLink) {

        $ppo->linkNames[] = $parentLink->parente;
        $ppo->linkIds[]   = $parentLink->id_pa;
      }

      // Récupération des situations familliales
      $situationDAO = _ioDAO ('kernel_bu_situation_familiale');
  	  $situations = $situationDAO->findAll ();

  	  $ppo->situationNames = array ();
  	  $ppo->situationIds   = array ();

  	  foreach ($situations as $situation) {

        $ppo->situationNames[] = $situation->situation;
        $ppo->situationIds[]   = $situation->id_sf;
      }

      // Récupération des pcs
      $pcsDAO = _ioDAO ('kernel_bu_pcs');
  	  $allPcs = $pcsDAO->findAll ();

  	  $ppo->pcsIds   = array ();
  	  $ppo->pcsNames = array ();

  	  foreach ($allPcs as $pcs) {

        $ppo->pcsNames[] = $pcs->pcs;
        $ppo->pcsIds[]   = $pcs->id_p;
      }

      // Civilités
      $ppo->civilities = array ('Monsieur', 'Madame', 'Mademoiselle');
  	   
      return _arPPO ($ppo, 'create_person_in_charge.tpl');
    }
    
    $personDAO->insert ($ppo->person);
    
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
	
	public function processRemovePersonInCharge () {
	  
	  $ppo = new CopixPPO ();
	  
	  $personId   = _request ('personId', null);
	  $studentId  = _request ('studentId', null);
	  
	  if (is_null ($personId) || is_null ($studentId)) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message'=> "Une erreur est survenue.", 'back'=> CopixUrl::get('gestionautonome||showTree')));
	  }
	  
	  // Suppression du responsable
	  $personInChargeLinkDAO = _ioDAO ('kernel|kernel_bu_res2ele');
	  $personInChargeLink    = $personInChargeLinkDAO->getByPersonAndStudent ($personId, $studentId);
	  
	  $personInChargeLinkDAO->delete ($personInChargeLink->res2ele_id_rel);
	  
	  // Récupération des responsables de l'élève
	  $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
	  $ppo->persons       = $personsInChargeDAO->getByStudent ($studentId);

    return _arPPO ($ppo, array ('template' => '_persons_in_charge.tpl', 'mainTemplate' => null));
	}
}