jQuery(document).ready(function($){
	/* Options */
	if ($('#save-mode-disabled').is(':checked'))
		$('#selectDestFolder').hide();
	$('input[name="save-mode"]').change(function(){
		if ($('#save-mode-disabled').is(':checked'))
			$('#selectDestFolder').hide();
		else
			$('#selectDestFolder').show();
	});
	
	
	/**********************************************************************/
	/*  Arborescence classeurs / dossiers                                 */
	/**********************************************************************/
	$('a.expand-classeur').live('click', function() {
	  
		var id = $(this).attr('class').substring(16);
		
		// Fonction d'ouverture/fermeture d'un menu
		expand(this);
		
		$.ajax({
			url:  getActionURL('classeur|default|sauvegardeEtatArbreClasseurs'),
			global:  true,
			type: 'get',
			data: { id: id }
		});
		
		return false;
	});
	
	$('a.expand-folder').live('click', function() {
	  
		var id = $(this).attr('class').substring(14);
		
		// Fonction d'ouverture/fermeture d'un menu
		expand(this);
		
		$.ajax({
			url:  getActionURL('classeur|default|sauvegardeEtatArbreDossiers'),
			global:  true,
			type: 'get',
			data: { id: id }
		});
		
		return false;
	});
	
	
	// On ferme tous les enfants qui doivent l'être
	$('.closed').hide();
	
	// Dépliage des arborescences ouvertes
	$('.open').each(function() {
		expand($(this).children('a.expand'));
	});
	
	// Fonction d'ouverture/fermeture d'un menu
	function expand (lien) {
		var li = $(lien).parent('p').parent('li');
		var arrow = $(lien).children('img');
		
		li.children('ul').slideToggle(function (){
			var arrowImg = (li.hasClass('collapsed')) ? (urlBase+'themes/default/images/sort_right_off.png') : (urlBase+'themes/default/images/sort_down_off.png');
			var arrowAlt = (li.hasClass('collapsed')) ? '+' : '-';
			arrow.attr('src',arrowImg).attr('alt',arrowAlt);
		});
		li.toggleClass('collapsed');
	}
	
	// Sélection de la destination
	$('.selectFolder input[type="radio"]').css({'position':'absolute','top':'-10000px','left':'-10000px'});
	$('.selectFolder label').click(function() {
		$('.selectFolder p').removeClass('current');
		$(this).parent('p').addClass('current');
	});
	
	/**********************************************************************/
	/*  Contenu - Cochage / Décochage des dossiers & fichiers             */
	/**********************************************************************/	
	$('#check_all').live('click', function() {
	  
	  if ($('#check_all').is(':checked')) {
	    
	    $('#folder-content tbody :checkbox').attr('checked', 'checked');
	  }
	  else {
	    
	    $('#folder-content tbody :checkbox').removeAttr('checked');
	  }
	})
	
	$('.check').live('click', function() {
    
    checkAll();
  });
  
  function checkAll() 
  {
    var all_checkboxes = $('#folder-content tbody :checkbox').size();
    var all_checked    = $('#folder-content tbody :checkbox').filter(':checked').size();
    
    if (all_checkboxes == all_checked) {
      
      $('#check_all').attr('checked', 'checked');
    }
    else {
      
      $('#check_all').removeAttr('checked');
    }
  };
  
  /**********************************************************************/
	/*  Vue vignette - Tri                                                */
	/**********************************************************************/
	$('#order-column').live('change', function() {
	  
	  $('#order-content').submit();
	});
	
	$('#order-direction').live('change', function() {
	  
	  $('#order-content').submit();
	});
	
	/**********************************************************************/
	/*  Vue vignette - Sélection                                          */
	/**********************************************************************/
	$('#selectAllThumbs').click(function() {
		if ($(this).is(':checked'))
			$('#folder-content :checkbox').attr('checked', 'checked');
		else
			$('#folder-content :checkbox').removeAttr('checked');
	});
	$('#folder-content :checkbox').click(function(){
		var all_checkboxes = $('#folder-content :checkbox').size();
    	var all_checked    = $('#folder-content :checkbox').filter(':checked').size();
    	if (all_checkboxes == all_checked)
            $('#selectAllThumbs').attr('checked', 'checked');
        else
		    $('#selectAllThumbs').removeAttr('checked');
	});
  
  /**********************************************************************/
	/*  Actions de masse : suppression / déplacement / copie et download  */
  /**********************************************************************/
  $('.mass-actions .button-move').click (function() {
    
		nb_checked = $('#folder-content :checked[name="dossiers[]"]').size() + $('#folder-content :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		    
		  // La sélection contient-elle des casiers ?
		  var withLockers = false;
		  $('#folder-content :checked[name="dossiers[]"]').each(function(){
		    
		    if ($(this).data('locker')) {
		      
		      withLockers = true;
		      return false;
		    }
		  });
		    
		  if (withLockers) {
		        
		    if (confirm('Au moins un des dossiers sélectionnés correspond à un travail à rendre du cahier de texte, êtes-vous sûr de vouloir le déplacer ?')) {
		            
		      var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
      		data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
      		data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
      		var url = getActionURL('classeur|default|deplacerContenu', data);
      		self.location = url;
		    }
		  }
		  else {
		        
		    var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
    		data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
    		data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
    		var url = getActionURL('classeur|default|deplacerContenu', data);
    		self.location = url;
		  }
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à déplacer');
		}
		
		return false;
  });
  
  $('.mass-actions .button-delete').click (function() {
    
		nb_checked = $('#folder-content :checked[name="dossiers[]"]').size() + $('#folder-content :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
		  // La sélection contient-elle des casiers ?
		  var withLockers = false;
		  $('#folder-content :checked[name="dossiers[]"]').each(function(){
		        
		    if ($(this).data('locker')) {
		            
		      withLockers = true;
		      return false;
		    }
		  });
		  
		  if (withLockers) {
		    
		    if (confirm('Au moins un des dossiers sélectionnés correspond à un travail à rendre du cahier de texte, êtes-vous sûr de vouloir le supprimer ?')) {

  		    var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
    			data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
    			data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
    			var url = getActionURL('classeur|default|supprimerContenu', data);
    			self.location = url;
  		  }
		  }
		  else if (confirm('Etes-vous sûr de vouloir supprimer ces éléments ?')) {
		    
		    var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
  			data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
  			data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
  			var url = getActionURL('classeur|default|supprimerContenu', data);
  			self.location = url;
		  }
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à supprimer');
		}
		
		return false;
  });
  
  $('.mass-actions .button-copy').click (function() {
    
		nb_checked = $('#folder-content :checked[name="dossiers[]"]').size() + $('#folder-content :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|copierContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à copier');
		}
		
		return false;
  });
  
  $('.mass-actions .button-download').click (function() {
    
		nb_checked = $('#folder-content :checked[name="dossiers[]"]').size() + $('#folder-content :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|telechargerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à télécharger');
		}
		
		return false;
  });
});

function insertDocument (mode, url, image, field, format, htmlDownload, htmlView, i18n_unsupportedFormat, idFile, nomFile, extension, align, size) {
  
  var popup = false;
  var html = '';
  var typeFile = 'MOD_CLASSEUR';
  var pictureTypes = ["PNG", "png", "JPG", "jpg", "gif", "GIF"];
  
  switch (format) {
    case 'wiki' :
      self.parent.current_url_doc = "[["+url+"|"+mode+"]]";
      self.parent.bblink ('', field, 80);
      break;

    case 'dokuwiki' :
      self.parent.current_url_doc = "{{"+url+"|_"+mode+"_}}";
      self.parent.bblink ('', field, 80);
      break;

    case 'fckeditor' :
    case 'ckeditor' :
    case 'html' :
      if (mode == 'view') 					html = urldecode(htmlView);
      else if (mode == 'download')	html = urldecode(htmlDownload);
      
      if (jQuery.inArray(extension, pictureTypes) > -1 && mode == 'view') {
        
        var html = '<img alt="'+nomFile+'" border="0" src="'+image+size+'.'+extension+'"';
  			  if 			(align == 'L')	html += ' align="left"';
  			  else if (align == 'R')	html += ' align="right"';
  			  html += '/>';
      }
      
      if (align == 'C') html = '<p style="text-align: center">'+html+'</p>';
      
      if (format == 'fckeditor')
        self.parent.add_photo_fckeditor (field, html);
      else if (format == 'ckeditor')
        self.parent.add_photo_ckeditor (field, html);
      else
        self.parent.add_html (field, html);
      break;

    //return only url
    case 'text':
      self.parent.add_text(field, url);
      break;

    // Retourne l'identifiant du fichier (ainsi que le type du module dont il provient)
    case 'id':
      self.parent.add_node(field, typeFile, idFile, nomFile);
      break;

    default :
      // alert (i18n_unsupportedFormat);
      break;
  }
}

function urldecode(ch) {
  
  ch = ch.replace(/[+]/g," ");
  return unescape(ch);
}