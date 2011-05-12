{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h2>{i18n key="cahierdetextes.message.domainsList"}</h2>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<div class="message_errors">
	  <ul>
	    {foreach from=$ppo->erreurs item=erreur}
		    <li>{$erreur}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="domain_creation" id="domain_creation" action="{copixurl dest="cahierdetextes||gererDomaines"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
    
  <div class="field">
    <input class="form" type="text" name="nom" id="nom" value="" />
  </div>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.addATitle"}" /></li>
  </ul>
</form>

{copixzone process=cahierdetextes|listeDomaines nid=$ppo->nid}