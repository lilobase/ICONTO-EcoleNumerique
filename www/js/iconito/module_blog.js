
function swapArticle (id) {
  $('#expand'+id).toggle();
}

function goBlog (form, value) {
	if (form.go)
		form.go.value = value;
}

function change_format (obj) {
	var form = obj.form;
	var sel = '';
	if ( (form.sumary_bact!='' || form.content_bact!='') ) {
		return confirm (i18n_blog_change_format);
	}
	return true;
}


// Suppression d'un droit sur un membre
function deleteMembres () {
  nb_checked = $('form#form input[type=checkbox]:checked').size();
  if (!nb_checked)
    alert (i18n_blog_check_members);
  else {
    $('form#form').submit();
  }
}


/* Vérification de la hauteur des blocs et modification de l'apparence si besoin */
$(document).ready(function(){
	
	sidebarHeight = $('#blog-sidebar').height();
	contentHeight = $('#blog-content').height();
	if (sidebarHeight > contentHeight)
		$('#blog-content').css('height', sidebarHeight);
	
});