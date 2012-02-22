

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


// Aperçu ou envoyer ?
function goMinimail (form, value) {
  if (form.go)
    form.go.value = value;
}


