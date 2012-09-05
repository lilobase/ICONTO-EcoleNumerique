<?php


/**
* @package copix
* @subpackage fluxrss
* @version	$Id: fluxrssservices.class.php,v 1.8 2008-01-25 10:23:15 cbeyer Exp $
* @author Cogoluegnes Arnaud
*/

class FluxRSSServices
{
    /**
    * Récupère le flux RSS à partir d'une URL.
    * Utilise MagpieRss http://magpierss.sourceforge.net/
    */
    public function getRss($url, $nbMax = -1)
    {
        $arFeeds = array ();

        // Test and Create rss temp_dir
        $cachePath = COPIX_CACHE_PATH . 'fluxrss/';
        $cacheTestFile = 'rss.txt';
        if (!file_exists($cachePath . $cacheTestFile)) {
            require_once (COPIX_UTILS_PATH . 'CopixFile.class.php');
            $objectWriter = new CopixFile();
            $objectWriter->write($cachePath . $cacheTestFile, date('Y/m/d H:i'));
        }

        // Use Magpie to parse current RSS file
        define('MAGPIE_CACHE_DIR', COPIX_CACHE_PATH . 'rss');
        require_once ('rss_fetch.inc');
        $rss = fetch_rss($url);
        if ($rss) {
            if ($nbMax > 0) {
                $arFeeds = array_slice($rss->items, 0, intval($nbMax));
            } else{
                $arFeeds = $rss->items;
            }
        }

        foreach ($arFeeds as $key => $Feed) {
            //print_r($Feed);
            if (isset ($Feed['dc']['date'])) {
                $currentDate = @parse_w3cdtf($Feed['dc']['date']); // Ok with dotclear that uses w3c date format
                //var_dump($currentDate);
            } else{
                $currentDate = -1;
            }

            if ($currentDate != -1) {
                $Feed['dc']['datecopix'] = date("Ymd", $currentDate);
                if (!isset($Feed['date_timestamp']))
                    $Feed['date_timestamp'] = $currentDate;
            } else {
                if (isset($Feed['date_timestamp'])) { // Ok with b2evolution that uses timestamp format
                    $Feed['dc']['datecopix'] = date('Ymd', $Feed['date_timestamp']);
                } else{
                    $Feed['dc']['datecopix'] = null;
                    $Feed['date_timestamp'] = null;
                }
            }
            //var_dump($Feed);
            //die();
            $arFeeds[$key] = $Feed;
        }
        return $arFeeds;
    }

   /**
     * Convertit les caractères
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/28
     * @param mixed $blog Recordset du blog
   */
  public function format_rss_field ($txt)
  {
      $txt = (trim($txt));
      $txt = str_replace("<BR>",'<BR/>',$txt);
      $txt = str_replace("&",'&amp;',$txt);
      $txt = str_replace("<","&lt;",$txt);
      $txt = str_replace(">","&gt;",$txt);
      $txt = str_replace (chr(146), "'", $txt);
      return $txt;
  }

}
