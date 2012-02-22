$(document).ready(function(){
	/* Au chargement de la page, on récupère le label pour le mettre par dessus l'input */
	$('#loginLabel').after('<div id="loginLabelMask">'+$('#loginLabel').html()+'</div>').hide();
	$('#passwordLabel').after('<div id="passwordLabelMask">'+$('#passwordLabel').html()+'</div>').hide();
	/* Si la valeur est automatiquement inséré par le navigateur (enregistrement des accès), on vire les styles */
	
	/* Gestion des interactions de l'utilisateur */
	$('#login').change(function() {checkInputStatus('login');});
	$('#login').focusin(function(){$('#loginLabelMask').hide();});
	$('#login').focusout(function(){checkInputStatus('login');});
	$('#loginLabelMask').click(function(event){
		event.stopPropagation();
		$('#loginLabelMask').hide();
		$('#login').focus();
	});
	$('#password').change(function() {checkInputStatus('password');});
	$('#password').focusin(function(){$('#passwordLabelMask').hide();});
	$('#password').focusout(function(){checkInputStatus('password');});
	$('#passwordLabelMask').click(function(event){
		event.stopPropagation();
		$('#passwordLabelMask').hide();
		$('#password').focus();
	});
	
	function checkInputStatus (nameInput)
	{
		str = '#'+nameInput;
		strMask = '#'+nameInput+'LabelMask';
		if ($(str).val() == '')
		{
			$(strMask).show();
			$(str).removeClass('filled');
		}
		else
		{
			$(strMask).hide();
			$(str).addClass('filled');
		}
	}
	
	$('#passwordLabelMask, #password').focusin(function(event){
		$('#passwordLabelMask').hide();
		$('#password').focus();
		event.stopPropagation();
	});
	$('#password').focusout(function(){
		if ($('#password').val() == '')
		{
			$('#passwordLabelMask').show();
			$('#password').removeClass('filled');
		}
		else
			$('#password').addClass('filled');
	});

});
