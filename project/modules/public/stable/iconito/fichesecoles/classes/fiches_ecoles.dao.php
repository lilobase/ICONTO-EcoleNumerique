<?php


class DAOFiches_ecoles
{
}

class DAORecordFiches_ecoles
{
    /**
     * Le nom en clair d'un document joint a une fiche ecole. Correspond au nom physique du fichier, en enlevant l'Id et l'underscore du debut
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2011/01/31
     * @param integer $iIndex Numero d'index du fichier
     * @return string Nom du fichier
     *
     */
     public function getDocumentNom($iIndex)
     {
         $oNom = '';
         $field = 'doc'.$iIndex.'_fichier';
         if ($this->$field && preg_match('/^([0-9]+)_(.+)$/', $this->$field, $regs)) {
            $oNom = $regs[2];
         }
         return $oNom;
     }

}
