<?php
   require('rss_fetch.inc');
    $rss = fetch_rss('http://sdaclin.free.fr/b2evolution/xmlsrv/rss2.php?blog=5');
   foreach ($rss->items as $key=>$RSSPost) {
       print_r($RSSPost);
   }
