<?php

_classInclude('kernel|basevalidator');
_classInclude('kernel|kernel');
_ioDAO('cahierdetextes|cahierdetextesmemo');

/**
 * Classe de base de la validation des mémos
 */
abstract class BaseMemoValidator extends BaseValidator
{
    /**
     * @var array La liste des fichiers validés
     */
    protected $fichiers = array();

    /**
     * Surcharge du setter de l'objet afin de tester la classe de l'objet
     *
     * @param $object
     *
     * @throws InvalidArgumentException
     */
    public function setObject($object)
    {
        if (!$object instanceof DAORecordCahierDeTextesMemo) {
            throw new InvalidArgumentException(CopixI18N::get('cahierdetextes.error.wrongObjectClass'));
        }

        parent::setObject($object);
    }

    /**
     * Retourne la liste des fichiers validés
     *
     * @return array
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }

    /**
     * Fonction de validation des données de l'objet
     *
     * @return null
     */
    protected function validate()
    {
        $memo                = $this->getObject();
        $fichierMalleDAO     = _ioDAO('malle|malle_files');
        $fichierClasseurDAO  = _ioDAO('classeur|classeurfichier');


        if ($memo->date_creation == '') {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noCreationDate'));
        }

        if ($memo->date_validite == '') {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noValidityDate'));
        }

        if (
            !is_null($memo->date_validite)
            && ($memo->date_validite < $memo->date_creation)
        ) {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongValidityDate'));
        }

        if ($memo->message == '') {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noContent'));
        }

        if ($memo->avec_signature && $memo->date_max_signature == '') {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noSignatureDate'));
        }

        if (
            (
                !is_null($memo->date_max_signature)
                && !is_null($memo->date_validite)
            )
            && (
                $memo->date_max_signature > $memo->date_validite
                || $memo->date_max_signature < $memo->date_creation
            )
        ) {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.wrongMaxSignatureDate'));
        }

        // Traitement des fichiers
        $fichiers = $this->getOption('fichiers', array());
        $nodeType = $this->getOption('nodeType');
        $nodeId   = $this->getOption('nodeId');
        if (!empty($fichiers)) {
            $fichiers = array_unique($fichiers);

            // Récupération de l'identifiant de la malle du node
            $mods = Kernel::getModEnabled ($nodeType, $nodeId);
            if ($malle = Kernel::filterModuleList ($mods, 'MOD_MALLE')) {
                $malleId = $malle[0]->module_id;
            }

            // Récupération des identifiants de classeur
            $classeurIds = array();

            // Classeur du node
            $mods = Kernel::getModEnabled ($nodeType, $nodeId);
            if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {
                $classeurIds[] = $classeur[0]->module_id;
            }

            // Classeur personnel
            $mods = Kernel::getModEnabled (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
            if ($classeur  = Kernel::filterModuleList ($mods, 'MOD_CLASSEUR')) {
                $classeurIds[] = $classeur[0]->module_id;
            }

            // On détermine s'il s'agit de documents de la malle ou du classeur
            foreach ($fichiers as $fichierInfos) {
                $fichierInfos = explode('-', $fichierInfos);

                if ($fichierInfos[0] == 'MOD_MALLE') {
                    // Erreur : le fichier n'appartient pas à la malle du node
                    if (!$fichierMalleDAO->isFileOfMalle($fichierInfos[1], $malleId)) {
                        $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile'));
                        break;
                    }
                    else {
                        $fichier = $fichierMalleDAO->get ($fichierInfos[1]);
                        $this->fichiers[] = array(
                            'type' => $fichierInfos[0],
                            'id'   => $fichierInfos[1],
                            'nom'  => $fichier->nom
                        );
                    }
                }
                elseif ($fichierInfos[0] == 'MOD_CLASSEUR') {
                    $fichier = $fichierClasseurDAO->get ($fichierInfos[1]);

                    // Erreur : le fichier n'appartient pas aux classeurs disponible à l'utilisateur
                    if (!in_array($fichier->classeur_id, $classeurIds)) {
                        $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.invalidFile'));
                        break;
                    }
                    else {
                        $this->fichiers[] = array(
                            'type' => $fichierInfos[0],
                            'id'   => $fichierInfos[1],
                            'nom'  => $fichier
                        );
                    }
                }
            }
        }
    }
}
