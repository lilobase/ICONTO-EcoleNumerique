<?php
/*
    EN2010
    -------
    @file 		menu.zone.php
    @version 	1.0.0b
    @date 		2010-09-01 09:09:02 +0200 (Wed, 1 Sep 2010)
    @author 	S.HOLTZ <sholtz@cap-tic.fr>

    Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/

/**
 * Zone affichant le menu
 */
class ZoneMenu extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $ppo = new CopixPPO ();
        $pMenu = $this->getParam ('MENU');
        $pPopup = $this->getParam ('popup');
        $pCanClose = $this->getParam ('canClose', true); // Seulement si popup, true par defaut

        if ($pCanClose===null) $pCanClose = true;
        // Si le menu est defini a partir d'un tableau, creation du HTML pour affichage.
        if( is_array($pMenu) ) {
            $html = '';
            $sep = '';
            $html .= '<ul>';
            foreach( $pMenu as $key=>$val ) {
                $url = (isset($val['url']) && trim($val['url'])!="") ? $val['url'] : '';

                $class = 'class="';
                $class .= (isset($val['behavior'])) ? $val['behavior'].' ':'';
                $class .= (isset($val['type'])) ? $val['type'].' ':'';
                $class .= (isset($val['current']) && $val['current']) ? 'current ':'';
                $class .= '"';

                $style = 'style="';
                $style .= (isset($val['size'])) ? 'width: '.$val['size'].'px;':'';
                $style .= '"';

                $html .= '<li>';
                $html .= '<a '.$class.' '.$style.' href="'.$url.'"';
                if( isset($val['target']) ) $html .= ' target="'.$val['target'].'"';
                $html .= '>';
                $html .= '<span class="valign"></span>';
                $html .= '<span>'.$val['txt'].'</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $ppo->menu = $html;
        } else {
            $ppo->menu = '<ul><li>'.$pMenu.'</li></ul>';
        }

        if ($pPopup && $pCanClose) {
            $ppo->menu .= ($ppo->menu) ? ' :: ' : '';
            $ppo->menu .= '<a href="javascript:self.close();">'.CopixI18N::get('kernel|kernel.popup.close').'</a>';
        }

        if (!isset($ppo->menu)) $ppo->menu = 'submenu is empty';

        $toReturn = $this->_usePPO ($ppo, 'menu.tpl');

        return true;
    }
}