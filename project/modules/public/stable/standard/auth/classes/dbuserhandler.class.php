<?php
/**
 * @package standard
 * @subpackage auth
 * @author		Gérald Croës
 * @copyright	CopixTeam
 * @link		http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU General Lesser  Public Licence, see LICENCE file
 */

/**
 * Utilisateur décrit en base de données.
 *
 */
class DBUser implements ICopixUser
{
    /**
     * Libellé.
     *
     * @var string
     */
    public $caption;

    /**
     * Login.
     *
     * @var string
     */
    public $login;

    /**
     * Identifiant.
     *
     * @var integer
     */
    public $id;

    /**
     * Adresse e-mail.
     *
     * @var string
     */
    public $email;

    /**
     * Enter description here...
     *
     * @var integer
     */
    public $enabled;

    /**
     * Construit un DBUser à partir d'un enregistrement en base.
     *
     * @param ICopixDAORecord $record
     */
    public function __construct(ICopixDAORecord $record)
    {
        $this->caption = $record->login_dbuser;
        $this->login   = $record->login_dbuser;
        $this->id      = intval($record->id_dbuser);
        $this->email   = $record->email_dbuser;
        $this->enabled = $record->enabled_dbuser ? true : false;
    }

    /**
     * Retourne le libellé de l'utilisateur.
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Retourne le login de l'utilisateur.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Retourne l'identifiant technique de l'utilisateur.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Retourne le nom du handler responsable de cet utilisateur.
     *
     * @return string
     */
    public function getHandler()
    {
        return 'auth|dbuserhandler';
    }



}

/**
 * Gestionnaire des utilisateurs depuis la base de données
 * @package standard
 * @subpackage auth
 */
class DBUserHandler implements ICopixUserHandler
{
    /**
     * Connexion
     *
     * @param array $pParams	paramètres de connexion
     * @return CopixUserLogResponse
     */
    public function login ($pParams)
    {
        if (!isset ($pParams['login'])){
            return new CopixUserLogResponse (false, null, null, null);
        }

        if (count ($results = CopixDB::getConnection ()->doQuery ('select id_dbuser, login_dbuser, password_dbuser, enabled_dbuser from dbuser where login_dbuser=:login',
                array ('login'=>$pParams['login'])))){
            if ($results[0]->enabled_dbuser == 0){
                return new CopixUserLogResponse (false, null, null, null);
            }

            // Si en SSO, mot de passe deja crypte
            $password_test = (isset($pParams['ssoIn']) && $pParams['ssoIn']) ? $pParams['password'] : ($this->_cryptPassword (isset ($pParams['password']) ? $pParams['password'] : ''));

            if ( !( (isset($pParams['assistance']) && $pParams['assistance']) || $results[0]->password_dbuser == $password_test ) ) {
                // Si le mot de passe crypt� commence par "$1$"
                if(0==strncmp($results[0]->password_dbuser,'$1$',3)) {
                    // Si la fonction "crypt" supporte le MD5 (hachage MD5 � 12 caract�res commen�ant par $1$)
                    if (CRYPT_MD5 == 1) {
                        // Si le mot de passe est le bon
                        if( $results[0]->password_dbuser == crypt($pParams['password'],$results[0]->password_dbuser) ) {

                            // Changement de mot de passe...
                            $user_tmp = _ioDAO ('dbuser')->get($results[0]->id_dbuser);
                            $user_tmp->password_dbuser = $this->_cryptPassword($pParams['password']);
                            _ioDAO ('dbuser')->update($user_tmp);
                            //_dump($user_tmp);

              $password_test = $results[0]->password_dbuser = $user_tmp->password_dbuser;
                        }
                    }
                }
            }

            if ( (isset($pParams['assistance']) && $pParams['assistance']) || $results[0]->password_dbuser == $password_test){

                $extra = array();

                $getUserInfo = Kernel::getUserInfo( "LOGIN", $results[0]->login_dbuser );
                //var_dump($getUserInfo);
                $extra = $getUserInfo;

                return new CopixUserLogResponse (true, 'auth|dbuserhandler', $results[0]->id_dbuser, $results[0]->login_dbuser, $extra);
            }
        }
        return new CopixUserLogResponse (false, null, null, null);
    }

    /**
     * Déconnexion
     *
     * @param array $pParams tableau de paramètres
     * @return CopixUserLogResponse
     */
    public function logout ($pParams)
    {
        return new CopixUserLogResponse (true, null, null, null);
    }

    /**
     * Récupération d'une liste d'utilisateurs (id, login, caption, email, enabled)
     * @param 	array	$pMatchPatterns	tableau d'éléments de recherche
     * @todo	Implémenter les patterns de recherche
     * @return array of DBUser
     */
    public function find ($pParams = array ())
    {
        $sp = _daoSp ();
        if (isset ($pParams['login'])){
            if (!is_array ($pParams['login']) && strpos ($pParams['login'], '%')){
                $sp->addCondition ('login_dbuser', 'like', $pParams['login']);
            }else{
                $sp->addCondition ('login_dbuser', '=', $pParams['login']);
            }
        }

        if (isset ($pParams['id'])){
            if (!is_array ($pParams['id']) && strpos ($pParams['id'], '%')){
                $sp->addCondition ('id_dbuser', 'like', $pParams['id']);
            }else{
                $sp->addCondition ('id_dbuser', '=', $pParams['id']);
            }
        }

        if (isset ($pParams['caption'])){
            if (!is_array ($pParams['caption']) && strpos ($pParams['caption'], '%')){
                $sp->addCondition ('login_dbuser', 'like', $pParams['caption']);
            }else{
                $sp->addCondition ('login_dbuser', '=', $pParams['caption']);
            }
        }

        if (isset ($pParams['email'])){
            if (!is_array ($pParams['email']) && strpos ($pParams['email'], '%')){
                $sp->addCondition ('email_dbuser', 'like', $pParams['email']);
            }else{
                $sp->addCondition ('email_dbuser', '=', $pParams['email']);
            }
        }

        if (isset ($pParams['enabled'])){
            $sp->addCondition ('enabled_dbuser', '=', $pParams['enabled']);
        }

        $results = array ();
        foreach (_ioDAO ('dbuser')->findBy ($sp) as $result){
            $results[] = new DBUser ($result);
        }
        return $results;
    }

    /**
     * Donne la forme cryptée du mot de passe.
     * @param string	$pClearPassword	le mot de passe en clair
     * @return string	le mot de passe crypté
     */
    private function _cryptPassword ($pClearPassword)
    {
        switch ($hashMethod = CopixConfig::get ('auth|cryptPassword')){
            case 'md5':
                return md5 ($pClearPassword);
            case 'sha1':
                return sha1 ($pClearPassword);
            case 'sha256':
                return hash ('sha256', $pClearPassword);
            default :
                throw new CopixException (_i18n ('auth.error.unknownHashMethod', $hashMethod));
        }
    }

    /**
     * L'email de l'utilisateur est renvoyé
     * @param integer identifiant de l'utilisateur
     * @return DBUser L'utilisateur
     */
    public function getInformations ($pUserId)
    {
        if ($objUser = _ioDAO ('dbuser')->get($pUserId)){
            return new DBUser($objUser);
        }
        throw new CopixException ('No informations on user '.$pUserId);
    }
}
