<?php
/**
 * Renderer output base class
 *
 * @author Harry Fuecks <hfuecks@gmail.com>
 * @author Andreas Gohr <andi@splitbrain.org>
 */
if(!defined('DOKU_INC')) define('DOKU_INC',fullpath(dirname(__FILE__).'/../../').'/');

require_once DOKU_INC . 'inc/parser/renderer.php';
require_once DOKU_INC . 'inc/plugin.php';
require_once DOKU_INC . 'inc/pluginutils.php';

/**
 * An empty renderer, produces no output
 *
 * Inherits from DokuWiki_Plugin for giving additional functions to render plugins
 */
class Doku_Renderer extends DokuWiki_Plugin
{
    public $info = array(
        'cache' => true, // may the rendered result cached?
        'toc'   => true, // render the TOC?
    );

    // keep some config options
    public $acronyms = array();
    public $smileys = array();
    public $badwords = array();
    public $entities = array();
    public $interwiki = array();

    // allows renderer to be used again, clean out any per-use values
    public function reset()
    {
    }

    public function nocache()
    {
        $this->info['cache'] = false;
    }

    public function notoc()
    {
        $this->info['toc'] = false;
    }

    /**
     * Returns the format produced by this renderer.
     *
     * Has to be overidden by decendend classes
     */
    public function getFormat()
    {
        trigger_error('getFormat() not implemented in '.get_class($this), E_USER_WARNING);
    }


    //handle plugin rendering
    public function plugin($name,$data)
    {
        $plugin =& plugin_load('syntax',$name);
        if($plugin != null){
            $plugin->render($this->getFormat(),$this,$data);
        }
    }

    /**
     * handle nested render instructions
     * this method (and nest_close method) should not be overloaded in actual renderer output classes
     */
    public function nest($instructions)
    {
      foreach ( $instructions as $instruction ) {
        // execute the callback against ourself
        call_user_func_array(array(&$this, $instruction[0]),$instruction[1]);
      }
    }

    // dummy closing instruction issued by Doku_Handler_Nest, normally the syntax mode should
    // override this instruction when instantiating Doku_Handler_Nest - however plugins will not
    // be able to - as their instructions require data.
    public function nest_close() {}

    public function document_start() {}

    public function document_end() {}

    public function render_TOC() { return ''; }

    public function toc_additem($id, $text, $level) {}

    public function header($text, $level, $pos) {}

    public function section_edit($start, $end, $level, $name) {}

    public function section_open($level) {}

    public function section_close() {}

    public function cdata($text) {}

    public function p_open() {}

    public function p_close() {}

    public function linebreak() {}

    public function hr() {}

    public function strong_open() {}

    public function strong_close() {}

    public function emphasis_open() {}

    public function emphasis_close() {}

    public function underline_open() {}

    public function underline_close() {}

    public function monospace_open() {}

    public function monospace_close() {}

    public function subscript_open() {}

    public function subscript_close() {}

    public function superscript_open() {}

    public function superscript_close() {}

    public function deleted_open() {}

    public function deleted_close() {}

    public function footnote_open() {}

    public function footnote_close() {}

    public function listu_open() {}

    public function listu_close() {}

    public function listo_open() {}

    public function listo_close() {}

    public function listitem_open($level) {}

    public function listitem_close() {}

    public function listcontent_open() {}

    public function listcontent_close() {}

    public function unformatted($text) {}

    public function php($text) {}

    public function phpblock($text) {}

    public function html($text) {}

    public function htmlblock($text) {}

    public function preformatted($text) {}

    public function file($text) {}

    public function quote_open() {}

    public function quote_close() {}

    public function code($text, $lang = NULL) {}

    public function acronym($acronym) {}

    public function smiley($smiley) {}

    public function wordblock($word) {}

    public function entity($entity) {}

    // 640x480 ($x=640, $y=480)
    public function multiplyentity($x, $y) {}

    public function singlequoteopening() {}

    public function singlequoteclosing() {}

    public function apostrophe() {}

    public function doublequoteopening() {}

    public function doublequoteclosing() {}

    // $link like 'SomePage'
    public function camelcaselink($link) {}

    public function locallink($hash, $name = NULL) {}

    // $link like 'wiki:syntax', $title could be an array (media)
    public function internallink($link, $title = NULL) {}

    // $link is full URL with scheme, $title could be an array (media)
    public function externallink($link, $title = NULL) {}

    // $link is the original link - probably not much use
    // $wikiName is an indentifier for the wiki
    // $wikiUri is the URL fragment to append to some known URL
    public function interwikilink($link, $title = NULL, $wikiName, $wikiUri) {}

    // Link to file on users OS, $title could be an array (media)
    public function filelink($link, $title = NULL) {}

    // Link to a Windows share, , $title could be an array (media)
    public function windowssharelink($link, $title = NULL) {}

//  function email($address, $title = NULL) {}
    public function emaillink($address, $name = NULL) {}

    public function internalmedia ($src, $title=NULL, $align=NULL, $width=NULL,
                            $height=NULL, $cache=NULL, $linking=NULL) {}

    public function externalmedia ($src, $title=NULL, $align=NULL, $width=NULL,
                            $height=NULL, $cache=NULL, $linking=NULL) {}

    public function internalmedialink (
        $src,$title=NULL,$align=NULL,$width=NULL,$height=NULL,$cache=NULL
        ) {}

    public function externalmedialink(
        $src,$title=NULL,$align=NULL,$width=NULL,$height=NULL,$cache=NULL
        ) {}

    public function table_open($maxcols = NULL, $numrows = NULL){}

    public function table_close(){}

    public function tablerow_open(){}

    public function tablerow_close(){}

    public function tableheader_open($colspan = 1, $align = NULL){}

    public function tableheader_close(){}

    public function tablecell_open($colspan = 1, $align = NULL){}

    public function tablecell_close(){}


    // util functions follow, you probably won't need to reimplement them


    /**
     * Removes any Namespace from the given name but keeps
     * casing and special chars
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public function _simpleTitle($name)
    {
        global $conf;

        //if there is a hash we use the ancor name only
        list($name,$hash) = explode('#',$name,2);
        if($hash) return $hash;

        //trim colons or slash of a namespace link
        $name = rtrim($name,':');
        if($conf['useslash'])
          $name = rtrim($name,'/');

        if($conf['useslash']){
            $nssep = '[:;/]';
        }else{
            $nssep = '[:;]';
        }
        $name = preg_replace('!.*'.$nssep.'!','',$name);

        if(!$name) return $this->_simpleTitle($conf['start']);
        return $name;
    }

    /**
     * Resolve an interwikilink
     */
    public function _resolveInterWiki(&$shortcut,$reference)
    {
        //get interwiki URL
        if ( isset($this->interwiki[$shortcut]) ) {
            $url = $this->interwiki[$shortcut];
        } else {
            // Default to Google I'm feeling lucky
            $url = 'http://www.google.com/search?q={URL}&amp;btnI=lucky';
            $shortcut = 'go';
        }

        //split into hash and url part
        list($wikiUri,$hash) = explode('#',$wikiUri,2);

        //replace placeholder
        if(preg_match('#\{(URL|NAME|SCHEME|HOST|PORT|PATH|QUERY)\}#',$url)){
            //use placeholders
            $url = str_replace('{URL}',rawurlencode($reference),$url);
            $url = str_replace('{NAME}',$reference,$url);
            $parsed = parse_url($reference);
            if(!$parsed['port']) $parsed['port'] = 80;
            $url = str_replace('{SCHEME}',$parsed['scheme'],$url);
            $url = str_replace('{HOST}',$parsed['host'],$url);
            $url = str_replace('{PORT}',$parsed['port'],$url);
            $url = str_replace('{PATH}',$parsed['path'],$url);
            $url = str_replace('{QUERY}',$parsed['query'],$url);
        }else{
            //default
            $url = $url.rawurlencode($reference);
        }
        if($hash) $url .= '#'.rawurlencode($hash);

        return $url;
    }
}


//Setup VIM: ex: et ts=4 enc=utf-8 :
