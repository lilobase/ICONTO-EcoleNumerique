{if ($title)}
	<h2>{$title}</h2>
{/if}
{if $message}
	<p class="center">{$message}</p>
{/if}

<p class="center">
<a href="{$cancel}" class="button button-cancel">{i18n key="copix:common.buttons.no"}</a>
&nbsp;
<a href="{$confirm}" class="button button-confirm">{i18n key="copix:common.buttons.yes"}</a>
</p>