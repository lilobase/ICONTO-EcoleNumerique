<?php

/**
 * Zone WikiButtons, qui affiche les boutons permettant de facilement mettre en forme selon la syntaxe wiki dans une zone de saisie libre.
 *
 * @package Iconito
 * @subpackage	Kernel
 */
class ZoneWikiButtons extends CopixZone
{
    /**
     * Affiche la série de boutons permettant à l'utilisateur de mettre en forme simplement le texte qu'il saisit dans une zone de texte libre.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/28
     * @param string $field Nom du champ dans le formulaire. Correspond à l'attribut "name" et "id" dans le template (les deux doivent être positionnés)
     * @param string $format Format de la zone de saisie (wiki, html, fckeditor...)
     * @param array $objet (option) Module dans lequel se trouve la barre wiki, dans un tableau indexé avec TYPE et ID (exemple: type=>MOD_BLOG, ID=>4). Si positionné, on va vérifier si le parent de cet objet a un album photos, une malle et un classeur, et si oui on affiche les liens vers l'album photos, la malle et le classeur en mode popup
     */
    public function _createContent(&$toReturn)
    {
        $tpl = new CopixTpl ();

        $field = $this->getParam('field', NULL);
        $format = $this->getParam('format', NULL);
        $object = $this->getParam('object', NULL);
        $withAlbum = $this->getParam('withAlbum', true);

        CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/wikibuttons.js');

        if ($field && $format) {

            $buttonAlbum = $buttonMalle = $buttonClasseur = array(); // Bouton album photos et malle
            $buttons = array(); // Tableau avec les boutons

            switch ($format) {
                case "wiki" :
                    CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/format_' . $format . '.js');
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.bold'), 'accesskey' => 'g', 'tabindex' => 10, 'link' => "javascript:bbstyle('','$field',0);"); // Gras
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.italic'), 'accesskey' => 'i', 'tabindex' => 11, 'link' => "javascript:bbstyle('','$field',2);"); // Italique
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.image'), 'accesskey' => 'm', 'tabindex' => 12, 'link' => "javascript:bblink('','$field',12);"); // Image
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.link'), 'accesskey' => 'l', 'tabindex' => 13, 'link' => "javascript:bblink('','$field',8);"); // Lien
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.hr'), 'accesskey' => 'h', 'tabindex' => 14, 'link' => "javascript:bbcar('','$field',4);"); // HR
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h1'), 'accesskey' => 'j', 'tabindex' => 15, 'link' => "javascript:bblink('','$field',20);"); // H1
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h2'), 'accesskey' => 'v', 'tabindex' => 16, 'link' => "javascript:bblink('','$field',22);"); // H2
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h3'), 'accesskey' => 'w', 'tabindex' => 17, 'link' => "javascript:bblink('','$field',24);"); // H3
                    break;

                case "dokuwiki" :
                    CopixHtmlHeader::addJSLink(CopixUrl::get() . 'js/iconito/format_' . $format . '.js');
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.bold'), 'accesskey' => 'g', 'tabindex' => 10, 'link' => "javascript:bbstyle('','$field',0);", 'icon' => 'bold.png'); // Gras
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.italic'), 'accesskey' => 'i', 'tabindex' => 11, 'link' => "javascript:bbstyle('','$field',2);", 'icon' => 'italic.png'); // Italique
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.underline'), 'accesskey' => 's', 'tabindex' => 12, 'link' => "javascript:bbstyle('','$field',4);", 'icon' => 'underline.png'); // Souligne
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.image'), 'accesskey' => 'm', 'tabindex' => 12, 'link' => "javascript:bblink('','$field',12);", 'icon' => 'image.png'); // Image
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.link'), 'accesskey' => 'l', 'tabindex' => 13, 'link' => "javascript:bblink('','$field',8);", 'icon' => 'linkextern.png'); // Lien
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.email'), 'accesskey' => 'e', 'tabindex' => 13, 'link' => "javascript:bblink('','$field',10);", 'icon' => '../../tpl/default/images/mail_icon.gif'); // Email
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.hr'), 'accesskey' => 'h', 'tabindex' => 14, 'link' => "javascript:bbcar('','$field',4);", 'icon' => 'hr.png'); // HR
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h1'), 'accesskey' => 'j', 'tabindex' => 15, 'link' => "javascript:bblink('','$field',20);", 'icon' => 'h1.png'); // H1
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h2'), 'accesskey' => 'v', 'tabindex' => 16, 'link' => "javascript:bblink('','$field',22);", 'icon' => 'h2.png'); // H2
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.h3'), 'accesskey' => 'w', 'tabindex' => 17, 'link' => "javascript:bblink('','$field',24);", 'icon' => 'h3.png'); // H3
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.ul'), 'accesskey' => 'w', 'tabindex' => 17, 'link' => "javascript:bbcar('','$field',5);", 'icon' => 'ul.png'); // UL
                    $buttons[] = array('titre' => CopixI18N::get('wikibuttons.ol'), 'accesskey' => 'w', 'tabindex' => 17, 'link' => "javascript:bbcar('','$field',6);", 'icon' => 'ol.png'); // OL
                    break;
            }

            if ($object) {
                $user_type=_currentUser()->getExtra('type');
                $user_id=_currentUser()->getExtra('id');

                if ('USER' === $object) {
                    Kernel::createMissingModules($user_type, $user_id);
                    $parent = array('type'=>$user_type, 'id'=>$user_id);
                } elseif(preg_match('/^MOD_/', $object['type'])) {
                    $parent = Kernel::getModParentInfo($object['type'], $object['id']);
                } else {
                    $parent = Kernel::getNodeInfo($object['type'], $object['id']);
                }

                if ($parent) {
                    $mods = Kernel::getModEnabled($parent['type'], $parent['id']);
                    $alb = Kernel::filterModuleList($mods, 'MOD_ALBUM');
                    if ($alb && $withAlbum) {
                        $url = CopixUrl::get('album||getpopup', array('album_id' => $alb[0]->module_id, 'field' => $field, 'format' => $format));
                        $buttonAlbum = array('titre' => CopixI18N::get('wikibuttons.album'), 'accesskey' => 'a', 'tabindex' => 18, 'link' => $url); // Album photos
                    }
                    $mal = Kernel::filterModuleList($mods, 'MOD_MALLE');
                    if ($mal) {
                        $url = CopixUrl::get('malle|malle|getMallePopup', array('id' => $mal[0]->module_id, 'field' => $field, 'format' => $format));
                        $buttonMalle = array('titre' => CopixI18N::get('wikibuttons.malleTxt'), 'accesskey' => 'd', 'tabindex' => 19, 'link' => $url); // Malle
                    }
                    $cla = Kernel::filterModuleList($mods, 'MOD_CLASSEUR');
                    if ($cla) {
                        $url = CopixUrl::get('classeur||getClasseurPopup', array('classeurId' => $cla[0]->module_id, 'field' => $field, 'format' => $format, 'withPersonal' => 1, 'moduleType' => $object['type'], 'moduleId' => $object['id']));
                        $buttonClasseur = array('titre' => CopixI18N::get('wikibuttons.classeurTxt'), 'accesskey' => 'd', 'tabindex' => 20, 'link' => $url); // Classeur
                    }
                }
            }

            $tpl->assign('format', $format);
            $tpl->assign('buttons', $buttons);
            $tpl->assign('buttonAlbum', $buttonAlbum);
            $tpl->assign('buttonMalle', $buttonMalle);
            $tpl->assign('buttonClasseur', $buttonClasseur);
            $tpl->assign('field', $field);
            $toReturn = $tpl->fetch('wikibuttons.tpl');
        }

        return true;
    }

}

