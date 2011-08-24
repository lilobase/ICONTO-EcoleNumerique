jQuery(document).ready(function($){

	/**********************************************************************/
	/*  Btn imprimer  */
	/**********************************************************************/
	/*$('.button-print').removeClass('hidden');
	$('.button-print').click(function(){
		window.print();
		return false;
	});*/
	
	
	/**********************************************************************/
	/*  Affecter une classe à plusieurs élèves directement  (change_students_affect.tpl) */
	/**********************************************************************/
	$('#btnAllAffect').click(function () {
        var valeur = $('#allAffect').val();
        $('select[name="newAffects[]"]').each(function () {
            $(this).val(valeur);
        });
    });
});