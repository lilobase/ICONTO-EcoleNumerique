jQuery(document).ready(function($){
	/**********************************************************************/
	/*  Arborescence classeurs / dossiers                                 */
	/**********************************************************************/
	$('a.expand').live('click', function() {
	  
	  var id = $(this).attr('class').substring(7);
	  
	  // Récupération du li
    var li = $(this).parent();
    
    $.ajax({
      url:  getActionURL('classeur|default|sauvegardeEtatArbreDossiers'),
      global:  true,
      type: 'get',
      data: { id: id }
    });

    li.children('ul').toggle();
    li.toggleClass('collapsed');
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
  $('.move-content').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|deplacerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Error JS');
		}
		
		return false;
  });
  
  $('.delete-content').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|supprimerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Error JS');
		}
		
		return false;
  });
  
  $('.copy-content').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|copierContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Error JS');
		}
		
		return false;
  });
  
  $('.download-content').click (function() {
    
		nb_checked = $('#folder-content tbody :checked[name="dossiers[]"]').size() + $('#folder-content tbody :checked[name="fichiers[]"]').size();
		
		if (nb_checked > 0) {
		  
			var data = 'classeurId='+classeurId+'&dossierId='+dossierId;
			data += '&'+$('#folder-content tbody :checked[name="dossiers[]"]').serialize();
			data += '&'+$('#folder-content tbody :checked[name="fichiers[]"]').serialize();
			var url = getActionURL('classeur|default|telechargerContenu', data);
			self.location = url;
		} 
		else {
		  
			alert ('Error JS');
		}
		
		return false;
  });
});