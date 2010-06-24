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

var lastMouseX;
var lastMouseY;

if(navigator.appName.substring(0,3) == "Net")
	document.captureEvents(Event.MOUSEMOVE);
document.onmousemove = mouseMoved;

function getMouseX(e){
	if (!e) var e = window.event;
  if(window.opera)                                               //OP6
  	return e.clientX;
  else if(document.all) {                                           //IE4,IE5,IE6
    //return document.documentElement.scrollLeft+e.clientX;
		return e.clientX;
	}
  else if(document.layers||document.getElementById)               //N4,N6,Moz
    return e.pageX;
}

function getMouseY(e){
	if (!e) var e = window.event;
  if(window.opera)                                                //OP6
  	return e.clientY;
  else if(document.all) {                                           //IE4,IE5,IE6
    //return document.documentElement.scrollTop+e.clientY;
		return e.clientY;
	}
  else if(document.layers||document.getElementById)               //N4,N6,Moz
    return e.pageY;
}


function mouseMoved(e)
{
	lastMouseX = getMouseX(e);
  lastMouseY = getMouseY(e);
}



/* =============================
	Profils utilisateurs
============================= */

var gProfilElm = null;
var	gProfilShowing = 0;

/* Initialisation */
function initUserProfil() {
	gProfilElm = document.getElementById('divUserProfil');
}

/* Affichage d'un profil */
function viewUser (type, id, i18nwaiting) {
	if (!gProfilElm)
		initUserProfil();

	//if (gProfilShowing)
	//	hideUser();

	x = lastMouseX;
	y = lastMouseY;
	var w = gProfilElm.offsetWidth; // largeur
	var windowWidth = getWindowWidth()-10;
	//alert ("x="+x+" / w="+w+" / wW="+windowWidth);
	if(x+w>windowWidth) x = windowWidth-w-7;
	gProfilElm.style.left=x+"px";
	gProfilElm.style.top=(y+16)+"px";
	gProfilElm.innerHTML = '<DIV ALIGN="CENTER">'+i18nwaiting+'<br><IMG SRC="'+getRessourcePathImg+'annuaire/spinner.gif" WIDTH="16" HEIGHT="16" BORDER="0" VSPACE="3" /><br></DIV>';
	gProfilElm.style.visibility = "visible";
	
	var url = getActionURL('annuaire|default|getUserProfil');
	var pars = 'type='+type+'&id='+id+'';
  var myAjax = new Ajax.Updater(
		{success: 'divUserProfil'},
    url,
    {method: 'get', parameters: pars, onComplete: userProfilResponse, onFailure: userProfilError}
  );
}

/* Masquage d'un profil */
function hideUser () {
	if(!gProfilElm)
		return false;
	gProfilShowing = 0;
	gProfilElm.style.visibility = "hidden";
	return false;
}

/* Résultat */
function userProfilResponse(originalRequest) {
	var h = gProfilElm.offsetHeight; // hauteur
	var windowHeight = getWindowHeight()-10;
	var scrollTop = (document.all) ? document.documentElement.scrollTop : 0;
	//alert ("y="+y+" / h="+h+" / wH="+windowHeight+" / scrollTop="+scrollTop);
	if (y>windowHeight) {	// On a scrollé
		// Comment savoir si ça va déborder ? (todo)
	} else {
		if(y+h>windowHeight) y = windowHeight-h-7;
	}
	//gProfilElm.style.width=w+"px";
	gProfilElm.style.top=(y+16+scrollTop)+"px";
	
	gProfilShowing = 1;
}

/* En cas d'erreur */
function userProfilError(request) {
	alert('Error userProfilError');
}



/* =============================
	Aid
============================= */

var gHelpElm = null;
var	gHelpShowing = 0;

/* Initialisation */
function initHelp() {
	gHelpElm = document.getElementById('divHelp');
}

/* Affichage d'une bulle d'aide */
function viewHelp (code) {
	if (!gHelpElm)
		initHelp();

	//if (gProfilShowing)
	//	hideUser();
	var obj = $(code);
	if (!obj) return;
	
	x = lastMouseX;
	y = lastMouseY;
	var w = gHelpElm.offsetWidth; // largeur
	var windowWidth = getWindowWidth()-10;
	//alert ("x="+x+" / w="+w+" / wW="+windowWidth);
	if(x+w>windowWidth) x = windowWidth-w-7;
	gHelpElm.style.left=x+"px";
	gHelpElm.style.top=(y+16)+"px";
	gHelpElm.style.visibility = "visible";
	
	gHelpElm.innerHTML = obj.innerHTML;
	
	var h = gHelpElm.offsetHeight; // hauteur
	var windowHeight = getWindowHeight()-10;
	var scrollTop = (document.all) ? document.documentElement.scrollTop : 0;
	//alert ("y="+y+" / h="+h+" / wH="+windowHeight+" / scrollTop="+scrollTop);
	if (y>windowHeight) {	// On a scrollé
		// Comment savoir si ça va déborder ? (todo)
	} else {
		if(y+h>windowHeight) y = windowHeight-h-7;
	}
	//gProfilElm.style.width=w+"px";
	gHelpElm.style.top=(y+16+scrollTop)+"px";
	
	gHelpShowing = 1;

}

/* Masquage d'un profil */
function hideHelp () {
	if(!gHelpElm)
		return false;
	gHelpShowing = 0;
	gHelpElm.style.visibility = "hidden";
	return false;
}

/* Masquage de ajaxDiv */
function hideAjaxDiv () {
	var div = $('ajaxDiv');
	div.style.visibility = "hidden";
	return false;
}


/* Fiche ecole */
function ajaxFicheEcole (id_ecole) {
	var div = $('ajaxDiv');
	div.style.width = "170px";
	x = lastMouseX;
	x -= 30;
	y = lastMouseY;
	var w = div.offsetWidth; // largeur
	var windowWidth = getWindowWidth()-10;
	//alert ("x="+x+" / w="+w+" / wW="+windowWidth);
	if(x+w>windowWidth) x = windowWidth-w-20;
	div.style.left=x+"px";
	div.style.top=(y+0)+"px";

	div.innerHTML = '<div align="center"><img src="'+getRessourcePathImg+'ajax-loader.gif" width="24" height="24" border="0" vspace="3" alt="loading" /></div>';
	div.style.visibility = "visible";
	var url = getActionURL('fichesecoles|default|ficheAjax');
	var pars = 'id='+id_ecole;
  var myAjax = new Ajax.Updater(
		{success: 'ajaxDiv'},
    url,
    {method: 'get', parameters: pars, onFailure :
				function (xmlHttp) {
					alert ("ajaxError / ajaxFicheEcole");
				}
		}
  );
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


function include(file) {
  var oScript = document.createElement("script");
  oScript.src = file;
  oScript.type = "text/javascript";
  document.body.appendChild(oScript);
}
function IncludeJavaScript(jsFile)
{
  document.write('<script type="text/javascript" src="'
    + jsFile + '"></scr' + 'ipt>'); 
}


// On l'utilise :

if (is_ie6) {
	IncludeJavaScript(urlBase+"js/iconito/ie6png.js");
	IncludeJavaScript(urlBase+"js/iconito/ie6fix.js");
}



// Active le datepicker pour un div
function setDatePicker (iDiv) {
	jQuery(iDiv).datepicker({showOn: 'both', buttonImage: '../../../js/jquery/images/datepicker/calendar.gif', buttonImageOnly: true, numberOfMonths: 3, showButtonPanel: true, appendText: '(JJ/MM/AAAA)', constrainInput: true});
}


