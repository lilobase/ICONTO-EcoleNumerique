<?php
/**
 * @package petiteenfance
 * @subpackage gestion
 * @version   $Id$
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Zone affichant le menu d'un dossier
 * @since 2009/03/19
 * @param object $rDemande Recordset de la demande
 * @param string $tab Onglet selectionne
 */

class ZoneStructures_Calendrier extends CopixZone {

	function _createContent (& $toReturn) {
		
		$tpl = new CopixTpl();
		
		$pId = $this->getParam ('id');
		$pJours = $this->getParam ('jours');
		$pDateDebut = $this->getParam ('date_debut');
		$pDateFin = $this->getParam ('date_fin');
		$pDisplay = $this->getParam ('display');
		
		
		//$debut = '2007-02-01';
		//$fin = '2007-12-31';
		$debut = $pDateDebut;
		$fin = $pDateFin;
$debut = '2009-01-01';
$fin   = '2009-12-31';
		
		// Calcul du calendrier
		$arMois = array();
		$max = array('cases'=>0);
		
		list ($y1,$m1,) = explode ('-', $debut);
		list ($y2,$m2,) = explode ('-', $fin);
		
		$mkDebut = mktime(12,0,0,$m1,1,$y1);
		$mkFin = mktime(13,0,0,$m2+1,0,$y2);
		
		//echo (date('d/m/Y',$mkDebut));
		//echo (date('d/m/Y',$mkFin));
		
		$cDates = _class ('kernel|Dates');
		
		$curMois = null;
		for ($i=1, $mk=$mkDebut ; $mk<=$mkFin ; $mk+=60*60*24, $i++) {
			$m = date('m', $mk);
			$n = date('n', $mk);
			$Y = date('Y', $mk);
			if ($curMois != $Y.$m) {
				if ($curMois != null) {
					$objMois['jours'] = $arJours;
					if (count($arJours)+count($objMois['casesVides']) > $max['cases'])
						$max['cases'] = count($arJours)+count($objMois['casesVides']);
					$arMois[$curMois] = $objMois;
				}
				// On calcule combien de jours s'écoulent avant le premier lundi
				$nbCasesVides = $cDates->getNbCasesVides ($mk);
				$objMois = array (
					'numero' => $m,
					'nom' => $cDates->get_jour_mois($m),
					'annee' => $Y,
					//'nbCasesVides' => $nbCasesVides,
					'casesVides' => ($nbCasesVides>0) ? array_fill(0, $nbCasesVides, 1) : array(),
					'index' => date('Y-m',$mk),
				);
				$curMois = $Y.$m;
				$arJours = array();
			}
			$key = date('Y-m-d', $mk);
			$arJours[$key] = array (
				'numero' => $i,
				'lettre' => $cDates->get_jour_lettre($mk),
				'slash'  => date('d/m/Y', $mk),
				'disabled' => ($key<$debut || $key>$fin) ? 1 : 0,
				'ferie' => $cDates->isJourFerie ($mk),
			);
		}
		if ($curMois != null) {
			$objMois['jours'] = $arJours;
			if (count($arJours)+count($objMois['casesVides']) > $max['cases'])
				$max['cases'] = count($arJours)+count($objMois['casesVides']);
			$arMois[$curMois] = $objMois;
		}
		
		// Les jours de la règle du haut
		$arJours = array();
		for ($i=0 ; $i<$max['cases'] ; $i++) {
			switch ($i%7) {
				case 0 : $nom = 'L'; break;
				case 1 : $nom = 'M'; break;
				case 2 : $nom = 'M'; break;
				case 3 : $nom = 'J'; break;
				case 4 : $nom = 'V'; break;
				case 5 : $nom = 'S'; break;
				case 6 : $nom = 'D'; break;
				default : $nom = 'ERR';
			}
			$arJours[] = array (
				'i' => $i,
				'nom' => $nom,
			);
		}
		
		$rForm = new CopixPPO ();
		$rForm->jours = $pJours;
		
		//print_r($rForm);
		//print_r($arMois);
		
	
	
		if( $pDisplay=='horaires' ) {
			$arHoraires = array();
			
			$criteres = _daoSp ()
				->addCondition ('structure', '=', $pId)
				->addCondition ('date', '>=', CopixDateTime::timeStampToyyyymmddhhiiss ($mkDebut))
				->addCondition ('date', '<=', CopixDateTime::timeStampToyyyymmddhhiiss ($mkFin))
				->orderBy ('date')
				->orderBy ('heure_debut');
				
			$horaires = _ioDAO ('kernel|structure_horaires')->findBy ($criteres);
			
			foreach( $horaires AS $horaire )
			{
				$date = CopixDateTime::yyyymmddhhiissToFormat($horaire->date,'Y-m-d');
				if(!isset($arHoraires[$date])) $arHoraires[$date]=array();
				$ppo_horaire = new CopixPPO ();
				$ppo_horaire->heure_debut = CopixDateTime::hhiissToFormat($horaire->heure_debut,'H:i');
				$ppo_horaire->heure_fin   = CopixDateTime::hhiissToFormat($horaire->heure_fin  ,'H:i');
				$arHoraires[$date][] = $ppo_horaire;
			}
			
			// hhiissToFormat
			
			// echo "<pre>"; print_r($arHoraires); die();
			
			$tpl->assign('arHoraires', $arHoraires);
		}
		
		$tpl->assign('arMois', $arMois);
		$tpl->assign('arJours', $arJours);
		$tpl->assign('rForm', $rForm);
		
		$toReturn = $tpl->fetch('zone.structures_calendrier.tpl');
		
		return true;
	}
	
	
} ?>