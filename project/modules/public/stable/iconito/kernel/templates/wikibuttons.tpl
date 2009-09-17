
{if $format eq 'wiki'}
<div>
{foreach from=$buttons item=button}<a tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|htmlentities} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}"><img border="0" width="22" height="23" src="img/wiki/toolbar_{$button.accesskey}.gif" alt="{$button.titre|htmlentities}" /></a>{/foreach}
{help mode="bulle" text="kernel|wikibuttons.help"}
</div>
{elseif $format eq 'dokuwiki'}

{foreach from=$buttons item=button}

<button style="background:#eee url(dokuwiki/lib/images/toolbar/{$button.icon}) no-repeat center center;height:20px;width:24px;border:solid 1px #ccc;" type="button" title="{$button.titre|htmlentities}" onClick="{$button.link}"></button>

{/foreach}
{help mode="bulle" text="kernel|wikibuttons.help.dokuwiki"}

{/if}


{if $buttonAlbum || $buttonMalle}
<div>

{if $buttonAlbum}
{assign var=button value=$buttonAlbum}
<a tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|htmlentities} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}">{i18n key="wikibuttons.albumTxt"}</a>
{/if}

{if $buttonMalle}
{if $buttonAlbum} | {/if}
{assign var=button value=$buttonMalle}
<a tabindex="" accesskey="{$button.accesskey}" title="{$button.titre|htmlentities} ({i18n key="wikibuttons.shortcut"} : {$button.accesskey})" href="{$button.link}">{i18n key="wikibuttons.malleTxt"}</a>
{/if}

</div>
{/if}

