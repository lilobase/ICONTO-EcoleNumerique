<?php
/**
 * Logs - ActionGroup
 *
 * Fonctions d'enregistrement et de recherche d'evenements.
 * @package	Iconito
 * @subpackage	Logs
 * @version   $Id: logs.actiongroup.php,v 1.3 2006-05-11 10:09:41 fmossmann Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

require_once (COPIX_MODULE_PATH.'kernel/'.COPIX_CLASSES_DIR.'kernel.class.php');
require_once (COPIX_MODULE_PATH.'logs/'.COPIX_CLASSES_DIR.'logs.class.php');
require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ActionGroupLogs extends CopixActionGroup {

   function display () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Logs");
		// $tpl->assign ('MENU', '');
		
		$dao = CopixDAOFactory::create("logs|logs");
		
		$data = $dao->getAll();
		$tplData = & new CopixTpl ();
		$tplData->assign ('data', $data);
		$result = $tplData->fetch('action_display.tpl');
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function display_details () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Logs :: Détails");
		$tpl->assign ('MENU', array(
			array( 'url'=>'url', 'titre'=>'titre' ),
		) );
		
		$dao = CopixDAOFactory::create("logs|logs");
		
		$data = $dao->get( $this->vars['id'] );
		$tplData = & new CopixTpl ();
		$tplData->assign ('data', $data);
		$result = $tplData->fetch('action_display_details.tpl');
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

   function test () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Logs");
		
		$dao = CopixDAOFactory::create("logs|logs");
		$data = $dao->lastLogin('admin');
		$tpl->assign ('MAIN', '<pre>'.print_r($data,true).'</pre>' );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}
?>
