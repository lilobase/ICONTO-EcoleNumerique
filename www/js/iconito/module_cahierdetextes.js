jQuery(document).ready(function($){

	/**********************************************************************/
	/*  Slider Memos  */
	/**********************************************************************/
	var elementWidth = $("#memos-list ul.memo").width();
    var nbElement = $('#memos-list ul.memo li').length;
	var left = (nbElement > 10) ? 10 : ((nbElement > 5) ? 20 : 35);
    var imageReelWidth = elementWidth * nbElement;
	var buttons ='';
	for (var i=0; i<nbElement; i++)
	{
		buttons += '<li><a href="#" rel="'+(i+1)+'"><span>'+(i+1)+'</span></a></li>';
	}
	
	$('#memos-list').prepend('<ul id="memosSteps">'+buttons+'</ul>');
    $('#memos-list ul#memosSteps li a:first').addClass('active');
    $('#memos-list ul#memosSteps').css('left',left+'%');
    $('#memos-list ul.memo').css('width', imageReelWidth);
	$('#memos-list ul.memo li').css('width', elementWidth-20);
    
	// Traitement des contenus des mémos
	$.each($('#memos-list ul.memo li'), function() {
		var content = $(this).children('a').html();
		//On enlève les balises Html qui pourraient trainer
		content = content.replace(/<.+?>/g,'');
		if (content.length > 100)
		{
			// On coupe au prochain espace suivant les 120 premiers caractères pour ne pas couper de mot
			//content = content.match(/^.{120}\S*/m);
			var pos = content.indexOf(' ', 120); 
			if (pos) content = content.substring(0, pos + 1);
			$(this).children('a').html(content+' (...)');
		}
		else
			$(this).children('a').html(content);
	});
	
    // Rotation
    rotate = function (){
        var triggerID = $active.attr("rel") - 1; //Get number of times to slide
        var next_pos = triggerID * elementWidth; //Determines the distance the image reel needs to slide
    
        $("#memos-list ul#memosSteps a").removeClass('active'); //Remove all active class
        $active.addClass('active'); //Add active class (the $active is declared in the rotateSwitch function)
        
        //Slider Animation
        $("#memos-list ul.memo").animate({ 
            left: -next_pos
        }, 500 );
    }; 

    //Rotation + Timing Event
    rotateSwitch = function(){        
        play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
            $active = $('#memos-list ul#memosSteps li a.active').parent('li').next().children();
            if ( $active.length === 0) { //If paging reaches the end...
                $active = $('#memos-list ul#memosSteps li a:first'); //go back to first
            }
            rotate(); //Trigger the paging and slider function
        }, 9000); //Timer speed in milliseconds 
    };

    rotateSwitch(); //Run function on launch
    
    //On Hover
    $("#memos-list").hover(function() {
        clearInterval(play); //Stop the rotation
    }, function() {
        rotateSwitch(); //Resume rotation
    });    
    
    //On Click
    $('#memos-list ul#memosSteps li a').click(function(){
        $active = $(this); //Activate the clicked paging
        //Reset Timer
        clearInterval(play); //Stop the rotation
        rotate(); //Trigger rotation immediately
        rotateSwitch(); // Resume rotation
        return false;
    });
	

	/**********************************************************************/
	/*  Signature des mémos  */
	/**********************************************************************/
	// Vérification au chargement
	if ($('#memo_avec_signature_non').is(':checked'))
		$('#fieldSignature span').hide();
	
	// S'il y a du changement
    $('#memo_avec_signature_oui, #memo_avec_signature_non').change(function() {
        if ($('#memo_avec_signature_oui').is(':checked'))
	        $('#memo_date_max_signature').val('');
        $('#fieldSignature span').toggle();
    });
	
	// Label des commentaires
	$('label.comment').css({'position':'absolute', 'top':'28px', 'left':'10px'});
	$('label.comment').click(function(){
		$(this).hide();
		$(this).next('input').focus();
	});
	$('label.comment + input').click(function() {
		$(this).prev('label').hide();
	});
	$('label.comment + input').blur(function() {
		if ($(this).val() == '')
			$(this).prev('label').show();
	});
	
	
	/**********************************************************************/
	/*  Traitement des blocs de textes long  */
	/**********************************************************************/
	jQuery.fn.extend({
		hideTooLongText : function()
		{
			var content = this.html();
			if (content.length > 100)
			{
				var pos = content.indexOf('</p>', 100); 
				if (pos) 
				{
					contentBegin = content.substring(0, pos + 5);
					contentEnd = content.substring(pos +5);
					if (contentEnd.length > 0)
						this.html(contentBegin+' <p class="right"><a href="#" class="openTextEnd">Voir la suite</a></p><div class="textEnd">'+contentEnd+'</div>');
					
				}
				
			}
		}
	});
	
	// Pour les mémos
	$('#cahierdetextes div.memo .memoMesg').each(function() {$(this).hideTooLongText();});
	$('#cahierdetextes .workDescription').each(function() {$(this).hideTooLongText();});
	
	$('.openTextEnd').click(function () {
		$(this).parent().next('.textEnd').slideToggle();
		if ($(this).hasClass('openTextEnd'))
		{
			$(this).removeClass('openTextEnd').addClass('closeTextEnd');
			$(this).html('Masquer');
		}
		else
		{
			$(this).removeClass('closeTextEnd').addClass('openTextEnd');
			$(this).html('Voir la suite');
		}
		return false;
	});
	
	
	$('.textEnd').hide();
	
	
	
	
	/**********************************************************************/
	/*  Calendrier pour les champs dates  */
	/**********************************************************************/
	$('.datepicker').datepicker({
    	/*showOn: 'button',
    	buttonImage: '../../../themes/default/img/cahierdetextes/calendar.png',
    	buttonImageOnly: true,*/
    	changeMonth: true,
        changeYear: true,
        yearRange: 'c-10:c+10'
    });
	
	
	/**********************************************************************/
	/*  Sélections dans la liste des élèves  */
	/**********************************************************************/
	checkboxChange();
    
    $('#check_all').click(function () {
		if ($('#check_all').is(':checked'))
			$(':checkbox[name^=eleves]').attr('checked', 'checked');
		else
			$(':checkbox[name^=eleves]').removeAttr('checked');
		checkboxChange();
    });

    $(':checkbox[name^=niveaux]').click(function () {
		var level = $(this).val();
		if ($(this).is(':checked'))
			$('.'+level).find('td.check :checkbox').attr('checked', 'checked');
		else
			$('.'+level).find('td.check :checkbox').removeAttr('checked');
		checkboxChange();
    });
    
    $('tbody :checkbox').change(function() {
        checkboxChange();
    });
    
    function checkboxChange() 
	{
        var all_checkboxes = $('tbody :checkbox').size();
        var all_checked    = $('tbody :checkbox').filter(':checked').size();
        if (all_checkboxes == all_checked) 
	    	$('#check_all').attr('checked', 'checked');
        else
        	$('#check_all').removeAttr('checked');
        
        $(':checkbox[name^=niveaux]').each(function() {
        	var level = $(this).val();
        	var level_checkboxes = $('.'+level).find('td.check :checkbox').size();
        	var level_checked = $('.'+level).find('td.check :checkbox').filter(':checked').size();
        	if (level_checkboxes == level_checked) 
				$(this).attr('checked', 'checked');
        	else 
				$(this).removeAttr('checked');
        
      	});
    };
    
	
	/**********************************************************************/
	/*  Edition d'un domaine  */
	/**********************************************************************/
	$('.updateDomain').focus();
    
	/**********************************************************************/
	/*  Suppression des fichiers  */
	/**********************************************************************/
	$('a.delete-node').live('click', function() {
      
      $(this).parent().remove();
      
      return false;
    });
});