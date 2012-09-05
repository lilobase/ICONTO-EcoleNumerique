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
<script type="text/javascript">var urlBase = '<?php echo CopixUrl::getRequestedScriptPath (); ?>'; getRessourcePathImg = urlBase+'<?php echo CopixURL::getResourcePath ('img/'); ?>/';</script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/jquery/jquery.tools.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/iconito/iconito.js"); ?>"></script>

<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/iconito/lang_".CopixI18N::getLang().".js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("flvplayer/ufo.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/jquery-1.4.4.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/jquery-ui-1.8.custom.min.js"); ?>"></script>

<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/fancybox/jquery.fancybox-1.3.4.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/fancybox/jquery.easing-1.3.pack.js"); ?>"></script>
<script type="text/javascript" src="<?php echo CopixUrl::getResource ("js/fancybox/jquery.mousewheel-3.0.4.pack.js"); ?>"></script>

<script src="<?php echo CopixUrl::getResource ("js/jquery/jquery.bgiframe.js" );  ?>" type="text/javascript"></script>
<script src="<?php echo CopixUrl::getResource ("js/jquery/jquery.tooltip.min.js"  );  ?>" type="text/javascript"></script>

<script type="text/javascript">
<?php
    $js = enic::get('javascript');
    echo $js->display();
?>
</script>

<script type="text/javascript">
$(document).ready(function(){
  $ = jQuery.noConflict();
/*
        var theme;
        // RMQ : getJSON use async mode and we need sync...
        $.ajax({
            url: '<?php echo CopixUrl::getResource ("theme.conf.json"); ?>',
            async: false,
            dataType: 'json',
            success: function (data) { theme = data; }
        });
        dim = theme.dimensions.STD;

        $('<style type="text/css" media="screen">#main-wrapper {width:'+dim.main_width+'px}</style>').appendTo('head');
        $('<style type="text/css" media="print">#main-wrapper {width:100%}</style>').appendTo('head');

        // AUTOMATIC RESIZING
        $('#left').css('width', dim.left_width);
        $('#left').css('margin-right', -dim.left_width);
        $('#right').css('width', dim.right_width);
        $('#right').css('margin-left', -dim.right_width);
        $('#content').css('margin-left', dim.left_width + dim.left_space);
        $('#content').css('margin-right', dim.right_width + dim.right_space);
*/

        $('.collapse').parent().each(function(){
            $(this).hide();
            if ($(this).is('#left'))	$('#content').css('margin-left', 0);
            if ($(this).is('#right'))	$('#content').css('margin-right', 0);
        });

        $('.kernel_dash > .dashpanel > .toolset').each(function(){
            $(this).parent().children('.content').css('min-height', $(this).height()-30);
        });

        $('.module_dash > .dashpanel > .toolset').each(function(){
            $(this).parent().children('.content').css('min-height', $(this).height()+80);
        });

        /* DASHBOARD TOOLSET BEHAVIOR */
/*
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

        $('.dashboard > .dashpanel > .toolset > ul > li').mouseover(function(){
            $(this).addClass('highlight');
        });
        $('.dashboard > .dashpanel > .toolset > ul > li').mouseout(function(){
            $(this).removeClass('highlight');
        });
*/
        $('.tools_left > .dashpanel > .toolset').bind('mouseenter mouseleave', function(event) {
              $(this).toggleClass('toolset-expand');
            $(this).children('ul').toggleClass('opacity50');
        });
        $('.tools_right > .dashpanel > .toolset').bind('mouseenter mouseleave', function(event) {
              $(this).toggleClass('toolset-expand');
        });
        $('.dashboard > .dashpanel > .toolset > ul > li').bind('mouseenter mouseleave', function(event) {
              $(this).toggleClass('highlight');
        });

        /* DASHBOARD BUTTON BEHAVIOR */
        $('.dashclose').mouseover(function () {
                $(this).addClass("dashclose_on");
        });
        $('.dashclose').mouseout(function () {
                $(this).removeClass("dashclose_on");
        });

        /* SUBMENU DROP DOWN PANEL BEHAVIOR */
/*
        $('#submenu > .menutab').hover(function(){
            $(this).parent().find('.menuitems').slideDown(100).show();
            $(this).parent().hover(function(){}, function(){
                $(this).parent().find('.menuitems').slideUp(400);
            });
        });
*/
        /* LOGIN BUTTON BEHAVIOR */
        $('.logout').mouseover(function () {
                $(this).addClass("logout_on");
        });
        $('.logout').mouseout(function () {
                $(this).removeClass("logout_on");
        });

        /* MODAL DIALOGS */
        $(function() {
            $('#dialog-message').dialog({
                modal: true,
                buttons: {
                    Ok: function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
        $(function() {
            $('#dialog').dialog({modal: true, resizable: false});
        });

        /* FANCY BOXES */
        $('a.fancybox').fancybox({
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'autoScale'       : true,
        'titleShow'       : false
        });

        $('a.fancyframe').fancybox({
                'width'				: '75%',
                'height'			: '75%',
                'autoScale'			: true,
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'type'				: 'iframe'
        });

        $('a.fancyframe-wfixed').fancybox({
                'width'				: 850
        });


        $('.viewuser').click(function(e) {
            var pos = $(this).offset();
            viewUserXY ($(this).attr('user_type'), $(this).attr('user_id'), '', pos.left, pos.top );
        });

        $('.evenement').tooltip({
            track: true,
            delay: 0,
            showURL: false,
            showBody: "\n$\n",
            fade: 0
        });

    new function($) {
      $.fn.setCursorPosition = function(pos) {
        if ($(this).get(0).setSelectionRange) {
          $(this).get(0).setSelectionRange(pos, pos);
        } else if ($(this).get(0).createTextRange) {
          var range = $(this).get(0).createTextRange();
          range.collapse(true);
          range.moveEnd('character', pos);
          range.moveStart('character', pos);
          range.select();
        }
      }
    }(jQuery);

    jQuery.fn.extend({
      insertAtCaret: function(myValue){
        return this.each(function(i) {
          if (document.selection) {
            this.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
            this.focus();
          } else if (this.selectionStart || this.selectionStart == '0') {
            var startPos = this.selectionStart;
            var endPos = this.selectionEnd;
            var scrollTop = this.scrollTop;
            this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
            this.focus();
            this.selectionStart = startPos + myValue.length;
            this.selectionEnd = startPos + myValue.length;
            this.scrollTop = scrollTop;
          } else {
            this.value += myValue;
            this.focus();
          }
        })
      }
    });






});

</script>
