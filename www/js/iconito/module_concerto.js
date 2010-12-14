
var submit_concerto = false;

function concerto_redirect () {
	if (submit_concerto) {
		$('form#form_concerto').submit();
	}
	return false;
}

