<?php

/**
 * Fonctions relatives au kernel et au module Carnet
 * 
 * @package Iconito
 * @subpackage Carnet
 */
class KernelCarnet {


	/**
	 * Statistiques du module carnet
	 *
	 * Renvoie des éléments chiffrés relatifs aux carnets de correspondance et dédiés à un utilisateur système : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbTopics"] ["nbMessages"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(id) AS nb FROM module_carnet_topics';
		$a = $dbw->fetchFirst ($sql);
		$res['nbTopics'] = array ('name'=>CopixI18N::get ('carnet|carnet.stats.nbTopics', array($a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM module_carnet_messages';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMessages'] = array ('name'=>CopixI18N::get ('carnet|carnet.stats.nbMessages', array($a->nb)));
		return $res;
	}


}

