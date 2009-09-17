
{if $format eq "wiki"}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "dokuwiki"}

{*<script type="text/javascript" charset="utf-8" src="/dokuwiki-2008-05-05/lib/exe/js.php?edit=1&amp;write=1&amp;field={$field}" ></script>
<div id="tool__bar__{$field}"></div>*}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "html"}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "htmlnl2br"}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "fckeditor"}

<div>{$fckeditor}</div>
<div>{$wikibuttons}</div>

{else}

{i18n key="kernel.zone.edition.error.format"}

{/if}


