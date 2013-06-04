<h3>{i18n key="cahierdetextes.message.classroomsConcerned"}</h3>

{if $ppo->classes neq null}
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
            {foreach from=$ppo->classes item=classe}
            <tr class="{if $index%2 eq 0}odd{else}even{/if}">
                <td><label for="eleve{$classe->id}">{$classe->nom}</label></td>
                <td class="center">{$eleve->niveau_court}</td>
                <td class="check center">
                    <input type="checkbox" value="{$eleve->idEleve}" id="eleve{$eleve->idEleve}" name="classes[]" {if in_array($eleve->idEleve, $ppo->elevesSelectionnes) || empty($ppo->elevesSelectionnes)}checked="checked"{/if} />
                </td>
            </tr>
                {assign var=index value=$index+1}
            {/foreach}
        </tbody>
    </table>
{else}
    <i>{i18n key="cahierdetextes.message.noClassroom"}</i>
{/if}
