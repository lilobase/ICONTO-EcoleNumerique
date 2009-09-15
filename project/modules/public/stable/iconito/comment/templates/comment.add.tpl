{if $showErrors}
<div>
   <ul>
   {foreach from=$errors item=message}
     <li>{$message}</li>
   {/foreach}
   </ul>
</div>
{/if}

<form method="post" action="{copixurl dest="comment||valid"}" class="copixForm">
<table>
   <tr>
      <th>{i18n key=comment.fields.title_cmt}</th>
      <td><input type="text" name="title_cmt" value="{$toEdit->title_cmt}"/></td>
   </tr>
   <tr>
      <th>{i18n key=comment.fields.textformat_cmt}</th>
      <td><select name="textformat_cmt">
            {foreach from=$formatList key=format item=caption}
            <option value="{$format}" {if $format eq $toEdit->textformat_cmt}selected="selected"{/if}>{$caption}</option>
            {/foreach}
           </select></td>
   </tr>
   <tr>
      <th>{i18n key=comment.fields.content_cmt}</th>
      <td><textarea name="content_cmt" cols="50" rows="4">{$toEdit->content_cmt}</textarea></td>
   </tr>
</table>
<p>
<input type="submit" value="{i18n key="copix:common.buttons.ok"}" />
{if $toEdit->backToComment}
<input type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="comment||cancelEdit"}'" />
{/if}
</p>
</form>
