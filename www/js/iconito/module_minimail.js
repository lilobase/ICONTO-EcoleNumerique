
/*
Un objet
*/
function get_ref(id)
{
  var obj;
  if (document.getElementById&&!document.all) {
    obj = document.getElementById(id);
	} else if (document.all) { //IE 4 et +
		obj = eval('document.all.'+id);
	}
  return obj;
}

// Suppression d'un message
function deleteMsgs () {
	var form = get_ref("form");
		var trouve=false;
		for (var i=0 ; i < form.length && trouve==false ; i++)
			if (form[i].checked == true)
				trouve=true;
		if (trouve == false)
			alert (i18n_minimail_check_messages);
		else {
			if(confirm(i18n_confirm_delete))
				form.submit();
		}
}


// Aperçu ou envoyer ?
function goMinimail (form, value) {
	if (form.go)
		form.go.value = value;
}
