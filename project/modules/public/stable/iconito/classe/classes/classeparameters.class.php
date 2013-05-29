<?php

/**
 * Classe de gestion des paramètres de la classe
 *
 * @author Julien Pottier <julien.pottier@isics.fr>
 */
class ClasseParameters
{
    // La classe
    protected $classe;

    /**
     * Constructeur de la classe
     *
     * @param $classe La classe
     */
    public function __construct($classe)
    {
        $this->classe = $classe;
    }

    /**
     * Traite les valeurs du formulaire de paramétrage d'une classe
     *
     * @param array $values Les valeur saisies
     */
    public function process(array $values)
    {
        // Le Kernel
        _classInclude('kernel|Kernel');
        $kernel = new Kernel();

        $this->processMinimail($kernel, $values);
    }

    /**
     * Traite les valeurs des préférences concernant le minimail
     *
     * @param Kernel $kernel Le kernel
     * @param array $values Les valeurs saisies
     */
    protected function processMinimail($kernel, array $values)
    {
        // Le minimail est-il coché
        $minimailChecked = (bool)(isset($values['minimail']) ? $values['minimail'] : false);

        // Le minimail est-il déjà activé pour la classe
        $hasMinimailEnabled = $kernel->hasRegisteredModule('MOD_MINIMAIL', 0, 'BU_CLASSE', $this->classe['id']);

        // Si le module n'était pas encore activé mais que l'on vient de cocher la case
        if (!$hasMinimailEnabled && $minimailChecked) {
            // On ajoute le module pour la classe
            $kernel->registerModule('MOD_MINIMAIL', 0, 'BU_CLASSE', $this->classe['id']);
        }
        // Si le module était activé et que l'on vient de décocher la case
        elseif ($hasMinimailEnabled && !$minimailChecked) {
            // On supprime le module pour la classe
            $kernel->unregisterModule('MOD_MINIMAIL', 0, 'BU_CLASSE', $this->classe['id']);
        }
    }
}
