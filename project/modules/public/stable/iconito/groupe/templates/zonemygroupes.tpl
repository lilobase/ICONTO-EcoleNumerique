<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_groupe.css"}" />

	{if $list neq null}

<div id="groups">

{foreach from=$list item=groupe}
<div class="body">

			<div class="actions">
			{if $groupe->canViewHome}<a class="home" href="{copixurl dest="|getHome" id=$groupe->id}">{i18n key="groupe.group.home"}</A>{/if}
			{if !$groupe->mondroit }<a class="subscribe" href="{copixurl dest="|doJoin" id=$groupe->id}">{i18n key="groupe.group.join"}</A>{/if}
			{if $groupe->canAdmin }<a class="admin" href="{copixurl dest="|getHomeAdmin" id=$groupe->id}">{i18n key="groupe.group.admin"}</A>{/if}
			{if $groupe->blog}<a class="blog" href="{copixurl dest="blog||listArticle" blog=$groupe->blog->url_blog}">{i18n key="groupe.group.blogView"}</A>{/if}
			</div>


			<div class="titleb">{if $groupe->canViewHome}<a href="{copixurl dest="|getHome" id=$groupe->id}">{$groupe->titre}</a>{else}{$groupe->titre}{/if}</div>
			{$groupe->description}
			<div class="infos">
			
			{$groupe->mondroitnom} - {i18n key="groupe.creation" nb=$groupe->date_creation|datei18n:"date_short" who=""} {user label=$groupe->createur_nom userType=$groupe->createur_infos.type userId=$groupe->createur_infos.id linkAttribs='STYLE="text-decoration:none;"'}
			 - {i18n key="groupe.group.member" pNb=$groupe->inscrits}
			</div>
</div>
{/foreach}

<br clear="all" />
</div>

	{else}


	<i>{i18n key="groupe.noSubsc"}</i>

	{/if}

