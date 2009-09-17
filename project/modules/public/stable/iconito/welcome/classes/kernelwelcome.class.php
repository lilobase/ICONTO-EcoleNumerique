<?php
/**
* @package  Iconito
* @subpackage Welcome
* @version   $Id: kernelwelcome.class.php,v 1.1 2006-05-22 15:11:49 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2006 CDC-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class KernelWelcome {

	function create () {
		$dao = _dao("welcome|homes");
		$new = _record("welcome|homes");
		$new->date = date("Y-m-d H:i:s");
		$dao->insert ($new);

		$return = $new->id;
		return $return;
	}

	/*
		Renvoie différentes infos chiffrées d'un album photos
	*/
	function getStats ($id_album) {
		die( 'KernelWelcome::getStats' );
		return $res;
	}

	function delete ($id_album) {
		die( 'KernelWelcome::delete' );
		return true;
	}

}

?>
