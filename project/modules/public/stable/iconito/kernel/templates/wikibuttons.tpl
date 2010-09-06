
{if $format eq 'wiki'}
<div>
{foreach from=$buttons item=button}<a tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|escape} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}"><img border="0" width="22" height="23" src="{copixresource path="img/wiki/toolbar_`$button.accesskey`.gif"}" alt="{$button.titre|escape}" /></a>{/foreach}
{help mode="tooltip" text_i18n="kernel|wikibuttons.help"}
</div>
{elseif $format eq 'dokuwiki'}

{foreach from=$buttons item=button}

<button style="background:#eee url({copixresource path="dokuwiki/lib/images/toolbar/`$button.icon`"}) no-repeat center center;height:20px;width:24px;border:solid 1px #ccc;" type="button" title="{$button.titre|escape}" onClick="{$button.link}"></button>

{/foreach}
{help mode="tooltip" text_i18n="kernel|wikibuttons.help.dokuwiki"}

{/if}


{if $buttonAlbum || $buttonMalle}
<div>

{if $buttonAlbum}
{assign var=button value=$buttonAlbum}
<a class="fancyframe" tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|escape} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}">{i18n key="wikibuttons.albumTxt"}</a>
{/if}

{if $buttonMalle}
{if $buttonAlbum} | {/if}
{assign var=button value=$buttonMalle}
<a class="fancyframe" tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|escape} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}">{i18n key="wikibuttons.malleTxt"}</a>
{/if}

</div>
{/if}

