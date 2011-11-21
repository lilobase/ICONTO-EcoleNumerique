<?php

    class rssEtagereService extends enicService{
        
        public function startExec(){
            
        }
        
        public function loadXml(){
            $this->rssUrl = $this->helpers->config('rssnotifier|rss_url');
            $this->xml = simplexml_load_file($this->rssUrl);
            
            if($this->xml == false)
                return false;
            
            return true;
        }
        
        public function getTitle(){
            return $this->xml->title;
        }
        
        public function getDescription(){
            return $this->xml->description;
        }
        
        public function getLink(){
            return $this->xml->link;
        }
        
        public function getItems(){
            $return = array();
            foreach($this->xml->item as $item){
                $itemObject = new stdClass();
                $itemObject->title = $item->title;
                $itemObject->desc = $item->description;
                $itemObject->link = $item->link;
                $itemObject->pic = $item->enclosure;
                $itemObject->quid = $item->quid;
                $return[] = $itemObject;
            }
            
            return $return;
        }
        
    }