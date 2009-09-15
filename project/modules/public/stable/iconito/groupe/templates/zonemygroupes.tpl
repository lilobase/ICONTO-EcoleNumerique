<link rel="stylesheet" type="text/css" href="styles/module_groupe.css" />

	{if $list neq null}

<DIV ID="groups">

{foreach from=$list item=groupe}
<DIV CLASS="body">

			<DIV CLASS="actions">
			{if $groupe->canViewHome}<A CLASS="home" href="{copixurl dest="|getHome" id=$groupe->id}">{i18n key="groupe.group.home"}</A>{/if}
			{if !$groupe->mondroit }<A CLASS="subscribe" href="{copixurl dest="|doJoin" id=$groupe->id}">{i18n key="groupe.group.join"}</A>{/if}
			{if $groupe->canAdmin }<A CLASS="admin" href="{copixurl dest="|getHomeAdmin" id=$groupe->id}">{i18n key="groupe.group.admin"}</A>{/if}
			{if $groupe->blog}<A CLASS="blog" href="{copixurl dest="blog||listArticle" blog=$groupe->blog->url_blog}">{i18n key="groupe.group.blogView"}</A>{/if}
			</DIV>


			<DIV CLASS="titleb">{if $groupe->canViewHome}<A HREF="{copixurl dest="|getHome" id=$groupe->id}">{$groupe->titre}</a>{else}{$groupe->titre}{/if}</DIV>
			{$groupe->description}
			<DIV CLASS="infos">
			
			{$groupe->mondroitnom} - {i18n key="groupe.creation" nb=$groupe->date_creation|datei18n:"date_short" who=""} {user label=$groupe->createur_nom userType=$groupe->createur_infos.type userId=$groupe->createur_infos.id linkAttribs='STYLE="text-decoration:none;"'}
			 - {i18n key="groupe.group.member" pNb=$groupe->inscrits}
			</DIV>
</DIV>
{/foreach}

<BR CLEAR="ALL" />
</DIV>

	{else}


	<i>{i18n key="groupe.noSubsc"}</i>

	{/if}

