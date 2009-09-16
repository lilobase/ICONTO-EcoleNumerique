<?php
/**
* @package		kernel
* @author	Christophe Beyer
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Affichage d'un lien vers Gael, pour modifier les donnees administratives
 *
 * @package kernel
 * @since 2007/11/09
 * @param string $mode Mode utilise (button ou txt). Par defaut : button
 * @param string $action Action souhaitee (modifier un eleve, un parent...)
 * @param string $type Type de l'objet a atteindre
 * @param integer $type_id Id de l'objet a atteindre
 * @param string $libelle Libelle utilise sur le bouton (si mode=button) ou au milieu du lien ahref (si mode=txt). Par defaut : Modifier
 * @param string $plusType (option) Type complementaire
 * @param integer $plusId (option) Id complementaire
 */

class ZoneLinkGael extends CopixZone {
	function _createContent (&$toReturn){
//print_r($_SESSION);
		$tpl = new CopixTpl();
		$pMode = $this->getParam ('mode', 'button');
		$pAction = $this->getParam ('action');
		$pType = $this->getParam ('type');
		$pId = $this->getParam ('type_id');
		$pLibelle = $this->getParam ('libelle', 'Modifier');
		$pPlusType = $this->getParam ('plusType');
		$pPlusId = $this->getParam ('plusId');
		
		//$txt = "Lien vers Gael a venir";
		$txt = '';
		
		switch ($pAction) {
			case 'modif_benef' : // Modification d'un beneficiaire
				if ($pType == 'eleve') {
					$pAction = 'modif_enfant';
					$pType = null;
				} else {
					$txt .= " PROBLEME pour lien vers $pType #$pId (modif_benef)";
				}
				break;
			case 'add_redevable' : // Ajout d'un redevable
				if ($pType == 'eleve') {
				} else {
					$txt .= " PROBLEME pour ajouter un redevable a $pType #$pId (add_redevable)";
				}
				break;
			case 'add_responsable' : // Ajout d'un responsable
				if ($pType == 'eleve') {
				} else {
					$txt .= " PROBLEME pour ajouter un responsable a $pType #$pId (add_responsable)";
				}
				break;
			case 'add_benef' : // Ajout d'un beneficiaire
				if ($pType == 'eleve') {
					$pAction = 'add_enfant';
					$pType = null;
				} elseif ($pType == 'responsable') {
					$pAction = 'add_responsable';
				} else {
					$txt .= " PROBLEME pour ajouter un beneficiaire $pType (add_benef)";
				}
				break;
			case 'modif_responsable' : // Modification d'un responsable (a partir de son ID)
					$pType = 'responsable';
				break;
			default :
				$txt .= " PROBLEME, cas ".$pAction." non gere";
		}

		//$href = "javascript:alert ('".$txt."');";
		$href = "javascript:sso_gael('"._url ('kernel|sso|doSso', array('operation'=>$pAction, 'objType'=>$pType, 'objId'=>$pId, 'plusType'=>$pPlusType, 'plusId'=>$pPlusId))."');";
		//die ($href);
		
		if (!_currentUser ()->getIdPersonnel ()) {
			$href = "javascript:alert('Erreur SSO : Compte non relie a un personnel');";
		} elseif (!_currentUser ()->getPrivateKey ()) {
			$href = "javascript:alert('Erreur SSO : Cle privee manquante');";
		}
		
		$tpl->assign('txt', $txt);
		$tpl->assign('mode', $pMode);
		$tpl->assign('libelle', $pLibelle);
		$tpl->assign('href', $href);
		
		$toReturn = $tpl->fetch('kernel|linkgael.tpl');

		return true;
	}
}


?>