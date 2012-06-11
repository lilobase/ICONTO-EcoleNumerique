<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/


class KernelClasseur {

  /* 
	 * Crée un classeur
	 * Renvoie son ID ou NULL si erreur
	*/
	function create ($infos = array()) {
		
		$return = null;
		
		_classInclude('classeur|classeurservice');
		$dao = _dao('classeur|classeur');
		$new = _record('classeur|classeur');
		$new->titre = ($infos['title']) ? $infos['title'] : CopixI18N::get ('classeur|classeur.moduleDescription');
		$new->cle   = ClasseurService::createKey();
		$new->date_creation = date('Y-m-d H:i:s');
		$dao->insert ($new);
		if (!is_null($new->id)) {
      
      $path2data = realpath('./static/classeur');
      $folder = $path2data.'/'.$new->id.'-'.$new->cle;
      if ($mkdir = mkdir($folder, 0777)) {
        
        chmod($folder, 0777);
        $return = $new->id;
      }
      
      if (!$return) {
        
        $dao->delete ($new->id);
      }
		}
		
		return $return;
	}



	function publish ($id, $image) {
		// $image['file']  -> nom de fichier
		// $image['title'] -> titre (ou nom à défaut)
		// $image['body']  -> commentaire
		// $image['data']  -> données
		
		if( !isset($image['file']) || trim($image['file'])=='' 
		||  !isset($image['data']) ||      $image['data'] =='' ) {
			return false;
		}
		
		$classeur_dao = _dao("classeur|classeur");
		$classeur = $classeur_dao->get($id);
		if( $classeur==null ) {
			return false;
		}

		$ext='';
		switch( strtolower(strrchr($image['file'], ".")) ) {
			case '.jpg':
			case '.jpe':
				$ext="jpg";
				break;
			case '.jpeg':
				$ext="jpeg";
				break;
			case '.gif':
				$ext="gif";
				break;
			case '.png':
				$ext="png";
				break;
			default:
				continue;
				break;
		}

		if( $ext != '' ) {
			$classeur_service = & CopixClassesFactory::Create ('classeur|ClasseurService');

			$photo_dao = & _dao("classeur|classeurfichier");
			$nouvelle_photo = _record("classeur|classeurfichier");
			$nouvelle_photo->classeur_id = $classeur->id;
			$nouvelle_photo->dossier_id = 0;
			if( trim($image['title']) != '' )
				$nouvelle_photo->titre = $image['title'];
			else
				$nouvelle_photo->titre = $image['file'];
			$nouvelle_photo->commentaire = '';

			$nouvelle_photo->fichier   = $image['file'];
			$nouvelle_photo->taille    = strlen($image['data']);
			$nouvelle_photo->user_type = '';
			$nouvelle_photo->user_id   = 0;
			
			$nouvelle_photo->date_upload = date("Y-m-d H:i:s");
			$nouvelle_photo->type = $ext;
			$nouvelle_photo->cle = $classeur_service->createKey();
			$photo_dao->insert( $nouvelle_photo );
			if( $nouvelle_photo->id ) {
				$path2data = realpath("static");
				$path2album = $path2data."/classeur/".$classeur->id."-".$classeur->cle;
				$photofile = $path2album."/".$nouvelle_photo->id."-".$nouvelle_photo->cle.'.'.$ext;
				$file = fopen( $photofile, 'w' );
				fwrite( $file, $image['data'] );
				fclose( $file );
			}
			
			$results = array(
				'title'     => $nouvelle_photo->titre,
				'album_id'  => $classeur->id,
				'album_key' => $classeur->cle,
				'photo_id'  => $nouvelle_photo->id,
				'photo_key' => $nouvelle_photo->cle,
				'photo_ext' => $ext,
				);
			return $results;
		}

		return false;
		
	}
    
    function getStatsRoot()
    {
        _classInclude('systutils|StatsServices');
        $res = array();

        /*
         * Nombre de classeurs
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_classeur';
        $a = _doQuery($sql);
        $res['nbClasseurs'] = array('name' => CopixI18N::get('classeur|classeur.stats.nbClasseurs', array($a[0]->nb)));

        /*
         * Nombre de dossiers
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_classeur_dossier';
        $a = _doQuery($sql);
        $res['nbDossiers'] = array('name' => CopixI18N::get('classeur|classeur.stats.nbDossiers', array($a[0]->nb)));

        /*
         * Nombre de documents
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_classeur_fichier';
        $a = _doQuery($sql);
        $res['nbFichiers'] = array('name' => CopixI18N::get('classeur|classeur.stats.nbFichiers', array($a[0]->nb)));

        $sql = '
            SELECT SUM(taille) AS taille 
            FROM module_classeur_fichier';
		$a = _doQuery ($sql);
		$res['size'] = array ('name'=>CopixI18N::get ('classeur|classeur.stats.size', array(StatsServices::human_file_size($a[0]->taille))));

        return $res;
    }    

}
