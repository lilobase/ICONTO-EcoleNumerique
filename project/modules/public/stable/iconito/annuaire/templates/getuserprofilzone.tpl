
<DIV>
<DIV ALIGN="RIGHT" STYLE="font-size:80%;"><A HREF="#" ONCLICK="return hideUser();">{i18n key="annuaire.btn.close"}</A></DIV>
<!--<DIV ALIGN="RIGHT">{$usr.civilite}</DIV>-->

<DIV STYLE="font-weight:bold; font-size:140%; margin-top: 3px; margin-bottom: 3px;">{if $usr.avatar}<IMG SRC="{$usr.avatar}" ALIGN="RIGHT" />{/if}{if $usr.sexe}<IMG src="{copixresource path="img/annuaire/sexe{$usr.sexe}b.png"}" width="15" height="17" /> {/if}{$usr.prenom} {$usr.nom}</DIV>

<!-- 
<DIV ALIGN="RIGHT">
{if $usr.ALL->ele_date_nais}Né(e) le {$usr.ALL->ele_date_nais|datei18n:"date_short"}{/if}
</DIV>
-->


<div>

{$usr.type_nom}<br/>

{if $usr.login}{i18n key="annuaire.minimail"} : {$usr.login} <A HREF="{copixurl dest="minimail||getNewForm" login=$usr.login}"><IMG WIDTH="12" HEIGHT="9" src="{copixresource path="img/minimail/new_minimail.gif"}" ALT="{i18n key="annuaire.writeMinimail"}" TITLE="{i18n key="annuaire.writeMinimail"}" BORDER="0" /></A>{/if}


{if $parents}
<HR NOSHADE SIZE="1" />
<DIV STYLE="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisParents"}</DIV>
<DIV ID="">
{foreach from=$parents item=item}
<DIV>{if $item.sexe}<IMG src="{copixresource path="img/annuaire/sexe`$item.sexe`b.png"}" border="0" width="15" height="17" />{/if} {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</DIV>
{/foreach}
</DIV>
{elseif $enfants}
<HR NOSHADE SIZE="1" />
<DIV STYLE="font-weight:bold; font-size:110%;">{i18n key="annuaire.hisEnfants"}</DIV>
<DIV ID="">
{foreach from=$enfants item=item}
<DIV>{if $item.sexe}<IMG src="{copixresource path="img/annuaire/sexe`$item.sexe`b.png"}" border="0" width="15" height="17" />{/if} {user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=1}

</DIV>
{/foreach}
</DIV>
{/if}
</div>

</DIV>
