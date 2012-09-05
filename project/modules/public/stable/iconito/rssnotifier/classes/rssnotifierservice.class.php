<?php

class rssnotifierService extends enicService
{
    public function __construct()
    {
        parent::__construct();
        $this->rssType = $this->helpers->config('rssnotifier|rss_type');
        $class = $this->rssType.'Rss';
        $this->rss = new $class();
        $this->rss->setRssStream($this->helpers->config('rssnotifier|rss_url'));
    }

    //0 = all items
    public function GetItems($limitItems = 0)
    {
        return $this->rss->getItems($limitItems);
    }

    public function getTitle()
    {
        return $this->rss->getTitle();
    }

    public function getSummary()
    {
        return $this->rss->getSummary();
    }

    public function getSource()
    {
        return $this->rss->getSource();
    }
}

Interface RSS {

    public function getTitle();

    public function getSource();

    public function getSummary();

    public function getItems();

    public function setRssStream($rssUrl);
}

abstract class RssAbstract
{
    protected $rssStream;
    protected $rss;

    public function __construct()
    {
        $this->rssStream = null;
        $this->rss = null;
        //disable XML error
        libxml_use_internal_errors(true);
    }

    public function setRssStream($rssUrl)
    {
        if (empty($this->rssStream)) {
            //get RSS Stream
            $this->rssStream = file_get_contents($rssUrl);

            if ($this->rssStream === false)
                Throw new Exception('RSS Stream is empty');
        }
    }

    protected function getRss()
    {
        if (empty($this->rss)) {
            $this->rss = new SimpleXMLElement($this->getRssStream());

            if ($this->rss === false)
                Throw new Exception('XML Error, debug RSS stream');
        }
        return $this->rss;
    }

    protected function getRssStream()
    {
        return $this->rssStream;
    }

}

class AtomRss extends RssAbstract implements RSS
{
    public $rss;

    public function __construct()
    {
        parent::__construct();
    }

    //0 = all items
    public function getItems($limitItems = 0)
    {
        $ItemsIterator = 1;

        foreach ($this->getRss()->entry as $entry) {
            if ($ItemsIterator > $limitItems && $limitItems != 0)
                break;

            $currentClass = new stdClass();
            $currentClass->title = strip_tags($entry->title);
            $currentClass->content = strip_tags($entry->content);
            $currentClass->link = (string)$entry->link['href'][0];

            $output[] = $currentClass;

            $ItemsIterator++;
        }

        return $output;
    }

    public function getTitle()
    {
        return $this->getRss()->title;
    }

    public function getSummary()
    {
        return $this->getRss()->subtitle;
    }

    public function getSource()
    {
        return (string) $this->rss->link['href'][0];
    }

}