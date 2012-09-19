
{if !$usr}
	{i18n key="kernel|kernel.error.noRights"}
{else}

<div>
<div class="right"><a href="#" onClick="return hideUser();">{i18n key="annuaire.btn.close"}</a></div>
<!--<DIV ALIGN="RIGHT">{$usr.civilite}</DIV>-->

<div style="font-weight:bold; font-size:140%; margin-top: 3px; margin-bottom: 3px;">{if $usr.avatar}<img src="{copixurl}{$usr.avatar}" align="right" />{/if}{if $usr.sexe == 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" />{elseif $usr.sexe == 2}<img src="{copixurl}themes/default/images/icon-16/user-female.png" />{/if}{$usr.prenom|escape} {$usr.nom|escape}</div>

<!-- 
<DIV ALIGN="RIGHT">
{if $usr.ALL->ele_date_nais}Né(e) le {$usr.ALL->ele_date_nais|datei18n:"date_short"}{/if}
</DIV>
-->


<div>

{$usr.type_nom}<br/>

{if $canWrite && $usr.login}{i18n key="annuaire.minimail"} : {$usr.login} <a href="{copixurl dest="minimail||getNewForm" login=$usr.login}"><img width="12" height="9" src="{copixresource path="img/minimail/new_minimail.gif"}" alt="{i18n key="annuaire.writeMinimail"}" title="{i18n key="annuaire.writeMinimail"}" /></a>{/if}

{if $parents}
<hr />
<div style="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisParents"}</div>
<div id="">
{foreach from=$parents item=item}
<div>{if $item.sexe == 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" />{elseif $item.sexe == 2}<img src="{copixurl}themes/default/images/icon-16/user-female.png" />{/if}
 {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</div>
{/foreach}
</div>
{elseif $enfants}
<hr />
<div style="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisEnfants"}</div>
<div id="">
{foreach from=$enfants item=item}
<div>{if $item.sexe == 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" />{elseif $item.sexe == 2}<img src="{copixurl}themes/default/images/icon-16/user-female.png" />{/if} {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</div>
{/foreach}
</div>
{/if}
</div>

</div>

{/if}