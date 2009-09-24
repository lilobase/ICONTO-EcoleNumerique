{if ($title)}
	<h2>{$title}</h2>
{/if}
{if $message}
	<p>{$message}</p>
{/if}

<a href="{$confirm}"><img border="0" src="{copixresource path="img/tools/valid.png"}" alt="{i18n key="copix:common.buttons.yes"}" />{i18n key="copix:common.buttons.yes"}</a>
&nbsp;
<a href="{$cancel}"><img border="0" src="{copixresource path="img/tools/cancel.png"}" alt="{i18n key="copix:common.buttons.no"}" />{i18n key="copix:common.buttons.no"}</a>