<?php

    class rssmixService extends enicService{
        
        public function addRssUrl($url){
            return $this->db->create('module_rssmix', array('url' => $this->db->quote($url)));
        }
        
        public function deleteRssUrl($id){
            return $this->db->delete('module_rssmix', (int)$id);
        }
        
        public function getRssUrl($id = null){
            
            $where = (is_null($id)) ? '' : ' WHERE id = '.(int)$id ;
            
            return $this->db->query('SELECT * FROM module_rssmix'.$where)->toArray();
        }
        
        public function updateRssUrl($id, $url){
            return $this->db->update('module_rssmix', array('url' => $this->db->quote($url), 'id' => (int)$id));
        }
        
    }