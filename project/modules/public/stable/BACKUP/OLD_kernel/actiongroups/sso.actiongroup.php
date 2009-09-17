<?php
/**
 * @package petiteenfance
 * @subpackage kernel
 * @author Christophe Beyer
 * @copyright	CAP-TIC
 * @link http://cap-tic.fr
*/

_classInclude ('kernel|sso');


/**
 * Actions gerant les SSO entrant/sortant
 * @package kernel
 */
class ActionGroupSso extends CopixActionGroup {


	/**
	 * Demarre un SSO sortant
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @param string $operation Action souhaitee (modif_benef, add_redevable...)
	 * @param string $objType Type de l'objet a atteindre
	 * @param integer $objId Id de l'objet a atteindre
	 * @param string $plusType (option) Type complementaire
 	 * @param integer $plusId (option) Id complementaire
	 * @since 2007/11/12
	 */
	public function processDoSso () {
    
    $criticErrors = array();

		$pOperation = CopixRequest::get('operation');
		$pObjType = CopixRequest::get('objType');
		$pObjId = CopixRequest::getInt('objId');
		$pPlusType = CopixRequest::get('plusType');
		$pPlusId = CopixRequest::get('plusId');
		
		//die ($pOperation);
		//print_r($_SESSION);
		
		$sso = new CopixPPO ();
		
		if (CopixConfig::exists('kernel|gaelUrl') && CopixConfig::get('kernel|gaelUrl')) {

			$sso->sso_auth_url = CopixConfig::get('kernel|gaelUrl').'/sso/in-vaccination.php';
	
			$sso->sso_auth_identifiant = _currentUser ()->getIdPersonnel ();
			$sso->sso_auth_secret_key = _currentUser ()->getPrivateKey ();
			
			$url = $sso->sso_auth_url.'?mode=challenge';
			$url.= '&identifiant='.urlencode($sso->sso_auth_identifiant);
			//print_r("url=$url");
			$file = @fopen( $url, 'r' );
			if ($file && !$criticErrors) {
				$challenge = '';
				while (!feof($file)) {
					$line = fread($file, 1024);
					//print_r("line=$line");
					$challenge .= $line;
				}
				fclose ($file);
				//var_dump($challenge);
				
				if( ereg( '^\-ERR (.*)$', $challenge, $regs ) ) {
					$criticErrors[] = 'Erreur challenge : '.$regs[1];
				}
				elseif( ! ereg( '^\+OK (.+)$', $challenge, $regs ) ) {
					$criticErrors[] = 'Erreur challenge inconnu';
				}
				
				$challenge = $regs[1];
				
				if (!$criticErrors) {
					//print_r("challenge=$challenge");
					$challenge_crypt = md5($challenge.$sso->sso_auth_secret_key);
					$url = $sso->sso_auth_url.'?mode=login';
					$url.= '&identifiant='.urlencode($sso->sso_auth_identifiant);
					$url.= '&action='.urlencode($pOperation);
					$url.= '&obj_type='.urlencode($pObjType);
					$url.= '&obj_id='.urlencode($pObjId);
					$url.= '&key='.urlencode($challenge_crypt);
	
					return _arRedirect ($url);
					//return _arRedirect ($url);
				}
			} elseif (!$file) {
				$criticErrors[] = 'Impossible d\'acc&eacute;der au site distant ('.CopixConfig::get('kernel|gaelUrl').')';
			}
		} elseif (CopixConfig::exists('kernel|gaelUrl')) {
			$criticErrors[] = 'Param&egrave;tre de configuration kernel|gaelUrl vide';
		}	else {
			$criticErrors[] = 'Param&egrave;tre de configuration kernel|gaelUrl non trouv&eacute;';
		}
		
    return CopixActionGroup::process ('generictools|Messages::getError',
			array (
						'message'=>implode('<br/>',$criticErrors),
						'back'=>'javascript:self.close();',
			)
		);

		//return new CopixActionReturn (COPIX_AR_REDIRECT, $url );

	}
	



	/**
	 * SSO entrant : Challenge
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $id Identifiant du compte utilisateur
	 */
	public function processInChallenge () {
    
		$pId = CopixRequest::getInt('id');
		
		if ($sso = SSO::getSsoId($pId)) {
		
			$token = SSO::randomKeys(16);
    	if ($newChallenge = SSO::newChallenge($pId, $token)) {
			
				$res = $token = '+OK '.$token;
			} else
				$res = '-ERR BDD: Erreur de d&eacute;marrage du challenge';
		} else
			$res = '-ERR ACC: Compte inexistant';

		echo $res;
		return _arNone ();

	}
	


	/**
	 * SSO entrant : Login
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/04/28
 	 * @param integer $id Identifiant du compte utilisateur
 	 * @param string $key Cle
 	 * @param string $go Action souhaitee
 	 * @param string $param1 Parametre 1
 	 * @param string $param2 Parametre 2
	 */
	public function processInLogin () {
    
		$pId = CopixRequest::getInt('id');
		$pKey = CopixRequest::get('key');
		$pGo = CopixRequest::get('go');
		$pParam1 = CopixRequest::get('param1');
		$pParam2 = CopixRequest::get('param2');
		
		if ($sso = SSO::getSsoId($pId)) {
			if (md5($sso->challenge.$sso->cle_privee) != $pKey) {
				$res = '-ERR KEY: Cle inconnue';
			}
			elseif ((mktime()-CopixDateTime::yyyymmddhhiissToTimeStamp($sso->challenge_date)) > SSO::CHALLENGE_DELAY) {
				$res = '-ERR TIM: Temps allou&eacute; pour la r&eacute;ponse d&eacute;pass&eacute;';
			} else { // OK

				SSO::deleteChallenge($pId);
				
				return CopixActionGroup::process ('auth|Log::in',
                                    array ('login'=>$sso->login_dbuser,
                                           'password'=>$sso->password_dbuser,
                                           'sso_in'=>true,
																					 'auth_url_return'=>SSO::constructUrl($pGo, $pParam1, $pParam2),
																					 ));
			}
				
		} else
			$res = '-ERR ACC: Compte inexistant';

		echo $res;
		return _arNone ();

	}
	


	public function processInTest () {
    
		$id = 4;
		$cle_privee = 'c87c74aa1704e5af2624471e0c4f6b1a';
		//$challenge = 'abcdef';
		
		$key = md5($challenge.$cle_privee);
		
		$urlChallenge = _url ('kernel|sso|inChallenge', array('id'=>$id));
		echo '<p>urlChallenge = <a href="'.$urlChallenge.'">'.$urlChallenge.'</a></p>';
		$urlLogin = _url ('kernel|sso|inLogin', array('id'=>$id, 'key'=>$key, 'go'=>'dossierMedical', 'param1'=>'eleve', 'param2'=>12308));
		echo '<p>urlLogin = <a href="'.$urlLogin.'">'.$urlLogin.'</a></p>';
		
		return _arNone ();

	}


}

?>
