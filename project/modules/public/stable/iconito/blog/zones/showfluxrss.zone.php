<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showfluxrss.zone.php,v 1.4 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'fluxrssservices.class.php');

class ZoneShowFluxrss extends CopixZone {
   function _createContent (&$toReturn) {

      $tpl  = & new CopixTpl ();
	  
      $blog = $this->getParam('blog', '');
	  $id_flux = $this->getParam('id_flux', '');
      
	  $daoFlux = CopixDAOFactory::create('blog|blogfluxrss');
      $flux = $daoFlux->getFluxById($id_flux);
	  $urlFlux = $flux->url_bfrs;

	  //on récupère le flux RSS de l'URL
	  $serviceFlux = new FluxRSSServices;
	  $arFlux = $serviceFlux->getRss($urlFlux);
		
		//print_r($arFlux);
		
	  $tpl->assign ('arFlux', $arFlux);

      // retour de la fonction :
      $toReturn = $tpl->fetch('showfluxrss.tpl');
      return true;
   }
}
?>
