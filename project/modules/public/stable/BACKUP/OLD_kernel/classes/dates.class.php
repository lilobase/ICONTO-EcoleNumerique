<?php

/**
 * Diverses fonctions traitant les dates
 * 
 * @package kernel
 */
class Dates {


	/**
	 * Determine si une date est un jour ferie
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/08/27
	 * @param integer $pDateTs Timestamp unix du jour (=mktime)
	 * @return string Le nom du jour férié si c'en est un, chaine vide sinon
	 */
	function isJourFerie($pDateTs)
	{

		$d['mday'] = date("d", $pDateTs);
	  $d['mon'] = date("m", $pDateTs);
	  $d['year'] = date("Y", $pDateTs);
	  
		$alsace = false;
		
    //$d=@getdate($date);
    if($d['mday']==1 && $d['mon']==1) return 'Jour de l\'An';
    elseif($d['mday']==1 && $d['mon']==5) return 'F&ecirc;te du travail';
    elseif($d['mday']==8 && $d['mon']==5) return 'Victoire 1945';
    elseif($d['mday']==14 && $d['mon']==7) return 'F&ecirc;te Nationale';
    elseif($d['mday']==15 && $d['mon']==8) return 'Assomption';
    elseif($d['mday']==1 && $d['mon']==11) return 'Toussaint';
    elseif($d['mday']==11 && $d['mon']==11) return 'Armistice 1918';
    elseif($d['mday']==25 && $d['mon']==12) return 'No&euml;l';
    elseif($alsace && $d['mday']==26 && $d['mon']==12) return 'Saint-Etienne';
    else {
	    //Autres cas
	    //Paques
	    $paques=@getdate(easter_date($d['year']));
	
	    //Lundi de paques
	    $Lpaques=$paques;
	    for($i=0; $Lpaques['wday']!=1 && $i<7; $i++)
	    $Lpaques=@getdate(@mktime(0,0,0,$Lpaques['mon'],$Lpaques['mday']+1,$Lpaques['year']));
	    if($d['mday']==$Lpaques['mday'] && $d['mon']==$Lpaques['mon'])
		    return 'Lundi de P&acirc;ques';
	    else {
        //Pentecote=septième dimanche après Pâques
        $pentecote=@getdate(@mktime(0,0,0,$paques['mon'],$paques['mday']+49,$paques['year']));
        for($i=0; $pentecote['wday']!=0 &&$i<7; $i++)
        $pentecote = @getdate(@mktime(0,0,0,$pentecote['mon'],$pentecote['mday']+$i,$pentecote['year']));
        
        //Lundi de Pentecote
        $Lpentecote = @getdate(@mktime(0,0,0,$pentecote['mon'],$pentecote['mday']+1,$pentecote['year']));
        if($d['year']<2005 && $d['mday']==$Lpentecote['mday'] && $d['mon']==$Lpentecote['mon'])
        	return 'Pentec&ocirc;te';
        else {
	         //Ascension = pentecote -10j
$ascension = @getdate(@mktime(0,0,0,$pentecote['mon'],$pentecote['mday']-10,$pentecote['year']));
	        if($d['mday']==$ascension['mday'] && $d['mon']==$ascension['mon'])
  	       	return 'Ascension';
					else {
						// Vendredi saint
						if ($alsace) {
							$Vpaques=$paques;
							//print_r2($Vpaques);
	      	  	for($i=0; $Vpaques['wday']!=5 && $i<7; $i++)
								$Vpaques = @getdate(@mktime(0,0,0,$Vpaques['mon'],$Vpaques['mday']-1,$Vpaques['year']));
     					if($d['mday']==$Vpaques['mday'] && $d['mon']==$Vpaques['mon'])
		  	     		return 'Vendredi Saint';
						}
					}
        }
	    }
		}
		return '';
	}



	/**
	 * Renvoie la lettre d'un jour
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/08/27
	 * @param integer $pMk Timestamp unix du jour (=mktime)
	 * @return string La lettre du jour (S pour samedi, D pour dimanche...)
	 */
	function get_jour_lettre ($pMk) {
		switch (date('w', $pMk)) {
			case 0 : $res = "Di"; break;
			case 1 : $res = "Lu"; break;
			case 2 : $res = "Ma"; break;
			case 3 : $res = "Me"; break;
			case 4 : $res = "Je"; break;
			case 5 : $res = "Ve"; break;
			case 6 : $res = "Sa"; break;
			default : $res = "ERR";
		}
		return $res;
	}



	function get_age ($date_nais) {
		if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date_nais, $regs)) {
			$res = "";
			$d = $regs[3];
			$m = $regs[2];
			$y = $regs[1];
			$nb_annees = date("Y") - $y;
			$nb_mois = date("m") - $m;
			if ($nb_mois<0) {
				$nb_mois = 12-($nb_mois*-1);
				$nb_annees--;
			}
			$res .= $nb_annees.' ans';
			//if ($nb_mois && $format != "years")
			//	$res .= ' et '.$nb_mois.' mois';
			if ($nb_mois>0)
				$res .= ' et '.$nb_mois.' mois';
		} else
			$res = $date_nais;
		return $res;
	}
	
	function getNbCasesVides ($mk) {
		$dateW = date('w', $mk);
		//echo (' | '.date('d/m/Y', $mk).'->'.$dateW);
		switch ($dateW) {
			case 0 : $res = 6; break;
			default : $res = $dateW-1;
		}
		return $res;
	}
	
	
	function get_jour_mois ($m) {
		switch ($m) {
			case 1 : $res = "Janvier"; break;
			case 2 : $res = "F&eacute;vrier"; break;
			case 3 : $res = "Mars"; break;
			case 4 : $res = "Avril"; break;
			case 5 : $res = "Mai"; break;
			case 6 : $res = "Juin"; break;
			case 7 : $res = "Juillet"; break;
			case 8 : $res = "Ao&ucirc;t"; break;
			case 9 : $res = "Septembre"; break;
			case 10 : $res = "Octobre"; break;
			case 11 : $res = "Novembre"; break;
			case 12 : $res = "D&eacute;cembre"; break;
			default : $res = "ERR";
		}
		return $res;
	}

}

?>