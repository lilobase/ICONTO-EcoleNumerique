<?php
/**
 * Actiongroup du module Stats pour les éventuelles opérations à mettre dans la crontab
 *
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: cron.actiongroup.php,v 1.3 2007-07-19 13:51:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


class ActionGroupCron extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }


  /**
    * Mise à jour des données n'ayant pas été enregistrées au moment des logs dans la base de données. Correspond à la recherche des parents des modules
    *
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2007/06/12
  */
    public function updateCron ()
    {
        $serv = CopixClassesFactory::create("stats|StatsService");
        $send = $serv->updateCron ();

        return new CopixActionReturn (COPIX_AR_NONE, 0);

    }




}



