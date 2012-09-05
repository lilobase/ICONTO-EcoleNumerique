<?php
/**
 * Renderer for metadata
 *
 * @author Esther Brunner <wikidesign@gmail.com>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',fullpath(dirname(__FILE__).'/../../').'/');

if ( !defined('DOKU_LF') ) {
    // Some whitespace to help View > Source
    define ('DOKU_LF',"\n");
}

if ( !defined('DOKU_TAB') ) {
    // Some whitespace to help View > Source
    define ('DOKU_TAB',"\t");
}

require_once DOKU_INC . 'inc/parser/renderer.php';

/**
 * The Renderer
 */
class Doku_Renderer_metadata extends Doku_Renderer
{
  public $doc  = '';
  public $meta = array();
  public $persistent = array();

  public $headers = array();
  public $capture = true;
  public $store   = '';

  public function getFormat()
  {
    return 'metadata';
  }

  public function document_start()
  {
    // reset metadata to persistent values
    $this->meta = $this->persistent;
  }

  public function document_end()
  {
    // store internal info in metadata (notoc,nocache)
    $this->meta['internal'] = $this->info;

    if (!$this->meta['description']['abstract']){
      // cut off too long abstracts
      $this->doc = trim($this->doc);
      if (strlen($this->doc) > 500)
        $this->doc = utf8_substr($this->doc, 0, 500).'…';
      $this->meta['description']['abstract'] = $this->doc;
    }
  }

  public function toc_additem($id, $text, $level)
  {
    global $conf;

    //only add items within configured levels
    if($level >= $conf['toptoclevel'] && $level <= $conf['maxtoclevel']){
      // the TOC is one of our standard ul list arrays ;-)
      $this->meta['description']['tableofcontents'][] = array(
        'hid'   => $id,
        'title' => $text,
        'type'  => 'ul',
        'level' => $level-$conf['toptoclevel']+1
      );
    }

  }

  public function header($text, $level, $pos)
  {
    if (!$this->meta['title']) $this->meta['title'] = $text;

    // add the header to the TOC
    $hid = $this->_headerToLink($text,'true');
    $this->toc_additem($hid, $text, $level);

    // add to summary
    if ($this->capture && ($level > 1)) $this->doc .= DOKU_LF.$text.DOKU_LF;
  }

  public function section_open($level){}
  public function section_close(){}

  public function cdata($text)
  {
    if ($this->capture) $this->doc .= $text;
  }

  public function p_open()
  {
    if ($this->capture) $this->doc .= DOKU_LF;
  }

  public function p_close()
  {
    if ($this->capture){
      if (strlen($this->doc) > 250) $this->capture = false;
      else $this->doc .= DOKU_LF;
    }
  }

  public function linebreak()
  {
    if ($this->capture) $this->doc .= DOKU_LF;
  }

  public function hr()
  {
    if ($this->capture){
      if (strlen($this->doc) > 250) $this->capture = false;
      else $this->doc .= DOKU_LF.'----------'.DOKU_LF;
    }
  }

  public function strong_open(){}
  public function strong_close(){}

  public function emphasis_open(){}
  public function emphasis_close(){}

  public function underline_open(){}
  public function underline_close(){}

  public function monospace_open(){}
  public function monospace_close(){}

  public function subscript_open(){}
  public function subscript_close(){}

  public function superscript_open(){}
  public function superscript_close(){}

  public function deleted_open(){}
  public function deleted_close(){}

  /**
   * Callback for footnote start syntax
   *
   * All following content will go to the footnote instead of
   * the document. To achieve this the previous rendered content
   * is moved to $store and $doc is cleared
   *
   * @author Andreas Gohr <andi@splitbrain.org>
   */
  public function footnote_open()
  {
    if ($this->capture){
      // move current content to store and record footnote
      $this->store = $this->doc;
      $this->doc   = '';
    }
  }

  /**
   * Callback for footnote end syntax
   *
   * All rendered content is moved to the $footnotes array and the old
   * content is restored from $store again
   *
   * @author Andreas Gohr
   */
  public function footnote_close()
  {
    if ($this->capture){
      // restore old content
      $this->doc = $this->store;
      $this->store = '';
    }
  }

  public function listu_open()
  {
    if ($this->capture) $this->doc .= DOKU_LF;
  }

  public function listu_close()
  {
    if ($this->capture && (strlen($this->doc) > 250)) $this->capture = false;
  }

  public function listo_open()
  {
    if ($this->capture) $this->doc .= DOKU_LF;
  }

  public function listo_close()
  {
    if ($this->capture && (strlen($this->doc) > 250)) $this->capture = false;
  }

  public function listitem_open($level)
  {
    if ($this->capture) $this->doc .= str_repeat(DOKU_TAB, $level).'* ';
  }

  public function listitem_close()
  {
    if ($this->capture) $this->doc .= DOKU_LF;
  }

  public function listcontent_open(){}
  public function listcontent_close(){}

  public function unformatted($text)
  {
    if ($this->capture) $this->doc .= $text;
  }

  public function php($text){}

  public function phpblock($text){}

  public function html($text){}

  public function htmlblock($text){}

  public function preformatted($text)
  {
    if ($this->capture) $this->doc .= $text;
  }

  public function file($text)
  {
    if ($this->capture){
      $this->doc .= DOKU_LF.$text;
      if (strlen($this->doc) > 250) $this->capture = false;
      else $this->doc .= DOKU_LF;
    }
  }

  public function quote_open()
  {
    if ($this->capture) $this->doc .= DOKU_LF.DOKU_TAB.'"';
  }

  public function quote_close()
  {
    if ($this->capture){
      $this->doc .= '"';
      if (strlen($this->doc) > 250) $this->capture = false;
      else $this->doc .= DOKU_LF;
    }
  }

  public function code($text, $language = NULL)
  {
    if ($this->capture){
      $this->doc .= DOKU_LF.$text;
      if (strlen($this->doc) > 250) $this->capture = false;
      else $this->doc .= DOKU_LF;
    }
  }

  public function acronym($acronym)
  {
    if ($this->capture) $this->doc .= $acronym;
  }

  public function smiley($smiley)
  {
    if ($this->capture) $this->doc .= $smiley;
  }

  public function entity($entity)
  {
    if ($this->capture) $this->doc .= $entity;
  }

  public function multiplyentity($x, $y)
  {
    if ($this->capture) $this->doc .= $x.'×'.$y;
  }

  public function singlequoteopening()
  {
    global $lang;
    if ($this->capture) $this->doc .= $lang['singlequoteopening'];
  }

  public function singlequoteclosing()
  {
    global $lang;
    if ($this->capture) $this->doc .= $lang['singlequoteclosing'];
  }

  public function apostrophe()
  {
    global $lang;
    if ($this->capture) $this->doc .= $lang['apostrophe'];
  }

  public function doublequoteopening()
  {
    global $lang;
    if ($this->capture) $this->doc .= $lang['doublequoteopening'];
  }

  public function doublequoteclosing()
  {
    global $lang;
    if ($this->capture) $this->doc .= $lang['doublequoteclosing'];
  }

  public function camelcaselink($link)
  {
    $this->internallink($link, $link);
  }

  public function locallink($hash, $name = NULL){}

  /**
   * keep track of internal links in $this->meta['relation']['references']
   */
  public function internallink($id, $name = NULL)
  {
    global $ID;

    $default = $this->_simpleTitle($id);

    // first resolve and clean up the $id
    resolve_pageid(getNS($ID), $id, $exists);
    list($page, $hash) = split('#', $id, 2);

    // set metadata
    $this->meta['relation']['references'][$page] = $exists;
    // $data = array('relation' => array('isreferencedby' => array($ID => true)));
    // p_set_metadata($id, $data);

    // add link title to summary
    if ($this->capture){
      $name = $this->_getLinkTitle($name, $default, $id);
      $this->doc .= $name;
    }
  }

  public function externallink($url, $name = NULL)
  {
    if ($this->capture){
      if ($name) $this->doc .= $name;
      else $this->doc .= '<'.$url.'>';
    }
  }

  public function interwikilink($match, $name = NULL, $wikiName, $wikiUri)
  {
    if ($this->capture){
      list($wikiUri, $hash) = explode('#', $wikiUri, 2);
      $name = $this->_getLinkTitle($name, $wikiName.'>'.$wikiUri);
      $this->doc .= $name;
    }
  }

  public function windowssharelink($url, $name = NULL)
  {
    if ($this->capture){
      if ($name) $this->doc .= $name;
      else $this->doc .= '<'.$url.'>';
    }
  }

  public function emaillink($address, $name = NULL)
  {
    if ($this->capture){
      if ($name) $this->doc .= $name;
      else $this->doc .= '<'.$address.'>';
    }
  }

  public function internalmedia($src, $title=NULL, $align=NULL, $width=NULL,
                         $height=NULL, $cache=NULL, $linking=NULL){
    if ($this->capture && $title) $this->doc .= '['.$title.']';
  }

  public function externalmedia($src, $title=NULL, $align=NULL, $width=NULL,
                         $height=NULL, $cache=NULL, $linking=NULL){
    if ($this->capture && $title) $this->doc .= '['.$title.']';
  }

  public function rss($url,$params)
  {
    $this->meta['relation']['haspart'][$url] = true;

    $this->meta['date']['valid']['age'] =
            isset($this->meta['date']['valid']['age']) ?
                min($this->meta['date']['valid']['age'],$params['refresh']) :
                $params['refresh'];
  }

  public function table_open($maxcols = NULL, $numrows = NULL){}
  public function table_close(){}

  public function tablerow_open(){}
  public function tablerow_close(){}

  public function tableheader_open($colspan = 1, $align = NULL){}
  public function tableheader_close(){}

  public function tablecell_open($colspan = 1, $align = NULL){}
  public function tablecell_close(){}

  //----------------------------------------------------------
  // Utils

  /**
   * Removes any Namespace from the given name but keeps
   * casing and special chars
   *
   * @author Andreas Gohr <andi@splitbrain.org>
   */
  public function _simpleTitle($name)
  {
    global $conf;

    if(is_array($name)) return '';

    if($conf['useslash']){
        $nssep = '[:;/]';
    }else{
        $nssep = '[:;]';
    }
    $name = preg_replace('!.*'.$nssep.'!','',$name);
    //if there is a hash we use the anchor name only
    $name = preg_replace('!.*#!','',$name);
    return $name;
  }

  /**
   * Creates a linkid from a headline
   *
   * @param string  $title   The headline title
   * @param boolean $create  Create a new unique ID?
   * @author Andreas Gohr <andi@splitbrain.org>
   */
  public function _headerToLink($title, $create=false)
  {
    $title = str_replace(':','',cleanID($title));
    $title = ltrim($title,'0123456789._-');
    if(empty($title)) $title='section';

    if($create){
      // make sure tiles are unique
      $num = '';
      while(in_array($title.$num,$this->headers)){
        ($num) ? $num++ : $num = 1;
      }
      $title = $title.$num;
      $this->headers[] = $title;
    }

    return $title;
  }

  /**
   * Construct a title and handle images in titles
   *
   * @author Harry Fuecks <hfuecks@gmail.com>
   */
  public function _getLinkTitle($title, $default, $id=NULL)
  {
    global $conf;

    $isImage = false;
    if (is_null($title)){
      if ($conf['useheading'] && $id){
        $heading = p_get_first_heading($id,false);
        if ($heading) return $heading;
      }
      return $default;
    } elseif (is_string($title)){
      return $title;
    } elseif (is_array($title)){
      return '['.$title.']';
    }
  }

}

//Setup VIM: ex: et ts=4 enc=utf-8 :
