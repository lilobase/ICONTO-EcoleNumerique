<h3>{i18n key="cahierdetextes.message.classroomsConcerned"}</h3>

{if $ppo->classes neq null}
    <table class="classic">
        <thead>
        <tr>
            <th>{i18n key="cahierdetextes.message.classroom"}</th>
            <th><input type="checkbox" name="check_all" id="check_all" /></th>
        </tr>
        </thead>
        <tbody>
            {assign var=index value=1}
            {foreach from=$ppo->classes item=classe}
            <tr class="{if $index%2 eq 0}odd{else}even{/if}">
                <td><label for="classe{$classe->id}">{$classe->nom}</label></td>
                <td class="check center">
                    <input type="checkbox" value="{$classe->id}" id="classe{$classe->id}" name="classes[]" {if in_array($classe->id, $ppo->classesSelectionnees) || empty($ppo->classesSelectionnees)}checked="checked"{/if} />
                </td>
            </tr>
                {assign var=index value=$index+1}
            {/foreach}
        </tbody>
    </table>
{else}
    <i>{i18n key="cahierdetextes.message.noClassroom"}</i>
{/if}
