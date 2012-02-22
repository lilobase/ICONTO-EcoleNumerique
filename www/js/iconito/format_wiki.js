
bbcode = new Array ();
bbtags = new Array ('__','__','\'\'','\'\'','__','__','[citation]','[/citation]','[lien]','[/lien]','[email]','[/email]','','','','','[equipe]','[/equipe]', '', '', '[barre]', '[/barre]','[classement]','[/classement]','[liste]','[/liste]', '[[', ']]');
bbcars = new Array ('«', '»', 'œ', '\n[PAGE]\n', '\n====\n');

// Formatage d'un lien
function construct_link (current_url, current_link_text) {
	if((current_link_text == null) || (current_link_text == "") || (current_link_text==current_url)) {
		final_link = '[' + current_url + ']';
	} else {
		final_link = '[' + current_link_text + '|' + current_url + ']';
	}
	return final_link;
}

// Formatage d'un email
function construct_email (current_url, current_link_text) {
	if((current_link_text == null) || (current_link_text == "") || (current_link_text==current_url)) {
		final_link = '[email]' + current_url + '[/email]';
	} else {
		final_link = '[email=' + current_url + ']' + current_link_text + '[/email]';
	}
	return final_link;
}

// Formatage d'une image
function construct_image (current_url) {
	final_image = '((' + current_url + '))';
	return final_image;
}


// Formatage des titres
function construct_h1 (txt) {
	final_txt = '\n!!!' + txt + '\n';
	return final_txt;
}
function construct_h2 (txt) {
	final_txt = '\n!!' + txt + '\n';
	return final_txt;
}
function construct_h3 (txt) {
	final_txt = '\n!' + txt + '\n';
	return final_txt;
}
		

function add_photo (field, current_url, popup) {

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

	final_image = '((' + current_url + '))';
	if( popup ) {
		final_image = '['+final_image+'|'+popup+'|||_blank]';
	}	

	bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_image+'\n');

}	
		
		