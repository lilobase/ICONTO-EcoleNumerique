<?php
/**
 * Actiongroup du module Stats pour les �ventuelles op�rations � mettre dans la crontab
 * 
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: cron.actiongroup.php,v 1.3 2007-07-19 13:51:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file 
 */

 
class ActionGroupCron extends CopixActionGroup {

  /**
	* Mise � jour des donn�es n'ayant pas �t� enregistr�es au moment des logs dans la base de donn�es. Correspond � la recherche des parents des modules
	* 
	* @author Christophe Beyer <cbeyer@cap-tic.fr>
	* @since 2007/06/12
  */
	function updateCron () {
		 	
		$serv = CopixClassesFactory::create("stats|StatsService");
		$send = $serv->updateCron ();
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);
		
	}


	

}



?>