<h3>{i18n key="cahierdetextes.message.studentsConcerned"} <span class="levels">{html_checkboxes name="niveaux" values=$ppo->nomsNiveau output=$ppo->nomsNiveau}</span></h3>
           
  {if $ppo->eleves neq null}
    <table class="classic">
      <thead>
        <tr>
          <th>{i18n key="cahierdetextes.message.name"}</th>
          <th>{i18n key="cahierdetextes.message.firstname"}</th>
          <th>{i18n key="cahierdetextes.message.level"}</th>
          <th><input type="checkbox" name="check_all" id="check_all" /></th>
        </tr>
      </thead>
      <tbody>
        {assign var=index value=1}
        {foreach from=$ppo->eleves item=eleve}
          <tr class="{if $index%2 eq 0}odd{else}even{/if} {$eleve->niveau_court}">
            <td><label for="eleve{$eleve->idEleve}">{$eleve->nom}</label></td>
            <td><label for="eleve{$eleve->idEleve}">{$eleve->prenom1}</label></td>
            <td class="center">{$eleve->niveau_court}</td>
            <td class="check center">
              <input type="checkbox" value="{$eleve->idEleve}" id="eleve{$eleve->idEleve}" name="eleves[]" {if in_array($eleve->idEleve, $ppo->elevesSelectionnes) || empty($ppo->elevesSelectionnes)}checked="checked"{/if} />
            </td>
          </tr>
        {assign var=index value=$index+1}
        {/foreach}
      </tbody>
    </table>
  {else}
    <i>{i18n key="cahierdetextes.message.noStudent"}</i>
  {/if} 
