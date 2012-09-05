<?php
/**
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: moduleactions.zone.php,v 1.1 2007-07-20 15:23:25 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
* Toutes les actions faites sur module
*
* @author Christophe Beyer <cbeyer@cap-tic.fr>
* @since 2007/06/14
* @param string $module_type Type du module
* @param integer $module_id Id du module
* @param date $date_debut (option) Début de la période interrogée (si vide : pas de restriction)
* @param date $date_fin (option) Fin de la période interrogée (si vide : pas de restriction)
*/
class ZoneModuleActions extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $module_type = $this->getParam('module_type', null);
        $module_id = $this->getParam('module_id', null);
        $date_debut = $this->getParam('date_debut', null);
        $date_fin = $this->getParam('date_fin', null);

        $dao = _dao('stats|logs');

        $stats = $dao->getStatsModule (array('module_type'=>$module_type, 'module_id'=>$module_id, 'date_debut'=>$date_debut, 'date_fin'=>$date_fin));

        foreach ($stats as $k=>$s) {
            $stats[$k]->action_name = CopixI18N::get('stats.action.'.$module_type.'-'.$s->action);
        }

        $tpl->assign ('getStatsModule', $stats);

        //$tpl->assign ('canWriteOnline' , BlogAuth::canMakeInBlog('ADMIN_ARTICLE_MAKE_ONLINE',create_blog_object($id_blog)));

        // retour de la fonction :
        $toReturn = $tpl->fetch('module.actions.tpl');
        return true;
    }
}
