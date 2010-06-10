<?php

/**
 * Actiongroup frontoffice du module Public
 * 
 * @package Iconito
 * @subpackage Public
 */
class ActionGroupDefault extends EnicActionGroup {
	
	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');

	}
		
	function processDefault () {
		//return _arRedirect (_url ('|getListBlogs'));
		return CopixActionGroup::process ('public|default::getListBlogs');
	}
	
			
   /**
   * Affiche la liste des blogs
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/09
	 * @todo Positionner $grville
   */
   function processGetListBlogs () {
		
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('public||rss', array()).'" type="application/rss+xml" title="'.htmlentities(CopixI18N::get ('public|public.rss.flux.title')).'" />');
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_annuaire.js');

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('public|public.blog.annuaire'));
		
		$kw = $this->getRequest('kw', null);
		$grville = 1;
		
		$tplListe = & new CopixTpl ();
		
		if ($ville_as_array = Kernel::getKernelLimits('ville_as_array')) {
			$tplListe->assign ('list', CopixZone::process ('GetListBlogs2', array('kw'=>$kw, 'ville'=>$ville_as_array)));
		} else
			$tplListe->assign ('list', CopixZone::process ('GetListBlogs2', array('kw'=>$kw, 'grville'=>$grville)));
		$tplListe->assign ('kw', $kw);
		
		//styles/module_public.css
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_public.css"));
		$result = $tplListe->fetch("getlistblogs.tpl");
		
		$tpl->assign ("MAIN", $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
	
	/**
	 * Page "a propos"
	 * 
	 * @author Pierre-Nicolas Lapointe <pnlapointe@cap-tic.fr>
	 * @since 2007/01/22
	 */
  function processAPropos () {
    
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = CopixI18N::get ('public|public.apropos');

		CopixHTMLHeader::addCSSLink (_resource("styles/module_public.css"));

		$nametpl = 'apropos_'.CopixI18N::getLang().'.html';
		
		return _arPPO ($ppo, $nametpl);
    
  }

	
	/**
	 * Flux RSS des blogs de tout Iconito
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/11/27
	 */
  function processRss () {
    
		$rss = CopixZone::process ('Rss');
		//echo "rss=$rss<p></p>";
		
		return _arContent ($rss, array ('content-type'=>CopixMIMETypes::getFromExtension ('xml')));
  }	
}

?>
