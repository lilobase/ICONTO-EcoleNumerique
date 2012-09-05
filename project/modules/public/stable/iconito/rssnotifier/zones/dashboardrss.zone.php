<?php

class ZoneDashboardRss extends enicZone
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _createContent(&$toReturn)
    {
        if ($this->helpers->config('enable') != 'false') {
            $this->addCss('styles/module_rssnotifier.css');
            $this->addJs('js/iconito/module_rssnotifier.js');
            $this->js->addFileByTheme('js/jquery.tmpl.js');
            $toReturn = '<div id="rssNotifier"><ul id="rssNotifierItems"></ul></div>';
        }
    }

}
