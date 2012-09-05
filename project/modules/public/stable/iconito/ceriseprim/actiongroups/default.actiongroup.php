<?php

/**
 * Actiongroup du module Ceriseprim
 *
 * @package Iconito
 * @subpackage	Ceriseprim
 */

class ActionGroupDefault extends EnicActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processGo()
    {
            $ppo = new CopixPPO();

        if (!Kernel::is_connected()) {
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('kernel|kernel.error.nologin'), 'back' => CopixUrl::get('auth|default|login')));
        }
        $user = _currentUser ();

        $ppo->ceriseprim_url   = '';
        $ppo->ceriseprim_admin = '';

        if( preg_match("/^(?P<node_type>[\w_]+)-(?P<node_id>\d+)$/",_request("id"),$regs)) {
            if($regs['node_type']=="BU_ECOLE"||$regs['node_type']=="BU_CLASSE") {
                if($regs['node_type']=="BU_ECOLE") {
                    $sql = "
                        SELECT *
                        FROM kernel_bu_ecole
                        WHERE numero=:id
                    ";
                    $params = array( ':id' => $regs['node_id'] );
                }
                if($regs['node_type']=="BU_CLASSE") {
                    $sql = "
                        SELECT kernel_bu_ecole.RNE
                        FROM kernel_bu_ecole
                        JOIN kernel_bu_ecole_classe ON kernel_bu_ecole.numero=kernel_bu_ecole_classe.ecole
                        WHERE kernel_bu_ecole_classe.id=:id
                    ";
                    $params = array( ':id' => $regs['node_id'] );
                }

                    $ecoles_list = _doQuery ($sql, $params);
                    if(count($ecoles_list)) {
                        $url = CopixConfig::get ('default|conf_Ceriseprim_url')."/".$ecoles_list[0]->RNE."/ico.php?user=personnel-".$user->getExtra('id')."&date=".date('Y-m-d')."&key=".md5($ecoles_list[0]->RNE."personnel-".$user->getExtra('id').date('Y-m-d').CopixConfig::get ('default|conf_Ceriseprim_secret'));
                        $ppo->ceriseprim_url = $url;

                        if($regs['node_type']=="BU_ECOLE") {
                            $mynodes = Kernel::getMyNodes();
                            foreach( $mynodes AS $node ) {
                                if( $node->type==$regs['node_type'] && $node->id==$regs['node_id'] && $node->droit>=70 ) {
                                    $ppo->ceriseprim_admin = CopixConfig::get ('default|conf_Ceriseprim_url')."/support/iconito/updateIconito.php?rne=".$ecoles_list[0]->RNE;
                                }
                            }
                        }

                    }

            }
        }
        return _arPPO($ppo, 'default.tpl');

    }

}

