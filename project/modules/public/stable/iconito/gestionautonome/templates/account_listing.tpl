<h2>{$ppo->title}</h2>

{copixzone process=gestionautonome|AccountsInfo}

<p class="mesgSuccess">{$ppo->msgSuccess}</p>

{if $ppo->subTitle}
  <h3>{$ppo->subTitle}</h3>
{/if}

<table>
  <thead>
    <tr>
      <th>Sexe</th>
      <th>Nom</th>
  		<th>Prénom</th>
  		<th>Identifiant</th>
  		<th>Mot de passe</th>
  		<th>Type</th>
  	</tr>
  </thead>
  <tbody>
  	{counter assign="i" name="i"}
  	{foreach from=$ppo->accounts key=k item=account}
  	  {counter name="i"}
  		<tr class="{if $i%2==0}even{else}odd{/if}">
  		  <td class="sexe">
  		    {if $account.gender eq 1}
            <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
          {else}                                                                 
            <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
          {/if}
        </td>
  		  <td>{$account.lastname|escape}</td>
  			<td>{$account.firstname|escape}</td>
  			<td>{$account.login|escape}</td>
  			<td>{$account.password|escape}</td>
  			<td>{$account.type_nom|escape}</td>
  		</tr>
  		{if $account.person}
    		{foreach from=$account.person key=j item=person}
          <tr class="{if $i%2==0}even{else}odd{/if}">
  		      <td><img src="{copixurl}themes/default/images/child-of.png" alt="" />{if $person.gender eq 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />{else}<img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />{/if}</td>
            <td>{$person.lastname|escape}</td>
      			<td>{$person.firstname|escape}</td>
  			    <td>{$person.login|escape}</td>
      			<td>{$person.password|escape}</td>
      			<td>{$person.type_nom|escape}</td>
  		    </tr>
        {/foreach}
      {/if}
  	{/foreach}
  </tbody>
</table>

{if $ppo->accountsInSession}
  <h3>Liste des comptes créés ou modifiés pendant la session</h3>
  <table>
    <thead>
      <tr>
        <th>Sexe</th>
        <th>Nom</th>
    		<th>Prénom</th>
    		<th>Identifiant</th>
    		<th>Mot de passe</th>
    		<th>Type</th>
    	</tr>
    </thead>
    <tbody>
    	{counter assign="i" name="i"}
    	{foreach from=$ppo->accountsInSession key=k item=account}
    	  {counter name="i"}
    		<tr class="{if $i%2==0}even{else}odd{/if}">
    		  <td class="sexe">
    		    {if $account.gender eq 1}
              <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
            {else}                                                                 
              <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
            {/if}
          </td>
    		  <td>{$account.lastname|escape}</td>
    			<td>{$account.firstname|escape}</td>
    			<td>{$account.login|escape}</td>
    			<td>{$account.password|escape}</td>
    			<td>{$account.type_nom|escape}</td>
    		</tr>
    		{if $account.person}
      		{foreach from=$account.person key=j item=person}
            <tr class="{if $i%2==0}even{else}odd{/if}">
    		      <td><img src="{copixurl}themes/default/images/child-of.png" alt="" />{if $person.gender eq 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />{else}<img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />{/if}</td>
              <td>{$person.lastname}</td>
        			<td>{$person.firstname|escape}</td>
    			    <td>{$person.login|escape}</td>
        			<td>{$person.password|escape}</td>
        			<td>{$person.type_nom|escape}</td>
    		    </tr>
          {/foreach}
        {/if}
    	{/foreach}
    </tbody>
  </table>
{/if}

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
