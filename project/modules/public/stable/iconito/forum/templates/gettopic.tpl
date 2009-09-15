{literal}<link rel="stylesheet" type="text/css" href="styles/module_forum.css" />{/literal}

			
{if $canAddMessage eq 1}
<DIV CLASS="" ALIGN="RIGHT">
<input style="" class="form_button" onclick="self.location='{copixurl dest="|getMessageForm" topic=$topic->id}'" type="button" value="{i18n key="forum.btn.reply"}" />
</DIV>
{/if}
	
	{$petitpoucet}
	
	{if $list neq null}
		{foreach from=$list item=message}
		<A NAME="{$message->id}"></A>
		<DIV CLASS="forum_message">
		<DIV CLASS="forum_message_infos">
    
    {if $message->avatar}<img src="{$message->avatar}" alt="{$message->avatar}" title="" align="right" hspace="2" vspace="2" />{/if}
    
    {i18n key="forum.msg.author" author=$message->auteur_infos date=$message->date|datei18n:"date_short_time"}</DIV>
		<DIV CLASS="forum_message_message">{$message->message|render:$message->format}</DIV>
		<DIV CLASS="forum_message_actions">
		<a href="{copixurl dest="|getTopic" message=$message->id}#{$message->id}">{i18n key="forum.msg.permalink"}</a>
		{if $canAddMessage eq 1}
		&bull; <a href="{copixurl dest="|getMessageForm" topic=$topic->id quote=$message->id}">{i18n key="forum.btn.quote"}</a>
		{/if}
		<!--&bull; <a href="{copixurl dest="|doAlertMessage" id=$message->id}">{i18n key="forum.btn.alert"}</a>-->
		{if $canModifyMessage eq 1}
		&bull; <a href="{copixurl dest="|getMessageForm" id=$message->id}">{i18n key="forum.btn.modify"}</a>
		{/if}
		{if $canDeleteMessage eq 1}
		&bull; <a href="{copixurl dest="|getDeleteMessage" id=$message->id}">{i18n key="forum.btn.delete"}</a>
		{/if}
		</DIV></DIV>
		{/foreach}
	{else}
		{i18n key="forum.list.noMessage"}
	{/if}


{$reglettepages}

<br/>
	
	