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
  <input type="hidden" name="domaineId" id="domaineId" value="{$ppo->domaine->id}" />
  <input type="hidden" name="jour" id="jour" value="{$ppo->jour}" />
  <input type="hidden" name="mois" id="mois" value="{$ppo->mois}" />
  <input type="hidden" name="annee" id="annee" value="{$ppo->annee}" />
    
  <div class="field">
    <label for="nom">{i18n key="cahierdetextes.message.domain"}</label>
    <input class="form" type="text" name="nom" id="nom" value="{$ppo->domaine->nom}" />
  </div>
  
  <input class="button button-add" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.save"}" />
</form>

{copixzone process=cahierdetextes|listeDomaines nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}