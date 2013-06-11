<?php

_classInclude('cahierdetextes|basememovalidator');

class EcoleMemoValidator extends BaseMemoValidator
{
    /**
     * Méthode de validation afin de vérifier que la liste des écoles n'est pas vide
     *
     * @return null
     */
    protected function validate()
    {
        parent::validate();

        $classes = $this->getOption('classes', array());

        if (empty($classes)) {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noClassrooms'));
        }
    }
}
