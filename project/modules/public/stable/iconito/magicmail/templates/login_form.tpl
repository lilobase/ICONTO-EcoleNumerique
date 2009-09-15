{i18n key="magicmail.message.explication"}

{if $infos->magicmail_login}
<p>{i18n key="magicmail.message.email_actual"} :
<b>{$infos->magicmail_login}@{$infos->magicmail_domain}</b></p>

<ul>
<li><a href="{copixurl dest="magicmail||doCreateMail" id=$id}">{i18n key="magicmail.link.email_renew"}</a></li>
<li><a href="{copixurl dest="magicmail||doDeleteMail" id=$id}">{i18n key="magicmail.link.email_delete"}</a></li>
</ul>

{else}
<p>{i18n key="magicmail.message.email_none"}</p>

<ul>
<li><a href="{copixurl dest="magicmail||doCreateMail" id=$id}">{i18n key="magicmail.link.email_create"}</a></li>
</ul>

{/if}


{if $return == "ok"}
<div class="prefs" align="center">
<table class="msg {$msg.type}" cellpadding="0" cellspacing="3">
<tr>
{if $msg.image_url}<td class="image" width="1"><img src="{$msg.image_url}" alt="{$msg.image_alt}" align="left"/></td>{/if}
<td class="text">{i18n key="magicmail.message.email_create_ok"}</td>
</table>
</div>
{/if}

{if $return == "error"}
<div align="center">
<table class="prefs" class="msg {$msg.type}" cellpadding="0" cellspacing="3">
<tr>
{if $msg.image_url}<td class="image" width="1"><img src="{$msg.image_url}" alt="{$msg.image_alt}" align="left"/></td>{/if}
<td class="text">{i18n key="magicmail.message.email_create_err"}</td>
</table>
</div>
{/if}

{if $infos->magicmail_login}
<div style="margin-top: 30px; border: 4px solid red; padding: 6px; color: black; font-weight: bold;">{i18n key="magicmail.message.warning"}</div>
{/if}