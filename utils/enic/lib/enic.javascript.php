<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class enicJavascript extends enicMod{
    
    public $js;
    
    public function startExec(){
        $this->js = '';
    }
    
    public function addFile($iPathToJs){
        CopixHtmlHeader::addJSLink(CopixUrl::get().$iPathToJs);
    }

    public function file($iPathToJs){
        $this->addFile($iPathToJs);
    }
    
    public function addJs($iJs){
        $this->js .= PHP_EOL.'/* ENIC AUTO JQUERY ITEMS FACTORY */'.PHP_EOL.PHP_EOL.$iJs.PHP_EOL.PHP_EOL;
    }

    public function date($iIdDom, $iType = 'simple'){
        $html =& enic::get('html');

        $js = '$.datepicker.setDefaults($.datepicker.regional[\'fr\']);';

        $js .= '$("'.$iIdDom.'").datepicker({';
        $js .= 'showOn: "button",
                buttonImage: "'.$html->addImg('colorful/24x24/calendar.png').'",
                buttonImageOnly: true';
        if($iType == 'full'){
            $js .= ',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true';
        }

        
        $js .= '});';

        $this->addJs($js);
    }

    /*
     * Add a simple wysiwyg editor in textarea
     * iIdDom => id of Dom item
     */
    public function wysiwyg($iIdDom, $iType = 'simple'){
        $this->addFile('js/ckeditor/ckeditor.js');
        $this->addFile('js/ckeditor/adapters/jquery.js');
        $css =& enic::get('css');

        $js = '$("'.$iIdDom.'").ckeditor()';
       
        $this->addJs($js);
    }

    public function display(){
    return  'jQuery.noConflict();
                jQuery(document).ready(function($){'
                        .$this->js.
            '});';
    }

    public function button($iSelector){
        $this->addJs('$("'.$iSelector.'").button()');
    }

    public function confirm($iSelector, $iMsg){
        $msg = html_entity_decode($iMsg, ENT_COMPAT, 'utf-8');
        $js = '$("'.$iSelector.'").click(function(){
                    return confirm("'.$msg.'")
                });';

        $this->addJs($js);
    }

}
?>
