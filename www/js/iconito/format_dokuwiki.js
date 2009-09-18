
bbcode = new Array ();
bbtags = new Array ('**','**','//','//','__','__','[citation]','[/citation]','[lien]','[/lien]','[email]','[/email]','','','','','[equipe]','[/equipe]', '', '', '[barre]', '[/barre]','[classement]','[/classement]','[liste]','[/liste]', '[[', ']]');
bbcars = new Array ('«', '»', 'œ', '\n[PAGE]\n', '\n----\n', '  * ', '  - ');

// Formatage d'un lien
function construct_link (current_url, current_link_text) {
	if((current_link_text == null) || (current_link_text == "") || (current_link_text==current_url)) {
		final_link = '[[' + current_url + ']]';
	} else {
		final_link = '[[' + current_url + '|' + current_link_text + ']]';
	}
	return final_link;
}

// Formatage d'un email
function construct_email (current_url, current_link_text) {
	if((current_link_text == null) || (current_link_text == "") || (current_link_text==current_url)) {
		final_link = '[[' + current_url + ']]';
	} else {
		final_link = '[[' + current_url + '|' + current_link_text + ']]';
	}
	return final_link;
}

// Formatage d'une image
function construct_image (current_url) {
	final_image = '{{' + current_url + '}}';
	return final_image;
}


// Formatage des titres
function construct_h1 (txt) {
	final_txt = '\n====== ' + txt + ' ======\n';
	return final_txt;
}
function construct_h2 (txt) {
	final_txt = '\n===== ' + txt + ' =====\n';
	return final_txt;
}
function construct_h3 (txt) {
	final_txt = '\n==== ' + txt + ' ====\n';
	return final_txt;
}

// Ajout d'une photo d'un album
// popup = false ou lien vers la photo a ouvrir
function add_photo (field, current_url, title, align, popup) {

	donotinsert = false;
	theSelection = false;
	var txtarea = getRef(field);

	if (current_url==null) return;
	var re = new RegExp ('http%3A//', 'gi') ;
	var current_url = current_url.replace(re, 'http://') ;
	if((current_url == 'null') || (current_url == "http://")) {
		current_url = "";
		return;
	}
	
	if (0 && txtarea.createTextRange) {	// Sélection IE
	} else {
		objectValue = txtarea.value;
		objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
		objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	} 
	
	if ((clientVer >= 4) && is_ie && is_win)
  	theSelection = document.selection.createRange().text; // Get text selection
	if (align == 'L') current_url = current_url+' ';
	else if (align == 'R') current_url = ' '+current_url;
	else if (align == 'C') current_url = ' '+current_url+' ';
	
	final_image = '{{'+current_url+'|'+title+'}}';
	if( popup ) {
		final_image = '[['+popup+'|'+final_image+']]';
	}	

	bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_image+'\n');

}