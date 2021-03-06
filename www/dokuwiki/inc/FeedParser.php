<?php
/**
 * Class used to parse RSS and ATOM feeds
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',fullpath(dirname(__FILE__).'/../').'/');
require_once(DOKU_INC.'inc/HTTPClient.php');
require_once(DOKU_INC.'inc/SimplePie.php');


/**
 * We override some methods of the original SimplePie class here
 */
class FeedParser extends SimplePie
{
    /**
     * Constructor. Set some defaults
     */
    public function FeedParser()
    {
        $this->SimplePie();
        $this->enable_cache(false);
        $this->set_file_class('FeedParser_File');
    }

    /**
     * Backward compatibility for older plugins
     */
    public function feed_url($url)
    {
        $this->set_feed_url($url);
    }
}

/**
 * Fetch an URL using our own HTTPClient
 *
 * Replaces SimplePie's own class
 */
class FeedParser_File extends SimplePie_File
{
    public $http;
    public $useragent;
    public $success = true;
    public $headers = array();
    public $body;
    public $error;

    /**
     * Inititializes the HTTPClient
     *
     * We ignore all given parameters - they are set in DokuHTTPClient
     */
    public function FeedParser_File($url, $timeout=10, $redirects=5,
                             $headers=null, $useragent=null, $force_fsockopen=false) {
        $this->http    = new DokuHTTPClient();
        $this->success = $this->http->sendRequest($url);

        $this->headers = $this->http->resp_headers;
        $this->body    = $this->http->resp_body;
        $this->error   = $this->http->error;
        return $this->success;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function body()
    {
        return $this->body;
    }

    public function close()
    {
        return true;
    }

}
