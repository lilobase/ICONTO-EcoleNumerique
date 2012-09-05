<?php
/**
* @package 	copix
* @subpackage	smarty_plugins
* @author		Croes Gérald
* @copyright	2001-2006 CopixTeam
* @link			http://copix.org
* @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

@require_once $smarty->_get_plugin_filepath('shared','make_timestamp');

/**
 * Plugin smarty type modifier
 * Purpose:  format a date given by its timestamp (YYYMMDD) to a date according
 *   to the current languages settings
 * if an incorrect date is given, returns the string without any modification
 * Input: YYYYMMDD
 * Output: (french) DD/MM/YYYY, (english) MM/DD/YYYY
 * Output plain text date if text is given as an extra parameter
 *
 * Example:  {$date|datei18n}
 *           {$date|datei18n:text}
 *
 * @return string
 */
function smarty_modifier_datei18n($string, $format="")
{
    // COPIX 3

    /*
   if ($format == ""){
      return (($date = CopixDateTime::yyyymmddToDate ($string)) !== false) ? $date : $string;
   }elseif ($format == "text"){
      return (($date = CopixDateTime::yyyymmddToText ($string)) !== false) ? $date : $string;
   }
     */
     require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');


    // COPIX 2


    if ($format == ""){
      return (($date = CopixDateTime::yyyymmddToDate ($string)) !== false) ? $date : $string;
   } elseif ($format == "text") {
      return (($date = CopixDateTime::yyyymmddToText ($string)) !== false) ? $date : $string;
   } elseif ($format == "date_short") { // JJ/MM/AAAA (fr)
             //var_dump($string);
            //return CopixDateTime::yyyymmddToDate($string);

            $mktime = smarty_make_timestamp($string);
            //var_dump($mktime.'/'.date('d/m/Y',$mktime));
            $yyyymmdd = CopixDateTime::timestampToyyyymmdd($mktime);
            $res = CopixDateTime::yyyymmddToDate ($yyyymmdd);
            return $res;

            /*
            return Kernel::ymd2dmy($string);

            $mktime = smarty_make_timestamp($string);
            $timestamp = date("Ymd", $mktime);
      //return (($date = CopixDateTime::timestampToDate ($timestamp)) !== false) ? $date : $string;
            $timestamp2 = CopixDateTime::dateToTimestamp ($string);

            var_dump($string);

            return (($timestamp==$timestamp2) ? CopixDateTime::timestampToDate ($timestamp) : $string);
            */

   } elseif ($format == "date_short_time") { // JJ/MM/AAAA HHhMM (fr)
            $mktime = smarty_make_timestamp($string);
      return (($date = CopixDateTime::mktimeToDatetime ($mktime)) !== false) ? $date : $string;
   }
}


/*

VERSION COPIX 2

if ($format == ""){
      return (($date = CopixDateTime::timestampToDate ($string)) !== false) ? $date : $string;
   } elseif ($format == "text") {
      return (($date = CopixDateTime::timestampToText ($string)) !== false) ? $date : $string;
   } elseif ($format == "date_short") { // JJ/MM/AAAA (fr)
             //var_dump($string);
            $mktime = smarty_make_timestamp($string);
            $timestamp = date("Ymd", $mktime);
      //return (($date = CopixDateTime::timestampToDate ($timestamp)) !== false) ? $date : $string;
            $timestamp2 = CopixDateTime::dateToTimestamp ($string);
            return (($timestamp==$timestamp2) ? CopixDateTime::timestampToDate ($timestamp) : $string);

   } elseif ($format == "date_short_time") { // JJ/MM/AAAA HHhMM (fr)
            $mktime = smarty_make_timestamp($string);
      return (($date = CopixDateTime::mktimeToDatetime ($mktime)) !== false) ? $date : $string;
   }


     */
