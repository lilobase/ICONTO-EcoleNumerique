

// Suppression d'un message
function deleteMsgs () {
  nb_checked = $('form#form input[type=checkbox]:checked').size();
  if (!nb_checked)
    alert (i18n_minimail_check_messages);
  else {
    if(confirm(i18n_confirm_delete))
      $('form#form').submit();
  }
}


// Apercu ou envoyer ?
function goMinimail (form, value) {
  if (form.go)
    form.go.value = value;
}


$(document).ready(function($){
    
    /**
     * Si aucun répertoire n'est sélectionné, on auto-sélectionne le premier
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/21
     */
    if (!$('.selectFolder input[type="radio"]:selected').length)
    {
        $('.selectFolder label').first().click();
    }
    
    
        
});



