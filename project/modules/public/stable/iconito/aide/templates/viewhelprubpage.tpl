<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_aide.css"}" />

<div class="aidePage">
{if $text}

	{if $links}
		<div class="helpLinksBloc">
		<div class="helpLinks"><img class="lucien" src="{copixresource path="img/lucien_coude.gif"}" />
		
		
		<b>{i18n key="aide.voirAussi"}</b> : 
		<ul>
		{foreach from=$links item=item}
		<li><a href="{copixurl dest="aide||viewHelp" rubrique=$item.rubrique page=$item.page}">{$item.title}</a></li>
		{/foreach}
		</ul>
		</div>
		</div>
	{/if}

	{$text}
	
{else}
	{i18n key="aide.error.noFile"}
{/if}

</div>
