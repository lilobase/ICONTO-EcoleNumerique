<?php
/**
 * Logs - Classes
 *
 * Fonctions d'enregistrement et de recherche d'evenements.
 * @package	Iconito
 * @subpackage	Logs
 * @version   $Id: logs.class.php,v 1.3 2006-05-03 08:58:50 fmossmann Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */


class Logs
{
    public function set( $data=null )
    {
        $dao = _dao("logs|logs");
        $nouveau = _record("logs|logs");

        $types = array( 'ERROR', 'DEBUG', 'INFO', 'ALERT', 'LOG', 'AUTO', 'GO' );

        if( isset($data['type']) && in_array( $data['type'], $types ) )
            $nouveau->logs_type = $data['type'];
        else
            $nouveau->logs_type = 'UNKNOWN';

        if( isset($data['message']) ) $nouveau->logs_message = $data['message'];

        $module = CopixRequest::get ('module', 'kernel');
        $action = CopixRequest::get ('action', 'default');

        $nouveau->logs_mod_name   = $module;
        $nouveau->logs_mod_action = $action;

        $user = Kernel::getSessionBU();
        if( sizeof($user) && isset($user['id']) ) {
            $nouveau->logs_user_id    = $user['id'];
            $nouveau->logs_user_login = $user['login'];

        }

        // Node

        $nouveau->logs_date = date("Y-m-j H:i:s", time());
        $nouveau->logs_user_ip = $_SERVER['REMOTE_ADDR'];
        $nouveau->logs_url = $_SERVER['QUERY_STRING'];

        // Kernel::MyDebug( $nouveau );

        if( isset($data['node_type'  ]) ) $nouveau->logs_node_type   = $data['node_type'  ];
        if( isset($data['node_id'    ]) ) $nouveau->logs_node_id     = $data['node_id'    ];
        if( isset($data['module_type']) ) $nouveau->logs_module_type = $data['module_type'];
        if( isset($data['module_id'  ]) ) $nouveau->logs_module_id   = $data['module_id'  ];

        $dao->insert( $nouveau );
    }

}

