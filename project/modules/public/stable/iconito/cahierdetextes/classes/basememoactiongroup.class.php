<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy Hubert <jeremy.hubert@isics.fr>
*/

abstract class BaseMemoActionGroup extends CopixActionGroup
{
    /**
     * Retourne le contexte dans lequel l'action est appelée
     *
     * @return string
     */
    protected abstract function getMemoContext();

    /**
     * Pagination des memos
     */
    public function paginateMemoList($ppo, $memos)
    {
        // Pager
        if (count($memos) > CopixConfig::get('cahierdetextes|nombre_max_memos')) {

            require_once (COPIX_UTILS_PATH . 'CopixPager.class.php');

            $params = array(
                'perPage' => intval(CopixConfig::get('cahierdetextes|nombre_max_memos')),
                'delta' => 5,
                'recordSet' => $memos,
                'template' => '|pager.tpl'
            );

            $pager = CopixPager::Load($params);
            $ppo->pager = $pager->GetMultipage();
            $ppo->memos = $pager->data;
        } else {

            $ppo->memos = $memos;
        }
    }

    /**
     * Suppression d'un mémo
     */
    public function supprimerMemo()
    {
        $ppo = new CopixPPO ();
        $memoDAO = _ioDAO('cahierdetextes|cahierdetextesmemo');

        if (!$memo = $memoDAO->get(_request('memoId', null))) {

            return CopixActionGroup::process('generictools|Messages::getError',
                array('message' => CopixI18N::get('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
        }

        // Suppression des relations mémo - eleves existantes
        $memo2eleveDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2eleve');
        $memo2eleveDAO->deleteByMemo($memo->id);

        // Suppression des relations mémo - fichiers existantes
        $memo2fichierDAO = _ioDAO('cahierdetextes|cahierdetextesmemo2files');
        $memo2fichierDAO->deleteByMemo($memo->id);

        // Suppression du mémos
        $memoDAO->delete($memo->id);
    }

    /**
     * Affichage pour impression d'un mémo - * Enseignant *
     */
    public function imprimerMemo()
    {
      $ppo = new CopixPPO ();
      $ppo->memoContext = $this->getMemoContext();
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');

      if (!$ppo->memo = $memoDAO->get (_request('memoId', null))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      // Récupération des paramètres
      $ppo->ecoleId   = _request ('ecoleId', null);
      $ppo->cahierId  = _request ('cahierId', null);
      $ppo->jour      = _request ('jour', date('d'));
      $ppo->mois      = _request ('mois', date('m'));
      $ppo->annee     = _request ('annee', date('Y'));

      // Récupération du nombre d'exemplaires nécessaires (nombre d'élèves concernés)
      $memo2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
      $ppo->count    = $memo2eleveDAO->retrieveNombreElevesConcernesParMemo($ppo->memo->id);

      return _arPPO ($ppo, 'imprimer_memo.tpl');
    }

    /**
     * Affichage du suivi d'un mémo (élèves concernés & signatures) - * Enseignant *
     */
    public function suiviMemo ()
    {
        $ppo = new CopixPPO ();
        $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');

        if (!$ppo->memo = $memoDAO->get (_request('memoId', null))) {
            return CopixActionGroup::process ('generictools|Messages::getError', array (
                'message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'),
                'back' => CopixUrl::get('')
            ));
        }

        // Récupération des élèves liés au mémo
        $memo2eleveDAO  = _ioDAO ('cahierdetextes|cahierdetextesmemo2eleve');
        $ppo->suivis    = $memo2eleveDAO->findSuiviElevesParMemo($ppo->memo->id);

        return _arPPO ($ppo, array ('template' => 'suivi_memo.tpl', 'mainTemplate' => 'main|main_fancy.php'));
    }
}
