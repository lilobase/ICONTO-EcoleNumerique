<?php

    class rssEtagereService extends enicService{
        
        public function startExec(){
            $this->rssUrl = $this->helpers->config('rssnotifier|rss_url');
            $this->xml = simplexml_load_file($this->rssUrl);
        }
        
        
    }