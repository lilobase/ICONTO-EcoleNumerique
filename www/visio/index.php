<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
    <!-- 
    Smart developers always View Source. 
    
    This application was built using Adobe Flex, an open source framework
    for building rich Internet applications that get delivered via the
    Flash Player or to desktops via Adobe AIR. 
    
    Learn more about Flex at http://flex.org 
    // -->
    <head>
        <title></title>
        <meta name="google" value="notranslate">         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Include CSS to eliminate any default margins/padding and set the height of the html element and 
		     the body element to 100%, because Firefox, or any Gecko based browser, interprets percentage as 
			 the percentage of the height of its parent container, which has to be set explicitly.  Fix for
			 Firefox 3.6 focus border issues.  Initially, don't display flashContent div so it won't show 
			 if JavaScript disabled.
		-->
        <style type="text/css" media="screen"> 
			html, body	{ height:100%; }
			body { margin:0; padding:0; overflow:auto; text-align:center; 
			       background-color: #ccc; }   
			object:focus { outline:none; }
			#flashContent { display:none; }
			#visioForm {
				position: absolute;
				top: 30px;
				right: 10px;
			}
			#visio {
				position: absolute;
				top: 30px;
				left: 10px;
			}
        </style>
		
		<!-- Enable Browser History by replacing useBrowserHistory tokens with two hyphens -->
        <!-- BEGIN Browser History required section -->
        <link rel="stylesheet" type="text/css" href="history/history.css" />
        <script type="text/javascript" src="history/history.js"></script>
        <!-- END Browser History required section -->  
		    
        <script type="text/javascript" src="swfobject.js"></script>
        <script type="text/javascript">
			<!-- This function returns the appropriate reference, depending on the browser. -->
			function getFlexApp(appName)
			{
				if (navigator.appName.indexOf ("Microsoft") !=-1)
				{
					return window[appName];
				}
				else 
				{
					return document[appName];
				}
			}
			<!-- Launch visio in flex app -->
			function startCall()
			{
				var callerLogin = document.getElementById('callerLogin').value;
				var receiverLogin = document.getElementById('receiverLogin').value;
				var red5Server = document.getElementById('red5').value;
				
				var secondsToWait = "";
				if (document.getElementById('secondsToWait') != null) {
					secondsToWait = document.getElementById('secondsToWait').value;
				}
				var secondsToRetry = "";
				if (document.getElementById('secondsToRetry') != null) {
					secondsToRetry = document.getElementById('secondsToRetry').value;
				}
				var textColor = "";
				if (document.getElementById('textColor') != null) {
					textColor = document.getElementById('textColor').value;
				}
				var textOverColor = "";
				if (document.getElementById('textOverColor') != null) {
					textOverColor = document.getElementById('textOverColor').value;
				}
				var infoTextColor = "";
				if (document.getElementById('infoTextColor') != null) {
					infoTextColor = document.getElementById('infoTextColor').value;
				}
				var isCaller = "off";
				if (document.getElementById('isCaller').checked) {
					isCaller=document.getElementById('isCaller').value;
				}
				
				var bandwith = "";
				if (document.getElementById('bandwith') != null) {
					bandwith = document.getElementById('bandwith').value;
				}
				var videoQuality = "";
				if (document.getElementById('videoQuality') != null) {
					videoQuality = document.getElementById('videoQuality').value;
				}
				var keyFrameInterval = "";
				if (document.getElementById('keyFrameInterval') != null) {
					keyFrameInterval = document.getElementById('keyFrameInterval').value;
				}
				var motionLevel = "";
				if (document.getElementById('motionLevel') != null) {
					motionLevel = document.getElementById('motionLevel').value;
				}
				var motionTimeout = "";
				if (document.getElementById('motionTimeout') != null) {
					motionTimeout = document.getElementById('motionTimeout').value;
				}
				var useEchoSuppression = "";
				if (document.getElementById('useEchoSuppression') != null) {
					useEchoSuppression = document.getElementById('useEchoSuppression').value;
				}
				var bufferTime = "";
				if (document.getElementById('bufferTime') != null) {
					bufferTime = document.getElementById('bufferTime').value;
				}
		


				//alert("callerLogin : "+callerLogin+"\nreceiverLogin : "+receiverLogin+"\nisCaller : "+isCaller+"\nred5 : "+red5Server+"\nsecondsToWait : "+secondsToWait+"\nsecondsToRetry : "+secondsToRetry+"\ntextColor : "+textColor+"\ntextOverColor : "+textOverColor+"\ninfoTextColor : "+infoTextColor);
				getFlexApp('Iconisio').callVisio(callerLogin, receiverLogin, isCaller, red5Server, secondsToWait, secondsToRetry, textColor, textOverColor, infoTextColor, bandwith, videoQuality, keyFrameInterval, motionLevel, motionTimeout, useEchoSuppression, bufferTime);
			}
			<!-- Reset visio form, this method is called by the flex app on visio close event -->
			function stopCall()
			{
				document.getElementById('callerLogin').value="";
				document.getElementById('receiverLogin').value="";
				document.getElementById('isCaller').checked=false;
			}
			
            <!-- For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. --> 
            var swfVersionStr = "10.0.0";
            <!-- To use express install, set to playerProductInstall.swf, otherwise the empty string. -->
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {};
<?php
			if ($_POST) {
				echo "\t\tflashvars.caller=\"".$_POST["callerLogin"]."\";\n";
				echo "\t\tflashvars.receiver=\"".$_POST["receiverLogin"]."\";\n";
				echo "\t\tflashvars.isCaller=\"".$_POST["isCaller"]."\";\n";
				echo "\t\tflashvars.red5=\"".$_POST["red5"]."\";\n";
				echo "\t\tflashvars.secondsToWait=\"".$_POST["secondsToWait"]."\";\n";
				echo "\t\tflashvars.secondsToRetry=\"".$_POST["secondsToRetry"]."\";\n";
				echo "\t\tflashvars.textColor=\"".$_POST["textColor"]."\";\n";
				echo "\t\tflashvars.textOverColor=\"".$_POST["textOverColor"]."\";\n";
				echo "\t\tflashvars.infoTextColor=\"".$_POST["infoTextColor"]."\";\n";
				echo "\t\tflashvars.bandwith=\"".$_POST["bandwith"]."\";\n";
				echo "\t\tflashvars.videoQuality=\"".$_POST["videoQuality"]."\";\n";
				echo "\t\tflashvars.keyFrameInterval=\"".$_POST["keyFrameInterval"]."\";\n";
				echo "\t\tflashvars.motionLevel=\"".$_POST["motionLevel"]."\";\n";
				echo "\t\tflashvars.motionTimeout=\"".$_POST["motionTimeout"]."\";\n";
				echo "\t\tflashvars.useEchoSuppression=\"".$_POST["useEchoSuppression"]."\";\n";
				echo "\t\tflashvars.bufferTime=\"".$_POST["bufferTime"]."\";\n";

			}
?>
            var params = {};
            params.quality = "high";
            params.wmode = "transparent";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "Iconisio";
            attributes.name = "Iconisio";
            attributes.align = "middle";
            swfobject.embedSWF(
                "Iconisio.swf", "flashContent", 
                "656", "362", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
			<!-- JavaScript enabled so display the flashContent div in case it is not replaced with a swf object. -->
			swfobject.createCSS("#flashContent", "display:block;text-align:left;");
			
        </script>
    </head>
    <body>
        <!-- SWFObject's dynamic embed method replaces this alternative HTML content with Flash content when enough 
			 JavaScript and Flash plug-in support is available. The div is initially hidden so that it doesn't show
			 when JavaScript is disabled.
		-->
        <div id="visio">
        <div id="flashContent">
        	<p>
	        	To view this page ensure that Adobe Flash Player version 
				10.0.0 or greater is installed. 
			</p>
			<script type="text/javascript"> 
				var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
				document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
								+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
			</script> 
        </div>
        </div>
	   	
       	<noscript>
        <div id="visio">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="689" height="424" id="Iconisio">
                <param name="movie" value="Iconisio.swf" />
                <param name="quality" value="high" />
				<param name="wmode" value="transparent" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="true" />
<?php
			if ($_POST) {
				echo "<param name=\"flashVars\" value=\"caller=".$_POST["callerLogin"]."&receiver=".$_POST["receiverLogin"]."&isCaller=".$_POST["isCaller"]."&red5=".$_POST["red5"]."&secondsToWait=".$_POST["secondsToWait"]."&secondsToRetry=".$_POST["secondsToRetry"]."&textColor=".$_POST["textColor"]."&textOverColor=".$_POST["textOverColor"]."&infoTextColor=".$_POST["infoTextColor"]."&bandwith=".$_POST["bandwith"]."&videoQuality=".$_POST["videoQuality"]."&keyFrameInterval=".$_POST["keyFrameInterval"]."&motionLevel=".$_POST["motionLevel"]."&motionTimeout=".$_POST["motionTimeout"]."&useEchoSuppression=".$_POST["useEchoSuppression"]."&bufferTime=".$_POST["bufferTime"]."\" />;\n";
			}
?>
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="Iconisio.swf" width="689" height="424">
                    <param name="quality" value="high" />
					<param name="wmode" value="transparent" />
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="true" />
<?php
			if ($_POST) {
				echo "<param name=\"flashVars\" value=\"caller=".$_POST["callerLogin"]."&receiver=".$_POST["receiverLogin"]."&isCaller=".$_POST["isCaller"]."&red5=".$_POST["red5"]."&secondsToWait=".$_POST["secondsToWait"]."&secondsToRetry=".$_POST["secondsToRetry"]."&textColor=".$_POST["textColor"]."&textOverColor=".$_POST["textOverColor"]."&infoTextColor=".$_POST["infoTextColor"]."&bandwith=".$_POST["bandwith"]."&videoQuality=".$_POST["videoQuality"]."&keyFrameInterval=".$_POST["keyFrameInterval"]."&motionLevel=".$_POST["motionLevel"]."&motionTimeout=".$_POST["motionTimeout"]."&useEchoSuppression=".$_POST["useEchoSuppression"]."&bufferTime=".$_POST["bufferTime"]."\" />;\n";
		   }
?>
                <!--<![endif]-->
                <!--[if gte IE 6]>-->
                	<p> 
                		Either scripts and active content are not permitted to run or Adobe Flash Player version
                		10.0.0 or greater is not installed.
                	</p>
                <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflashplayer">
                        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                    </a>
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
            </div>
	    </noscript>	
        
        <div id="visioForm">
        	<p>
	        	Paramètres de la visio 
			</p>
        	<form id="visioParams" name="visioParams" method="post" action="">
        	  <label>Login de l'appelant : <input name="callerLogin" type="text" id="callerLogin" value="<?php echo $_POST["callerLogin"];?>" /></label><br/>
        	  <label>Login de l'appelé&nbsp;&nbsp;&nbsp; : <input type="text" name="receiverLogin" id="receiverLogin" value="<?php echo $_POST["receiverLogin"];?>" /></label><br/>
        	  <input name="isCaller" type="checkbox" id="isCaller" <?php (isset($_POST["isCaller"])==true)? "checked=\"checked\"" : ""; ?> />Je suis l'appelant<br/>
              <input name="red5" id="red5" type="hidden" value="rtmp://sandbox.gribin.net/oflaDemo" />
<!--
              <input name="secondsToWait" id="secondsToWait" type="hidden" value="30" />
              <input name="secondsToRetry" id="secondsToRetry" type="hidden" value="60" />
              <input name="textColor" id="textColor" type="hidden" value="#FF0000" />
              <input name="textOverColor" id="textOverColor" type="hidden" value="#00FF00" />
              <input name="infoTextColor" id="infoTextColor" type="hidden" value="#0000FF" />
-->
              <input name="bandwith" id="bandwith" type="hidden" value="0" />
              <input name="videoQuality" id="videoQuality" type="hidden" value="95" />
              <input name="keyFrameInterval" id="keyFrameInterval" type="hidden" value="15" />
              <input name="motionLevel" id="motionLevel" type="hidden" value="60" />
              <input name="motionTimeout" id="motionTimeout" type="hidden" value="1500" />
              <input name="useEchoSuppression" id="useEchoSuppression" type="hidden" value="on" />
              <input name="bufferTime" id="bufferTime" type="hidden" value="0" />
              
        	  <br/>	
              <script type="text/javascript">
			  	document.write("<input type=\"button\" name=\"submit\" id=\"submit\" value=\"Démarrer la visio\" onclick=\"startCall()\"/>");
			  </script>
              <noscript>
			  <input type="submit" name="submit" id="submit" value="Démarrer la visio" />
              <noscript>
      	  </form>
        </div>	
   </body>
</html>
