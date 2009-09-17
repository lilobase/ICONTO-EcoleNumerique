<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: profil.actiongroup.php,v 1.4 2008-01-22 08:41:39 fmossmann Exp $
* @author	Croes Gérald, Julien Mercier, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ActionGroupProfil extends CopixActionGroup {

   /**
   * Affiche le profil
   *
   * @return Object CopixActionReturn
   */
   function getProfil() {
      $tpl = & new CopixTpl();

      $plugAuth = CopixPluginRegistry::get ("auth|auth");

      $tpl->assignTpl ('MAIN', 'profil.tpl', array('user'=> $plugAuth->getUser()));
      $tpl->assign ('TITLE_PAGE', CopixI18N::get ('auth|auth.strings.profilutilisateur'));

	   return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
   }  // function getProfil()


   /**
   * Affiche le formulaire de modification du profil
   *
   * @param string message d'erreur s'il y a lieu
   * @return Object CopixActionReturn
   */
   function getModifUserForm($err_msg = '') {
      $tpl = & new CopixTpl();

      $plugAuth = CopixPluginRegistry::get ("auth|auth");

      $param = array();
      if (! empty($err_msg)){
         $param['err_msg']= $err_msg;
      }
      $param['user']= $plugAuth->getUser();

      $tpl->assignTpl ('MAIN', 'formModifUser.tpl',$param);
      $tpl->assign ('TITLE_PAGE', CopixI18N::get ('auth|auth.strings.profilutilisateur'));

	   return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
   }  // function getFormModifProfil


   /**
   * Modifie l'utilisateur en cour
   *
   * @return Object CopixActionReturn
   */

   function doModifUser()
   {
      $plugAuth = CopixPluginRegistry::get ("auth|auth");
      $user = & $plugAuth->getUser();

      // Vérification que les informations sont valides
      if ($this->vars['auth_password'] != $this->vars['auth_password2']) {
         return $this->getModifUserForm('Erreur dans la saisie du mot de passe.');
      }  // if
      if (! $user->checkLoginIsUnique($this->vars['auth_login'])) {
         return $this->getModifUserForm('Ce login éxiste déjà.');
      }  // if
      if (! $user->checkEMailIsUnique($this->vars['auth_email'])) {
         return $this->getModifUserForm('Cette adresse E-Mail éxiste déjà.');
      }  // if

      // Sauvegarde de l'utilisateur
      if (($plugAuth->config->verifEMailAddress === true) && ($this->vars['auth_email'] != $user->email)) {
         // ==> Avec vérification de l'adresse mail et adresse mail modifiée
         $user->doUpdate($this->vars['auth_login'], $this->vars['auth_password'], $this->vars['auth_name'],
                       $this->vars['auth_surname'], $this->vars['auth_email'], 0);
         $this->_mailActiveKey();
         $user->logout();
         return $this->_getEndModifUser();
      } else {
         // ==> Sans vérification de l'adresse mail
         $user->doUpdate($this->vars['auth_login'], $this->vars['auth_password'], $this->vars['auth_name'],
                    $this->vars['auth_surname'], $this->vars['auth_email'], 1);
         return $this->getProfil();
      }  // if
   }  // function doModifUser


   /**
   * Envoie le mail de confirmation de création de compte avec la clé d'activation associée
   *
   * @return bool
   */
   function _mailActiveKey()
   {
      $plugAuth = CopixPluginRegistry::get ("auth|auth");
      // test si cette action est activé dans le fichier de configuration
      if ($plugAuth->config->allowCreateNew === false) {
         return true;
      }  // if

      $tpl = & new CopixTpl();

      $user = & $plugAuth->getUser();
      $tpl->assign('user', $user);
      $mailMsg = $tpl->fetch('mailKey.tpl');

      return mail ($user->email, 'Copix, Activation de votre compte', $mailMsg);
   }  // function mailActiveKey

   /**
   * Affiche la confirmation de mise à jour du compte et l'envoi d'une nouvelle clé
   *
   * @return Object CopixActionReturn
   */
   function _getEndModifUser()
   {
      $tpl = & new CopixTpl();
      $plugAuth = CopixPluginRegistry::get ("auth|auth");

      $tpl->assignTpl ('MAIN', 'endModifUser.tpl',array('user'=> $plugAuth->getUser()));
      $tpl->assign ('TITLE_PAGE', 'Indentification');

      return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
   }  // function _getEndModifUser
}  // class ActionGroupProfil
?>
