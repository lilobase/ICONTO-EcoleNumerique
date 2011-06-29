
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

imageTag = false;

// Replacement for arrayname.length property
function getarraysize(thearray) {
	for (i = 0; i < thearray.length; i++) {
		if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
			return i;
		}
	return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
	thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
	thearraysize = getarraysize(thearray);
	retval = thearray[thearraysize - 1];
	delete thearray[thearraysize - 1];
	return retval;
}


function bbfontstyle(formObj, field, bbopen, bbclose) {
	var txtarea = getRef(field);
	if ((parseInt(navigator.appVersion) >= 4) && (navigator.appName == "Microsoft Internet Explorer")) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			txtarea.value += bbopen + bbclose;
			txtarea.focus();
			return;
		}
		document.selection.createRange().text = bbopen + theSelection + bbclose;
		txtarea.focus();
		return;
	} else {
		txtarea.value += bbopen + bbclose;
		txtarea.focus();
		return;
	}
}


function bbcar (formObj, field, bbnumber) {
	theSelection = false;
  var txtarea = getRef(field);

    // Get text selection
    if (txtarea.createTextRange) { // IE
        theSelection = document.selection.createRange().text; // Get text selection
    } else {
        objectValue = txtarea.value;
        objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
        objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
        theSelection = objectValue.substring( txtarea.selectionStart ,txtarea.selectionEnd );
    }    

	if (theSelection) {
		// Add tags around selection
    if (txtarea.createTextRange) {// IE
      document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
      txtarea.focus();
    } else {
      plus = bbcars[bbnumber];
      txtarea.value = objectValueDeb+plus+objectValueFin;
      txtarea.setSelectionRange (objectValueDeb.length+plus.length,objectValueDeb.length+plus.length);
      txtarea.focus();
			// testtxtarea.selectionStart = txtarea.selectionEnd = objectValueDeb.length+plus.length;
		}
		theSelection = '';
		return;
	}

		text = bbcars[bbnumber];
		if (txtarea.createTextRange && txtarea.caretPos) {	// Insertion IE
			var caretPos = txtarea.caretPos;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
      txtarea.focus();
		} else {
	    objectValue = txtarea.value;
  	  objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
    	objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	    txtarea.value = objectValueDeb+text+objectValueFin;
    	txtarea.setSelectionRange (objectValueDeb.length+text.length,objectValueDeb.length+text.length);
      txtarea.focus();
		}

		txtarea.focus();
		return;
	
}

function bbstyle(formObj, field, bbnumber) {

	donotinsert = false;
	theSelection = false;
	bblast = 0;
  var txtarea = getRef(field);

	if (bbnumber == -1) { // Close all open tags & default button names
		while (bbcode[0]) {
			butnumber = arraypop(bbcode) - 1;
			txtarea.value += bbtags[butnumber + 1];
			//buttext = eval('formObj.addbbcode' + butnumber + '.value');
			//eval('formObj.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
		}
		txtarea.focus();
		return;
	}
	if ((clientVer >= 4) && is_ie && is_win)
  {
  	theSelection = document.selection.createRange().text; // Get text selection
    if (theSelection) {
    	// Add tags around selection
      document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
      txtarea.focus();
      theSelection = '';
      return;
    }
  }
  else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))	// Si sélection
	{
  	pos = mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
		txtarea.selectionStart = pos;
		txtarea.selectionEnd = pos;
		txtarea.focus();
		//txtarea.selectionStart = 3;
  	return;
  }	

	
	// Find last occurance of an open tag the same as the one just clicked
	for (i = 0; i < bbcode.length; i++) {
		if (bbcode[i] == bbnumber+1) {
			bblast = i;
			donotinsert = true;
		}
	}

	if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
		//alert ("donotinsert");
		while (bbcode[bblast]) {
				butnumber = arraypop(bbcode) - 1;

				text = bbtags[butnumber + 1];
	
				if ((clientVer >= 4) && is_ie && is_win) {	// Insertion IE
					var caretPos = txtarea.caretPos;
					caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
				} else if (txtarea.selectionStart == txtarea.selectionEnd && txtarea.selectionEnd != undefined) {
	    		objectValue = txtarea.value;
  	  		objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
    			objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	    		txtarea.value = objectValueDeb+text+objectValueFin;
    			txtarea.setSelectionRange (objectValueDeb.length+text.length,objectValueDeb.length+text.length);
				} else {		// Safari MAC
	 				//alert ("Mac / text="+text);
					//return;
					txtarea.value += text;
	 			}
				
				//txtarea.value += bbtags[butnumber + 1];
				//buttext = eval('formObj.addbbcode' + butnumber + '.value');
				//eval('formObj.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
				imageTag = false;
		}
		txtarea.focus();
		return;

	} else { // Open tags
		if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
			txtarea.value += bbtags[15];
			lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
			formObj.addbbcode14.value = "image";	// Return button back to normal state
			imageTag = false;
		}
		
		// Open tag
		// Ouverture d'un tag sans sélection, on insère à l'emplacement courant
		
		text = bbtags[bbnumber];

		if ((clientVer >= 4) && is_ie && is_win)
   	{
			var caretPos = txtarea.caretPos;
			//alert ("a");
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
      txtarea.focus();
   	}
   	else if (txtarea.selectionStart == txtarea.selectionEnd && txtarea.selectionEnd != undefined)
   	{
			//alert ("b"+txtarea.selectionStart);
	    objectValue = txtarea.value;
  	  objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
    	objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	    txtarea.value = objectValueDeb+text+objectValueFin;
    	txtarea.setSelectionRange (objectValueDeb.length+text.length,objectValueDeb.length+text.length);
      txtarea.focus();

   	} else {		// Safari MAC
	 		//alert ("Mac / text="+text);
			//return;
			txtarea.value += text;
	 	}

	
		/*if (txtarea.createTextRange && txtarea.caretPos) {	// Insertion IE
			var caretPos = txtarea.caretPos;
			alert ("a");
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
      txtarea.focus();
		} else {
			alert ("b");
	    objectValue = txtarea.value;
  	  objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
    	objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	    txtarea.value = objectValueDeb+text+objectValueFin;
    	txtarea.setSelectionRange (objectValueDeb.length+text.length,objectValueDeb.length+text.length);
      txtarea.focus();
		}
		*/

		if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
		arraypush(bbcode,bbnumber+1);
		//eval('formObj.addbbcode'+bbnumber+'.value += "*"');
		txtarea.focus();
		return;
	}

}	
	
	
	
	

// From http://www.massless.org/mozedit/
// Renvoie la position
function mozWrap(txtarea, open, close)
{
   var selLength = txtarea.textLength;
   var selStart = txtarea.selectionStart;
   var selEnd = txtarea.selectionEnd;
   if (selEnd == 1 || selEnd == 2)
      selEnd = selLength;

   var s1 = (txtarea.value).substring(0,selStart);
   var s2 = (txtarea.value).substring(selStart, selEnd)
   var s3 = (txtarea.value).substring(selEnd, selLength);
   txtarea.value = s1 + open + s2 + close + s3;
   return (s1 + open + s2 + close).length;
} 


// Remplace la sélection par un autre texte
function mozWrapReplace(txtarea, txt)
{
   var selLength = txtarea.textLength;
   var selStart = txtarea.selectionStart;
   var selEnd = txtarea.selectionEnd;
   if (selEnd == 1 || selEnd == 2)
      selEnd = selLength;

   var s1 = (txtarea.value).substring(0,selStart);
   var s2 = (txtarea.value).substring(selStart, selEnd)
   var s3 = (txtarea.value).substring(selEnd, selLength);
   txtarea.value = s1 + txt + s3;
   return;
} 


// S'occupe d'insérer
function bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link) {

			if ((clientVer >= 4) && is_ie && is_win && theSelection) {	// Sélection IE
				document.selection.createRange().text = final_link;
   		  theSelection = '';
			} else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))	{ // Sélection autre
  			mozWrapReplace(txtarea, final_link);
  		} else if (txtarea.selectionStart == txtarea.selectionEnd && txtarea.selectionEnd != undefined) { // Insertion
        txtarea.value = objectValueDeb+final_link+objectValueFin;
        txtarea.setSelectionRange (objectValueDeb.length+final_link.length,objectValueDeb.length+final_link.length);
			} else if (txtarea.caretPos) {
				var caretPos = txtarea.caretPos;
	      caretPos.text = final_link;
    	} else {
				//alert ("Mac");
				txtarea.value += final_link;
			}
 		  txtarea.focus();

}

var current_url_doc;
// swirlee's bblink hack, slightly corrected
function bblink(formObj, field, bbnumber) {

	donotinsert = false;
	theSelection = false;
  var txtarea = getRef(field);


	if (bbnumber==10) 	    current_url = prompt("Saisissez l'adresse email","");
	else if (bbnumber==14)   current_url = prompt("Saisissez le nom d'utilisateur (login)","");
	else if (bbnumber==18)   current_url = prompt("Saisissez la couleur (en hexadécimal)","");
	else if (bbnumber==20)   current_url = prompt(i18n_wiki_h1,"");
	else if (bbnumber==22)   current_url = prompt(i18n_wiki_h2,"");
	else if (bbnumber==24)   current_url = prompt(i18n_wiki_h3,"");
	else if (bbnumber==80)   current_url = current_url_doc;
  else current_url = prompt(i18n_wiki_url,"http://");
	
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

	if(bbnumber == 8) {	// Lien
		current_link_text = unescape(prompt(i18n_wiki_link,""));
		final_link = construct_link (current_url, current_link_text);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}
	
	else if(bbnumber == 10) {	// Email
		current_link_text = unescape(prompt(i18n_wiki_link,""));
		final_link = construct_email (current_url, current_link_text);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}
	
	else if(bbnumber == 12) {	// Image
		final_image = construct_image (current_url);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_image);
  }

	/*else if(bbnumber == 14) {	// Login
		final_link = '[login=' + current_url + ']';
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
    }*/

	/*else if(bbnumber == 18) {	// Couleur
		current_link_text = unescape(prompt("Texte à écrire en couleur",""));
		if((current_link_text == null) || (current_link_text == "") || (current_link_text==current_url)) {
		} else {
			final_link = '[couleur=' + current_url + ']' + current_link_text + '[/couleur]';
		}
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}*/

	else if(bbnumber == 20) {	// H1
		final_link = construct_h1 (current_url);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}
	else if(bbnumber == 22) {	// H2
		final_link = construct_h2 (current_url);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}
	else if(bbnumber == 24) {	// H3
		final_link = construct_h3 (current_url);
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}
  else if(bbnumber == 80) {	// Lien téléchargeable
		final_link = '\n' + current_url + '';
		bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, final_link);
	}

}




// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

function set_curseur_debut (textEl, field) {
  var txtarea = getRef(textEl);
  var message = getRef(field);
  if (txtarea.createTextRange) {
    txtarea.focus(); 
    storeCaret(message);
  } else {
    txtarea.focus(); 
    txtarea.setSelectionRange (0,0);  
  }
}



function add_photo_fckeditor (field, txt) {
	var oEditor = FCKeditorAPI.GetInstance(field);
	oEditor.InsertHtml(txt);
}

function add_photo_ckeditor (field, txt) {
	if (oEditor = CKEDITOR.instances[field])
		oEditor.insertHtml(txt);
}


function add_html (field, txt) {

	donotinsert = false;
	theSelection = false;
	var txtarea = getRef(field);

	if (txt==null) return;
	
	if (0 && txtarea.createTextRange) {	// Sélection IE
	} else {
		objectValue = txtarea.value;
		objectValueDeb = objectValue.substring( 0 , txtarea.selectionStart );
		objectValueFin = objectValue.substring( txtarea.selectionEnd , txtarea.textLength );
	} 
	
	if ((clientVer >= 4) && is_ie && is_win)
  	theSelection = document.selection.createRange().text; // Get text selection

	bblink_add (txtarea, objectValue, objectValueDeb, objectValueFin, txt+'\n');

}

function add_node (field, typeFile, idFile, nomFile) {
  
  jQuery('.'+field).append('<li><input type="hidden" name="'+field+'[]" value="'+typeFile+'-'+idFile+'" /><span>'+nomFile+'</span> <a href="#" class="delete-node">X</a></li>');
}