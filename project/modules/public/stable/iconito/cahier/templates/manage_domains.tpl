<h2>Liste des domaines</h2>

{if $ppo->save neq null}
  <p class="success">Domaine ajouté</p>
{elseif not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="domain_creation" id="domain_creation" action="{copixurl dest="cahier||manageDomains"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
    
    <div class="field">
      <input class="form" type="text" name="nom" id="nom" value="" />
    </div>
  </fieldset>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="Ajouter un intitulé" /></li>
  </ul>
</form>

{copixzone process=cahier|showDomains nid=$ppo->nid}