<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enic
 *
 * @author alemaire
 */
class enicSocial {

    public $twitter;
    
    public function __construct(){
        $this->twitter = new enicSocialTwitter;
    }
    

    public function startExec(){
        
    }
    
    public function twitter($userName){
        $this->twitter->setUserName($userName);
        return $this->twitter->printSource();
    }
    
}

class enicSocialTwitter {
    
    public $source = 'http://widgets.twimg.com/j/2/widget.js';
    public $numberOfTweets = 4;
    public $version = 2;
    public $type = 'profile';
    public $interval = 3000;
    public $width = 'auto';
    public $height = 150;   
    public $theme = array(
        'shell' => array(
            'background' => '#7FC3D2',
            'color' => '#434343'
        ),
        'tweets' => array(
            'background' => '#fff',
            'color' => '#434343',
            'links' => '#354E81'
        )
    );
    public $features = array(
        'scrollbar' => 'false',
        'loop' => 'true',
        'live' => 'false',
        'behavior' => 'default'
    );
    
    public $userName;
    
    private $options = array();
    private $html;
    private $javascript;
    
    public function setSource($source){
        if(!filter_var($source, FILTER_VALIDATE_URL)){
            throw new Exception('Is not an Url');
        }else{
            $this->source = $source;
        }
    }
    
    public function setUserName($user){
        $this->userName = $user;
    }
    
    public function setNumberOfTweets($number){
        if(!is_int($number)){
            throw new Exception('Is not an integer');
        }else{
            $this->numberOfTweets = $number;
        }
    }
    
    public function setInterval($interval){
        if(!is_int($interval)){
            throw new Exception('Is not an integer');
        }else{
            $this->interval = $interval;
        }
    }
    
    public function setHeight($height){
        $this->height = $height;
    }
    
    public function setWidth($width){
        $this->width = $width;
    }
    
    public function setType($type){
        $this->type = $type;
    }
    
    public function setTheme($theme){
        if(!is_array($theme)){
            throw new Exception('Is not an array');
        }if(empty($theme)){
            throw new Exception('array is empty');
        }else{
            $this->theme = $theme;
        }
    }
    
    public function setFeatures($features){
        if(!is_array($features)){
            throw new Exception('Is not an array');
        }if(empty($features)){
            throw new Exception('array is empty');
        }else{
            $this->features = $features;
        }
    }
    
    private function builOptions(){
        
        $this->options['version'] = $this->version;
        $this->options['type'] = $this->type;
        $this->options['rpp'] = $this->numberOfTweets;
        $this->options['interval'] = $this->interval;
        $this->options['width'] = $this->width;
        $this->options['height'] = $this->height;
        $this->options['theme'] = $this->theme;
        $this->options['features'] = $this->features;
        
        $this->options = json_encode($this->options);
        
    }
    
    private function buildJavascript(){
        $this->javascript = 'new TWTR.Widget('.$this->options.').render().setUser(\''.$this->userName.'\').start()';
    }
    
    private function buildHtml(){
        $this->html = '<script charset="utf-8" src="'.$this->source.'"></script>';
        $this->html .= '<script type="text/javascript">'.$this->javascript.'</script>';   
    }
    
    public function printSource(){
        $this->builOptions();
        $this->buildJavascript();
        $this->buildHtml();
        
        return $this->html;
    }
}

?>