<h1>Recherche</h1>

<form method="GET" action="{copixurl dest="kernel||search"}">
<input name="nom" value="" size="30">
<input type="Submit" value="Rechercher">
</form>

{foreach from=$caracteres item=car name=boucle_car}
{if ! $smarty.foreach.boucle_car.first} | {/if}
<a href="{copixurl dest="kernel||search" car=$car}">{$car}</a>
{/foreach}


<ul>
<li><a href="{copixurl dest="kernel||getBu"}">Dans la base unique (par nom, prénom, etc.)</a></li>
<li><a href="{copixurl dest="kernel||getEnt"}">Par compte (utilisateur de l'ENT)</a></li>
</ul>