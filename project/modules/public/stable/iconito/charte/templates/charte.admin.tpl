{foreach from=$ppo->chartes item=charte key=key}
<div class="ca-item">
    <h3>{$key}</h3>

    <form action="" method="post">
        <label>{i18n key="charte.file" noEscape=1}</label>
        <input type="text" name="ca-file_url" value="{$charte.file_url}" />
        <label for="ca-activate">{i18n key="charte.activate" noEscape=1}</label>
        {html_radios name="ca-activate" value=$charte.active options=$ppo->radio}
        <input type="hidden" value="{$key}" />
        <input type="submit" />
    </form>
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_charte target=$key}">{i18n key="charte.supprCharte" noEscape=1}</a>
    <a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_validation target=$key}">{i18n key="charte.delUserValid" noEscape=1}</a>
</div>
{/foreach}