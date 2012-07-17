<h2>{i18n key="cahierdetextes.message.returnWork"}</h2>

{if $ppo->erreur neq null}
  <p class="mesgError">{$ppo->erreur}</p>
{/if}

<form action="{copixurl dest="|rendreTravail"}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
  <input type="hidden" name="travailId" id="travailId" value="{$ppo->travail->id}" />
  <input type="file" name="fichier" id="fichier" />
  <div class="actions submit">
    <input class="button button-confirm" type="submit" value="{i18n key="cahierdetextes.message.send"}" />
  </div>
</form>