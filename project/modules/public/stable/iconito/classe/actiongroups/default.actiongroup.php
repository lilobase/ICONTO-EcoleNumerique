<?php

/**
 * @package    Iconito
 * @subpackage Classe
 * @author     Julien POTTIER <julien.pottier@isics.fr>
 */
class ActionGroupDefault extends CopixActionGroup
{
    public function processConfigure()
    {
        $ppo    = new CopixPPO ();

        _classInclude('kernel/Kernel');
        $kernel = new Kernel();

        $classe = CopixSession::get('myNode');
        // Si le type de l'objet manipulé n'est pas une classe, on sort en erreur
        if ($classe['type'] !== 'BU_CLASSE') {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                'back'    => CopixUrl::get('||')
            ));
        }

        // Seul l'enseignant de la classe peut accéder à ce paramétrage
        if (!$kernel->isEnseignantOfClasse($classe['id']))
        {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                'back'    => CopixUrl::get('||')
            ));
        }

        if (CopixRequest::isMethod('post')) {
            _classInclude('classe|ClasseParameters');
            $classeParameters = new ClasseParameters($classe);
            $classeParameters->process(array(
                'minimail' => _request('minimail', false)
            ));
        }
        $ppo->has_minimail_enabled = $kernel->hasRegisteredModule('MOD_MINIMAIL', 0, 'BU_CLASSE', $classe['id']);

        return _arPPO($ppo, 'configure.tpl');
    }
}
