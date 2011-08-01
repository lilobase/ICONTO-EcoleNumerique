<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Re-génération des mots de passe</h2>

{copixzone process=gestionautonome|AccountsInfo}

<p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
  <strong>Modification effectuée !</strong>
</p>

<h3>Liste des comptes modifiés</h3>

<table>
  <thead>
    <tr>
      <th>Prénom</th>
  		<th>Nom</th>
  		<th>Login</th>
  		<th>Mot de passe</th>
  		<th>Type</th>
  	</tr>
  </thead>
  <tbody>
  	{counter assign="i" name="i"}
  	{foreach from=$ppo->accounts item=account}
  		{counter name="i"}
  		<tr class="list_line{math equation="x%2" x=$i}">
  		  <td>{$account.firstname}</td>
  			<td>{$account.lastname}</td>
  			<td >{$account.login}</td>
  			<td>{$account.password}</td>
  			<td>{$account.type_nom}</td>
  		</tr>
  	{/foreach}
  </tbody>
</table>

<p>
  <a href="{copixurl dest="gestionautonome||showTree"}" class="button">Retour</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=html}" class="button">Imprimer</a>
  <a href="{copixurl dest="gestionautonome||getPasswordsList" format=csv}" class="button">Télécharger</a> 
</p>