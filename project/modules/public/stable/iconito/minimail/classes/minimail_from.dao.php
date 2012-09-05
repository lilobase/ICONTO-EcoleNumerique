<?php

_classInclude('minimail|MinimailService');

class DAOMinimail_from
{
}

class DAORecordMinimail_from
{
    /**
     * __toString
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Retourne le nombre de pièces jointes d'un message
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @return integer Nombre de PJ
     */
    public function getNbAttachments()
    {
        $return = 0;
        $return += ($this->attachment1) ? 1 : 0;
        $return += ($this->attachment2) ? 1 : 0;
        $return += ($this->attachment3) ? 1 : 0;
        return $return;
    }

    /**
     * Si une pièce jointe est une image
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @param integer $key Clé de la PJ
     * @return boolean True si c'est une image
     */
    public function isAttachmentImage($key)
    {
        $attachement = 'attachment'.$key;
        return ($this->$attachement && MinimailService::isAttachmentImage($this->$attachement)) ? true : false;
    }

    /**
     * Nom d'une pièce jointe en clair (sans l'id du début du nom du fichier)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/22
     * @param integer $key Clé de la PJ
     * @return string nom
     */
    public function getAttachmentFilename($key)
    {
        $attachement = 'attachment'.$key;
        return ($this->$attachement) ? MinimailService::getAttachmentName($this->$attachement) : '';
    }





}

