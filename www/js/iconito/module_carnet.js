

function submitTopicForm (form, value) {
	if (form.go)
		form.go.value = value;
}

function checkAllClasse () {

  nb = $('form#form input[type=checkbox]').size();
  nb_checked = $('form#form input[type=checkbox]:checked').size();
  
  if (nb_checked == nb)
    $('form#form input[type=checkbox]').attr('checked','');
  else
    $('form#form input[type=checkbox]').attr('checked','checked');
  
}

