

function submitTopicForm (form, value) {
	if (form.go)
		form.go.value = value;
}

function checkAllClasse () {
	var form = $('form');
	for (var i=0, trouve=false, allChecked=true ; allChecked && i < form.length ; i++) {
		if (form[i].name == 'eleves[]') {
			if (!form[i].checked)
				allChecked=false;
		}
	}

	for (var i=0 ; i < form.length ; i++) {
		if (form[i].name == 'eleves[]') {
			form[i].checked = !allChecked;
		}
	}

}

