<?php

class ZoneDashboardRss extends enicZone {

    public function __construct() {
        parent::__construct();
    }

    function _createContent(&$toReturn) {
        _dump($this->helpers->config('enable'));
        if ($this->helpers->config('enable') != 'false') {
            $this->addJs('js/iconito/module_rssnotifier.js');
            $this->js->addFileByTheme('js/jquery.tmpl.js');
            $toReturn = '<ul id="rssNotifier"></ul>';
        }
    }

}
