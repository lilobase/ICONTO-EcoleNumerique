<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneVueVignette extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      _classInclude('classeur|ClasseurService');

      // Récupération des paramètres
      $ppo->classeurId      = $this->getParam('classeurId');
      $ppo->dossierId       = $this->getParam('dossierId');

      // Gestion des droits
      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
      $ppo->typeUtilisateur   = _currentUser()->getExtra('type');
      $ppo->idUtilisateur     = _currentUser()->getExtra('id');

    // Récupération des paramètres d'affichages
    $ppo->tri = ClasseurService::getContentSort ();

        // Récupération des dossiers & des fichiers / favoris
        $fichierDAO = _ioDAO('classeur|classeurfichier');
        $dossierDAO = _ioDAO('classeur|classeurdossier');

        if ($ppo->dossier = $dossierDAO->get($ppo->dossierId)) {

          if ($ppo->dossier->parent_id != 0) {

            $ppo->dossierParent = $dossierDAO->get($ppo->dossier->parent_id);
          } else {

            $classeurDAO = _ioDAO('classeur|classeur');
            $ppo->classeurParent = $classeurDAO->get($ppo->classeurId);

            $ppo->classeurParent->isPersonnel = ClasseurService::getClasseurPersonnelId () == $ppo->classeurParent->id;
          }
        }

        if (!$ppo->dossier->casier || $ppo->niveauUtilisateur >= PROFILE_CCV_MODERATE) {

          $imgTypes = array('PNG', 'GIF', 'JPG', 'JPEG', 'png', 'gif', 'jpg', 'jpeg');
          $ppo->contenus = $dossierDAO->getContenus($ppo->classeurId, $ppo->dossierId, $ppo->tri);
          foreach ($ppo->contenus as $contenu) {

            if ($contenu->content_type == 'fichier' && in_array($contenu->type, $imgTypes)) {

              $fichier = $fichierDAO->get($contenu->id);
              $contenu->lienMiniature = $fichier->getLienMiniature (90);
            }
            if ($ppo->dossier->casier) {

              $user = Kernel::getUserInfo($contenu->user_type, $contenu->user_id);
              $contenu->user = $user['prenom'].' '.$user['nom'];
            }
          }
          $ppo->fileExtensionAllowed = array('ai', 'avi', 'bmp', 'css', 'csv', 'doc', 'docx', 'eps', 'gif', 'html', 'ico', 'jpg', 'jpeg', 'js', 'json', 'mov', 'mp3', 'odp', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pptx', 'psd', 'svg', 'swf', 'tiff', 'ttf', 'txt', 'wav', 'xls', 'xlsx', 'xml', 'zip');
        }

      $toReturn = $this->_usePPO ($ppo, '_vue_vignette.tpl');
  }
}