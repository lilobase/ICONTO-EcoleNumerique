<?php
/**
* @package  Iconito
* @subpackage Prefs
* @version   $Id: prefs.class.php,v 1.4 2007-03-22 15:31:37 cbeyer Exp $
* @author   Frédéric Mossmann
* @copyright 2005 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class Prefs
{
    public function getModules()
    {
        $my_modules = array();
        $nodes = Kernel::getMyNodes();

        if(sizeof($nodes)) {
            foreach( $nodes AS $node_key=>$node ) {

                $modules = Kernel::getModEnabled( $node->type, $node->id,
                    _currentUser()->getExtra('type'),   _currentUser()->getExtra('id') );

                foreach( $modules AS $modules_key=>$module ) {
                    if( $module->module_type!="MOD_PREFS" && ereg('^MOD_(.+)$', $module->module_type, $regs) ) {
                        $mod_tmp->type = $module->module_type;
                        $mod_tmp->nom  = $module->module_nom;
                        $mod_tmp->rep  = strtolower($regs[1]);
                        $my_modules[$module->module_type] = $mod_tmp;
             unset ($mod_tmp);
                    }
                }
            }
        }

        ksort( $my_modules );

        $pref_module['MOD_PREFS']->type = 'MOD_PREFS';
        $pref_module['MOD_PREFS']->nom = CopixI18N::get ('prefs|prefs.string.generalprefs');;
        $pref_module['MOD_PREFS']->rep = 'prefs';
        $all_modules = array_merge( $pref_module, $my_modules );

        return( $all_modules );
    }

    public function getPrefs( $user=-1 )
    {
        $data  = array();
        $datas = array();

        if( $user == -1 ) {
            $bu = Kernel::getSessionBU();
            if( isset( $bu['user_id'] ) ) $user=$bu['user_id'];
        }

        $dao = _dao("prefs|prefs");
        $data = $dao->getByUser( $user );

        if(sizeof($data)) {
            foreach( $data AS $data_key=>$data_val ) {
                $datas[$data_val->prefs_module][$data_val->prefs_code] = $data_val->prefs_value;
            }
        }

        return $datas;
    }

    public function setPrefs( $module, $data )
    {
        $dao = _dao('prefs|prefs');
        $bu = Kernel::getSessionBU();

        foreach( $data AS $data_key => $data_value ) {
            if( $pref = $dao->get( $bu['user_id'], $module, $data_key ) ) {
                $dao->delete( $bu['user_id'], $module, $data_key );
            }
            $pref = _record ('prefs|prefs');
            $pref->prefs_user = $bu['user_id'];
            $pref->prefs_module = $module;
            $pref->prefs_code = $data_key;
            $pref->prefs_value = $data_value;
            $dao->insert ($pref);
        }
    }

    public function get( $module, $code, $user=-1 )
    {
        $dao = _dao('prefs|prefs');

        if( $user == -1 ) {
            $bu = Kernel::getSessionBU();
            if( isset( $bu['user_id'] ) ) $user=$bu['user_id'];
        }

        if( $pref = $dao->get( $user, $module, $code ) ) {
            return $pref->prefs_value;
        } else {
            return false;
        }
    }

    public function set( $module, $code, $value, $user=-1 )
    {
        $dao = _dao('prefs|prefs');

        if( $user == -1 ) {
            $bu = Kernel::getSessionBU();
            if( isset( $bu['user_id'] ) ) $user=$bu['user_id'];
        }

        if( $pref = $dao->get( $user, $module, $code ) ) {
            $dao->delete( $user, $module, $code );
        }
        $pref = _record ('prefs|prefs');
        $pref->prefs_user = $user;
        $pref->prefs_module = $module;
        $pref->prefs_code = $code;
        $pref->prefs_value = $value;
        $dao->insert ($pref);
    }

    public function del( $module, $code, $user=-1 )
    {
        $dao = _dao('prefs|prefs');

        if( $user == -1 ) {
            $bu = Kernel::getSessionBU();
            if( isset( $bu['user_id'] ) ) $user=$bu['user_id'];
        }

        if( $pref = $dao->get( $user, $module, $code ) ) {
            $dao->delete( $user, $module, $code );
        }
    }

}

