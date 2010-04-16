<h4>Responsables de cet élève</h4>

{if count($ppo->persons) > 0}
  <table class="liste">
    <tr>
      <th class="liste_th">login</th>
      <th class="liste_th">nom</th>
      <th class="liste_th">prenom</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->persons item=item}
      <tr>
        <td></td>
        <td>{$item->res_nom}</td>
        <td>{$item->res_prenom1}</td>
        <td>
          Modifier -
          <a href="#" onclick="removePerson({$item->res_numero}, {$ppo->studentId})";>Retirer</a> -
          Supprimer
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<a href="{copixurl dest="gestionautonome||createPersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId}">Ajouter un responsable</a>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  function removePerson(idPerson, idStudent) {
         
    $.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|removePersonInCharge}'{literal},
      global: true,
      type: "GET",
      data: ({personId: idPerson, studentId: idStudent}),
      success: function(html){
        $('#persons-in-charge').empty();
        $("#persons-in-charge").append(html);
      }
    }).responseText;
  }
  
  $('#remove').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}