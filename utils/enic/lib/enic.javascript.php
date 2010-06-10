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
        $this->js .= '/* ENIC JS */'.PHP_EOL.PHP_EOL.$iJs.PHP_EOL.PHP_EOL;
    }

    public function date($iIdDom, $iType = 'simple'){
        $js = '$.datepicker.setDefaults($.datepicker.regional[\'fr\']);';

        $js .= '$("'.$iIdDom.'").datepicker({';

        if($iType == 'full'){
            $js .= '
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
        $this->addFile('js/jwysiwyg/jquery.wysiwyg.js');
        $css =& enic::get('css');
        $css->addFile('styles/jquery.wysiwyg.css');

        $js = '$("'.$iIdDom.'").wysiwyg({';
        if($iType == 'full'){
            $js .= 'controls: {
              strikeThrough : { visible : true },
              underline     : { visible : true },

              separator00 : { visible : true },

              justifyLeft   : { visible : true },
              justifyCenter : { visible : true },
              justifyRight  : { visible : true },
              justifyFull   : { visible : true },

              separator01 : { visible : true },

              indent  : { visible : true },
              outdent : { visible : true },

              separator02 : { visible : true },

              subscript   : { visible : true },
              superscript : { visible : true },

              separator03 : { visible : true },

              undo : { visible : true },
              redo : { visible : true },

              separator04 : { visible : true },

              insertOrderedList    : { visible : true },
              insertUnorderedList  : { visible : true },
              insertHorizontalRule : { visible : true },

              h4mozilla : { visible : true, className : \'h4\', command : \'heading\', arguments : [\'h4\'], tags : [\'h4\'], tooltip : "Header 4" },
              h5mozilla : { visible : true, className : \'h5\', command : \'heading\', arguments : [\'h5\'], tags : [\'h5\'], tooltip : "Header 5" },
              h6mozilla : { visible : true, className : \'h6\', command : \'heading\', arguments : [\'h6\'], tags : [\'h6\'], tooltip : "Header 6" },

              separator07 : { visible : true },

              cut   : { visible : true },
              copy  : { visible : true },
              paste : { visible : true }

              }';
        }else{
             $js .= 'controls: {
              strikeThrough : { visible : false },
              underline     : { visible : false },

              separator00 : { visible : true },

              justifyLeft   : { visible : true },
              justifyCenter : { visible : true },
              justifyRight  : { visible : true },
              justifyFull   : { visible : false },

              separator01 : { visible : false },

              indent  : { visible : false },
              outdent : { visible : false },

              separator02 : { visible : true },

              subscript   : { visible : true },
              superscript : { visible : true },

              separator03 : { visible : false },

              undo : { visible : false },
              redo : { visible : false },
              insertImage : { visible : false },
              html : {visible : true },
              separator04 : { visible : true },
              
              separator06 : { visible : false },
              insertOrderedList    : { visible : true },
              insertUnorderedList  : { visible : false },
              insertHorizontalRule : { visible :false },

              h1 : { visible : false },
              h2 : { visible : false },
              h3 : { visible : false },
              h1mozilla : { visible : false },
              h2mozilla : { visible : false },
              h3mozilla : { visible : false },
              h4 : { visible : true, className : \'h4\', command : \'heading\', arguments : [\'h4\'], tags : [\'h4\'], tooltip : "Header 4" },
              h5 : { visible : true, className : \'h5\', command : \'heading\', arguments : [\'h5\'], tags : [\'h5\'], tooltip : "Header 5" },
              h6 : { visible : true, className : \'h6\', command : \'heading\', arguments : [\'h6\'], tags : [\'h6\'], tooltip : "Header 6" },
              separator07 : { visible : false },
              
              removeFormat : { visible : true },
              cut   : { visible : false },
              copy  : { visible : false },
              paste : { visible : false }

              }';
        }
        $js .= '});';
        $this->addJs($js);
    }

    public function display(){
    return  'jQuery.noConflict();
                jQuery(document).ready(function($){'
                        .$this->js.
            '});';
    }
}
?>
