<span class="title">{i18n key="cahierdetextes.message.studentsConcerned"}</title>
  
<div id="students-data">
  <span class="levels">
    {html_checkboxes name="niveaux" values=$ppo->nomsNiveau output=$ppo->nomsNiveau}
  </span>
           
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
    
    checkboxChange();
    
    $('#check_all').click(function () {
      
      $(':checkbox[name^=eleves]').attr('checked', $('#check_all').is(':checked'));
    });

    $(':checkbox[name^=niveaux]').click(function () {
      
      var class = $(this).val();
      $('.'+class).find('td.check :checkbox').attr('checked', $(this).is(':checked'));
    });
    
    $(':checkbox').change(function() {
      checkboxChange();
    })
    
    function checkboxChange() {
      
      var all_checkboxes = $("tbody :checkbox").length;
      var all_checked    = $("tbody :checkbox").filter(':checked').length;

      if (all_checkboxes == all_checked) {

        $('#check_all').attr('checked', true);
      }
      else {
        
        $('#check_all').attr('checked', false);
      }
      
      $(':checkbox[name^=niveaux]').each(function() {
        
        var class = $(this).val();
        var class_checkboxes = $('.'+class).find('td.check :checkbox').length;
        var class_checked = $('.'+class).find('td.check :checkbox').filter(':checked').length;
        
        if (class_checkboxes == class_checked) {

          $(this).attr('checked', true);
        }
        else {

          $(this).attr('checked', false);
        }
      });
    }
  });
//]]> 
</script>
{/literal}