<?php

/**
 * Zone PetitPoucet, qui affiche un fichier de type "lien internet"
 *
 * @package Iconito
 * @subpackage	Malle
 */
class ZoneLink extends CopixZone
{
  /**
  * Affiche un fichier de type "lien internet", en affichant le nom du lien et en renvoyant vers le site contenu dans le fichier
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2010/09/15
  * @param integer $malle Id de la malle
  * @param integer $folder Id du dossier
  * @param object $file Recordset du fichier
  * @param array $can Tableau avec les droits
  */
  public function _createContent (&$toReturn)
  {
    $ppo = new CopixPPO ();

    $file = $this->getParam('file');
    $can = $this->getParam('can');
    $ppo->id = $this->getParam('malle');
    $ppo->folder = $this->getParam('folder');

    $url = $fullUrl = '';

    $fichier = $file->id.'_'.$file->fichier;

        $fullFile = realpath('./static/malle').'/'.$file->malle.'_'.$file->malle_cle.'/'.($fichier);
        if (file_exists($fullFile)) {

      // Doc : http://www.pragmasoft.be/wiki_kub904/?page=Ouvrir%20les%20fichiers%20.URL%20dans%20le%20navigateur

      $regExp =     '@^(http[s]?:\/\/)([_a-zA-Z0-9-.?%#&=\/]+)@i';
      $regExpURL =  '@^(URL=)(http[s]?:\/\/)([_a-zA-Z0-9-.?%#&=\/]+)@i';

      $content = file_get_contents ($fullFile);

      $lines = explode ("\n",$content);
      //Kernel::myDebug($lines);

      $firstLine = (isset($lines[0])) ? $lines[0] : '';
      $firstLine9 = strtolower(substr($firstLine,0,9));

      if ($firstLine9 == '[internet') {
        $line = (isset($lines[1])) ? $lines[1] : '';
        if ($line) {
          if (preg_match($regExpURL, $line, $regs)) {
            $url = $regs[3];
            $fullUrl = $regs[2].$regs[3];
          }
        }
      } else {
        if ($firstLine9 == '[default]') {
          $line = (isset($lines[3])) ? $lines[3] : '';
          if ($line) {
            if (preg_match($regExpURL, $line, $regs)) {
              $url = $regs[3];
              $fullUrl = $regs[2].$regs[3];
            }
          }
        } else {
          $line = (isset($lines[0])) ? $lines[0] : '';
          if (preg_match($regExp, $line, $regs)) {
            $url = $regs[2];
            $fullUrl = $regs[1].$regs[2];
          }
        }
      }
    }

    $file->url = $url;
    $file->fullUrl = $fullUrl;

    $ppo->file = $file;
    $ppo->can = $can;

    $toReturn = $this->_usePPO ($ppo, 'malle|link.tpl');
    return true;

  }

}
