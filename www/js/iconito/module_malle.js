


// Action sur les cases cochées
function actionChecked (action) {
	var trouve=false;
  var form = getRef ("form");
	for (var i=0 ; trouve==false && i < form.length ; i++)
		if (form[i].checked == true)
			trouve=true;
	if (trouve == false)
		alert (i18n_alert_check);
	return trouve;
}

function cocherElements (form, elements, checked) {
	var form = getRef (form);
	for (var i=0 ; i < form.length ; i++) {
		if (form[i].name == elements)
			form[i].checked = checked;
	}	
	/*
		if (form[i].checked == true)
			trouve=true;
			*/
}

function confirmDelete () {
	return confirm (i18n_confirm_delete);
}

function confirmRename () {
	return actionChecked ();
}

function confirmDownloadZip () {
	return actionChecked ();
}

//var current_url_doc;
function sendDocument (url, field, format, htmlDownload, htmlView, i18n_unsupportedFormat) {
	var popup = false;
  var form = $('form');
  var mode = html = '';
  if( form.mode[0].checked ) mode='view';
	if( form.mode[1].checked ) mode='download';
	
	
	switch (format) {
		case 'wiki' :
		  window.opener.current_url_doc = "[["+url+"|"+mode+"]]";
		  window.opener.bblink ('', field, 80);
			break;	
		
		case 'dokuwiki' :
		  window.opener.current_url_doc = "{{"+url+"|_"+mode+"_}}";
		  window.opener.bblink ('', field, 80);
			break;	
		
		case 'fckeditor' :
		case 'ckeditor' :
		case 'html' :
			if (mode == 'view') 					html = urldecode(htmlView);
			else if (mode == 'download')	html = urldecode(htmlDownload);
			if (format == 'fckeditor')
				window.opener.add_photo_fckeditor (field, html);
			else if (format == 'ckeditor')
				window.opener.add_photo_ckeditor (field, html);
			else
				window.opener.add_html (field, html);
			break;
		
		default :
			alert (i18n_unsupportedFormat);
			break;
	}
	
	if( ! form.multi.checked ) self.close();
}

function urldecode(ch) {
   ch = ch.replace(/[+]/g," ");
   return unescape(ch);
}

