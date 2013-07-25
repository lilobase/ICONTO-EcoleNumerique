<?php

/**
 * @package    Iconito
 * @subpackage Classe
 * @author     Julien POTTIER <julien.pottier@isics.fr>
 */
class ActionGroupDefault extends CopixActionGroup
{
    /**
     * Action de configuration des paramètres d'une classe
     *
     * @return CopixActionReturn
     */
    public function processConfigure()
    {
        // Récupération de la classe
        $classe = CopixSession::get('myNode');

        // Si le type de l'objet manipulé n'est pas une classe, on sort en erreur
        if ($classe['type'] !== 'BU_CLASSE') {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                'back'    => CopixUrl::get('||')
            ));
        }

        // Seul l'enseignant de la classe peut accéder à ce paramétrage
        _classInclude('kernel/Kernel');
        $kernel = new Kernel();
        if (!$kernel->isEnseignantOfClasse($classe['id'])) {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.noRights'),
                'back'    => CopixUrl::get('||')
            ));
        }

        $ppo = new CopixPPO ();

        // Traitement du formulaire
        if (CopixRequest::isMethod('post')) {
            _classInclude('classe|ClasseParameters');
            $classeParameters = new ClasseParameters($classe);
            $classeParameters->process(array('minimail' => _request('minimail', false)));

            $ppo->success = true;
        }

        $ppo->has_minimail_enabled = $kernel->hasRegisteredModule('MOD_MINIMAIL', 0, 'BU_CLASSE', $classe['id']);

        return _arPPO($ppo, 'configure.tpl');
    }

    public function go()
    {
        return _arRedirect(CopixUrl::get ('classe||configure'));
    }
}
