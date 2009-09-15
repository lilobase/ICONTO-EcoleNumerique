<?php

/**
 * Actiongroup du module Aide
 * 
 * @package Iconito
 * @subpackage	Aide
 */
class ActionGroupDefault extends CopixActionGroup {
		
	function processDefault () {
		return _arRedirect (_url ('|viewHelp'));
	}

   /**
   * Affiche l'aide
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/12
   */
   function processViewHelp () {
		
		$rubrique = $this->getRequest ('rubrique', null);
		$page = $this->getRequest ('page', null);
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('aide|aide.description'));
		//$tpl->assign ('MENU', $menu);
		
		if ($rubrique) {
			$file = COPIX_MODULE_PATH.$rubrique.'/'.COPIX_CLASSES_DIR.'help'.$rubrique.'.class.php';
			if (file_exists ($file)) {
				$modhelp = & CopixClassesFactory::Create ($rubrique.'|help'.$rubrique);
				if (method_exists($modhelp, 'getPages')) {
				
					$pages = $modhelp->getPages();
				}
				
				if ($page && is_array($pages) && isset($pages[$page])) {	// Page précise
					$tpl->assign ('TITLE_PAGE', Kernel::Code2Name('mod_'.$rubrique).' - '.$pages[$page]['title']);
					$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('aide||', array('rubrique'=>$rubrique)).'">'.CopixI18N::get ('aide|aide.link.top').'</a> :: <a href="'.CopixUrl::get ('aide||').'">'.CopixI18N::get ('aide|aide.link.index').'</a>');
					
					// On vérifie que le fichier existe bien
					$lg = 1;
					//print_r($GLOBALS['COPIX']);
					$lg = $GLOBALS['COPIX']['CONFIG']->default_language;
					//print_r("lg=$lg");
					$file = COPIX_MODULE_PATH.$rubrique.'/'.COPIX_STATIC_DIR.'help_'.$page.'_'.$lg.'.html';
					if (file_exists ($file)) {
						
						$tpl->assignStatic ('text', $rubrique.'|help_'.$page.'_'.$lg.'.html');
						$tpl->assign ('rubrique', $rubrique);
						$tpl->assign ('pages', $pages);
						
						$links = $pages[$page]['links'];
						$see = array();
						if (is_array($links)) {
							//print_r($links);
							foreach ($links as $link) {
								$l = explode ('|', $link);
								//print_r($l);
								if (count($l)==1) {	// Même module
									$see[] = array('rubrique'=>$rubrique, 'page'=>$l[0], 'title'=>$pages[$l[0]]['title']);
								} else {	// Autre module
									$file = COPIX_MODULE_PATH.$l[0].'/'.COPIX_CLASSES_DIR.'help'.$l[0].'.class.php';
									if (file_exists ($file)) {
										$modhelp2 = & CopixClassesFactory::Create ($l[0].'|help'.$l[0]);
										if (method_exists($modhelp2, 'getPages')) {
											$pages2 = $modhelp2->getPages();
											//print_r($pages2);
											$see[] = array('rubrique'=>$l[0], 'page'=>$l[1], 'title'=>$pages2[$l[1]]['title']);
										}
									}
								}
							
							}
						}
						
						$tpl->assign ('links', $see);
					
					}
					$MAIN = $tpl->fetch('viewhelprubpage.tpl');
				} else {	// Sommaire de l'aide du module
					$tpl->assign ('TITLE_PAGE', Kernel::Code2Name('mod_'.$rubrique));
					$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('aide||').'">'.CopixI18N::get ('aide|aide.link.index').'</a>');
					$tpl->assign ('rubrique', $rubrique);
					$tpl->assign ('pages', $pages);
					$MAIN = $tpl->fetch('viewhelprub.tpl');
				}
			} else
				$MAIN = $tpl->fetch('viewhelp.tpl');			
		} else {
			$rubs = array('minimail', 'album');	// Compléter avec les modules dont l'aide est écrite
			
			$rubriques = array();
			foreach ($rubs as $rub) {
				$rubriques[] = array(
					'name' => $rub,
					'title' => Kernel::Code2Name('mod_'.$rub),
				);
			}
			$tpl->assign ('rubriques', $rubriques);
			$MAIN = $tpl->fetch('viewhelp.tpl');			
		}
		
		$tpl->assign ('MAIN', $MAIN);

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

}
?>