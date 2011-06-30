<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Album photo : {$album_titre|escape:"html"}{if $dossier_id gt 0} / {$dossier_nom|escape:"html"|utf8_decode}{/if}</title>
<script type="text/javascript" src="{copixurl}js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="{copixurl}galleria/galleria-1.2.4.min.js"></script>
</head>
<body>
	<div id="gallery">
	  {foreach from=$images item=image}
	    <img src="{$path2public}/images/{$image}" />
	  {/foreach}
  </div>
  <script>
    Galleria.loadTheme('{copixurl}galleria/themes/classic/galleria.classic.min.js');
    {literal}$("#gallery").galleria({width: 500, height: 500});{/literal}
  </script>
</body>
</html>