
{if $list|@count}

  <FORM NAME="form" ID="form" ACTION="{copixurl dest="|doDelete"}" METHOD="POST">
  <INPUT TYPE="hidden" NAME="mode" VALUE="send" ></INPUT>
  <table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
  	<tr>
  		<th CLASS="liste_th">{i18n key="minimail.list.title"}</th>
  		<th CLASS="liste_th">{i18n key="minimail.list.to"}</th>
  		<th CLASS="liste_th">{i18n key="minimail.list.attach"}</th>
  		<th CLASS="liste_th">{i18n key="minimail.list.date"}</th>
  		<th CLASS="liste_th">{i18n key="minimail.list.read"}</th>
  		<th CLASS="liste_th">{i18n key="minimail.list.delete"}</th>
  	</tr>
		{counter assign="i" name="i"}
		{foreach from=$list item=mp}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td><a href="{copixurl dest="|getMessage" id=$mp->id}">{$mp->title}</a></td>
				<td>{assign var=sep value=""}{assign var=is_read value=0}{foreach from=$mp->destin item=dest}{$sep}

{user label=$dest->to_id_infos userType=$dest->to.type userId=$dest->to.id linkAttribs='STYLE="text-decoration:none;";'}{assign var=sep value=", "}{if $dest->is_read eq 1}{assign var=is_read value=1}{/if}{/foreach}</td>
				<td ALIGN="CENTER">{if $mp->attachment1 }<IMG src="{copixresource path="img/minimail/attachment.gif"}" ALT="{i18n key="minimail.msg.attachments"}" TITLE="{i18n key="minimail.msg.attachments"}" />{/if}</td>
				<td ALIGN="CENTER"><NOBR>{$mp->date_send|datei18n:"date_short_time"}</NOBR></td>
				<td ALIGN="CENTER"><IMG src="{copixresource path="img/minimail/msg_read_`$is_read`.gif"}" /></td>
				<td ALIGN="CENTER"><input type="checkbox" name="messages[]" value="{$mp->id}" class="noBorder"></td>
			</tr>
		{/foreach}
  	<tr CLASS="liste_footer">
  		<TD COLSPAN="5"></TD>
  		<TD ALIGN="CENTER"><a class="button button-delete" href="javascript:deleteMsgs();">{i18n key="minimail.btn.delete"}</a></TD>
  		</TR>
  </table>
  
  {$reglettepages}
  
  </FORM>

{else}
  <p>{i18n key="minimail.list.empty"}</p>
{/if}
