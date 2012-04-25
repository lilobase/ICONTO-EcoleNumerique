<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Frédéric MOSSMANN <fmossmann@cap-tic.fr>
*/

class ActionGroupOptions extends enicActionGroup {
  
  public function beforeAction ($actionName) {
    
    // Contrôle d'accès au module
    if (!is_null($classeurId = _request ('classeurId', _request('id', null)))
      && ($actionName != 'sauvegardeEtatArbreClasseurs' && $actionName != 'sauvegardeEtatArbreDossiers')) {
            
      if (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_ADMIN) {
    
        return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
      }
    }
    
    $this->addJs('js/iconito/module_classeur.js');
  }
  
  public function processDefault() {
		_classInclude('classeur|classeurService');
		$classeurDAO = _ioDAO('classeur|classeur');
		$ppo->conf_ModClasseur_upload = (CopixConfig::exists ('default|conf_ModClasseur_upload')) ? CopixConfig::get ('default|conf_ModClasseur_upload') : 0;

		if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {
			return CopixActionGroup::process ('generictools|Messages::getError',
				array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
		}

		if( $ppo->save->mode = _request ('save-mode', null) ) {
			switch($ppo->save->mode) {
				case 'upload':
					$ppo->save->ok = true;
					$ppo->save->error = '';
					$ppo->save->folder_input = _request('destination',0);
					if( ! preg_match('/dossier-(?P<folder>\d+)/', $ppo->save->folder_input, $matches) ) { $ppo->save->ok = false; $ppo->save->error = '-ERR destination'; break; }
					$ppo->save->folder_id = $matches['folder'];

					if($ppo->classeur->upload_fs) {
						$dir = realpath('./upload').'/'.$ppo->classeur->upload_fs.'/';
						if (is_dir($dir)) {
							$this->rrmdir($dir);
						}
					}

					$ppo->classeur->upload_db = $ppo->save->folder_id;
					// $ppo->classeur->upload_fs = 'classeur-'.$ppo->classeur->id.'-'.substr(md5($ppo->classeur->id.$ppo->save->folder_id.$ppo->classeur->cle.date('YmdHis')),0,8);
					// $ppo->classeur->upload_pw = substr(md5($ppo->classeur->upload_fs.$ppo->classeur->cle),0,8);
					$ppo->classeur->upload_fs = 'classeur-'.$ppo->classeur->id;
					$ppo->classeur->upload_pw = substr(md5($ppo->classeur->id.$ppo->save->folder_id.$ppo->classeur->cle.date('YmdHis')),0,8);
					$classeurDAO->update( $ppo->classeur );
					
					// Création du répertoire
					$dir = realpath('./upload').'/'.$ppo->classeur->upload_fs.'/';
					if (!file_exists($dir)) {
						mkdir($dir, 0755, true);
						$htaccess = fopen( $dir.'.htaccess', 'w' );
						fwrite( $htaccess, "<Limit GET HEAD OPTIONS POST>\nrequire user ".$ppo->classeur->upload_fs."\n</Limit>" );
						fclose( $htaccess );
					}

					$htpasswd_file = realpath('./upload').'/.htpasswd';
					$htpasswd_output = '';
					$in = fopen( $htpasswd_file, 'r' );
					$htpasswd_updated = false;
					if($in) while ( preg_match("/:/", $line = fgets($in) ) )
					{
						$line = rtrim( $line );
						$a = explode( ':', $line );
						if( $a[0] != 'classeur-'.$ppo->classeur->id) {
							$htpasswd_output .= $line."\n";
						}
					}
					$htpasswd_salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 2);
					$htpasswd_output .= $ppo->classeur->upload_fs.":".crypt($ppo->classeur->upload_pw,$htpasswd_salt)."\n";
					fclose($in);

					$out = fopen( $htpasswd_file, 'w' );
					fwrite( $out, $htpasswd_output );
					fclose( $out );


					break;
				case 'upload-delete':
					$dir = realpath('./upload').'/'.$ppo->classeur->upload_fs.'/';
					if (is_dir($dir)) {
						$this->rrmdir($dir);
					}
					$ppo->classeur->upload_db = null;
					$ppo->classeur->upload_fs = null;
					$classeurDAO->update( $ppo->classeur );

					$htpasswd_file = realpath('./upload').'/.htpasswd';
					$htpasswd_output = '';
					$in = fopen( $htpasswd_file, 'r' );
					$htpasswd_updated = false;
					if($in) while ( preg_match("/:/", $line = fgets($in) ) )
					{
						$line = rtrim( $line );
						$a = explode( ':', $line );
						if( $a[0] != 'classeur-'.$ppo->classeur->id) {
							$htpasswd_output .= $line."\n";
						}
					}
					fclose($in);

					$out = fopen( $htpasswd_file, 'w' );
					fwrite( $out, $htpasswd_output );
					fclose( $out );

					break;
					
				default:
					break;
			}
			$ppo->classeur = $classeurDAO->get($ppo->classeur->id);
		}
		$ppo->classeur->upload_url = CopixUrl::get()."upload/".$ppo->classeur->upload_fs."/";

		$dossierDAO = _ioDAO('classeur|classeurdossier');
		if($ppo->classeur->upload_db) $ppo->classeur->folder_infos = $dossierDAO->get($ppo->classeur->upload_db);
		else $ppo->classeur->folder_infos = NULL;


		$ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id);

		return _arPPO ($ppo, 'options_default.tpl');
	}

	private function rrmdir($dir) {
		$objects = scandir($dir);
		foreach ($objects as $file) {
			if ($file != "." && $file != "..") {
				if (filetype($dir."/".$file) == "dir") rrmdir($dir."/".$file); else unlink($dir."/".$file);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}
