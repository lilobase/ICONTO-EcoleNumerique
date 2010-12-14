
// Suppression d'un message
function deleteMembres () {
  
  nb_checked = $('form#form input[type=checkbox]:checked').size();
  
  if (!nb_checked)
    alert (i18n_groupe_check_members);
  else {
    if(confirm(i18n_groupe_confirm_unsub_members)) {
      $('form#form').submit();
    }
  }
  
}
