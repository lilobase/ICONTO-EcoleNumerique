
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
	<div class="mesgSuccess">
	<ul>
	{foreach from=$oks item=ok}
		<li>{$ok}</li>
	{/foreach}
	</ul>
	</div>
{/if}

<A class="button button-continue" HREF="{copixurl dest="groupe||getListPublic"}">{i18n key="kernel|kernel.back"}</A>









