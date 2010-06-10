<?php
/*
	@file 		scripts.php
	@desc		JS scripts loader [adding JQuery support and events watchers]
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<script type="text/javascript">var urlBase = '<?php echo CopixUrl::getRequestedScriptPath (); ?>'; getRessourcePathImg = urlBase+'<?php echo CopixURL::getResourcePath ('images/'); ?>/';</script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/iconito/iconito.js"); ?>"></script>

<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/prototype-1.6.0.3.js"); ?>"></script>

<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/iconito/lang_".CopixI18N::getLang().".js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("flvplayer/ufo.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/jquery-1.4.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/jquery-ui-1.8.custom.min.js"); ?>"></script>


<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
	$(document).ready(function(){
		var theme;
		/* RMQ : getJSON use async mode and we need sync... */
		$.ajax({
			url: '<?php echo CopixUrl::getResource ("theme.conf.json"); ?>',
			async: false,
			dataType: 'json',
			success: function (data) { theme = data; }
		});
		dim = theme.dimensions.STD;
    
 		//$('#main-wrapper').css('width', dim.main_width);
    $('<style media="screen">#main-wrapper {width:'+dim.main_width+'px}</style>').appendTo('head');
    $('<style media="print">#main-wrapper {width:100%}</style>').appendTo('head');
    
		$('#left').css('width', dim.left_width);
		$('#left').css('margin-right', -dim.left_width);
		$('#right').css('width', dim.right_width);
		$('#right').css('margin-left', -dim.right_width);
		$('#content').css('margin-left', dim.left_width + dim.left_space);
		$('#content').css('margin-right', dim.right_width + dim.right_space);
		$('.collapse').parent().each(function(){
			$(this).hide();
			if ($(this).is('#left'))	$('#content').css('margin-left', 0);
			if ($(this).is('#right'))	$('#content').css('margin-right', 0);
		});
		
		$('.tools_left > .dashpanel > .toolset').each(function(){
			$(this).parent().children('.content').css('min-height', $(this).height()-30);
		});
		
		$('.tools_right > .dashpanel > .toolset').each(function(){
			$(this).parent().children('.content').css('min-height', $(this).height()+80);
		});
		
		$('.dashboard > .dashpanel > .toolset').mouseover(function(){
			$(this).addClass('toolset-expand');
		});
		
		$('.dashboard > .dashpanel > .toolset').mouseout(function(){
			$(this).removeClass('toolset-expand');
		});
		
		$('.tools_left > .dashpanel > .toolset').mouseover(function(){
			$(this).children('ul').removeClass('opacity50');
		});
		
		$('.tools_left > .dashpanel > .toolset').mouseout(function(){
			$(this).children('ul').addClass('opacity50');
		});
		
		$('.dashboard > .dashpanel > .toolset > ul > li').each(function(){
			$(this).mouseover(function(){
				$(this).addClass('highlight');
			});
			$(this).mouseout(function(){
				$(this).removeClass('highlight');
			});
		});

		$('.dashclose').mouseover(function () {
				$(this).addClass("dashclose_on");
		});		
		$('.dashclose').mouseout(function () {
				$(this).removeClass("dashclose_on");
		});
		
		$('.logout').mouseover(function () {
				$(this).addClass("logout_on");
		});		
		$('.logout').mouseout(function () {
				$(this).removeClass("logout_on");
		});		
	});
});

</script>
