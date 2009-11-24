<?php

$version = trim(file_get_contents('version.txt'));

$version_txt = ($version) ? $version : 'inconnue';

define( '_MAIN_TITLE', "Installation d'ICONITO EcoleNumerique (version ".$version_txt.")" );

function display_menu() {
	global $titles;
	global $step;
	global $display_header;
	$display_header = true;
	$cpt=0;
?>
<style>
<!--
BODY {
	padding: 0px;
	margin: 0px;
	font-family: Arial, Helvetica, Verdana, Univers, "MS Trebuchet", sans-serif;
}

DIV.steps {
	font-size: 0.7em;
	font-family: Arial, Helvetica, Verdana, Univers, "MS Trebuchet", sans-serif;
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

DIV.steps SPAN.actif {
	color: blue;
	font-weight: bold;
}

DIV.steps A.old {
	color: #333;
	text-decoration: none;
}

DIV.steps A.old:hover {
	color: #000;
	text-decoration: underline;
}

DIV.page {
	padding: 10px;
}

H1, H2, H3 {
	padding: 0px;
	margin: 0px;
}
-->
</style>
<?php
	echo '<div class="steps">'."\n";
	foreach( $titles AS $title_id => $title_name ) {
		if( $cpt++ ) echo ' &raquo; ';
		if( $title_id == $step ) echo '<span class="actif">'.htmlentities($title_name).'</span>';
		elseif( $title_id < $step ) echo '<a class="old" href="index.php?step='.$title_id.'">'.htmlentities($title_name).'</a>';
		else echo htmlentities($title_name);
	}
	echo "\n".'</div>'."\n";
	
	echo '<div class="page">'."\n";
}
function display_title( $title="" ) {
	global $titles;
	if( isset($_GET['step']) ) $step=0+$_GET['step'];
	else $step=1;
	
	if( $title=='' ) {
		$title = _MAIN_TITLE;
		if( isset($titles[$step]) && $titles[$step] ) $title .= " &raquo; ".$titles[$step];
	}
	echo '<h1>'.$title.'</h1>'."\n";
}

function display_title2( $title ) {
	echo '<h2>'.$title.'</h2>'."\n";
}

function display_title3( $title ) {
	echo '<h3>'.$title.'</h3>'."\n";
}

function display_message( $message ) {
	echo '<p>'.$message.'</p>'."\n";
}

function display_list_start() {
	echo '<ul>'."\n";
}
function display_list_stop() {
	echo '</ul>'."\n";
}

function display_listno_start() {
	echo '<ol>'."\n";
}
function display_listno_stop() {
	echo '</ol>'."\n";
}

function display_list_item( $message ) {
	echo '<li>'.$message.'</li>'."\n";
}

function display_link( $text, $url ) {
	echo '<a href="'.$url.'">'.$text.'</a>'."\n";
}

?>
