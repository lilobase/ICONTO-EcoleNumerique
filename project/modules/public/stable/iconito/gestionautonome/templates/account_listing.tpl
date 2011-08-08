{if !$ppo->isUpdated}
  <h2>Ajout d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='ajouté'}
{else}
  <h2>Modification d'un {$ppo->firstElement.type_nom|lower}</h2>
  {assign var=verb value='modifié'}
{/if}

{copixzone process=gestionautonome|AccountsInfo}

<p class="mesgSuccess">Modification effectuée !</p>

{if $k eq 0}
    <h3>{$ppo->firstElement.type_nom} {$verb}</h3> 
{else}
    <h3>{$ppo->firstElement.type_nom}{$k} ajouté</h3>
{/if}

<table>
  <thead>
    <tr>
      <th>Sexe</th>
      <th>Prénom</th>
  		<th>Nom</th>
  		<th>Identifiant</th>
  		<th>Mot de passe</th>
  		<th>Type</th>
  	</tr>
  </thead>
  <tbody>
  	{counter assign="i" name="i"}
  	{foreach from=$ppo->sessionDatas key=k item=sessionData}
  	  {counter name="i"}
  		<tr class="{if $i%2==0}even{else}odd{/if}">
  		  <td class="sexe">
  		    {if $sessionData.gender eq 1}
            <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
          {else}                                                                 
            <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
          {/if}
        </td>
  		  <td>{$sessionData.firstname}</td>
  			<td>{$sessionData.lastname}</td>
  			<td >{$sessionData.login}</td>
  			<td>{$sessionData.password}</td>
  			<td>{$sessionData.type_nom}</td>
  		</tr>
  	{/foreach}
  </tbody>
</table>

<div class="submit">
  {if $ppo->firstElement.bu_type == "USER_ENS"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=1}" class="button button-previous">Retour</a>
  {elseif $ppo->firstElement.bu_type == "USER_RES"}
    <a href="{copixurl dest="gestionautonome||showTree" tab=2}" class="button button-previous">Retour</a>
  {else}
    <a href="{copixurl dest="gestionautonome||showTree"}" class="button button-previous">Retour</a>
  {/if}
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=html}" class="button button-print">Imprimer</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=csv}" class="button button-save">Télécharger</a>
</div>