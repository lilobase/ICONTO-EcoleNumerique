
function swapArticle (id) {
	var obj = getRef ('expand'+id); 
	if (obj)
		obj.className = (obj.className=='show') ? 'hidden' : 'show';
}

function goBlog (form, value) {
	if (form.go)
		form.go.value = value;
}

function change_format (obj) {
	var form = obj.form;
	var sel = '';
	/*	
	for (var i=0; i<form.format_bact.length; i++) {
   if (form.format_bact[i].checked)
	 	sel=form.format_bact[i].value;
	}
	alert (sel);
	*/
	if ( (form.sumary_bact!='' || form.content_bact!='') ) {
		return confirm (i18n_blog_change_format);
	}
	return true;
}


// Suppression d'un droit sur un membre
function deleteMembres () {
	var form = $("form");
		var trouve=false;
		for (var i=0 ; i < form.length && trouve==false ; i++)
			if (form[i].checked == true)
				trouve=true;
		if (trouve == false)
			alert (i18n_blog_check_members);
		else {
			if(confirm(i18n_blog_delete_droits))
				form.submit();
		}
}