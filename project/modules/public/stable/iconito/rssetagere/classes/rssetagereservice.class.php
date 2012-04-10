<?php

    class rssEtagereService extends enicService{
        
        public function startExec(){
            
        }
        
        public function loadXml(){
		if (!is_null($myNode = _sessionGet('myNode'))) {
			$ppo->targetId   = $myNode['id'];
			$ppo->targetType = $myNode['type'];
			$ppo->myNodeInfos = Kernel::getNodeInfo ($myNode['type'], $myNode['id']);

			if( $ppo->targetType == "BU_CLASSE" ) {
				$ppo->url_classe = urlencode($ppo->myNodeInfos['nom']);
				$ppo->url_ecole = $ppo->myNodeInfos['ALL']->eco_numero;
			}
		}

            $this->rssUrl = $this->helpers->config('rssetagere|rss_url');

            $this->rssUrl = $this->rssUrl.'?classe='.$ppo->url_classe.'&siren='.$ppo->url_ecole;
            $this->xml = @simplexml_load_file($this->rssUrl);

            if($this->xml == false)
                return false;
            
            return true;
        }
        
        public function getTitle(){
            
            return $this->xml->channel->title;
        }
        
        public function getDescription(){
            return $this->xml->channel->description;
        }
        
        public function getLink(){
            return $this->xml->channel->link;
        }
        
        public function getItems(){
            $return = array();
            foreach($this->xml->channel->item as $item){
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
