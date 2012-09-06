<?php

class ActionGroupDefault extends enicActionGroup
{
    public function __construct()
    {
        parent::__construct();
        $this->service = & $this->service('rssmixService');

        enic::to_load('image');
    }

    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processGo()
    {
        return $this->redirect('rssmix|default|');
    }

    public function processDefault()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        $ppo = new CopixPPO();

        $ppo->rssUrl = $this->service->getRssUrl();

        $ppo->rss = $ppo->rssUrl;

        if (isset($this->flash->success)) {
            $ppo->success = $this->flash->success;
        }

        $this->js->confirm('.rm-item .delete', 'rssmix.deleteConfirm');

        return _arPPO($ppo, 'liste.tpl');
    }

    public function processCreate()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        $ppo = new CopixPPO();

        //check errors :
        if (isset($this->flash->error))
            $ppo->error = $this->flash->error;

        $this->js->inputPreFilled('#rm-i-url', 'rssmix.create');

        $ppo->formAction = $this->url('rssmix|default|createp');
        $ppo->title = '';
        $ppo->url = '';
        $ppo->image = '';

        return _arPPO($ppo, 'update.tpl');
    }

    public function processCreateP()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }


        if ($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE) {

            $image = (isset($_FILES['rm-file']) && !empty($_FILES['rm-file']['name'])) ? $_FILES['rm-file'] : '';

            $this->service->addRssUrl($this->request('rm-url'), $this->request('rm-title'), $image);

            $this->flash->success = $this->i18n('rssmix.new.success');

            return $this->redirect('rssmix|default|default');
        } else {

            $this->flash->error = $this->i18n('rssmix.noDatas');

            return $this->redirect('rssmix|default|create');
        }
    }

    public function processTest()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        try {
            $feed = $this->service->getRssFeed($this->request('url'), 2);
            echo '<p class="mesgSuccess">' . $this->i18n('rssmix.feedValid') . '</p>';
            foreach ($feed as $item) {
                echo '<h4>' . $item['title'] . '</h4>';
                echo '<p>' . $item['content'] . '</p>';
                echo '<hr />';
            }
        } catch (Exception $e) {
            $config = CopixConfig::instance();
            echo '<p class="mesgError">' . $this->i18n('rssmix.feedNoValid') . '</p>';
            if ($config->getMode() == CopixConfig::DEVEL) {
                echo '<div class="content-panel"><h3>Debug informations (only in devel)</h3>
                    <p> url : ' . $this->request('url') . '</p>
                    <p>' . $e->getMessage() . '</p>
                    <p><pre>' . $e->getFile() . ' : ' . $e->getLine() . '</pre></p>
                    <p><pre>' . $e->getTraceAsString() . '</pre></p>
                        </div>';
            }
        }



        return _arNone();
    }

    public function processDelete()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        $this->service->deleteRssUrl($this->request('id'));

        $this->flash->success = $this->i18n('rssmix.del.success');

        return $this->redirect('rssmix|default|default');
    }

    public function processUpdate()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        $ppo = new CopixPPO();

        //check errors :
        if (isset($this->flash->error))
            $ppo->error = $this->flash->error;

        if (isset($this->flash->success))
            $ppo->success = $this->flash->success;

        if (!$this->istyreq('id'))
            return $this->error('rssmix.error');

        $id = (int) $this->request('id');

        $url = $this->service->getRssUrl($id);

        if (empty($url))
            return $this->error('rssmix.error');

        $ppo->url = $url[0]['url'];
        $ppo->title = $url[0]['title'];
        if (!empty($url[0]['image'])) {
            $imageClass = new enicImage();
            try {
                $ppo->image = $imageClass->get($url[0]['image'], 50, 50, 'crop');
            } catch (Exception $e) {
                $ppo->error = $e->getMessage();
            }
        }
        $ppo->id = $id;
        $ppo->formAction = $this->url('rssmix|default|updatep', array('id' => $id));
        return _arPPO($ppo, 'update.tpl');
    }

    public function processUpdatep()
    {
        if (!Kernel::isAdmin()) {
            return $this->error('rssmix.noRight', true, '||');
        }

        if (!$this->istyreq('id'))
            return $this->error('rssmix.error');

        if ($this->istyreq('rm-url') || filter_var($this->request('rm-url'), FILTER_VALIDATE_URL) === TRUE) {

            $image = (isset($_FILES['rm-file']) && !empty($_FILES['rm-file']['name'])) ? $_FILES['rm-file'] : '';

            $this->service->updateRssUrl($this->request('id'), $this->request('rm-url'), $this->request('rm-title'), $image);

            $this->flash->success = $this->i18n('rssmix.update.success');

            return $this->redirect('rssmix|default|default');
        } else {

            $this->flash->error = $this->i18n('rssmix.noDatas');

            return $this->redirect('rssmix|default|update', array('id' => $this->request('id')));
        }
    }

    public function processDeleteImage()
    {
        if (!Kernel::isAdmin())
            return $this->error('rssmix.noRight', true, '||');

        if (!$this->istyreq('id'))
            return $this->error('rssmix.error');

        $id = (int)$this->request('id');

        $this->service->deleteImage($id);

        $this->flash->success = $this->i18n('rssmix.image.success');
        return $this->redirect('rssmix|default|update', array('id' => $this->request('id')));
    }

    public function processGetRssFeedAjax()
    {
        try {
            $feeds = $this->service->getRssFeeds();

            if (empty($feeds)) {
                echo '<p class="mesgInfo">' . $this->i18n('rssmix.noUrl') . '</p>';
            } else {
                echo '<div id="rssmix-cycle" class="widget-rssmix"><ul>';
                foreach ($feeds as $feed) {

                    echo '<li class="content-panel" >
                        <h4 class="rm-title">' . $feed['title'];
                    if (!empty($feed['fluxTitle'])) {
                        echo '<span>('.$feed['fluxTitle'].')</span>';
                    }
                    echo '</h4>';
                    if (!empty($feed['img'])) {
                        echo '<img src="'.$feed['img'].'" alt="" />';
                    }
                    echo '<a href="' . $feed['link'] . '" class="button button-continue floatright" target="_blank">' . $this->i18n('rssmix.url') . '</a>
                        </li>';
                }
                echo '</ul></div>';
            }
        } catch (Exception $e) {
            echo '<p class="mesgInfo">' . $this->i18n('rssmix.errorConfig') . '</p>';
        }

        return _arNone();
    }

}
