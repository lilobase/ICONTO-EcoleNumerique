<?php
/**
 * Actiongroup du module Stats pour les éventuelles opérations à mettre dans la crontab
 *
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: stats.actiongroup.php,v 1.5 2007-07-19 13:51:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


class ActionGroupStats extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }


  /**
    * TODO
    *
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2007/06/12
  */
    public function viewModule ()
    {
        return new CopixActionReturn (COPIX_AR_NONE, 0);

    }




}



