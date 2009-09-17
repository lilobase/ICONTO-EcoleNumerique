<?php
/**
 * Sso - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Sso
 * @version     $Id: in.actiongroup.php,v 1.8 2008-10-21 12:47:37 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

require_once (COPIX_MODULE_PATH.'kernel/'.COPIX_CLASSES_DIR.'kernel.class.php');

class ActionGroupIn extends CopixActionGroup {

   /**
   * Etablissement d'un challenge, sur la base d'un identifiant SSO
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/08
	 * @param integer $id_sso Id SSO
	 * @return string résultat du challenge. -ERR xxx si erreur, +OK xxx si c'est bon
   */
    function challenge () {
      
      $id_sso = $this->getRequest('id_sso', null);
			
			if (!$id_sso)
		    echo "-ERR ACC: id_sso manquant";
			elseif (!preg_match('/^[0-9]+$/',$id_sso))
		    echo "-ERR ACC: id_sso doit être un nombre";
			else {
				
	    	$token = false;
	      
	      $dbw = & CopixDbFactory::getDbWidget ();
	
	      $sql = "SELECT login FROM kernel_sso_users WHERE id_sso = $id_sso";
	  		$sso = $dbw->fetchFirst ($sql);
	      //print_r($sso);
	      
	      if ($sso) {
				
					// On efface l'éventuel challenge courant
					$daoChallenges = CopixDAOFactory::create('sso|sso_challenges');
					$daoChallenges->deleteByIdSso ($id_sso);
					
					// On insère le nouveau challenge
	    		$token = randomkeys(CopixConfig::get ('sso|in_encrypt_size'));
	  		  $res = $dbw->doInsert('kernel_sso_challenges', array('id_sso'=>$id_sso, 'challenge'=>"'".$token."'", 'date'=>mktime()));
	        //print_r($res);
	
	  		  if ($res->_idResult != 1)	{ echo "-ERR BDD: Erreur lors de l'enregistrement dans la base de données"; }
					//Kernel::deb (md5($token.'FobVVbarwb'));
					//die();
	  		  $token = "+OK ".$token;
	    	} else {
			    echo "-ERR ACC: id_sso inexistant";
	    	}
	    	echo $token;
			}
  
      return new CopixActionReturn (COPIX_AR_NONE, 0);
      
	}
	
	/**
	 * Login SSO simulant une vraie connexion
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/08
	 * @param integer $id_sso Id SSO
	 * @param string $key Clé
	 * @param string $node_type (option) Type du noeud à atteindre après connexion
	 * @param integer $node_id (option) Id du noeud à atteindre après connexion
	 * @param string $module_type (option) Type du module du noeud à atteindre (MOB_BLOG,...)
	 * @return integer $id_sso Id SSO
	 */
	function login () {
      
		$id_sso = $this->getRequest('id_sso', null);
		$key = $this->getRequest('key', null);
		$node_type = $this->getRequest('node_type', null);
		$node_id = $this->getRequest('node_id', null);
		$module_type = $this->getRequest('module_type', null);
      
		$dbw = & CopixDbFactory::getDbWidget ();
      
		//$sql = "SELECT PER.numero, PER.cle_privee, PER.challenge, PER.dateChallenge, USER.login_cusr AS login FROM kernel_bu_personnel PER, kernel_link_bu2user LINK, copixuser USER WHERE PER.numero=LINK.bu_id AND LINK.bu_type IN ('USER_ENS', 'USER_VIL') AND LINK.user_id=USER.id_cusr AND PER.numero = $identifiant";

		$sql = "SELECT CHA.date, CHA.challenge, SSO.cle_privee, SSO.login FROM kernel_sso_challenges CHA, kernel_sso_users SSO, copixuser USER WHERE CHA.id_sso=SSO.id_sso AND SSO.login=USER.login_cusr AND SSO.id_sso=$id_sso";
		//Kernel::deb($sql);	
		$sso = $dbw->fetchFirst ($sql);
		//print_r($sso);

		if ($sso) {
			/*
			Kernel::deb($sso->challenge);
			Kernel::deb($sso->cle_privee);
			Kernel::deb('md5='.md5($sso->challenge.$sso->cle_privee));
			*/
			if (md5($sso->challenge.$sso->cle_privee) == $key) {
				if ((mktime()-$sso->date) < CopixConfig::get ('sso|in_challenge_delay')) { // OK
					//echo("OK!");
					return CopixActionGroup::process ('auth|Login::doLogin',
						array (
							'login'=>$sso->login,
							'key'=>$key,
							'node_type'=>$node_type,
							'node_id'=>$node_id,
							'module_type'=>$module_type
						)
					);
					
				} else {
		   			echo "-ERR REP: Le temps alloué pour la réponse est dépassé. Veuillez essayer une nouvelle connexion ou contacter votre administrateur pour lui signaler le problème.";
		   		}
			} else {
				echo "-ERR REP: Erreur (1) lors de la vérification d'identité. Veuillez essayer une nouvelle connexion ou contacter votre administrateur pour lui signaler le problème.";
			}
		} else {
			echo "-ERR REP: Erreur (2) lors de la vérification d'identité. Veuillez essayer une nouvelle connexion ou contacter votre administrateur pour lui signaler le problème.";
		}
    
		return new CopixActionReturn (COPIX_AR_NONE, 0); 
	} 

}



function randomkeys($length)
{
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

?>
