
function change_classe (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
		form.submit();
}

function change_ecole (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
  if ($('select[name=classe]').val())
    $('select[name=classe]').val('');
	form.submit();
}

function change_ville (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
	var ecole = getRef ("ecole");
	var classe = getRef ("classe");
  if ($('select[name=classe]').val())
    $('select[name=classe]').val('');
  if ($('select[name=ecole]').val())
    $('select[name=ecole]').val('');
	form.submit();
}

function change_grville (obj,form) {
	if (obj.value=="" || obj.value=="0") return;
  if ($('select[name=classe]').val())
    $('select[name=classe]').val('');
  if ($('select[name=ecole]').val())
    $('select[name=ecole]').val('');
  if ($('select[name=ville]').val())
    $('select[name=ville]').val('');
	form.submit();
}

// right = le droit qu'il faudra vérifier à l'affichage des users
function open_annuaire (field, right) {
		var url = getActionURL('annuaire|default|getPopup', 'field='+field+'&right='+right);
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





