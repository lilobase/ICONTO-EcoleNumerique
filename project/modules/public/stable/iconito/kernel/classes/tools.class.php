<?php
/**
* @package    
* @subpackage 
* @author     Sébastien CAS
*/

class Tools {
  
  /**
   * Supprime tous les caractères non conformes
   *
   * @param    string   $text
   * @param    string   $encoding
   * @return   string   stripped text
   */
  public static function stripText ($text, $encoding = 'UTF-8') {
    
    $text = mb_strtolower ($text, $encoding);

    $patterns = array ('/à/', '/á/', '/â/', '/ã/', '/ä/', '/å/', '/ò/', '/ó/',
      '/ô/', '/õ/', '/ö/', '/ø/', '/è/', '/é/', '/ê/', '/ë/',
      '/ç/', '/ì/', '/í/', '/î/', '/ï/', '/ù/', '/ú/', '/û/',
      '/ü/', '/ÿ/', '/ñ/');

    $replace = array ('a', 'a', 'a', 'a', 'a', 'a', 'o', 'o',
      'o', 'o', 'o', 'o', 'e', 'e', 'e', 'e',
      'c', 'i', 'i', 'i', 'i', 'u', 'u', 'u',
      'u', 'y', 'n');
    
    $text = preg_replace ($patterns, $replace, $text);
    
    // strip all non word chars
    $text = preg_replace ('/[^a-z0-9]/', ' ', $text);
    
    // replace all white space sections with a dash
    $text = preg_replace ('/\ +/', '-', $text);
 
    // trim dashes
    $text = preg_replace ('/\-$/', '', $text);
    $text = preg_replace ('/^\-/', '', $text);
 
    return $text;
  }
  
  /**
   * Retourne la chaîne en majuscule sans accent
   *
   * @param    string   $encoding
   * @return   string   capitalized text
   */
  public static function capitalizeAll ($text) {
    
    $patterns = array (
      '/à/', '/á/', '/â/', '/ã/', '/ä/', '/å/', '/ò/', '/ó/',
      '/ô/', '/õ/', '/ö/', '/ø/', '/è/', '/é/', '/ê/', '/ë/',
      '/ç/', '/ì/', '/í/', '/î/', '/ï/', '/ù/', '/ú/', '/û/',
      '/ü/', '/ÿ/', '/ñ/'
    );
    $replace = array (
      '\1a', '\1a', '\1a', '\1a', '\1a', '\1a', '\1o', '\1o',
      '\1o', '\1o', '\1o', '\1o', '\1e', '\1e', '\1e', '\1e',
      '\1c', '\1i', '\1i', '\1i', '\1i', '\1u', '\1u', '\1u',
      '\1u', '\1y', '\1n'
    );
    
    $text = strtolower ($text);
    $text = strtr ($text, 'ÄÂÀÁÅÃÒÓÔÕÖØÉÈËÊÇÌÍÎÏÙÚÛÜÝÑ', 'äâàáåãòóôõöøéèëêçìíîïùúûüýñ');
    
    $text = preg_replace ($patterns, $replace, $text);
    
    $text = strtoupper ($text);

    return $text;
  }

  /**
   * Retourne la chaîne avec les majuscule placées au bon endroit
   *
   * @param    string   $encoding
   * @return   string   capitalized text
   */
  public static function capitalize ($text) {
    
    $patterns = array (
      '/(\s|-|^|\')à/', '/(\s|-|^|\')á/', '/(\s|-|^|\')â/', '/(\s|-|^|\')ã/', '/(\s|-|^|\')ä/', '/(\s|-|^|\')å/', '/(\s|-|^|\')ò/', '/(\s|-|^|\')ó/',
      '/(\s|-|^|\')ô/', '/(\s|-|^|\')õ/', '/(\s|-|^|\')ö/', '/(\s|-|^|\')ø/', '/(\s|-|^|\')è/', '/(\s|-|^|\')é/', '/(\s|-|^|\')ê/', '/(\s|-|^|\')ë/',
      '/(\s|-|^|\')ç/', '/(\s|-|^|\')ì/', '/(\s|-|^|\')í/', '/(\s|-|^|\')î/', '/(\s|-|^|\')ï/', '/(\s|-|^|\')ù/', '/(\s|-|^|\')ú/', '/(\s|-|^|\')û/',
      '/(\s|-|^|\')ü/', '/(\s|-|^|\')ÿ/', '/(\s|-|^|\')ñ/'
    );
    $replace = array (
      '\1a', '\1a', '\1a', '\1a', '\1a', '\1a', '\1o', '\1o',
      '\1o', '\1o', '\1o', '\1o', '\1e', '\1e', '\1e', '\1e',
      '\1c', '\1i', '\1i', '\1i', '\1i', '\1u', '\1u', '\1u',
      '\1u', '\1y', '\1n'
    );
    
    $text = strtolower ($text);
    $text = strtr ($text, 'ÄÂÀÁÅÃÒÓÔÕÖØÉÈËÊÇÌÍÎÏÙÚÛÜÝÑ', 'äâàáåãòóôõöøéèëêçìíîïùúûüýñ');
    
    $text = preg_replace ($patterns, $replace, $text);
    
    $text = ucwords ($text);
    
    $patterns = array ('/-\w/', '/\'\w/');
    $text = preg_replace_callback(
      $patterns,
      create_function(
        '$matches',
        'return strtoupper($matches[0]);'
      ),
      $text
    );

    return $text;
  }
  
  public static function capitalizeFirst ($text) {
    
    $patterns = array (
      '/^à/', '/^á/', '/^â/', '/^ã/', '/^ä/', '/^å/', '/^ò/', '/^ó/',
      '/^ô/', '/^õ/', '/^ö/', '/^ø/', '/^è/', '/^é/', '/^ê/', '/^ë/',
      '/^ç/', '/^ì/', '/^í/', '/^î/', '/^ï/', '/^ù/', '/^ú/', '/^û/',
      '/^ü/', '/^ÿ/', '/^ñ/'
    );
    $replace = array (
      'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o',
      'o', 'o', 'o', 'o', 'e', 'e', 'e', 'e',
      'c', 'i', 'i', 'i', 'i', 'u', 'u', 'u',
      'u', 'y', 'n'
    );
    
    $text = strtolower ($text);
    $text = strtr ($text, 'ÄÂÀÁÅÃÒÓÔÕÖØÉÈËÊÇÌÍÎÏÙÚÛÜÝÑ', 'äâàáåãòóôõöøéèëêçìíîïùúûüýñ');
    
    $text = preg_replace ($patterns, $replace, $text);
    
    return ucfirst ($text);
  }
}