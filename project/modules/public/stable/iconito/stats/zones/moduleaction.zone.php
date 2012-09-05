<?php
/**
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: moduleaction.zone.php,v 1.2 2007-09-04 09:59:55 cbeyer Exp $
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
* @param string $action Action analysée
* @param date $date_debut (option) Début de la période interrogée (si vide : pas de restriction)
* @param date $date_fin (option) Fin de la période interrogée (si vide : pas de restriction)
*/
class ZoneModuleAction extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $module_type = $this->getParam('module_type');
        $module_id = $this->getParam('module_id');
        $action = $this->getParam('action');
        $date_debut = $this->getParam('date_debut', null);
        $date_fin = $this->getParam('date_fin', null);

        $dao = _dao('stats|logs');

        $stats = $dao->getStatsModuleAction (array('module_type'=>$module_type, 'module_id'=>$module_id, 'action'=>$action, 'date_debut'=>$date_debut, 'date_fin'=>$date_fin));

        //print_r($stats);

        foreach ($stats as $k=>$s) {

            // Détection du nom Copix du module
            list (,$module) = explode ("_", strtolower($module_type));

            $class = CopixClassesFactory::create("$module|Stats$module");
            $obj = $class->getObjet($action, $s->objet_a);
            //print_r($obj);
            $stats[$k]->objet_name = (isset($obj->name)) ? $obj->name : '';
        }
        $tpl->assign ('name', CopixI18N::get('stats.action.'.$module_type.'-'.$action));
        $tpl->assign ('stats', $stats);

        //$tpl->assign ('canWriteOnline' , BlogAuth::canMakeInBlog('ADMIN_ARTICLE_MAKE_ONLINE',create_blog_object($id_blog)));

        // retour de la fonction :
        $toReturn = $tpl->fetch('module.action.tpl');
        return true;
    }
}
