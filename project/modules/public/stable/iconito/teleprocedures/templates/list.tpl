
<div class="teleprocedures_titre">Les t&eacute;l&eacute;proc&eacute;dures</div>
<div class="teleprocedures">
	{if $types}
		<div class="types">
			{$types}
		</div>
		<div class="list1">
			<div class="titre_zone">Les t&eacute;l&eacute;proc&eacute;dures en cours</div>
			{$filtre}
			{$list}
		</div>
	{else}
		<div class="list2">
			<div class="titre_zone">Les t&eacute;l&eacute;proc&eacute;dures en cours</div>
			{$filtre}
			{$list}
		</div>
	{/if}
	
</div>

<div>

	{if $pagesVille}
		<div class="telep_pages_ville">
			<div class="teleprocedures_titre">{i18n key=teleprocedures.blog.pagesVille}</div>
			{if $canAdminBlog}<div class="admin">[ <a href="{copixurl dest="blog|admin|showBlog" id_blog=$rBlog->id_blog kind=5}">{i18n key="teleprocedures|teleprocedures.blog.admin"}</a> ]</div>{/if}
			{$pagesVille}
		</div>
	{/if}
	
	{if $infosVille}
		{if $pagesVille}<div class="telep_infos_ville1">
		{else}<div class="telep_infos_ville2">
		{/if}
			<div class="teleprocedures_titre">{i18n key=teleprocedures.blog.infosVille}</div>
			{if $canAdminBlog}<div class="admin">[ <a href="{copixurl dest="blog|admin|showBlog" id_blog=$rBlog->id_blog kind=0}">{i18n key="teleprocedures|teleprocedures.blog.admin"}</a> ]</div>{/if}
			{$infosVille}
		</div>
	{/if}

</div>
