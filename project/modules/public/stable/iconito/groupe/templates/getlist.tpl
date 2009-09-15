<link rel="stylesheet" type="text/css" href="styles/module_groupe.css" />

<div class="" align="right">

{if $canCreate}<div style="float: left;"><a class="button_like" href="{copixurl dest="|getEdit"}">{i18n key="groupe.btn.addGroup"}</a></div>
{/if}

<form action="{copixurl dest="|getSearch"}" method="post">
{i18n key="groupe.search"} :
<input type="text" name="kw" class="form" style="width: 120px;" value="{$kw}" />
<input type="submit" value="{i18n key="groupe.searchSubmit"}" class="form_button" />
</form>

</div>

	{if $list neq null}

<div id="groups">

{foreach from=$list item=groupe}
<div class="body">

			<div class="actions">
			{if $groupe->canViewHome}<a class="home" href="{copixurl dest="|getHome" id=$groupe->id}">{i18n key="groupe.group.home"}</a>{/if}
			{if !$groupe->mondroit }<a class="subscribe" href="{copixurl dest="|doJoin" id=$groupe->id}">{i18n key="groupe.group.join"}</a>{/if}
			{if $groupe->canAdmin }<a class="admin" href="{copixurl dest="|getHomeAdmin" id=$groupe->id}">{i18n key="groupe.group.admin"}</a>{/if}
			{if $groupe->blog}<a class="blog" href="{copixurl dest="blog||listArticle" blog=$groupe->blog->url_blog}">{i18n key="groupe.group.blogView"}</a>{/if}
			</div>


			<div class="titleb">{if $groupe->canViewHome}<a href="{copixurl dest="|getHome" id=$groupe->id}">{$groupe->titre}</a>{else}{$groupe->titre}{/if}</div>
			{$groupe->description}
			<div class="infos">
			{i18n key="groupe.creation" nb=$groupe->date_creation|datei18n:"date_short" who=""} {user label=$groupe->createur_nom userType=$groupe->createur_infos.type userId=$groupe->createur_infos.id linkAttribs='STYLE="text-decoration:none;"'}
			 - {$groupe->rattachement} - {i18n key="groupe.group.member" pNb=$groupe->inscrits}
			</div>
</div>
{/foreach}

<br clear="all" />
</div>

	{else}

	<i>{i18n key="groupe.noGroup"}</i>

	{/if}

{$reglettepages}
