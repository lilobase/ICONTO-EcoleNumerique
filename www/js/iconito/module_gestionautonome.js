jQuery(document).ready(function($){

	/**********************************************************************/
	/*  Btn imprimer  */
	/**********************************************************************/
	$('.button-print').removeClass('hidden');
	$('.button-print').click(function(){
		window.print();
		return false;
	});
});