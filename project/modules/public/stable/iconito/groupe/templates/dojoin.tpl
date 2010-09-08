
{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}


{if not $oks eq null}
	<DIV CLASS="message_ok">
	<UL>
	{foreach from=$oks item=ok}
		<LI>{$ok}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}

<A class="button button-continue" HREF="{copixurl dest="groupe||getListPublic"}">{i18n key="kernel|kernel.back"}</A>









