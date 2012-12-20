
{if not $errors eq null}
	<div class="mesgErrors">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</div>
{/if}


{if not $oks eq null}
	<p class="mesgSuccess">
	{foreach from=$oks item=ok}
		{$ok}
	{/foreach}
	</p>
{/if}

{if $can_group_showlist}
<p class="center"><a class="button button-back" href="{copixurl dest="groupe||getListPublic"}">{i18n key="kernel|kernel.back"}</a></p>
{else}
<p class="center"><a class="button button-back" href="{copixurl dest="||"}">{i18n key="kernel|kernel.back"}</a></p>
{/if}









