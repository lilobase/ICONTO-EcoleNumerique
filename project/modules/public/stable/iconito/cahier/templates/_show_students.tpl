<span class="title">Eleves concernés</title>
  
<div id="students-data">
  {if $ppo->students neq null}
    <table class="liste">
      <tr>
        <th class="liste_th">compte</th>
        <th class="liste_th">nom</th>
        <th class="liste_th">prénom</th>
        <th class="liste_th">niveau</th>
        <th class="liste_th"><input type="checkbox" name="check_all" /></th>
      </tr>
      {foreach from=$ppo->students item=student}
        <tr>
          <td>{$student->login}</td>
          <td>{$student->nom}</td>
          <td>{$student->prenom1}</td>
          <td>{$student->niveau_court}</td>
          <td class="check">
            <input type="checkbox" value="{$student->idEleve}" name="students[]" {if in_array($student->idEleve, $ppo->selectedStudentIds) || empty($ppo->selectedStudentIds)}checked=checked{/if} />
          </td>
        </tr>
      {/foreach}
    </table>
  {else}
    <i>Aucun élève...</i>
  {/if} 
</div>