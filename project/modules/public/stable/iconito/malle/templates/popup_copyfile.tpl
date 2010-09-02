<form name="form" id="form" action="{copixurl dest="|doAction"}" method="post" target="_parent">

<input type="hidden" name="id" value="{$ppo->id}" />
<input type="hidden" name="folder" value="{$ppo->folder}" />

{foreach from=$ppo->files item=file}
<input type="text" name="files[]" value="{$file}"/>
{/foreach}
{$ppo->combofoldersdest}

<input type="submit" name="actionCopy" value="{i18n key="malle.btn.copy"}" class="button button-confirm" />

</form>