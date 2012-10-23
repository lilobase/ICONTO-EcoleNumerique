/**
 * Prépare les filtres de gestion d'affectation
 */
function prepareAssignmentsManagementFilter(filterAndDisplay, cityFilter, schoolFilter, classFilter, classLevelFilter) {
  
  var filterAndDisplayUrl = filterAndDisplay;
  var cityFilterUrl = cityFilter;
  var schoolFilterUrl = schoolFilter;
  var classFilterUrl = classFilter;
  var classLevelFilterUrl = classLevelFilter;
  
  
  // Onglets
  $('#origin').tabs();
  
  // Au changement d'onglet on traite les valeurs pour que la recherche concorde avec les résultats
	$('.originTab a').click(function(){
		if ($(this).attr('href') == '#originName')
			$('#search-mode').val('byName');
		else
			$('#search-mode').val('byStructure');
		$('#filter-form').submit();
	});

	// Le changement de type doit être effectif pour les deux recherches d'origine
	$('#origin_usertype_search, #origin_usertype').change(function() {
		var selectType = $(this).val();
		$('#origin_usertype_search').val(selectType);
		$('#origin_usertype').val(selectType);
	});

  // Modification de l'année scolaire pour la classe d'origine, rafraichissement de la liste des classes
  $('#origin select[name="origin_grade"]').change(function(){
  
    $('#origin-class').empty();
    $('#assignments').empty();
  
    if ($('#origin [name="origin_school"]').val()) {
    
      $.ajax({
        url: classFilterUrl,
        data: ({grade: $(this).val(), school_id: $('#origin [name="origin_school"]').val(), with_label: 1, with_empty: 1, label_empty: "Toutes", name: "origin_classroom", all: 1}),
        success: function(html){
          $('#origin-class').append(html);
          $('#origin select[name="origin_classroom"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  
    return false;
  });

  // Modification de l'année scolaire pour la classe de destination, rafraichissement de la liste des classes
  $('#destination select[name="destination_grade"]').change(function(){
  
    $('#destination-class').empty();
    $('#assignments').empty();
    
    if ($('#destination [name="destination_school"]').val()) {
  
      $.ajax({
        url: classFilterUrl,
        data: ({grade: $(this).val(), school_id: $('#destination [name="destination_school"]').val(), with_label: 1, with_empty: 1, label_empty: "Toutes", name: "destination_classroom"}),
        success: function(html){
          $('#destination-class').append(html);
          $('#destination select[name="destination_classroom"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  
    return false;
  });

  // Soumission du formulaire
  $('#origin select[name="origin_level"], #destination select[name="destination_level"], #origin select[name="origin_usertype"], #origin input[name="origin_lastname"], #origin input[name="origin_lastname_search"], #origin input[name="origin_firstname_search"], #origin select[name="origin_usertype_search"], #origin input[name="origin_firstname"]').live('change', function(){
    
    $('#filter-form').submit();
  });

  // Soumission du formulaire, mise à jour de la liste des élèves
  $('#filter-form').submit(function(e){
  
    $('#assignments').empty();
    $('#assignments').html('<p class="center">Chargement en cours...</p>');
  
    $.ajax({
      url: filterAndDisplayUrl,
      data: $('#filter-form').serialize(),
      success: function(list){
        $('#assignments').empty();
        $('#assignments').append(list);
      }
    });
  
    return false;
  });

  // Modification du groupe de ville d'origine, rafraichissement de la liste des villes
  $('#origin select[name="origin_citygroup"]').live('change', function(){

    $('#origin-city').empty();
    $('#origin-school').empty();
    $('#origin-class').empty();
    $('#assignments').empty();
  
    var cityGroupId = $('#origin select[name="origin_citygroup"]').val();
    if (cityGroupId) {
    
      $.ajax({
        url: cityFilterUrl,
        data: ({city_group_id: cityGroupId, with_label: 1, name: "origin_city", with_empty: 0}),
        success: function(html){

          $('#origin-city').append(html);
          $('#origin select[name="origin_city"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de la ville d'origine, rafraichissement de la liste des écoles
  $('#origin select[name="origin_city"]').live('change', function(){
  
    $('#origin-school').empty();
    $('#origin-class').empty();
    $('#assignments').empty();
  
    var cityId = $('#origin select[name="origin_city"]').val();
    if (cityId) {
    
      $.ajax({
        url: schoolFilterUrl,
        data: ({city_id: cityId, with_label: 1, with_empty: 0, name: "origin_school"}),
        success: function(html){

          $('#origin-school').append(html);
          $('#origin select[name="origin_school"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de l'école d'origine, rafraichissement de la liste des classes et des élèves
  $('#origin select[name="origin_school"]').live('change', function(){
  
    $('#origin-class').empty();
    $('#origin-level').empty();
    $('#assignments').empty();
  
    var schoolId = $('#origin select[name="origin_school"]').val();
    if (schoolId) {
    
      $.ajax({
        url: classFilterUrl,
        data: ({school_id: schoolId, with_label: 1, grade: $('[name="origin_grade"]').val(), with_empty: 1, label_empty: "Toutes", name: "origin_classroom", all: 1}),
        success: function(html){

          $('#origin-class').append(html);
          $('#origin select[name="origin_classroom"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de la classe d'origine, rafraichissement de la liste des niveaux et des élèves
  $('#origin select[name="origin_classroom"]').live('change', function(){

    $('#origin-level').empty();
    $('#assignments').empty();
  
    var classroomId = $('#origin select[name="origin_classroom"]').val();
    var schoolId = $('#origin [name="origin_school"]').val();
    if (schoolId) {
    
      $.ajax({
        url: classLevelFilterUrl,
        data: ({classroom_id: classroomId, school_id: schoolId, with_label: 1, grade: $('[name="origin_grade"]').val(), with_empty: 1, label_empty: "Tous", name: "origin_level", all: 1}),
        success: function(html){

          $('#origin-level').append(html);
          $('#origin select[name="origin_level"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification du groupe de ville de destination, rafraichissement de la liste des villes
  $('#destination select[name="destination_citygroup"]').live('change', function(){

    $('#destination-city').empty();
    $('#destination-school').empty();
    $('#destination-class').empty();
  
    var cityGroupId = $('#destination select[name="destination_citygroup"]').val();
    if (cityGroupId) {
    
      $.ajax({
        url: cityFilterUrl,
        data: ({city_group_id: cityGroupId, with_label: 1, name: "destination_city", with_empty: 0}),
        success: function(html){

          $('#destination-city').append(html);
          $('#destination select[name="destination_city"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de la ville de destination, rafraichissement de la liste des écoles
  $('#destination select[name="destination_city"]').live('change', function(){
  
    $('#destination-school').empty();
    $('#destination-class').empty();
    $('#destination-level').empty();
  
    var cityId = $('#destination select[name="destination_city"]').val();
    if (cityId) {
    
      $.ajax({
        url: schoolFilterUrl,
        data: ({city_id: cityId, with_label: 1, with_empty: 0, name: "destination_school"}),
        success: function(html){

          $('#destination-school').append(html);
          $('#destination select[name="destination_school"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de l'école de destination, rafraichissement de la liste des classes
  $('#destination select[name="destination_school"]').live('change', function(){
  
    $('#destination-class').empty();
    $('#destination-level').empty();
  
    var schoolId = $('#destination select[name="destination_school"]').val();
    if (schoolId) {
    
      $.ajax({
        url: classFilterUrl,
        data: ({school_id: schoolId, with_label: 1, grade: $('[name="destination_grade"]').val(), with_empty: 1, label_empty: "Toutes", name: "destination_classroom"}),
        success: function(html){

          $('#destination-class').append(html);
          $('#destination select[name="destination_classroom"]').trigger('change');
        }
      });
    }
    else {
    
      $('#filter-form').submit();
    }
  });

  // Modification de la classe de destination, rafraichissement de la liste des niveaux de la classe
  $('#destination select[name="destination_classroom"]').live('change', function(){
  
    $('#destination-level').empty();
    $('#assignments').empty();
  
    var classroomId = $('#destination select[name="destination_classroom"]').val();
    var schoolId = $('#destination [name="destination_school"]').val();
    if (schoolId) {
    
      $.ajax({
        url: classLevelFilterUrl,
        data: ({classroom_id: classroomId, school_id: schoolId, grade: $('[name="destination_grade"]').val(), with_label: 1, with_empty: 1, label_empty: "Tous", name: "destination_level"}),
        success: function(html){

          $('#destination-level').append(html);
        }
      });
    }
  
    $('#filter-form').submit();
  });

  // Modification du niveau de la classe
  $('#destination select[name="destination_level"]').live('change', function(){
  
    $('#filter-form').submit();
  });
}


/**
 * Prépare les actions de gestion d'affectation
 */
function prepareAssignmentsManagementActions(changeManageAssignmentClassroomState, removeAssignment, updateAssignment) {
  
  var changeManageAssignmentClassroomStateUrl = changeManageAssignmentClassroomState;
  var removeAssignmentUrl = removeAssignment;
  var updateAssignmentUrl = updateAssignment;
  
  // Masquer Groupes de villes inutiles
  if ($('#origin-citygroup select option').size() < 2)
      $('#origin-citygroup').hide();
  if ($('#destination-citygroup select option').size() < 2)
      $('#destination-citygroup').hide();

  $('#persons-to-assign a.classroomClosed, #assigned-persons a.classroomClosed').each(function(){
      $(this).parent('h3').next('div.class-box').hide();
  });
  
  /**
   * Déplacement des affectations d'une personne (élève / enseignant) / classe
   */
  $('#assigned-persons .classroom').droppable({
    activeClass: "ui-state-default",
    hoverClass: "ui-state-hover",
    accept: ":not(.ui-sortable-helper)",
    drop: function (event, ui) {
      
      var item = $(ui.draggable);
      var target = $(event.target);
      
      if (item.is('li')) {
        
        if (item.parents('.classroom:first').data('classroom-id') != target.data('classroom-id') 
          || item.parents('.classroom:first').data('classroom-level') != target.data('classroom-level')) {
          
          $('<img class="load-img" src="../../../themes/default/img/ajax-loader-mini.gif" />').appendTo(target.find('h3 a')); 
          
          reassignPerson(item, target, changeManageAssignmentClassroomStateUrl);
        }
      }
      else {
        
        var allLi = item.next('.class-box').find('li');          
        $.each(allLi, function(index) {
          
          var item = $(this);
          
          if (item.parents('.classroom:first').data('classroom-id') != target.data('classroom-id') 
            || item.parents('.classroom:first').data('classroom-level') != target.data('classroom-level')) {
              
              if (target.find('.load-img').size() == 0) {
                $('<img class="load-img" src="../../../themes/default/img/ajax-loader-mini.gif" />').appendTo(target.find('h3 a'));
              }
              
              reassignPerson(item, target, changeManageAssignmentClassroomStateUrl);
          }
        });
      }
    }
  });
  
  $('#persons-to-assign .class-box li').draggable({
    revert: "invalid",
    helper: "clone",
    cursor: "move"
  });  
  $('#persons-to-assign h3').draggable({
    revert: "invalid",
    helper: "clone",
    cursor: "move"
  });
  
  // Stopper la sélection du texte lors du drag & drop
  $('#persons-to-assign .class-box li, #persons-to-assign h3').each(function(){
	  this.onselectstart = function () { return false; }
  });
  
  /**
   * Suppression d'une affectation
   */
  $('#assigned-persons').delegate('.remove-person', 'click', function(e) {
    
    var item          = $(this);
    var classroomId   = item.closest('.classroom').data('classroom-id');
    var userId        = item.parent('li').data('user-id');
    var userType      = item.parent('li').data('user-type');
    
    $.ajax({
      url: removeAssignmentUrl,
      data: { classroom_id: classroomId, user_id: userId, user_type: userType },
      success: function(data){
        
        $('#assignments').html(data);
      }
    });
    
    e.stopPropagation();
    return false;
  });
  
  /**
   * Assigne une personne (enseignant / élève) à une classe
   */
  function reassignPerson(item, target, changeManageAssignmentClassroomStateUrl) {
    
    if (target.find("li[data-user-id='"+item.data('user-id')+"'][data-user-type='"+item.data('user-type')+"']").length == 0) {
      
      var classroomId    = target.data('classroom-id');
      var classroomLevel = target.data('classroom-level');
      var userId         = item.data('user-id');
      var userType       = item.data('user-type');
      var oldClassroomId = item.closest('.classroom').data('classroom-id');

      $.ajax({
        url: updateAssignmentUrl,
        data: { classroom_id: classroomId, classroom_level: classroomLevel, user_id: userId, user_type: userType, old_classroom_id: oldClassroomId },
        success: function(data) {
          
          $('#assignments').html(data);
          if (classroomLevel) {
            
            var newTarget = $('#assigned-persons').find("li[data-classroom-level='"+classroomLevel+"'][data-classroom-id='"+classroomId+"']:first");
          }
          else {
            
            var newTarget = $('#assigned-persons').find("li[data-classroom-id='"+classroomId+"']:first");
          }
          if (newTarget.size()) {
            var targetLink = newTarget.find('h3 a');
            if (targetLink.hasClass('classroomClosed')) {
              
              toggleClassroomState (changeManageAssignmentClassroomStateUrl, targetLink, 'destination');
            }
          }
        }
      });
    }
    else {
      
      $('.load-img').remove();
    }
  };
}

/**
 * Ouverture / fermeture d'une classe et stockage en session
 */
function toggleClassroomState (changeManageAssignmentClassroomState, item, type) {
  
  var changeManageAssignmentClassroomStateUrl = changeManageAssignmentClassroomState;
  
  // Mise en session de l'ouverture / fermeture d'une classe
  if ($(item).parents('.classroom:first').data('classroom-level') != undefined) {
    var id = $(item).parents('.classroom:first').data('classroom-id')+"-"+$(item).parents('.classroom:first').data('classroom-level')
  }
  else {
    var id = $(item).parents('.classroom:first').data('classroom-id');
  }
  
  $.ajax({
      url: changeManageAssignmentClassroomStateUrl,
      data: { id: id, type: type }
  });
    
  if ($(item).hasClass('classroomClosed'))
      $(item).removeClass('classroomClosed').addClass('classroomOpen');
  else
      $(item).removeClass('classroomOpen').addClass('classroomClosed');
  $(item).parent('h3').next('div.class-box').slideToggle();
}