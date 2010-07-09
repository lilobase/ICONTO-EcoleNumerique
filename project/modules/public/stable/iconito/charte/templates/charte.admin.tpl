{literal}
<script type="text/javascript">
function add_text(field, url){
    var itemClass = ".file_url-"+field;
    jQuery(itemClass).val(url);
}


</script>
{/literal}

{if !empty($ppo->success)}
    <p class="ui-state-highlight"><strong>{$ppo->success}</strong></p>
{/if}

{foreach from=$ppo->chartes item=charte key=key}
<div class="ca-item">
    <h3>{$charte.title}</h3>
    {if !empty($ppo->errors.$key)}
        <p class="ui-state-error" >{$ppo->errors.$key}</p>
    {/if}
    <form action="{copixurl dest="charte|charte|adminAction" typeaction=new_charte target=$key}" method="post">
        <label>{i18n key="charte.file" noEscape=1}</label>
        <input type="text" name="ca-file_url" class="file_url-{$key}" value="{$charte.file_url}" />
        <a class="button-ui" href="javascript:openWindow('{$key}', '{copixurl dest='malle||getMallePopup' id=$ppo->idMalle field=$key format='text'}', 710,550);">{i18n key="charte.addFile" noEscape=1}</a>
         <br />
        <label for="ca-activate">{i18n key="charte.activate" noEscape=1}</label>
        {html_radios name="ca-activate" checked=$charte.active options=$ppo->radio}
<br />
        <input type="hidden" value="{$key}" /><br />
        <input type="submit" class="button-ui"/>
    </form>
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_charte target=$key}" class="button-ui">{i18n key="charte.supprCharte" noEscape=1}</a><br />
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_validation target=$key}" class="button-ui">{i18n key="charte.delUserValid" noEscape=1}</a>
</div>
{/foreach}