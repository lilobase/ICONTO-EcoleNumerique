<DIV CLASS="minimail_message">
<table WIDTH="100%" BORDER=0 CELLSPACING=1 CELLPADDING=1 CLASS="minimail_message">
<tr class="minimail_navig">
<th class="left">
{if $message->prev}
<a href="{copixurl dest="|getMessage" id=$message->prev}">< {i18n key="minimail.msg.previous"}</a>
{else}
<FONT COLOR="GRAY">< {i18n key="minimail.msg.previous"}</FONT>
{/if}
</th>
<th class="center">
{if $message->type eq "recv"}
<a href="{copixurl dest="|getListRecv"}">{i18n key="minimail.msg.backList"}</a>
{else}
<a href="{copixurl dest="|getListSend"}">{i18n key="minimail.msg.backList"}</a>
{/if}
</th>
<th class="right">
{if $message->next}
<a href="{copixurl dest="|getMessage" id=$message->next}">{i18n key="minimail.msg.next"} ></a>
{else}
<FONT COLOR="GRAY">{i18n key="minimail.msg.next"} ></FONT>
{/if}
</th>
</tr>
</table>

{if $message->avatar}<img src="{copixurl}{$message->avatar}" alt="{$message->avatar}" title="{$message->from.login}" align="right" hspace="2" vspace="2" />{/if}

<b>{i18n key="minimail.msg.from"}</b> 

{user label=$message->from_id_infos userType=$message->from.type userId=$message->from.id linkAttribs='STYLE="text-decoration:none;";'}, <b>{i18n key="minimail.msg.to"}</b> {assign var=sep value=""}{foreach from=$dest item=to}{$sep}{user label=$to->to_id_infos userType=$to->to.type userId=$to->to.id linkAttribs='STYLE="text-decoration:none;";'}{assign var=sep value=", "}{/foreach}, <b>{i18n key="minimail.msg.date}</b> {$message->date_send|datei18n:"date_short_time"}

<HR CLASS="minimail_hr" NOSHADE SIZE="1" />
<div class="render">
  {$message->message|render:$message->format}
</div>
{if $message->attachment1}
    <DIV CLASS="minimail_attachment">
    <b>{i18n key="minimail.msg.attach1}</b> : <a href="{copixurl dest="|downloadAttachment"  file=$message->attachment1|escape}">{$message->attachment1Name}</a>
    {if $message->attachment1IsImage }<br/><a href="{copixurl dest="|downloadAttachment"  file=$message->attachment1|escape}"><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$message->attachment1}"></a>{/if}
    </DIV>
{/if}
{if $message->attachment2}
    <DIV CLASS="minimail_attachment">
    <b>{i18n key="minimail.msg.attach2}</b> : <a href="{copixurl dest="|downloadAttachment"  file=$message->attachment2|escape}">{$message->attachment2Name}</a>
    {if $message->attachment2IsImage }<br/><a href="{copixurl dest="|downloadAttachment"  file=$message->attachment2|escape}"><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$message->attachment2}"></a>{/if}
    </DIV>
{/if}
{if $message->attachment3}
    <DIV CLASS="minimail_attachment">
    <b>{i18n key="minimail.msg.attach3}</b> : <a href="{copixurl dest="|downloadAttachment"  file=$message->attachment3|escape}">{$message->attachment3Name}</a>
    {if $message->attachment3IsImage }<br/><a href="{copixurl dest="|downloadAttachment"  file=$message->attachment3|escape}"><img width="100" border="0" src="{copixurl dest="|previewAttachment" file=$message->attachment3}"></a>{/if}
    </DIV>
{/if}
<br clear="all" />
</div>

<span class="floatleft">
    
    
{if $message->getNbAttachments() > 0}
    
        <a href="{copixurl dest="|attachmentToClasseur" id=$message->id}" class="button button-move fancyframe" id="buttonAttachmentToClasseur">{i18n key="minimail.attachmentToClasseur.action" pNb=$message->getNbAttachments()}</a>
      
{/if}
</span>

<p class="right">
{if $message->type eq "recv"}
  <input style="margin:2px;" class="button button-continue" onclick="self.location='{copixurl dest="|getNewForm" reply=$message->id}'" type="button" value="{i18n key="minimail.btn.reply}" />

  {if $dest|@count>1}
    <input style="margin:2px;" class="button button-continue" onclick="self.location='{copixurl dest="|getNewForm" reply=$message->id all=1}'" type="button" value="{i18n key="minimail.btn.replyAll}" />
  {/if}
{/if}

<input style="margin:2px;" class="button button-continue" onclick="self.location='{copixurl dest="|getNewForm" forward=$message->id}'" type="button" value="{i18n key="minimail.btn.forward}" />

  </p>




