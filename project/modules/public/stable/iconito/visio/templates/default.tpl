{if $ppo->error}
<div class="error">{$ppo->error}</div>
{/if}

<h2>{i18n key="visio.contact.titre" noEscape=1}</h2>
<form>
	{i18n key="visio.contact.login" noEscape=1}
	<input name="login" />
	<input type="submit" value="{i18n key="visio.contact.appeler" noEscape=1}" />
</form>


<h2>{i18n key="visio.contact.help.titre" noEscape=1}</h2>
<p>
{i18n key="visio.contact.help.contenu" noEscape=1}
{i18n key="visio.contact.principe.contenu" noEscape=1}
</p>
