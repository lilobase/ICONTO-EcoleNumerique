<?php
/**
 * @package	Iconito
 * @subpackage Stats
 * @version $Id: module.zone.php,v 1.1 2007-06-15 15:05:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
* Affichage des stats d'un module précis
*
* @author Christophe Beyer <cbeyer@cap-tic.fr>
* @since 2007/06/15
* @param string $module_type Type du module
* @param integer $module_id Id du module
* @param string $url Adresse à utiliser pour les liens à l'intérieur de l'affichage des stats, pour afficher d'autres périodes etc.
* @param date $date (option) Date
* @param integer $mois (option) Mois
* @param integer $annee (option) Année
*/
class ZoneModule extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_stats.css"));

        $tpl = new CopixTpl ();

        $module_type = $this->getParam('module_type');
        $module_id = $this->getParam('module_id');
        $url = $this->getParam('url', null);
        $date = $this->getParam('date', null);
        $mois = $this->getParam('mois', null);
        $annee = $this->getParam('annee', null);

        if ($mois && $annee) {
            $mkdebut = mktime(0,0,0,$mois,1,$annee);
         $date_debut = date ("Y-m-d", $mkdebut);
            $mkfin = mktime(0,0,0,$mois+1,0,$annee);
         $date_fin = date ("Y-m-d", $mkfin);
            $date = '';
        } elseif ($annee) {
            $mkdebut = mktime(0,0,0,1,1,$annee);
         $date_debut = date ("Y-m-d", $mkdebut);
            $mkfin = mktime(0,0,0,12,31,$annee);
         $date_fin = date ("Y-m-d", $mkfin);
            $date = '';
        } else {
            switch ($date) {
                case "month" :
               $mkdebut = mktime(0,0,0,date("m"),1,date("Y"));
               $date_debut = date ("Y-m-d", $mkdebut);
               $date_fin = date ("Y-m-d");
               break;
                case "last7" :
               $mkdebut = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
               $date_debut = date ("Y-m-d", $mkdebut);
               $date_fin = date ("Y-m-d");
               break;
          case "yesterday" :
               $mkdebut = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
               $date_debut = $date_fin = date ("Y-m-d", $mkdebut);
               break;
                default :
              $date = "today";
               $date_debut = $date_fin = date ("Y-m-d");
               break;
            }
        }

        $stats1 = CopixZone::process ('moduleActions', array(
            'module_type'=>$module_type,
            'module_id'=>$module_id,
            'date_debut'=>$date_debut,
            'date_fin'=>$date_fin,
            ));

        $stats2 = CopixZone::process ('moduleAction', array(
            'module_type'=>$module_type,
            'module_id'=>$module_id,
            'date_debut'=>$date_debut,
            'date_fin'=>$date_fin,
            'action'=>'showArticle',
            ));
        $stats3 = CopixZone::process ('moduleAction', array(
            'module_type'=>$module_type,
            'module_id'=>$module_id,
            'date_debut'=>$date_debut,
            'date_fin'=>$date_fin,
            'action'=>'showPage',
            ));
        $tpl->assign ('stats1', $stats1);
        $tpl->assign ('stats2', $stats2);
        $tpl->assign ('stats3', $stats3);

        $tpl->assign ('comboMois', array(
        1 => CopixI18N::get('kernel|date.mois1'),
        2 => CopixI18N::get('kernel|date.mois2'),
        3 => CopixI18N::get('kernel|date.mois3'),
        4 => CopixI18N::get('kernel|date.mois4'),
        5 => CopixI18N::get('kernel|date.mois5'),
        6 => CopixI18N::get('kernel|date.mois6'),
        7 => CopixI18N::get('kernel|date.mois7'),
        8 => CopixI18N::get('kernel|date.mois8'),
        9 => CopixI18N::get('kernel|date.mois9'),
        10 => CopixI18N::get('kernel|date.mois10'),
        11 => CopixI18N::get('kernel|date.mois11'),
        12 => CopixI18N::get('kernel|date.mois12'),
        ));
        $tpl->assign ('mois', $mois);

        $tmp = array();
        for ($i=2007 ; $i<=date('Y') ; $i++)
            $tmp[$i] = $i;
        $tpl->assign ('comboAnnees', $tmp);
        $tpl->assign ('annee', $annee);
        $tpl->assign ('date_debut', $date_debut);
        $tpl->assign ('date_fin', $date_fin);
        $tpl->assign ('url', $url);

        $urlTab = getUrlTab();
        $form_dest = CopixUrl::get ($urlTab['module'].'|'.$urlTab['group'].'|'.$urlTab['action']);
        $tpl->assign ('form_dest', $form_dest);
        unset ($urlTab['module']);
        unset ($urlTab['group']);
        unset ($urlTab['action']);
        unset ($urlTab['Copix']);
        unset ($urlTab['mois']);
        unset ($urlTab['annee']);
        unset ($urlTab['date']);
        //print_r($urlTab);
        $tpl->assign ('urlTab', $urlTab);
        $tpl->assign ('date', $date);

        // retour de la fonction :
        $toReturn = $tpl->fetch('module.tpl');
        return true;
    }
}
