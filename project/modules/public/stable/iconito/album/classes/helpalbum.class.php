<?php

/**
 * Aide du module Album
 *
 * @package Iconito
 * @subpackage	Minimail
 */
class HelpAlbum
{
    /**
     * Les pages de l'aide
     *
     * @author PNL <pnlapointe@cap-tic.fr>
     * @since 2006/07/25
     * @return array Les pages d'aide de la rubrique. Clé: nom de la page (genre de login). Valeur: tableau, indexé avec "name" (login), "title" (titre i18n), "links" (tableau permettant de proposer des liens de type "Voir aussi". Mettre le login de la page ou module|page si c'est une page d'un autre module
     */
    public function getPages ()
    {
        return array (
            'presentation' => array(
                'name'=>'presentation',
                'title'=>CopixI18N::get ('album|album.help.page.presentation'),
                'links'=>array('create'),
            ),
            'create' => array(
                'name'=>'create',
                'title'=>CopixI18N::get ('album|album.help.page.create'),
            ),
        );
    }
}

