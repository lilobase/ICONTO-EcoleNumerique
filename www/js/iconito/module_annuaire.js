
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
		var url = getActionURL('annuaire|default|getPopup', 'field='+field);
    var name = "messagerie";
    var options = "height=500,width=540,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no";
    var a = window.open (url, name, options);
		if (a)
			a.focus();
}
function open_annuaire_profil (field, profil) {
		var url = getActionURL('annuaire|default|getPopup', 'field='+field+'&profil='+profil);
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





