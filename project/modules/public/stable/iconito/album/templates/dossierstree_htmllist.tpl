{if $commands neq null}
	<ol style="margin: 0px;">
	{foreach from=$commands item=valeur}
		{if $valeur.type eq 'open'}
			<ol>
		{elseif $valeur.type eq 'close'}
			</ol>
		{elseif $valeur.type eq 'folder'}
			<li><a href="{copixurl dest="album||album" album_id=$valeur.data->album_id dossier_id=$valeur.data->dossier_id}">{$valeur.data->dossier_nom}</a> (<i>{$valeur.data->photos|@count}</i>)</li>
		{/if}
	{/foreach}
	</ol>
{/if}