
{if !$usr}
	{i18n key="kernel|kernel.error.noRights"}
{else}

<div>
<div class="right" style="font-size:80%;"><a href="#" onClick="return hideUser();">{i18n key="annuaire.btn.close"}</a></div>
<!--<DIV ALIGN="RIGHT">{$usr.civilite}</DIV>-->

<div style="font-weight:bold; font-size:140%; margin-top: 3px; margin-bottom: 3px;">{if $usr.avatar}<img src="{copixurl}{$usr.avatar}" align="right" />{/if}{if $usr.sexe}<img src="{copixresource path="img/annuaire/sexe`$usr.sexe`b.png"}" width="15" height="17" /> {/if}{$usr.prenom|escape} {$usr.nom|escape}</div>

<!-- 
<DIV ALIGN="RIGHT">
{if $usr.ALL->ele_date_nais}Né(e) le {$usr.ALL->ele_date_nais|datei18n:"date_short"}{/if}
</DIV>
-->


<div>

{$usr.type_nom}<br/>

{if $canWrite && $usr.login}{i18n key="annuaire.minimail"} : {$usr.login} <A HREF="{copixurl dest="minimail||getNewForm" login=$usr.login}"><IMG WIDTH="12" HEIGHT="9" src="{copixresource path="img/minimail/new_minimail.gif"}" ALT="{i18n key="annuaire.writeMinimail"}" TITLE="{i18n key="annuaire.writeMinimail"}" BORDER="0" /></A>{/if}

{if $parents}
<hr NOSHADE SIZE="1" />
<div style="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisParents"}</div>
<div id="">
{foreach from=$parents item=item}
<div>{if $item.sexe}<img src="{copixresource path="img/annuaire/sexe`$item.sexe`b.png"}" border="0" width="15" height="17" />{/if} {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</div>
{/foreach}
</div>
{elseif $enfants}
<hr NOSHADE SIZE="1" />
<div style="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisEnfants"}</div>
<div id="">
{foreach from=$enfants item=item}
<div>{if $item.sexe}<IMG src="{copixresource path="img/annuaire/sexe`$item.sexe`b.png"}" border="0" width="15" height="17" />{/if} {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</div>
{/foreach}
</div>
{/if}
</div>

</div>

{/if}