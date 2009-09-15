<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Album photo : {$album_titre}{if $dossier_id gt 0} / {$dossier_nom}{/if}</title>
<script type="text/javascript" src="{copixurl}simpleviewer/flashobject.js"></script>
<style type="text/css">	
	/* hide from ie on mac \*/
	html {ldelim}
		height: 100%;
		overflow: hidden;
	{rdelim}
	
	#flashcontent {ldelim}
		height: 100%;
	{rdelim}
	/* end hide */

	body {ldelim}
		height: 100%;
		margin: 0;
		padding: 0;
		background-color: #181818;
		color:#ffffff;
	{rdelim}
</style>
</head>
<body>
	<div id="flashcontent">SimpleViewer requires Macromedia Flash. <a href="http://www.macromedia.com/go/getflashplayer/">Get Macromedia Flash.</a> If you have Flash installed, <a href="index.html?detectflash=false">click to view gallery</a></div>	
	<script type="text/javascript">
		var fo = new FlashObject("{copixurl}simpleviewer/viewer.swf", "viewer", "100%", "100%", "6", "#181818");		
		fo.addParam("quality", "best");
		fo.addVariable("xmlDataPath", "{copixurl}static/album/{$album_id}_{$album_key}/{if $dossier_id gt 0}{$dossier_id}_{$dossier_key}/{/if}imageData.xml");
		fo.write("flashcontent");	
	</script>
</body>
</html>