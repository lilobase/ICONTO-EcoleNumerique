
var submit_concerto = false;

function concerto_redirect () {
	if (submit_concerto) {
		$('form_concerto').submit();
	}
	return false;
}

