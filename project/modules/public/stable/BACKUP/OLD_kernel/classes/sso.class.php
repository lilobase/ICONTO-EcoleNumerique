<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: sso.class.php,v 1.3 2009-05-07 09:29:36 cbeyer Exp $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

class SSO {
	
	const CHALLENGE_DELAY = 10; // Delai en secondes

	/**
	 * Recupere les infos du compte pour lequel on veut simuler un SSO
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $id_dbuser Identifiant du user
 	 * @return mixed Recordset de l'objet (du compte) ou false si probleme
	 */
	public function getSsoId ($id_dbuser) {
		$userDAO = _ioDAO ('dbuser');
	  $res = false;
		if ($rUser = $userDAO->get ($id_dbuser)) {
			$res = $rUser;
		}
		return $res;
	}

	
	/**
	 * Generation d'une cle aleatoire
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $length Longueur souhaitee
 	 * @return string Chaine aleatoire
	 */
	function randomKeys ($length) {
		//return 'abcdef';
	  $pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  for($i=0;$i<$length;$i++)
	  {
	   if(isset($key))
	     $key .= $pattern{rand(0,61)};
	   else
	     $key = $pattern{rand(0,61)};
	  }
	  return $key;
	}


	/**
	 * Enregistre le demarrage d'un nouveau challenge
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $id_dbuser Id du user
 	 * @param string $challenge Challenge a stocker
 	 * @return boolean True si challenge bien stocke, false si probleme
	 */
	function newChallenge ($id_dbuser, $challenge) {
		$userDAO = _ioDAO ('dbuser');
	  $res = false;
		if ($rUser = $userDAO->get ($id_dbuser)) {
			$rUser->challenge = $challenge;
			$rUser->challenge_date = date('YmdHis');
			$userDAO->update($rUser);
			$res = true;
		}
		return $res;
	}

	/**
	 * Efface le challenge d'un compte
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $id_dbuser Id du user
 	 * @return none
	 */
	function deleteChallenge ($id_dbuser) {
		$userDAO = _ioDAO ('dbuser');
		if ($rUser = $userDAO->get ($id_dbuser)) {
			$rUser->challenge = null;
			//$rUser->challenge_date = null;
			$userDAO->update($rUser);
		}
	}



	/**
	 * Construit l'URL sur laquelle envoyer l'usager selon les parametres passes
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param string $go Action souhaitee
 	 * @param string $param1 Parametre 1
 	 * @param string $param2 Parametre 2
 	 * @return string URL de redirection
	 */
	function constructUrl ($go, $param1, $param2) {
		switch ($go) {
			case 'dossierMedical' : $url = _url ('dossiers||detail', array('type'=>$param1, 'id'=>$param2, 'tab'=>'vaccins')); break;
			default : $url = _url ();
		}
		return $url;
	}


}

?>