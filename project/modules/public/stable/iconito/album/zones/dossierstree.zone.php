<?php
/**
 * @package  Iconito
 * @subpackage Album
 * @version   $Id: dossierstree.zone.php,v 1.4 2007-07-04 10:20:20 fmossmann Exp $
 * @author   Frédéric Mossmann
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @link      http://www.iconito.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class ZoneDossiersTree extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $dossiers_tree = $this->getParam('tree');
        $dossiers_commands = Album::tree2commands( $dossiers_tree );

        $dossiers_tree_move = Album::tree2move( $dossiers_tree, $this->getParam('dossier_id') );
        $dossiers_commands_move = Album::tree2commands( $dossiers_tree_move );

        //Kernel::MyDebug( $dossiers_commands_move );

        $tpl->assign('album_id', $this->getParam('album_id') );
        $tpl->assign('dossier_id', $this->getParam('dossier_id') );
        $tpl->assign('dossier', $this->getParam('dossier') );
        $tpl->assign('commands', $dossiers_commands );
        $tpl->assign('commands_move', $dossiers_commands_move );
        $tpl->assign('dossiermenu', $this->getParam('dossiermenu') );

        switch( $this->getParam('mode') ) {
            case 'htmllist':
                $toReturn = $tpl->fetch ('dossierstree_htmllist.tpl');
                break;

            case 'combo':
            default:
                $toReturn = $tpl->fetch ('dossierstree_combo.tpl');
                break;
        }

        return true;
    }

}
