<?php
/**
* @package	copix
* @subpackage kernel
* @version	$Id: kernel.plugin.php,v 1.11 2007-06-04 14:39:55 cbeyer Exp $
* @author	Frédéric Mossmann - CAP-TIC
* @copyright 2005 CAP-TIC
* @link      http://www.cap-tic.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude ('kernel|kernel');
_classInclude ('prefs|prefs');
_classInclude ('logs|logs');

class PluginKernel extends CopixPlugin {
    /**
	 * Includes the user class definition
	 * @access public
	 */
    function beforeSessionStart (){
    }

    /**
	 * IPCheck if needed, creating the user object if not yet.
	 * @param	CopixAction	$action	le descripteur de page détécté.
	 * @access public
	 */
    function beforeProcess (&$execParam){
		
        if (isset ($_SESSION["user"]->_isConnected) && $_SESSION["user"]->_isConnected==1){
			if( !isset($_SESSION["user"]->_isIdentified) || $_SESSION["user"]->_isIdentified != true ) {
				
				$_SESSION["user"]->bu = Kernel::getUserInfo( "LOGIN", $_SESSION["user"]->login );
				$_SESSION["user"]->_isIdentified = true; // true;
				if (isset($GLOBALS['COPIX']['DEBUG'])) {
					$GLOBALS['COPIX']['DEBUG']->addInfo("Login ("._currentUser()->getExtra('type')."/".$_SESSION["user"]->bu["id"]." : ".$_SESSION["user"]->bu["prenom"]." ".$_SESSION["user"]->bu["nom"].")", 'Kernel Plugin :');
				}
				
				// Cas du parent d'élève
				if ( _currentUser()->getExtra('type') == "USER_RES") {
					/*
					print_r($_SESSION);
					print_r($mynodes);
					$enfants = Kernel::getNodeParents( _currentUser()->getExtra('type'), $_SESSION["user"]->bu["id"] );
					print_r($enfants);
					while (list($k,$v) = each($enfants)) {
						if ($v["type"] != "USER_ELE") continue;
						// Pour chaque enfant...
					}
					*/
				} else {
				
					$mynodes = Kernel::getMyNodes();
					
					foreach( $mynodes AS $key=>$val ) {
						if( !ereg( "^BU_", $val->type) && !ereg( "^ROOT$", $val->type) ) unset( $mynodes[$key] );
					}
					reset($mynodes);
					
					if( count($mynodes) == 0 ) {
/* Coupé pour ne pas aller directement dans un groupe par défaut...
						// Non lié à la base unique : on teste les clubs
						$mynodes = Kernel::getMyNodes();
						foreach( $mynodes AS $key=>$val ) {
							// Suppression de tous les noeuds qui ne sont pas des groupes
							if( !ereg( "^CLUB$", $val->type) ) {
								unset( $mynodes[$key] );
							}
							// Suppression des groupes dont on est pas membre validé (demande en cours)
							if( $val->droit <= 10 ) {
								unset( $mynodes[$key] );
							}
							
						}
						if( count($mynodes) > 0 ) {
							$home = current($mynodes);
							Kernel::setMyNode( $home->type, $home->id );
						} else {
							// die( "Login non lié à la base unique. Contactez votre administrateur." );
						}
*/
					} elseif( count($mynodes) == 1 ) {
						$home = current($mynodes);
						/*
						$_SESSION["user"]->home["type"] = $home->type;
						$_SESSION["user"]->home["id"] = $home->id;
						*/
						Kernel::setMyNode( $home->type, $home->id );
						// return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getHome' ));
					} else {
						if( ($home_prefs=Prefs::get('kernel','home')) && (ereg('^([^-]+)-(.+)$', $home_prefs, $regs)) ) {
							$home->type = $regs[1];
							$home->id   = $regs[2];
						} else {
							// On positionne sur le 1er element de myNodes (prévoir une mémorisation dans les prefs)
							$home = current($mynodes);
						}
						Kernel::setMyNode( $home->type, $home->id );
						
							// return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getNodes' ));
					}
				}
				
			} // if( !isset( $_SESSION["user"]->_isIdentified ) )
        } // if (isset ($_SESSION["user"]->_isConnected) && $_SESSION["user"]->_isConnected==1)
		else
		{
			
			if (isset($GLOBALS['COPIX']['DEBUG'])&&isset( $_SESSION["user"]->bu )){
				$GLOBALS['COPIX']['DEBUG']->addInfo("Logout ("._currentUser()->getExtra('type')."/".$_SESSION["user"]->bu["id"]." : ".$_SESSION["user"]->bu["prenom"]." ".$_SESSION["user"]->bu["nom"].")", 'Kernel Plugin :');
			}
			
			if( isset( $_SESSION["user"]->bu ) )
			unset( $_SESSION["user"]->bu );
			unset( $_SESSION["user"]->email );
			unset( $_SESSION["user"]->home );
			unset( $_SESSION["modules"] );
			unset( $_SESSION["cache"] );
			$_SESSION["user"]->_isIdentified = false;
		}
	
    }

}

?>
