<?php

/**
 * Zone Edition, qui affiche une zone de saisie selon le format choisi (wiki, html, fckeditor...)
 *
 * @package Iconito
 * @subpackage	Kernel
 */
class ZoneEdition extends CopixZone
{
    /**
     * Affiche la série de boutons permettant à l'utilisateur de mettre en forme simplement le texte qu'il saisit dans une zone de texte libre.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/22
     * @param string $field Nom du champ dans le formulaire.
     * @param string $format Format à utiliser (html, wiki...)
     * @param string $content Contenu "brut" saisi
     * @param array $objet (option) Module dans lequel se trouve la zone d'édition, dans un tableau indexé avec TYPE et ID (exemple: type=>MOD_BLOG, id=>4). Si positionné, on va vérifier si le parent de cet objet a un album photos, et si oui on affiche le lien vers l'album photos en mode popup
     * @param integer $width (option) Largeur de la zone de saisie
     * @param integer $height (option) Hauteur de la zone de saisie
     * @param array $options (option) Options permettant de personnaliser la zone
     */
    public function _createContent(&$toReturn)
    {
        $tpl = new CopixTpl ();

        $field = $this->getParam('field', NULL);
        $format = $this->getParam('format', NULL);
        $content = $this->getParam('content', NULL);
        $object = $this->getParam('object', array());
        $width = $this->getParam('width', 650);
        $height = $this->getParam('height', 300);
        $class = $this->getParam('class');
        $options = $this->getParam('options', array());

        $html = '';

        if ($field && $format) {

            if ('USER' === $object) {
            } else {
                $object_type = (isset($object['type'])) ? $object['type'] : null;
                $object_id = (isset($object['id'])) ? $object['id'] : null;
                $object = ($object_type && $object_id) ? array('type' => $object_type, 'id' => $object_id) : null;
            }

            switch ($format) {
                case "wiki" :
                    $tpl->assign('wikibuttons', CopixZone::process('kernel|wikibuttons', array('field' => $field, 'format' => $format, 'object' => $object)));
                    break;
                case "html" :
                    $tpl->assign('wikibuttons', CopixZone::process('kernel|wikibuttons', array('field' => $field, 'format' => $format, 'object' => $object)));
                    break;
                case "htmlnl2br" :
                    $tpl->assign('wikibuttons', CopixZone::process('kernel|wikibuttons', array('field' => $field, 'format' => $format, 'object' => $object)));
                    break;
                case "dokuwiki" :
                    $tpl->assign('wikibuttons', CopixZone::process('kernel|wikibuttons', array('field' => $field, 'format' => $format, 'object' => $object)));
                    break;
                case "fckeditor" :
                case "ckeditor" :
                    CopixHTMLHeader::addJSLink(_resource('js/ckeditor/ckeditor.js'));
                    CopixHTMLHeader::addJSLink(_resource('js/ckeditor/config.js'));
                    $tpl->assign('wikibuttons', CopixZone::process('kernel|wikibuttons', array('field' => $field, 'format' => 'ckeditor', 'object' => $object)));
                    break;
            }

            $tpl->assign('field', $field);
            $tpl->assign('format', $format);
            $tpl->assign('content', $content);
            $tpl->assign('width', $width);
            $tpl->assign('height', $height);
            $tpl->assign('class', $class);
            $tpl->assign('options', $options);
        }

        //_dump($options);

        $toReturn = $tpl->fetch('zone.edition.tpl');
        return true;
    }

}

