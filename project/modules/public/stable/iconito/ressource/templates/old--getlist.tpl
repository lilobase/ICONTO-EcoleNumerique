{if $bloglist neq null}
	<ol>
	{foreach from=$bloglist item=blog}
		<li>
		<b>{$blog->blog_nom}</b>
		<br />Créé le {$blog->blog_date}
		<br />
		{assign var=sep value=""}{assign var=sepval value=" :: "}
		{if $blog->droit_lire}{$sep}{assign var=sep value=$sepval}<a href="{copixurl dest="blog||getBlog" blog=$blog->blog_id}">Lire</a>{/if}
		{if $blog->droit_publier}{$sep}{assign var=sep value=$sepval}Publier un article{/if}
		{if $blog->droit_moderer}{$sep}{assign var=sep value=$sepval}Modérer les articles{/if}
		{if $blog->droit_administrer}{$sep}{assign var=sep value=$sepval}Effacer{/if}
		</li>
	{/foreach}
	</ol>
{else}
	Aucun blog visible...
{/if}