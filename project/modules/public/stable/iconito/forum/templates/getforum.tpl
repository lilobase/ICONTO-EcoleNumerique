<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_forum.css"}" />

{if $canAddTopic }
<DIV CLASS="" ALIGN="RIGHT">
<input style="" class="form_button" onclick="self.location='{copixurl dest="|getTopicForm" forum=$forum->id}'" type="button" value="{i18n key="forum.btn.addTopic"}" />
</DIV>
{/if}

{$petitpoucet}

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="forum.list.title"}</th>
		<th CLASS="liste_th">{i18n key="forum.list.nbMessages"}</th>
		<th CLASS="liste_th">{i18n key="forum.list.nbReads"}</th>
		<th CLASS="liste_th">{i18n key="forum.list.lastMessage"}</th>

	</tr>
	{if $list neq null}
		{counter assign="i" name="i"}
		
		{foreach from=$list item=topic}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td>
				{if $topic->last_msg_id neq null and ($topic->last_visite eq null or $topic->last_visite<$topic->last_msg_date)}<b><a title="{i18n key="forum.list.goNewTitle"}" class="forum_topic" href="{copixurl dest="|getTopic" id=$topic->id go=new}">{i18n key="forum.list.goNew"}</A></b>{/if}
				
				<a class="forum_topic" href="{copixurl dest="|getTopic" id=$topic->id}">{$topic->titre}</a><br/>{i18n key="forum.list.author" auteur=$topic->createur_infos}

				</td>
				<td ALIGN="CENTER">{$topic->nb_messages}</td>
				<td ALIGN="CENTER">{$topic->nb_lectures}</td>
				<td>
				{if $topic->last_msg_id neq null}
				{i18n key="forum.list.lastMessageAuthor" date=$topic->last_msg_date|datei18n:"date_short_time" author=$topic->last_msg_auteur_infos} (<a href="{copixurl dest="|getTopic" message=$topic->last_msg_id}#{$topic->last_msg_id}">{i18n key="forum.list.lastMessageGo"}</a>)
				{/if}
				</td>

			</tr>
		{/foreach}

<TR CLASS="liste_footer"><TD COLSPAN=4>
{i18n key="forum.list.orderBy"} <a href="{copixurl dest="|getForum" id=$forum->id page=$page orderby=last_msg_date}">{i18n key="forum.list.orderByLastMessage"}</a> | <a href="{copixurl dest="|getForum" id=$forum->id page=$page orderby=date_creation}">{i18n key="forum.list.orderByCreationDate"}</a>

</TD></TR>

	{else}
		<tr>
			<td COLSPAN="4">{i18n key="forum.list.noTopic"}</td>
		</tr>
	{/if}

</table>

<br/>
{$reglettepages}



<br/>

	
	