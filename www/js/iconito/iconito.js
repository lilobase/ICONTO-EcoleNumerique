/**
 * Author: Pontus Lundwall (dr-m@spray.se)
 * Version: $Id: iconito.js,v 1.14 2008-11-10 14:05:34 cbeyer Exp $
 */

// Detect client browser:
// Code origin: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html

var conf_minimail_limit_dest = 50; // Nb max de destin d'1 minimail

var agt=navigator.userAgent.toLowerCase();

var is_major = parseInt(navigator.appVersion);
var is_minor = parseFloat(navigator.appVersion);

var is_nav = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1)
			&& (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)
			&& (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
var is_nav2 = (is_nav && (is_major == 2));
var is_nav3 = (is_nav && (is_major == 3));
var is_nav4 = (is_nav && (is_major == 4));
var is_nav4up = (is_nav && (is_major >= 4));
var is_navonly = (is_nav && ((agt.indexOf(";nav") != -1) ||
					  (agt.indexOf("; nav") != -1)) );
var is_nav6 = (is_nav && (is_major == 5));
var is_nav6up = (is_nav && (is_major >= 5));
var is_gecko = (agt.indexOf('gecko') != -1);

var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
var is_ie3 = (is_ie && (is_major < 4));
var is_ie4 = (is_ie && (is_major == 4) && (agt.indexOf("msie 4")!=-1) );
var is_ie4up = (is_ie && (is_major >= 4));
var is_ie5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")!=-1) );
var is_ie5_5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.5") !=-1));
var is_ie5up = (is_ie && !is_ie3 && !is_ie4);
var is_ie5_5up =(is_ie && !is_ie3 && !is_ie4 && !is_ie5);
var is_ie6 = (is_ie && (is_major == 4) && (agt.indexOf("msie 6.")!=-1) );
var is_ie6up = (is_ie && !is_ie3 && !is_ie4 && !is_ie5 && !is_ie5_5);

var is_opera = (agt.indexOf("opera") != -1);
var is_opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
var is_opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
var is_opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
var is_opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
var is_opera6 = (agt.indexOf("opera 6") != -1 || agt.indexOf("opera/6") != -1);
var is_opera5up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4);
var is_opera6up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4 && !is_opera5);

var is_mozilla = (navigator.userAgent.toLowerCase().indexOf('gecko')!=-1) ? true : false

var is_win   = ( (agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1) );
var is_win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));
var is_win16 = ((agt.indexOf("win16")!=-1) || 
           (agt.indexOf("16bit")!=-1) || (agt.indexOf("windows 3.1")!=-1) || 
           (agt.indexOf("windows 16-bit")!=-1) );  
var is_win31 = ((agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("win16")!=-1) ||
                (agt.indexOf("windows 16-bit")!=-1));
var is_winme = ((agt.indexOf("win 9x 4.90")!=-1));
var is_win2k = ((agt.indexOf("windows nt 5.0")!=-1));
var is_win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
var is_winnt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));
var is_win32 = (is_win95 || is_winnt || is_win98 || 
                ((is_major >= 4) && (navigator.platform == "Win32")) ||
                (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));
var is_os2   = ((agt.indexOf("os/2")!=-1) || 
                (navigator.appVersion.indexOf("OS/2")!=-1) ||   
                (agt.indexOf("ibm-webexplorer")!=-1));
var is_mac    = (agt.indexOf("mac")!=-1);
if (is_mac && is_ie5up) is_js = 1.4;
var is_mac68k = (is_mac && ((agt.indexOf("68k")!=-1) || 
                           (agt.indexOf("68000")!=-1)));
var is_macppc = (is_mac && ((agt.indexOf("ppc")!=-1) || 
                            (agt.indexOf("powerpc")!=-1)));


function getRef(id)
{   

var obj;
  if (document.getElementById&&!document.all) {
    obj = document.getElementById(id);
	} else if (document.all) { //IE 4 et +
		obj = eval('document.all.'+id);
	} else {
	}
  return obj;
}

function openWindow (winName, winUrl, winWidth, winHeight) {
    var winOptions  = "toolbar=yes,"
                            + "location=no,"
                            + "directories=no,"
                            + "status=no,"
                            + "alwaysRaised=yes,"
                            + "menubar=no,"
                            + "scrollbars=yes,"
                            + "resizable=yes,"
                            + "copyhistory=no,"
                            + "width=" + winWidth + ","
                            + "height=" + winHeight;
 	var newWin = window.open(winUrl, winName, winOptions);
	newWin.focus();
	
} 



// Source : http://pompage.net/pompe/pieds/
function getWindowHeight() {
    var windowHeight=0;
    if (typeof(window.innerHeight)=='number') {
        windowHeight=window.innerHeight;
    }
    else {
     if (document.documentElement&&
       document.documentElement.clientHeight) {
         windowHeight = document.documentElement.clientHeight;
    }
    else {
     if (document.body&&document.body.clientHeight) {
         windowHeight=document.body.clientHeight;
      }
     }
    }
    return windowHeight;
}

// Source : http://pompage.net/pompe/pieds/
function getWindowWidth() {
    var windowWidth=0;
    if (typeof(window.innerWidth)=='number') {
        windowWidth=window.innerWidth;
    }
    else {
     if (document.documentElement&&
       document.documentElement.clientWidth) {
         windowWidth = document.documentElement.clientWidth;
    }
    else {
     if (document.body&&document.body.clientWidth) {
         windowWidth=document.body.clientWidth;
      }
     }
    }
    return windowWidth;
}



function viewUserXY (type, id, i18nwaiting, x, y ) {
  $('#divUserProfil').css('top', y+12);
  $('#divUserProfil').css('left', x);
  w = 150;
  h = 100;
  wWindow = ($.browser.msie) ? $(window).width() : window.innerWidth;
  hWindow = ($.browser.msie) ? $(window).height() : window.innerHeight;
  $('#divUserProfil').css('width', '50px');
  $('#divUserProfil').html('<DIV ALIGN="CENTER"><IMG SRC="'+getRessourcePathImg+'annuaire/spinner.gif" WIDTH="16" HEIGHT="16" BORDER="0" /></DIV>');
  $.ajax({
    url: getActionURL('annuaire|default|getUserProfil'),
    data: 'type='+type+'&id='+id,
    success: function(data) {
      $('#divUserProfil').hide();
      $('#divUserProfil').css('width', 150+'px');
      $('#divUserProfil').html(data);
      h = $('#divUserProfil').height();
      if (x+w+10 >= wWindow) {
        $('#divUserProfil').css('left', wWindow-w-35);
      }
      if (y+h+10 >= hWindow) {
        $('#divUserProfil').css('top', hWindow-h-35);
      }
      $('#divUserProfil').show();
    }
  });
  $('#divUserProfil').show();
}

/* Masquage d'un profil */
function hideUser () {
  $('#divUserProfil').hide();
	return false;
}


/* Masquage de ajaxDiv */
function hideAjaxDiv () {
	$('ajaxDiv').hide();
	return false;
}

var module = 'default';

function getActionURL (action, data) {
  var parts = action.split('|');
  var url = urlBase + 'index.php/' + $pick(parts[0], module) + '/' + $pick(parts[1], 'default') + '/' + parts[2];
  if(data) {
    //url += (url.contains('?') ? '&' : '?') + Object.toQueryString(data);
    url += (url.indexOf('?') > -1 ? '&' : '?') + data;
  }
  return url;
}

function $defined(obj){return(obj!=undefined);};
function $pick(obj,picked){return $defined(obj)?obj:picked;};


// Active le datepicker pour un div
function setDatePicker (iDiv) {
	jQuery(iDiv).datepicker({showOn: 'both', buttonImage: '../../../js/jquery/images/datepicker/calendar.gif', buttonImageOnly: true, numberOfMonths: 3, showButtonPanel: true, appendText: '(JJ/MM/AAAA)', constrainInput: true});
}



$(document).ready(function($){
    
    $('a.fancyboxClose').live('click',function(e){
        e.preventDefault();
        parent.jQuery.fancybox.close();
    });
    
});


Array.prototype.inArray = function(p_val) {
    var l = this.length;
    for(var i = 0; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}

