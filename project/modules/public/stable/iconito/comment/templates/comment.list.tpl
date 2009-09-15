{if count($comments)}
{foreach from=$comments item=comment}
<table class="comment">
   <tr>
      <th class="author">{$comment->author_cmt}</th>
      <th class="title">{$comment->title_cmt}</th>
      <td class="actions">{if $adminEnabled || $user->login eq $comment->author_cmt}
                              {if $back}
                              <a href="{copixurl dest="comment||prepareEdit" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$back|urlencode backToComment="true"}">{i18n key="copix:common.buttons.update"}</a>
                              / <a href="{copixurl dest="comment||delete" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$back|urlencode backToComment="true"}">{i18n key="copix:common.buttons.delete"}</a>
                              {else}
                              {currenturl assign=currentUrl}
                              <a href="{copixurl dest="comment||prepareEdit" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$currentUrl|urlencode }">{i18n key="copix:common.buttons.update"}</a>
                              / <a href="{copixurl dest="comment||delete" position_cmt=$comment->position_cmt id_cmt=$comment->id_cmt type_cmt=$comment->type_cmt back=$currentUrl|urlencode }">{i18n key="copix:common.buttons.delete"}</a>
                              {/if}
                           {/if}</td>
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
{else}
{i18n key=comment.messages.noComment}
{/if}
{if $back}
<p>
<input type="button" value="{i18n key=comment.messages.addComment}" onclick="window.location='{copixurl dest="comment||add" type=$type id=$id backToComment="true" back=$back|urlencode}'" />
<input type="button" value="{i18n key=copix:common.buttons.back}" onclick="window.location='{$back}'" />
</p>
{/if}

