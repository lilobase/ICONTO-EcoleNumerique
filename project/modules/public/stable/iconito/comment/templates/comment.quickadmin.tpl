<div class="quickAdminModule">
{copixurl dest="quickadmin|admin|" currentModule="comment" assign=backUrl}

{foreach from=$comments item=comment}
<table class="comment">
   <tr>
      <th class="author">{$comment->author_cmt}</th>
      <th class="title">{$comment->title_cmt}</th>
      <td class="actions"><a href="{copixurl dest="comment||prepareEdit" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$backUrl|urlencode }">{i18n key="copix:common.buttons.update"}</a>
                          / <a href="{copixurl dest="comment||delete" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$backUrl|urlencode }">{i18n key="copix:common.buttons.delete"}</a>
                          </td>
   </tr>
   <tr>
      <th class="date">{$comment->date_cmt|datei18n}</th>
      <td class="content" colspan="2">{if $comment->textformat_cmt eq 'wiki'}{$comment->content_cmt|wiki}{else}{if $comment->textformat_cmt eq 'html'}{$comment->content_cmt}{else}{$comment->content_cmt|escape|nl2br}{/if}{/if}</td>
   </tr>
</table>
{/foreach}
<p>
{$pager}
</p>
</div>
