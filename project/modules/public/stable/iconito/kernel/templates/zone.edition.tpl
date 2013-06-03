
{if $format eq "wiki"}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "dokuwiki"}

{*<script type="text/javascript" charset="utf-8" src="/dokuwiki-2008-05-05/lib/exe/js.php?edit=1&amp;write=1&amp;field={$field}" ></script>
<div id="tool__bar__{$field}"></div>*}

<div><textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "html"}

<div><textarea{if !$class} style="width:{$width}px; height:{$height}px;"{/if} name="{$field}" id="{$field}" class="form {$class}">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "htmlnl2br"}
<div><textarea{if !$class} style="width:{$width}px; height:{$height}px;"{/if} name="{$field}" id="{$field}" class="form {$class}">{$content}</textarea></div>
<div>{$wikibuttons}</div>

{elseif $format eq "ckeditor" || $format eq "fckeditor"}

<textarea style="width:{$width}px; height:{$height}px;" name="{$field}" id="{$field}" class="form">{$content}</textarea>

<script>
    window.onload = function() {literal}{{/literal}
        CKEDITOR.replace('{$field}', {literal}{{/literal}
            customConfig: '{copixresource path="js/ckeditor.js"}',
            width: '{$width}',
            height: '{$height}',
            on:
            {literal}{{/literal}
              {if $options.focus}
              'instanceReady': function(evt) {literal}{{/literal}
                CKEDITOR.instances.{$field}.focus();
              {literal}}{/literal}
              {/if}
            {literal}}{/literal}
            {if $options.enterMode == 'br'}, enterMode: CKEDITOR.ENTER_BR{/if}
            {if $options.toolbarSet}, toolbar: '{$options.toolbarSet}'{/if}
            {if $options.toolbarStartupExpanded}, toolbarCanCollapse: true, toolbarStartupExpanded: {$options.toolbarStartupExpanded}{/if}
        {literal}}{/literal});
    {literal}}{/literal};
</script>

<div>{$wikibuttons}</div>

{else}

{i18n key="kernel.zone.edition.error.format"}

{/if}


