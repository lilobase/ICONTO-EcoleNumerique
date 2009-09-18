
function change_classe (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
		form.submit();
}

function change_ecole (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
	var classe = getRef ("classe");
	if (classe && classe.selectedIndex>1) 
		classe.selectedIndex=0;
	form.submit();
}

function change_ville (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
	var ecole = getRef ("ecole");
	var classe = getRef ("classe");
	if (ecole && ecole.selectedIndex>1)
		ecole.selectedIndex=0;
	if (classe && classe.selectedIndex>1)
		classe.selectedIndex=0;
	form.submit();
}

function click_all (field, tab) {
	var logins = eval("tab_"+tab);
	for (var i=0, add=true, res="" ; i < logins.length; i++) {
		res = window.opener.add_destin (logins[i], field);
		if (res) add=false;
	}
	if (res)
		alert (res);
	self.close();
}

function open_annuaire (field) {
		var url = "index.php?module=annuaire&action=getPopup&field="+field+"";
    var name = "messagerie";
    var options = "height=500,width=540,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no";
    var a = window.open (url, name, options);
		if (a)
			a.focus();
}
function open_annuaire_profil (field, profil) {
		var url = "index.php?module=annuaire&action=getPopup&field="+field+"&profil="+profil;
    var name = "messagerie";
    var options = "height=500,width=540,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no";
    var a = window.open (url, name, options);
		if (a)
			a.focus();
}


// Regarde si un login est dans les destinataires
function is_login_in_destin (login, field) {
		//alert ("login="+login+" / field="+field);
    var dest = getRef (field);
		logins = dest.value.replace(/ /g,"");
    logins = logins.split(',');
    for (var i=0, trouve=false; !trouve && i < logins.length; i++) {
        if (login==logins[i])   trouve = true;
    }
    return trouve;
}

function click_destin (login, field) {
  //alert ("login="+login+" / field="+field);
    is =     is_login_in_destin (login, field);
		//alert ("is="+is);
    if (!is)    click = add_destin (login, field);
    else        click = del_destin (login, field);
		if (click) {
			alert (click);
			return false;
		}
}


// Ajoute un destinataire d'un MP. Renvoie une erreur ou claire ou rien si OK.
function add_destin (login, field) {
	limit = conf_minimail_limit_dest;
	var res = "";
  var dest = getRef (field);
	is =     is_login_in_destin (login, field);
	if (!is) {
    if (dest.value=="")     dest.value = login;
    else {
			logins = dest.value.replace(/ /g,"");
	    tab = logins.split(',');
			//alert (tab.length);
			if (tab.length>=limit)
				res = i18n_minimail_limit_dest+"\n";
			else
				dest.value += ', '+login;
		}                    
	}
	return res;
}


function del_destin (login, field) {
    var dest = getRef (field);
		logins = dest.value.replace(/ /g,"");
    logins = logins.split(',');
    var res = "";
    for (var i=0; i < logins.length; i++) {
        if (login != logins[i]) {
            if (res != "") res += ', ';
            res += logins[i];
        }
        dest.value = res;        
    }
}


/* Carte Google Map */
function loadGoogleMapsEcole (latitude, longitude, isAjax) {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById('googleMap'));
		map.setCenter(new GLatLng(latitude,longitude),14);
		//if (!isAjax) map.addControl(new GMapTypeControl()); // Map/sat/hybrid
		if (!isAjax) map.addControl(new GSmallMapControl());
		map.addOverlay(new GMarker(new GLatLng(latitude,longitude)));
	} else {
		alert('Probleme : votre navigateur n\'est pas compatible avec Google Maps');
	}
}


function ficheViewBlogs (ecoleId) {
	if ($('ficheblogs').innerHTML == '') {
		Element.toggle('ficheblogs');
		//$('ficheblogs').style.display = 'block';
		//$('ficheblogs').style.display
		$('ficheblogs').innerHTML = '<div align="center"><img src="'+getRessourcePathImg+'img/ajax-loader.gif" width="24" height="24" border="0" vspace="3" alt="loading" /></div>';
		var url = 'index.php';
		var pars = 'module=fichesecoles&action=blogs&id='+ecoleId;
	  var myAjax = new Ajax.Updater(
			{success: 'ficheblogs'},
	    url,
	    {method: 'get', parameters: pars }
	  );
		
	} else {
		//alert ('a');
		Element.toggle('ficheblogs');
	}
}



