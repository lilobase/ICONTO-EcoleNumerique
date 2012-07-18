
jQuery(document).ready(function($){

    
    /**
     * Précochage selon les logins déjà écrits dans la fenêtre parente
     * 
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/07/18
     */
    if (window.opener !== null) {
        var $logins = $('#'+$field, window.opener.document);
        if ($logins && $logins.val())
        {
            var $select = $logins.val().replace(/ /g,"");
            $select = $select.split(',');
            $(".enUser").each(function(i){
                if ($select.inArray($(this).val()))
                {
                    $(this).attr('checked', 'checked');
                }
            });
        }
    }
    

    /**
     * Ajout du bouton Terminer
     * 
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/07/18
     */
    $.get(getActionURL('kernel|default|i18n'), { key: 'annuaire|annuaire.popup.close' }, function(data){
        if (window.opener && data) {
            $.get(getActionURL('kernel|default|i18n'), { key: 'annuaire|annuaire.popup.explain2js' }, function(data2){
                $('p.endForm').html('<a href="#" class="button button-confirm">'+data+'</a>');
                $('p.explain span').html(data2);
                $('p.endForm a.button-confirm').click(function(){
                    self.close();
                    return false;
                });
            });
        }
    });
    
    $(".tablesorter").tablesorter( {sortList: [[1,0]]} );
    //$(".tablesorter").tablesorter();
    

    
    /**
     * Cochage/décochage d'un user
     * 
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/07/18
     */
    $('.enUser').change(function(){
        if (window.opener && ($logins = $('#'+$field, window.opener.document)))
        {
            var $login = $(this).val();
            var $arrayLogins = $logins.val().replace(/ /g,"").split(',');
            if ($(this).attr('checked')) // On vient de cocher
            {
                if (!$arrayLogins.inArray($login)) // Login pas encore dans les destinataires
                {
                    if (!$logins.val()) // Premier login
                    {
                        $logins.val($login);
                    }
                    else // On l'ajoute aux autres logins
                    {
                        $logins.val($logins.val() + ', ' + $login);
                    }
                }    
            }
            else // On vient de décocher
            {
                var $newValue = "";
                for (var i=0; i < $arrayLogins.length; i++)
                {
                    if ($login != $arrayLogins[i])
                    {
                        if ($newValue !== "") $newValue += ', ';
                        $newValue += $arrayLogins[i];
                    }
                    $logins.val($newValue);
                }
            }
            
        }
        else
        {
            alert('Impossible de joindre la fenêtre ouvrante');
        }
        
    });
    
    
    /**
     * Sélectionner tous/aucun
     * 
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/07/18
     */
    $('.enSelect').click(function(e){
        e.preventDefault();
        if ($(this).data('all'))
        {
            $('.enUser').attr('checked', 'checked').change();
        }
        else
        {
            $('.enUser').attr('checked', '').change();
        }    
    });
    
    
});
