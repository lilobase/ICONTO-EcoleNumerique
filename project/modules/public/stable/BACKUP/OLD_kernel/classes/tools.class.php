<?php
/**
* @package   vaccination
* @subpackage kernel
* @version   $Id: tools.class.php 42 2009-08-11 13:58:28Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

class Tools {

	
	/**
	 * Formate un nombre
	 */
	public function format_somme ($number, $format='number') {
		
		if ($format == 'words_euros') {
		  
			require_once (COPIX_UTILS_PATH.'Words/Words.php');
			
			$cents = 0;
			
			if (strpos ($number,'.') !== false) {
			 
			  list ($euros,$cents) = explode (".", $number);
			}
			else {
			  
				$euros = $number;
		  }
		  
			$nbEuros = Numbers_Words::toWords ($euros, "fr");
			
			$res = $nbEuros.' euro';
			
			if ($euros > 1) {
			 
			  $res .= 's'; 
			}
			
			if ($cents > 0) {

				$cents = str_pad ($cents, 2, 0, STR_PAD_RIGHT);
				$nbCents = Numbers_Words::toWords ($cents,"fr");
				$res .= ' et '.$nbCents.' cent';
				
				if ($cents > 1) {
				  
				  $res .= 's';
				}
			}
			
			return $res;
			
		} else {
		  return number_format ($number, 2, '.', ' ');
		}
	}
	
	
	function _date2Html( $date ) {
	 if( ereg( "^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$", $date, $regs ) )
	 	$return = str_pad($regs[3], 2, "0", STR_PAD_LEFT)."/".str_pad($regs[2], 2, "0", STR_PAD_LEFT)."/".$regs[1];
	 elseif( ereg( "^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $date, $regs ) )
	 	$return = str_pad($regs[1], 2, "0", STR_PAD_LEFT)."/".str_pad($regs[2], 2, "0", STR_PAD_LEFT)."/".$regs[3];
	 elseif( ereg( "^([0-9]{4})([0-9]{2})([0-9]{2})$", $date, $regs ) )
	 	$return = str_pad($regs[3], 2, "0", STR_PAD_LEFT)."/".str_pad($regs[2], 2, "0", STR_PAD_LEFT)."/".$regs[1];
	 else
	 	$return = $date;
	 return $return;
	}
	/*
		Formatage d'une date au format HH:MM en HHMMSS
	*/
	public function TimeTohhiiss ($time) {
		list ($hh,$ii) = explode(':',$time);
		return str_pad($hh,2,STR_PAD_LEFT,0).str_pad($ii,2,STR_PAD_LEFT,0).'00';
	}
	
	/*
		Verifie qu'une heure est bien saisie au format HH:MM (ou HH)
	*/
	public function checkTime ($time) {
		$preg_match = preg_match('/^([0-9]{1,2}):?([0-9]{0,2})$/',$time,$regs);
		return ($preg_match>0);
	}

	/**
	 * Age d'une personne, en nb d'annees, selon sa date de naissance
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/14
	 * @param date $pDateNaissance Date de naissance, au format YYYY-MM-DD
	 * @param date $pToday (option) Date de calcul, par defaut aujourd'hui, format YYYY-MM-DD
	 * @return integer Age, en annees. Si date incorrecte, renvoie NULL
	 */
	function getAge ($pDateNaissance, $pToday=null) {	
		if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $pDateNaissance, $regs)) {
			
			$toDayY = date("Y");
			$toDayM = date("m");
			$toDayD = date("d");
			
			if ($pToday != null && preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $pToday, $regs2)) {
				$toDayY = $regs2[1];
				$toDayM = $regs2[2];
				$toDayD = $regs2[3];
			}

			$y = $regs[1];
			$m = $regs[2];
			$d = $regs[3];
			
			$nb_annees = $toDayY - $y;
			$nb_mois = $toDayM - $m;
			$nb_jours = $toDayD - $d;
      
			if ($nb_mois<0) {
				$nb_mois = 12-($nb_mois*-1);
				$nb_annees--;
			} elseif ($nb_mois==0 && $nb_jours<0) { // Anniv a venir dans le mois
				$nb_mois = 11;
				$nb_annees--;
			}

			$res = $nb_annees;
		} else {
			$res = null;		
		}
		return $res;
	}
	
	
	/**
	 * Le nom d'un departement
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/14
	 * @param string $dep Numero du departement
	 * @return string Nom du departement, ou false si inconnu
	 */
	function departementName ($dep) {
		switch ($dep) {
			case '87' : $res = 'Haute-Vienne'; break;
			default : $res = false; break;
		}
		return $res;
	}
	
	function print_r2 ($var)
	{ echo "<pre>"; print_r($var); echo "</pre>"; }
	
}

?>