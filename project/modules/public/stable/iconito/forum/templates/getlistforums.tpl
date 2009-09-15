

	{if $list neq null}
		{foreach from=$list item=forum}
		
			<DIV CLASS=""><b>{$forum->titre}</b></DIV>
			<DIV CLASS="" ALIGN="">Crée le {$forum->date_creation}
			
			&bull; <a href="{copixurl dest="|getForum" id=$forum->id}">Lire ce forum</a>
			
			</DIV>
			<br/>


		{/foreach}
	{else}



	{/if}
