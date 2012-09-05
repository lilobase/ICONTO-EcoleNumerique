<?php
/**
* @package		kernel
* @author	Christophe Beyer
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Combo avec la liste d'annees scolaires
 * @package kernel
 * @param string $name Nom HTML de la combo
 * @param string $selected Valeur courante selectionnee
 * @param string $extra (option) HTML supplementaire
 * @since 2009/10/14
 */
class ZoneCombo_annees extends CopixZone
{
   public function _createContent (&$toReturn)
   {
            $tpl = new CopixTpl();

            $pName = $this->getParam ('name');
            $pSelected = $this->getParam ('selected');
            $pExtra = $this->getParam ('extra2');
            $pValues = $this->getParam ('values', array());

            $pDebut = $this->getParam ('debut');
            $pFin = $this->getParam ('fin');

            $tab = array();

            for ($i = $pDebut ; $i<=$pFin ; $i++) {
                $tab[$i] = $i.'-'.($i+1);
            }

            $tpl->assign('arValues', $tab);
            $tpl->assign('name', $pName);
            $tpl->assign('selected', $pSelected);
            $tpl->assign('extra', $pExtra);

      $toReturn = $tpl->fetch('kernel|combo_generic.tpl');

      return true;
   }
}


