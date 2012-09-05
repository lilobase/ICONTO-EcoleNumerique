<?php

$version = trim(file_get_contents('version.txt'));

$version_txt = ($version) ? $version : 'inconnue';

if (!defined('_MAIN_TITLE'))
    define( '_MAIN_TITLE', "Installation d'Iconito Ecole Num&eacute;rique ".$version_txt );
define( '_VERSION_TXT', $version_txt);

function display_menu()
{
    global $titles;
    global $step;
    global $display_header;
    $display_header = true;
    $cpt=0;
?>
<html>
<head>
  <title>Installation d'Iconito Ecole Num&eacute;rique <?php echo _VERSION_TXT ?></title>
<style>
<!--
@font-face {
    font-family: 'DroidSans';
    src: url('/themes/default/fonts/droidsans-webfont.eot');
    src: local('?'), url('/themes/default/fonts/droidsans-webfont.woff') format('woff'), url('/themes/default/fonts/droidsans-webfont.ttf') format('truetype'), url('/themes/default/fonts/droidsans-webfont.svg#webfontyzQjp3pD') format('svg');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'DroidSans';
    src: url('/themes/default/fonts/droidsans-bold-webfont.eot');
    src: local('?'), url('/themes/default/fonts/droidsans-bold-webfont.woff') format('woff'), url('/themes/default/fonts/droidsans-bold-webfont.ttf') format('truetype'), url('/themes/default/fonts/droidsans-bold-webfont.svg#webfontyzQjp3pD') format('svg');
    font-weight: bold;
    font-style: normal;
}

BODY {
    padding: 0px;
    margin: 0px;
    font-family: 'DroidSans', Lucida grande, Arial, Helvetica, sans-serif;
}

INPUT {
    font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;
    font-size: 1em;
}

DIV.steps {
    font-size: 0.7em;
    font-family: 'DroidSans', Lucida grande, Arial, Helvetica, sans-serif;
    color: #CCC;
    font-weight: bold;
    padding: 3px;
    margin: 5px;
    background-color: #E0E0E0;
    border-top: 2px solid #F3F3F3;
    border-left: 2px solid #F3F3F3;
    border-bottom: 2px solid #CCC;
    border-right: 2px solid #CCC;
}

A {
    color: #354E81;
}

DIV.steps SPAN.actif {
    color: #354E81;
    font-weight: bold;
}

DIV.steps A.old {
    color: #354E81;
    text-decoration: none;
}

DIV.steps A.old:hover {
    color: #354E81;
    text-decoration: underline;
}

DIV.page {
    padding: 10px;
}

H1, H2, H3 {
    padding: 0px;
    margin: 0px;
    color: #354E81;
    font-size: 1.5em;
}

-->
</style>
</head>
<body>
<?php
  if (is_array($titles)) {
    echo '<div class="steps">'."\n";
    foreach( $titles AS $title_id => $title_name ) {
        if( $cpt++ ) echo ' &raquo; ';
        if( $title_id == $step ) echo '<span class="actif">'.$title_name.'</span>';
        elseif( $title_id < $step ) echo '<a class="old" href="index.php?step='.$title_id.'">'.$title_name.'</a>';
        else echo $title_name;
    }
    echo "\n".'</div>'."\n";
  }
    echo '<div class="page">'."\n";
}

function close_page ()
{
  echo '</body></html>';
}


function display_title( $title="" )
{
    global $titles;
    if( isset($_GET['step']) ) $step=0+$_GET['step'];
    else $step=1;

    if( $title=='' ) {
        $title = _MAIN_TITLE;
        if( isset($titles[$step]) && $titles[$step] ) $title .= " &raquo; ".$titles[$step];
    }
    echo '<h1>'.$title.'</h1>'."\n";
}

function display_title2( $title )
{
    echo '<h2>'.$title.'</h2>'."\n";
}

function display_title3( $title )
{
    echo '<h3>'.$title.'</h3>'."\n";
}

function display_message( $message )
{
    echo '<p>'.$message.'</p>'."\n";
}

function display_list_start()
{
    echo '<ul>'."\n";
}
function display_list_stop()
{
    echo '</ul>'."\n";
}

function display_listno_start()
{
    echo '<ol>'."\n";
}
function display_listno_stop()
{
    echo '</ol>'."\n";
}

function display_list_item( $message )
{
    echo '<li>'.$message.'</li>'."\n";
}

function display_link( $text, $url )
{
    echo '<a href="'.$url.'">'.$text.'</a>'."\n";
}

