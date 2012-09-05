<?php
/**
 * ClasseurCasier - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Sysutils
 * @version     $Id$
 * @author      Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
 */

class ActionGroupCreateLockers extends CopixActionGroup
{
    public function processDefault ()
    {
      _classInclude('sysutils|admin');
    if (!Admin::canAdmin()) {

      return CopixActionGroup::process ('genericTools|Messages::getError',
        array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get ()));
    }

      echo "Récupération des classeurs de classe sans casier\n";
      echo "----------------------------------------------------------------------\n\n";

      // Récupération des classeurs de classe sans casier
      $sql = 'SELECT DISTINCT module_classeur.id'
        .' FROM kernel_mod_enabled, module_classeur'
      .' LEFT JOIN module_classeur_dossier ON (module_classeur_dossier.module_classeur_id = module_classeur.id)'
      .' WHERE module_classeur.id = kernel_mod_enabled.module_id'
      .' AND kernel_mod_enabled.module_type = "MOD_CLASSEUR"'
      .' AND kernel_mod_enabled.node_type = "BU_CLASSE"'
      .' AND (module_classeur_dossier.id IS NULL'
      .' OR module_classeur_dossier.id NOT IN (SELECT id FROM module_classeur_dossier WHERE casier = 1 AND module_classeur_id = module_classeur.id))';

        $results = _doQuery ($sql);

        $dossierDAO = _ioDAO('classeur|classeurdossier');
        _classInclude('classeur|classeurService');

        echo count($results)." casiers à créer.\n";

        foreach($results as $result) {

      $casier = _record ('classeur|classeurdossier');

      $casier->classeur_id    = $result->id;
      $casier->parent_id      = 0;
      $casier->nom            = CopixI18N::get ('classeur|classeur.casierNom');
      $casier->nb_dossiers    = 0;
      $casier->nb_fichiers    = 0;
      $casier->taille         = 0;
      $casier->cle            = classeurService::createKey();
      $casier->casier         = 1;
      $casier->date_creation  = date('Y-m-d H:i:s');

      $dossierDAO->insert($casier);

      echo "Casier du classeur $result->id créé avec succès !\n";
    }

    echo "\n\nFin de la tâche";
    return _arNone();
    }
}