

/* Carte Google Map */
function loadGoogleMapsEcole (latitude, longitude, isAjax) {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById('googleMap'));
		map.setCenter(new GLatLng(latitude,longitude),14);
		//if (!isAjax) map.addControl(new GMapTypeControl()); // Map/sat/hybrid
		if (!isAjax) map.addControl(new GSmallMapControl());
		map.addOverlay(new GMarker(new GLatLng(latitude,longitude)));
	} else {
		alert('Probleme : votre navigateur n\'est pas compatible avec Google Maps');
	}
}


function ficheViewBlogs (ecoleId, annee) {

	if (!$('#ficheblogs').html() || annee!='close') 
	{
    	$('#ficheblogs').html('<div align="center"><img src="'+getRessourcePathImg+'ajax-loader.gif" width="24" height="24" border="0" vspace="3" alt="loading" /></div>');
		$('#ficheblogs').show();
		
		var url = getActionURL('fichesecoles|default|blogs');
		var pars = 'id='+ecoleId+'&annee='+annee;
		$('#ficheblogs').load( url, pars, function () {
			var h = $(this).height();
			$(this).height('auto');
			$(this).height(h);
		});
		
	}
	else if (annee == 'close') 
	{
    	$('#ficheblogs').toggle();
	}
}

