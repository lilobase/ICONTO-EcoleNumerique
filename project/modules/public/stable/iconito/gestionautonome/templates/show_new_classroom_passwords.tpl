<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2 class="noPrint">Réinitialisation des mots de passe</h2>

{copixzone process=gestionautonome|AccountsInfo}

<p class="mesgSuccess">Modification effectuée !</p>

<h3>Liste des comptes modifiés</h3>

<table>
  <thead>
    <tr>
      <th>Prénom</th>
  		<th>Nom</th>
  		<th>Identifiant</th>
  		<th>Mot de passe</th>
  		<th>Type</th>
  	</tr>
  </thead>
  <tbody>
  	{counter assign="i" name="i"}
  	{foreach from=$ppo->accounts item=account}
  		{counter name="i"}
  		<tr class="{if $i%2==0}even{else}odd{/if}">
  		  <td>{$account.firstname}</td>
  			<td>{$account.lastname}</td>
  			<td >{$account.login}</td>
  			<td>{$account.password}</td>
  			<td>{$account.type_nom}</td>
  		</tr>
  	{/foreach}
  </tbody>
</table>

<div class="submit">
  <a href="{copixurl dest="gestionautonome||showTree"}" class="button button-previous">Retour</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=html}" class="button button-print">Imprimer</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=csv}" class="button button-save">Télécharger</a> 
</div>