<?php



    function order_tab_blogs ($a, $b)
    {
        //print_r($a);
        if ($a->stats['lastUpdate']['value_order'] == $b->stats['lastUpdate']['value_order']) {
        return 0;
       }
       return ($a->stats['lastUpdate']['value_order'] > $b->stats['lastUpdate']['value_order']) ? -1 : 1;
    }


