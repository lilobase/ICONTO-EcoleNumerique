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

class ActionGroupIn extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }

   /**
   * Etablissement d'un challenge, sur la base d'un identifiant SSO
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/12/08
     * @param integer $id_sso Id SSO
     * @return string résultat du challenge. -ERR xxx si erreur, +OK xxx si c'est bon
   */
    public function challenge ()
    {
      $id_sso = $this->getRequest('id_sso', null);

            if (!$id_sso)
            echo "-ERR ACC: id_sso manquant";
            elseif (!preg_match('/^[0-9]+$/',$id_sso))
            echo "-ERR ACC: id_sso doit être un nombre";
            else {

            $token = false;

          $sql = "SELECT login FROM kernel_sso_users WHERE id_sso = $id_sso";
              $sso = _doQuery($sql);
          //print_r($sso);

          if ($sso) {

                    // On efface l'éventuel challenge courant
                    $daoChallenges = CopixDAOFactory::create('sso|sso_challenges');
                    $daoChallenges->deleteByIdSso ($id_sso);

                    // On insère le nouveau challenge
                $token = randomkeys(CopixConfig::get ('sso|in_encrypt_size'));

                    $res = record('kernel_sso_challenges');
                    $res->id_sso = $id_sso;
                    $res->challenge = $token;
                    $res->date = mktime();
                    _ioDao('kernel_sso_challenges')->insert($record);


            //print_r($res);

               // if ($res->_idResult != 1)	{ echo "-ERR BDD: Erreur lors de l'enregistrement dans la base de données"; }
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
    public function login ()
    {
        $id_sso = $this->getRequest('id_sso', null);
        $key = $this->getRequest('key', null);
        $node_type = $this->getRequest('node_type', null);
        $node_id = $this->getRequest('node_id', null);
        $module_type = $this->getRequest('module_type', null);

        $sql = "SELECT CHA.date, CHA.challenge, SSO.cle_privee, SSO.login FROM kernel_sso_challenges CHA, kernel_sso_users SSO, dbuser USER WHERE CHA.id_sso=SSO.id_sso AND SSO.login=USER.login_dbuser AND SSO.id_sso=$id_sso";
        //Kernel::deb($sql);
        $sso = _doQuery($sql);
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
                    return CopixActionGroup::process ('auth|log::in',
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
  for($i=0;$i<$length;$i++) {
   if(isset($key))
     $key .= $pattern{rand(0,61)};
   else
     $key = $pattern{rand(0,61)};
  }
  return $key;
}

