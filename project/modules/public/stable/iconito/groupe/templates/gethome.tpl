<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_groupe.css"}" />


<DIV CLASS="groupe_actu_bloc">

<DIV CLASS="groupe_actu"><img class="lucien" src="{copixresource path="img/groupe/lucien_coude.gif"}" alt="lucien_coude.gif" width="62" height="63" border="0" />




{$groupe->description}
<DIV ALIGN="RIGHT"><i>
{user label=$groupe->createur_nom userType=$groupe->createur_infos.type userId=$groupe->createur_infos.id login=$groupe->createur_infos.login dispMail=1 linkAttribs='STYLE="text-decoration:none;"' assign='who'}
{i18n key="groupe.homeCreation" nb=$groupe->date_creation|datei18n:"date_short" who=$who noEscape=1} </i>
<br/>
{if $canUnsubscribeHimself eq 1}<a href="{copixurl dest="groupe||doUnsubscribeHimself" id=$groupe->id}" title="">{i18n key="groupe.group.unsubscribe"}</a>{/if}

</DIV>

</DIV>

</DIV>

{if $his_modules neq null}
	{foreach from=$his_modules item=val_modules key=key_modules}
		{assign var="module_type_array" value="_"|split:$val_modules->module_type|lower}
		{assign var="a" value=$val_modules->module_type }
		
			{if $val_modules->module_type neq "MOD_MAGICMAIL" or $canViewAdmin eq 1}

<div class="groupe_bloc">
<div class="groupe_thumb_autour"><div class="groupe_thumb"><a href="{copixurl dest="$module_type_array[1]||go" id=$val_modules->module_id}" title=""><img WIDTH="64" HEIGHT="64" src="{copixresource path="img/kernel/module_`$val_modules->module_type`_S.png"}" alt="" BORDER=0 /></a></div>
</div>
<DIV class="groupe_title"><a href="{copixurl dest="$module_type_array[1]||go" id=$val_modules->module_id}" title="">{$val_modules->module_nom}</a></DIV>
<div class="groupe_infos">
	{assign var=sep value=""}
	{foreach from=$val_modules->infos item=infos}
	{$sep}
	{$infos.name}{if $infos.name && $infos.value neq ""}&nbsp;:{/if}
	<b>{$infos.value}</b>
	{assign var=sep value=" &middot; "}
{/foreach}
</div>

</div>

			{/if}


	{/foreach}
{else}
	<DIV>{i18n key="groupe.noModule"}</DIV>
{/if}

{if 1}
<div class="groupe_bloc">
<div class="groupe_thumb_autour"><div class="groupe_thumb"><A HREF="{copixurl dest="|getHomeMembers" id=$groupe->id}"><img WIDTH="64" HEIGHT="64" src="{copixresource path="img/kernel/module_MOD_MEMBERS_S.png"}" alt="" BORDER=0 /></a></div>
</div>
<DIV class="groupe_title"><A HREF="{copixurl dest="|getHomeMembers" id=$groupe->id}">{i18n key="groupe.group.members"}</a></DIV>
<div class="groupe_infos">{$groupe->nbMembers}</div>

</div>
{/if}



{if $canViewAdmin eq 1}
<div class="groupe_bloc_admin">
<div class="groupe_thumb_autour"><div class="groupe_thumb"><a  href="{copixurl dest="|getHomeAdmin" id=$groupe->id}" title="{i18n key="groupe.admin"}"><img WIDTH="64" HEIGHT="64" src="{copixresource path="img/kernel/module_MOD_ADMIN_S.png"}" alt="{i18n key="groupe.admin"}" BORDER=0 /></a></div>
</div>
<DIV class="groupe_title"><a href="{copixurl dest="|getHomeAdmin" id=$groupe->id}" title="{i18n key="groupe.admin"}">{i18n key="groupe.admin"}</a></DIV>
<div class="groupe_infos">{assign var=sep value=""}{foreach from=$groupe->infos item=infos}{$sep}{$infos.name}{if $infos.name && $infos.value neq ""} : <b>{$infos.value}</b>{/if}{assign var=sep value=" &middot; "}{/foreach}</div>
</div>
{/if}


