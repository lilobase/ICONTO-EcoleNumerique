<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class enicJavascript extends enicMod
{
    public $js;

    public function startExec()
    {
        $this->helpers      =& enic::get('helpers');
        $this->js = '';
    }

    public function addFile($iPathToJs)
    {
        CopixHtmlHeader::addJSLink(CopixUrl::get().$iPathToJs);
    }

    public function addFileByTheme($iPathToJs)
    {
        CopixHTMLHeader::addJSLink (_resource($iPathToJs));
    }

    public function file($iPathToJs)
    {
        $this->addFile($iPathToJs);
    }

    public function addJs($iJs)
    {
        $this->js .= PHP_EOL.'/* ENIC AUTO JQUERY ITEMS FACTORY */'.PHP_EOL.PHP_EOL.$iJs.PHP_EOL.PHP_EOL;
    }

    public function date($iIdDom, $iType = 'simple')
    {
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
     * iType => nom d'une toolbar CKEditor, défini dans /www/js/ckeditor/config.js
     */
    public function wysiwyg($iIdDom, $iType = 'simple')
    {
        $this->addFile('js/ckeditor/ckeditor.js');
        $this->addFile('js/ckeditor/adapters/jquery.js');
        $this->addFileByTheme('js/ckeditor.js');
        $css =& enic::get('css');

        switch( $iType ) {
            case 'full':
                $toolbarName = 'full';
                break;
            case 'simple':
            default:
                $toolbarName = 'IconitoBasic';
        }

        $js = '$("'.$iIdDom.'").ckeditor({toolbar: "' . $toolbarName . '"})';

        $this->addJs($js);
    }

    public function display()
    {
    return  'jQuery(document).ready(function($){'
                        .$this->js.
            '});';
    }

    public function button($iSelector)
    {
        $this->addJs('$("'.$iSelector.'").button()');
    }

    public function confirm($iSelector, $iMsg)
    {
        $iMsg = $this->helpers->i18n($iMsg);
        $msg = html_entity_decode($iMsg, ENT_COMPAT, 'utf-8');
        $js = '$("'.$iSelector.'").click(function(){
                    return confirm("'.$msg.'")
                });';

        $this->addJs($js);
    }

    public function inputPreFilled($iSelector, $iMsg)
    {
        $iMsg = $this->helpers->i18n($iMsg);
        $msg = html_entity_decode($iMsg, ENT_COMPAT, 'utf-8');

        $js = 'if($("'.$iSelector.'").val() == "") {
                $("'.$iSelector.'").val("'.$iMsg.'");
                $("'.$iSelector.'").addClass("preFilled");
                $("'.$iSelector.'").focus(function(){ $(this).val(""); $(this).removeClass("preFilled")});
                $("'.$iSelector.'").parents("form").submit(function(){
                    if($("'.$iSelector.'").val() == "'.$iMsg.'"){
                        $("'.$iSelector.'").val("");
                    }
                });
            }';

        $this->addJs($js);
    }

    public function dialog($iSelectorClick, $iSelectorData)
    {
        $js = '$("'.$iSelectorData.'").dialog({autoOpen: false});';
        $js .= '$("'.$iSelectorClick.'").click(function(){ $("'.$iSelectorData.'").dialog("open"); });';
        $this->addJs($js);

    }

}
