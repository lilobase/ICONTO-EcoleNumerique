{if !empty($ppo->success)}
    <p class="ui-state-highlight"><strong>{$ppo->success}</strong></p>
{/if}

{foreach from=$ppo->chartes item=charte key=key}
<div class="ca-item">
    <h3>{$key}</h3>
    {if !empty($ppo->errors.$key)}
        <p class="ui-state-error" >{$ppo->errors.$key}</p>
    {/if}
    <form action="{copixurl dest="charte|charte|adminAction" typeaction=new_charte target=$key}" method="post">
        <label>{i18n key="charte.file" noEscape=1}</label>
        <input type="text" name="ca-file_url" value="{$charte.file_url}" /><br />
        <label for="ca-activate">{i18n key="charte.activate" noEscape=1}</label>
        {html_radios name="ca-activate" checked=$charte.active options=$ppo->radio}
<br />
        <input type="hidden" value="{$key}" /><br />
        <input type="submit" class="button"/>
    </form>
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_charte target=$key}" class="button">{i18n key="charte.supprCharte" noEscape=1}</a><br />
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_validation target=$key}" class="button">{i18n key="charte.delUserValid" noEscape=1}</a>
</div>
{/foreach}