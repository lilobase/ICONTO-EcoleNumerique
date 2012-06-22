<?php

    class ActionGroupDefault extends enicActionGroup {

        public function __construct(){
            parent::__construct();
            $this->service =& $this->service('rssmixService');
        }

        public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault(){
            $ppo = new CopixPPO();
            
            $ppo->rssUrl = $this->service->getRssUrl();
            
            $ppo->rss = $ppo->rssUrl;

            if(isset($this->flash->success)){
                $ppo->success = $this->flash->success;
            }
            
            $this->js->confirm('.rm-item .delete', 'rssmix.deleteConfirm');
            
            return _arPPO($ppo, 'liste.tpl');
        }
        
        public function processCreate(){
            

            $ppo = new CopixPPO();

            //check errors :
            if(isset($this->flash->error))
                $ppo->error = $this->flash->error;
            
            $this->js->inputPreFilled('#rm-i-url', 'rssmix.create');
            return _arPPO($ppo, 'create.tpl');
            
        }
        
        public function processCreateP(){
            
            if($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE){
            
                $this->service->addRssUrl($this->request('rm-url'));

                $this->flash->success = $this->i18n('rssmix.new.success');
                
                return $this->redirect('rssmix|default|default');
                
            }else{
                
                $this->flash->error = $this->i18n('rssmix.noDatas');
                
                return $this->redirect('rssmix|default|create');
            }
        }
        
        public function processTest(){            
            
            $item = $this->service->getRssFeeds();
            
            _dump($item);
            
            return _arNone();
        }
        
        public function processDelete(){
            
            $this->service->deleteRssUrl($this->request('id'));
            
            $this->flash->success = $this->i18n('rssmix.del.success');
                
            return $this->redirect('rssmix|default|default');
            
        }
        
        public function processUpdate(){
            
            $ppo = new CopixPPO();

            //check errors :
            if(isset($this->flash->error))
                $ppo->error = $this->flash->error;
            
            if(!$this->istyreq('id'))
               return $this->error('rssmix.error');
            
            $id = (int)$this->request('id');
            
            $url = $this->service->getRssUrl($id);
            
            if(empty($url))
                return $this->error('rssmix.error');
            
            $ppo->url = $url[0]['url'];
            $ppo->id = $id;

            return _arPPO($ppo, 'update.tpl');
            
        }
        
        public function processUpdatep(){
            
            if(!$this->istyreq('id'))
               return $this->error('rssmix.error');
            
             if($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE){
            
                $this->service->updateRssUrl($this->request('id'), $this->request('rm-url'));

                $this->flash->success = $this->i18n('rssmix.update.success');
                
                return $this->redirect('rssmix|default|default');
                
            }else{
                
                $this->flash->error = $this->i18n('rssmix.noDatas');
                
                return $this->redirect('rssmix|default|update', array('id' => $this->request('id')));
            }
            
        }
        
        public function processGetRssFeedAjax(){
            
        }

    }