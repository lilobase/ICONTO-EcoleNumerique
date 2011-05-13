<span class="title">{i18n key="cahierdetextes.message.studentsConcerned"}</title>
  
<div id="students-data">
  {html_checkboxes name="niveaux" values=$ppo->nomsNiveau output=$ppo->nomsNiveau}
           
  {if $ppo->eleves neq null}
    <table class="liste">
      <thead>
        <tr>
          <th class="liste_th">{i18n key="cahierdetextes.message.account"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.name"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.firstname"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.level"}</th>
          <th class="liste_th"><input type="checkbox" name="check_all" id="check_all" /></th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$ppo->eleves item=eleve}
          <tr class="{$eleve->niveau_court}">
            <td>{$eleve->login}</td>
            <td>{$eleve->nom}</td>
            <td>{$eleve->prenom1}</td>
            <td>{$eleve->niveau_court}</td>
            <td class="check">
              <input type="checkbox" value="{$eleve->idEleve}" name="eleves[]" {if in_array($eleve->idEleve, $ppo->elevesSelectionnes) || empty($ppo->elevesSelectionnes)}checked=checked{/if} />
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  {else}
    <i>{i18n key="cahierdetextes.message.noStudent"}</i>
  {/if} 
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function() {
    
    $('#check_all').click(function () {
      
      $(':checkbox[name^=eleves]').attr('checked', $('#check_all').is(':checked'));
    });

    $(':checkbox[name^=niveaux]').click(function () { 
      
      var class = $(this).val();
      $('.'+class+':checkbox').css("border","13px solid red");

    });
    
    
  });
//]]> 
</script>
{/literal}