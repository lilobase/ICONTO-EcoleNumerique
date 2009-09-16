<?php

/**
 * Diverses fonctions traitant les structures
 * 
 * @package gestion
 */
class Structures {


	/**
	 * Ajoute ou retire un horaire à un jour
	 *
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2009/08/14
	 */
	function modifyHoraire($action, $structure, $jour, $debut_heure, $debut_minute, $fin_heure, $fin_minute )
	{
		if( !preg_match('/(?<annee>\d\d\d\d)-(?<mois>\d\d)-(?<jour>\d\d)/', $jour, $jour_matches) ) return false;
		
		$jour_copix  = $jour_matches['annee'].$jour_matches['mois'].$jour_matches['jour'].'000000';
		$debut_copix = str_pad($debut_heure, 2, "0", STR_PAD_LEFT).str_pad($debut_minute, 2, "0", STR_PAD_LEFT).'00';
		$fin_copix   = str_pad($fin_heure,   2, "0", STR_PAD_LEFT).str_pad($fin_minute,   2, "0", STR_PAD_LEFT).'00';
		
// echo "<li>Traitement : $action, $structure, $jour_copix, $debut_copix, $fin_copix</li>";
		
		$criteres = _daoSp ()
			->addCondition ('structure', '=', $structure)
			->addCondition ('date', '=', $jour_copix)
			->orderBy ('heure_debut');
				
		$horaires = _ioDAO ('kernel|structure_horaires')->findBy ($criteres);
		
		$horaires_list = new HoraireList;
		
		if(count($horaires)) {
// print_r($horaires);
			foreach( $horaires AS $horaire ) {
				$horaires_list->add( new Horaire( $horaire->heure_debut, $horaire->heure_fin ) );
				
			}
			
			
		}
		
		if( $action=='add') $horaires_list->add( new Horaire( $debut_copix, $fin_copix ) );
		if( $action=='del') $horaires_list->del( new Horaire( $debut_copix, $fin_copix ) );
		
		$horaires_list->save( $structure, $jour_copix );
		
// print_r($horaires_list);
	}

}

class HoraireList {
	private $horaires = array();
	
	public function HoraireList() {
	}
	
	public function add( $horaire ) {
		$this->horaires[] = $horaire;
		$this->check();
// echo "<p>Apres add()</p><pre>"; print_r( $this->horaires ); echo "</pre>";
	}
	
	public function del( $horaire ) {
		
	}
	
	public function check() {
		$this->sort();
		
// echo "<p>Avant check()</p><pre>"; print_r( $this->horaires ); echo "</pre>";
		
		$prev = null;
		reset($this->horaires);
		foreach( $this->horaires AS $horaire_key => $horaire ) {
			
// echo "<p>".$horaire->debut." &raquo; ".$horaire->fin."</p>";
			
			if( $prev===null) {
				$prev = $horaire_key;
			} else {
			

				if( $horaire->debut <= $this->horaires[$prev]->fin ) {
					$this->horaires[$prev]->debut = $this->horaires[$prev]->debut;
					$this->horaires[$prev]->fin   = max($this->horaires[$prev]->fin,$this->horaires[$horaire_key]->fin);
					unset($this->horaires[$horaire_key]);
				} else {
					$prev = $horaire_key;
				}
			}
		}
		
// echo "<p>Apres check()</p><pre>"; print_r( $this->horaires ); echo "</pre>";
	}
	
	private function sort() {
		usort( $this->horaires, array("HoraireList", "sort_cmp") );
	}
	
	private function sort_cmp( $a, $b ) {
		if($a->debut==$b->debut) return $a->fin   > $b->fin  ?1:-1;
		else                     return $a->debut > $b->debut?1:-1;
	}
	
	public function save( $structure, $jour ) {

		/*
		$criteres = _daoSp ()
			->addCondition ('structure', '=', $structure)
			->addCondition ('date', '=', $jour);
				
		$horaires = _ioDAO ('kernel|structure_horaires')->deleteBy ($criteres);
		*/
		
		_ioDAO ('kernel|structure_horaires')->deleteByStructDate( $structure, $jour );
		
		$horaire = _record ('kernel|structure_horaires');
		foreach( $this->horaires AS $horaire ) {
			$horaire->structure   = $structure;
			$horaire->date        = $jour;
			$horaire->heure_debut = $horaire->debut;
			$horaire->heure_fin   = $horaire->fin;
			_dao ('kernel|structure_horaires')->insert ($horaire);
		}
	}
}

class Horaire {
	public $debut;
	public $fin;
	
	public function Horaire( $debut, $fin ) {
		$this->debut = $debut;
		$this->fin   = $fin; 
	}
}
?>