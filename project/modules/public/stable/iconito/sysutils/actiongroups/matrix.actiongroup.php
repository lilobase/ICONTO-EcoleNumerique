<?php
/**
 * Matrix - ActionGroup
 *
 * @package	Iconito
 * @subpackage	Sysutils/Matrix
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

class ActionGroupMatrix extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }


	public function processDefault ()
	{
		if (!Kernel::isAdmin())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
		
		$ppo = new CopixPPO();
		$ppo->from  = array( 'USER_VIL', 'USER_DIR', 'USER_ENS', 'USER_RES', 'USER_ELE', 'USER_ATI', 'USER_EXT', 'USER_ADM' );
		$ppo->to    = array( 'USER_VIL', 'USER_DIR', 'USER_ENS', 'USER_RES', 'USER_ELE', 'USER_ATI', 'USER_EXT', 'USER_ADM' );
		$ppo->where = array( 'NOWHERE', 'BU_CLASSE', 'BU_ECOLE', 'BU_VILLE', 'BU_GRVILLE', 'ROOT' );
		$ppo->do    = array( 'VOIR', 'COMM' );
		
		$ppo->trad = array(
			'USER_VIL'   => "Agent ville",
			'USER_DIR'   => "Directeur",
			'USER_ENS'   => "Enseignant",
			'USER_RES'   => "Parent",
			'USER_ELE'   => "Elève",
			'USER_ADM'   => "Admin",
			'USER_ATI'   => "Anim TICE",
			'USER_EXT'   => "Personne ext",
			'NOWHERE'    => "Interdit",
			'BU_CLASSE'  => "Même classe",
			'BU_ECOLE'   => "Même école",
			'BU_VILLE'   => "Même ville",
			'BU_GRVILLE' => "Même groupe<br/>de ville",
			'ROOT'       => "Partout",
			'VOIR'       => "Voir",
			'COMM'       => "Ecrire",
		);
		
		if( _request("save",0)==1 ) {
			
			// echo "<pre>"; print_r($_POST); die();
			
			// Clear matrix
			_doQuery('DELETE FROM module_rightmatrix');
			
			/* Mode checkbox
			foreach( $ppo->from AS $cpt_from ) {
				foreach( $ppo->to AS $cpt_to ) {
					foreach( $ppo->do AS $cpt_do ) {
						// Retournement de la Matrice pour écrire dans le domaine le plus large (uniquement)
						foreach( array_reverse($ppo->where) AS $cpt_where ) {
							$droit = _request("right_".$cpt_from."_".$cpt_to."_".$cpt_where."_".$cpt_do);
							// if($droit) echo "<li>[$cpt_from][$cpt_to][$cpt_where][$cpt_do] = ".($droit?"OUI":"non")."</li>";
							// if($droit) echo "<li>[$cpt_from][$cpt_to][$cpt_where][$cpt_do] = ".($droit?"OUI":"non")."</li>";
							if($droit) _doQuery('INSERT INTO module_rightmatrix (user_type_in, user_type_out, node_type, `right`) VALUES (:user_type_in, :user_type_out, :node_type, :right)', array( ':user_type_in'=> $cpt_from, ':user_type_out' => $cpt_to, ':node_type' => $cpt_where, ':right' => $cpt_do ));
							if($droit) break;
						}
					}
				}
			}
			*/
			
			foreach( $ppo->from AS $cpt_from ) {
				foreach( $ppo->to AS $cpt_to ) {
					foreach( $ppo->do AS $cpt_do ) {
						$droit = _request("right_".$cpt_from."_".$cpt_to."_".$cpt_do);
						if($droit != "NOWHERE") _doQuery('INSERT INTO module_rightmatrix (user_type_in, user_type_out, node_type, `right`) VALUES (:user_type_in, :user_type_out, :node_type, :right)', array( ':user_type_in'=> $cpt_from, ':user_type_out' => $cpt_to, ':node_type' => $droit, ':right' => $cpt_do ));
					}
				}
			}
			
			// die('save');
		}
		
		
		// Initialisation de la matrice
		$ppo->right = array();
		foreach( $ppo->from AS $cpt_from ) {
			$ppo->right[$cpt_from] = array();
			foreach( $ppo->to AS $cpt_to ) {
				$ppo->right[$cpt_from][$cpt_to] = array();
				foreach( $ppo->where AS $cpt_where ) {
					$ppo->right[$cpt_from][$cpt_to][$cpt_where] = array();
					foreach( $ppo->do AS $cpt_do ) {
						$ppo->right[$cpt_from][$cpt_to][$cpt_where][$cpt_do] = false;
					}
				}
			}
		}
		
		// Récupération des information de la matrice en base de données
		$tmp_right = _doQuery('SELECT * FROM module_rightmatrix');
		foreach($tmp_right AS $tmp_right_item ) {
			$ppo->right[$tmp_right_item->user_type_in][$tmp_right_item->user_type_out][$tmp_right_item->node_type][$tmp_right_item->right] = true;
		}
		return _arPPO($ppo, 'matrix-display.tpl');
	}

}
