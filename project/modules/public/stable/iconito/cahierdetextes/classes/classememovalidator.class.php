<?php

_classInclude('cahierdetextes|basememovalidator');

class ClasseMemoValidator extends BaseMemoValidator
{
    /**
     * Méthode de validation afin de vérifier que la liste des élèves destinataires n'est pas vide
     *
     * @return null
     */
    protected function validate()
    {
        parent::validate();

        $eleves = $this->getOption('eleves', array());

        if (empty($eleves)) {
            $this->addError(CopixI18N::get ('cahierdetextes|cahierdetextes.error.noStudents'));
        }
    }
}
