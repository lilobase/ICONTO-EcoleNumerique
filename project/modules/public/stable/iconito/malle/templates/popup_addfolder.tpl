<h1>{i18n key="malle.addFolder"}</h1>
<form action="{copixurl dest="|doAddFolder"}" method="post">
<input type="hidden" name="id" value="{$ppo->id}" />
<input type="hidden" name="folder" value="{$ppo->folder}" />
<input type="text" name="new_folder" value="{$ppo->new_folder}" size="36" maxlength="200" />
<input type="submit" value="{i18n key="malle.btn.submitAddFolder"}" class="button button-confirm" />
</form>
