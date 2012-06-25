<?php

class ActionGroupDefault extends enicActionGroup {

    public function __construct() {
        parent::__construct();
        $this->service = & $this->service('rssmixService');
    }

    public function beforeAction() {
        _currentUser()->assertCredential('group:[current_user]');

    }

    public function processDefault() {
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }
        
        $ppo = new CopixPPO();

        $ppo->rssUrl = $this->service->getRssUrl();

        $ppo->rss = $ppo->rssUrl;

        if (isset($this->flash->success)) {
            $ppo->success = $this->flash->success;
        }

        $this->js->confirm('.rm-item .delete', 'rssmix.deleteConfirm');

        return _arPPO($ppo, 'liste.tpl');
    }

    public function processCreate() {
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }

        $ppo = new CopixPPO();

        //check errors :
        if (isset($this->flash->error))
            $ppo->error = $this->flash->error;

        $ppo->urlTest = $this->url('rssmix|default|test');

        $this->js->inputPreFilled('#rm-i-url', 'rssmix.create');
        return _arPPO($ppo, 'create.tpl');
    }

    public function processCreateP() {
        
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }


        if ($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE) {

            $this->service->addRssUrl($this->request('rm-url'));

            $this->flash->success = $this->i18n('rssmix.new.success');

            return $this->redirect('rssmix|default|default');
        } else {

            $this->flash->error = $this->i18n('rssmix.noDatas');

            return $this->redirect('rssmix|default|create');
        }
    }

    public function processTest() {
        
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }

        try {
            $feed = $this->service->getRssFeed($this->request('url'), 2);
            echo '<p class="mesgSuccess">'.$this->i18n('rssmix.feedValid').'</p>';
            foreach ($feed as $item) {
                echo '<h4>' . $item['title'] . '</h4>';
                echo '<p>' . $item['content'] . '</p>';
                echo '<hr />';
            }
        } catch (Exception $e) {
            echo '<p class="mesgError">'.$this->i18n('rssmix.feedNoValid').'</p>';
        }



        return _arNone();
    }

    public function processDelete() {
        
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }

        $this->service->deleteRssUrl($this->request('id'));

        $this->flash->success = $this->i18n('rssmix.del.success');

        return $this->redirect('rssmix|default|default');
    }

    public function processUpdate() {
        
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }

        $ppo = new CopixPPO();

        //check errors :
        if (isset($this->flash->error))
            $ppo->error = $this->flash->error;

        if (!$this->istyreq('id'))
            return $this->error('rssmix.error');

        $id = (int) $this->request('id');

        $url = $this->service->getRssUrl($id);

        if (empty($url))
            return $this->error('rssmix.error');

        $ppo->urlTest = $this->url('rssmix|default|test');
        $ppo->url = $url[0]['url'];
        $ppo->id = $id;

        return _arPPO($ppo, 'update.tpl');
    }

    public function processUpdatep() {
        
        if (!$this->user->root) { return $this->error('rssmix.noRight', true, '||'); }


        if (!$this->istyreq('id'))
            return $this->error('rssmix.error');

        if ($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE) {

            $this->service->updateRssUrl($this->request('id'), $this->request('rm-url'));

            $this->flash->success = $this->i18n('rssmix.update.success');

            return $this->redirect('rssmix|default|default');
        } else {

            $this->flash->error = $this->i18n('rssmix.noDatas');

            return $this->redirect('rssmix|default|update', array('id' => $this->request('id')));
        }
    }

    public function processGetRssFeedAjax() {

        try{
        $feeds = $this->service->getRssFeeds();

        if (empty($feeds)) {
            echo '<p class="mesgInfo">' . $this->i18n('rssmix.noUrl') . '</p>';
        } else {
            echo '<div id="rssmix-cycle" class="widget-rssmix"><ul>';
            foreach ($feeds as $feed) {
                echo '<li class="content-panel" >
                        <h4 class="rm-title">' . $feed['title'] . '</h4>
                        <a href="' . $feed['link'] . '" class="button button-continue">' . $this->i18n('rssmix.url') . '</a>
                        </li>';
            }
            echo '</ul></div>';
        }
        } catch (Exception $e){
            echo '<p class="mesgInfo">' . $this->i18n('rssmix.errorConfig') . '</p>';
        }

        return _arNone();
    }

}