<?php
/*
 * Enic class with user informations
 */
  class enicUser
  {
    /**
     * @var int User's current Id
     */
    public $id;
    
    /**
     * @var int User's Ecole Numerique internal Id
     */
    public $idEN;
    
    /**
     * @var string User's type (ex: USER_ELE, USER_DIR, etc.)
     */
    public $type;
    
    /**
     * @var string User's login
     */
    public $login;
    
    /**
     * @var string User's name
     */
    public $nom;
    
    /**
     * @var string User's Surname
     */
    public $prenom;
    
    /**
     * @var boolean True if user is super admin, false if not
     */
    public $root;
    
    /**
     * @var boolean|int[] False if the user is not a director, in the other case : an array of school he's director.
     */
    public $director;
    
    /**
     * @var boolean True if the user is an animator, false if not
     */
    public $animator;
    
    /**
     * @var boolean True if the user is authentificated, false if not.
     */
    public $connected;
    

    public function startExec()
    {
        if(_currentUser()->isConnected()){
            $userId = _currentUser()->getId();
            $userInfos = Kernel::getUserInfo('ID', $userId);
            
            $this->director = false;
            $this->animator = _currentUser()->hasAssistance();
            $this->idEn = (isset($userInfos['id']))?$userInfos['id']:null;
            $this->id = $userId*1;
            $this->type = (isset($userInfos['type']))?$userInfos['type']:null;
            $this->root = false;
            $this->login = $userInfos['login'];
            $this->nom = $userInfos['nom'];
            $this->prenom = $userInfos['prenom'];
            $this->connected = true;
            $this->chartValid = $_SESSION['chartValid'];
        }else{
            $this->director = false;
        $this->animator = false;
            $this->id = 0;
            $this->type = 'USER_ANON';
            $this->root = false;
            $this->login = 'Anon';
            $this->nom = 'Anon';
            $this->prenom = 'Anon';
            $this->connected = false;
            $this->idEn = 0;
            $this->chartValid = true;
        }
    }

    public function forceReload()
    {
        $this->startExec();
    }

    public function addExec()
    {
    }

}
