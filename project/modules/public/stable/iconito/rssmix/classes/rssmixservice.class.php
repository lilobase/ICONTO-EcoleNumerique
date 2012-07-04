<?php

class rssmixService extends enicService {

    public function addRssUrl($url) {
        return $this->db->create('module_rssmix', array('url' => $this->db->quote($url)));
    }

    public function deleteRssUrl($id) {
        return $this->db->delete('module_rssmix', (int) $id);
    }

    public function getRssUrl($id = null) {

        $where = (is_null($id)) ? '' : ' WHERE id = ' . (int) $id;

        return $this->db->query('SELECT * FROM module_rssmix' . $where)->toArray();
    }

    public function updateRssUrl($id, $url) {
        return $this->db->update('module_rssmix', array('url' => $this->db->quote($url), 'id' => (int) $id));
    }

    public function __construct() {
        $this->feed = & enic::get('zend');
        parent::__construct();
    }

    public function getRssFeeds() {
        
        $urls = $this->getRssUrl();
        
        if(empty($urls))
            return null;
        
        $item = array();
        
        $globalLimit = $this->helpers->config('rssmix|itemsLimit');
        
        $limit = round($globalLimit / count($urls));
        
        foreach($urls as $url){
            
            $item = array_merge($this->getRssFeed($url['url'], $limit), $item);
        }
        
        return $item;
        
    }

    public function getRssFeed($url, $limit = 3) {
        $feed = Zend_Feed_Reader::import($url);
        
        $iterator = 1;
        $item = array();
        
        $img = $feed->getImage();
        
        foreach ($feed as $entry) {
            
            if($iterator > $limit)
                break;
            
            $item[$iterator]['title'] = $entry->getTitle();
            $item[$iterator]['link'] = $entry->getLink();
            $item[$iterator]['content'] = $entry->getContent();
            $item[$iterator]['img'] = $img;
            
            $iterator++;
        }
        return $item;
    }

}