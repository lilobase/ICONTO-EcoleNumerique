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
class enicSocial
{
    public $twitter;

    public function __construct()
    {
        $this->twitter = new enicSocialTwitter;
    }

    public function startExec()
    {
    }

    public function twitter($userName)
    {
        $this->twitter->setUserName($userName);
        return $this->twitter->printSource();
    }

}

class enicSocialTwitter
{
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
    public $i18n = array(
        'fr-FR' => array(
            'lang' => 'fr-FR',
            'dir' => 'ltr',
            'join-the-conversation' => 'Rejoignez la conversation',
            'reply' => 'Répondre',
            'months' => array('Janvier', 'Février', 'Mars', 'Avril', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'),
            'right-now' => 'Maintenant',
            'x-seconds-ago' => 'Il y a %s secondes',
            'about-1-minute-ago' => 'Il y a environ 1 minute',
            'x-minutes-ago' => 'Il y a %s minutes',
            'about-1-hour-ago' => 'Il y a environ 1 heure',
            'x-hours-ago' => 'Il y a environ %s heures',
            'yesterday' => 'hier',
            'x-days-ago' => 'Il y a %s jours',
            'over-a-year' => 'Il y a plus d\'un an'
        )
    );
    public $userName;
    public $language = 'fr-FR';
    private $options = array();
    private $html;
    private $javascript;

    public function __construct()
    {
        $this->source = 'js/twitter/widget.min.js';
    }

    public function setSource($source)
    {
        if (!filter_var($source, FILTER_VALIDATE_URL)) {
            throw new Exception('Is not an Url');
        } else {
            $this->source = $source;
        }
    }

    public function setUserName($user)
    {
        $this->userName = $user;
    }

    public function setNumberOfTweets($number)
    {
        if (!is_int($number)) {
            throw new Exception('Is not an integer');
        } else {
            $this->numberOfTweets = $number;
        }
    }

    public function setInterval($interval)
    {
        if (!is_int($interval)) {
            throw new Exception('Is not an integer');
        } else {
            $this->interval = $interval;
        }
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setLanguage($lang)
    {
        $this->language = $lang;
    }

    public function setThemeByContext($context)
    {
        $theme = $this->theme;
        switch ($context) {
            case 'BU_VILLE':
            case 'BU_ECOLE':
            case 'ROOT':
                $theme = array(
                    'shell' => array(
                        'background' => '#7CD54F',
            'color' => '#434343'
            ),
            'tweets' =>

    array(

     'background'

     =>

    '#fff',

    'color'

     =>

    '#434343',

    'links'

     =>

    '#354E81'
                )
                );
                break;

                case 'CLUB':
                $theme = array(
                'shell' => array(
        'background' => '#FFB94E',
        'color' => '#434343'
            ),
            'tweets' => array(
                'background' => '#fff',
                'color' => '#434343',
                'links' => '#354E81'
            )
        );
        break;

    case 'BU_CLASSE' :
    case 'BU_ELE':
    default:
        $theme = array(
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
        break;
}

$this->setTheme($theme);
}

public function setTheme($theme)
{
if (!is_array($theme)) {
    throw new Exception('Is not an array');
}if (empty($theme)) {
    throw new Exception('array is empty');
} else {
    $this->theme = $theme;
}
}

public function setFeatures($features)
{
if (!is_array($features)) {
    throw new Exception('Is not an array');
}if (empty($features)) {
    throw new Exception('array is empty');
} else {
    $this->features = $features;
}
}

private function buildOptions()
{
$this->options['version'] = $this->version;
$this->options['type'] = $this->type;
$this->options['rpp'] = $this->numberOfTweets;
$this->options['interval'] = $this->interval;
$this->options['width'] = $this->width;
$this->options['height'] = $this->height;
$this->options['theme'] = $this->theme;
$this->options['features'] = $this->features;

return json_encode($this->options);
}

private function buildLanguage()
{
    return json_encode($this->i18n[$this->language]);
}

private function buildJavascript()
{
    $this->javascript = 'TWTR.i18n.init('.$this->buildLanguage().');'.PHP_EOL;
    $this->javascript .= 'new TWTR.Widget(' . $this->buildOptions() . ').render().setUser(\'' . $this->userName . '\').start();';
}

private function buildHtml()
{
//$this->html = '<script charset="utf-8" src="' . $this->source . '"></script>';

$js = enic::get('javascript');
$js->addFile($this->source);
$this->html .= '<script type="text/javascript">' .PHP_EOL . $this->javascript . PHP_EOL. '</script>';
}

public function printSource()
{
$this->buildJavascript();
$this->buildHtml();

return $this->html;
}

}

