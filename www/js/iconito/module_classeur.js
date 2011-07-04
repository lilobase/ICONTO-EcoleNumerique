jQuery(document).ready(function($){
	/**********************************************************************/
	/*  Arborescence classeurs / dossiers                                 */
	/**********************************************************************/
	$('a.expand').live('click', function() {
	  
		var id = $(this).attr('class').substring(7);
		
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
	$('.classeur input[type="radio"]').hide();
	$('.classeur label').click(function() {
		$('.classeur p').removeClass('current');
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
	/*  Actions de masse : suppression / déplacement / copie et download  */
	/**********************************************************************/
  $('.mass-actions .button-move').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|deplacerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à déplacer');
		}
		
		return false;
  });
  
  $('.mass-actions .button-delete').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|supprimerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à supprimer');
		}
		
		return false;
  });
  
  $('.mass-actions .button-copy').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|copierContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à copier');
		}
		
		return false;
  });
  
  $('.mass-actions .button-download').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|telechargerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Erreur : aucun fichier/dossier à télécharger');
		}
		
		return false;
  });
});

function insertDocument (mode, url, field, format, htmlDownload, htmlView, i18n_unsupportedFormat, idFile, nomFile) {
  
  var popup = false;
  var html = '';
  var typeFile = 'MOD_CLASSEUR';
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
      alert (i18n_unsupportedFormat);
      break;
  }
}

function urldecode(ch) {
  
  ch = ch.replace(/[+]/g," ");
  return unescape(ch);
}