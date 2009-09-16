
{if $admin || $canInsert}
	{if $admin}<div class="titre_zone">{i18n key="teleprocedures.title.adminType"}</div>
	{else}<div class="titre_zone">{i18n key="teleprocedures.title.newTelep"}</div>
{/if}

<ul>
{foreach from=$list item=r}
<li>{if $admin}

<a title="{i18n key="kernel|kernel.btn.modify"}" class="is_online{$r->is_online}" href="{copixurl dest="admin|formtype" idtype=$r->idtype}"><img src="{copixresource path="img/edit_16x16.gif"}" alt="edit_16x16.gif" align="right" width="8" height="8" /> {$r->nom|htmlentities}</a>{* - <a href="{copixurl dest="|insert" idtype=$r->idtype}">{i18n key="kernel|kernel.btn.delete"}</a>*}

{else}<a href="{copixurl dest="|insert" idtype=$r->idtype}">{$r->nom|htmlentities}</a>{/if}

</li>


{/foreach}


</ul>

{/if}

{if $admin}<p></p><div align="center"><a title="" href="{copixurl dest="admin|formtype" teleprocedure=$rTelep->id}"><img src="{copixresource path="img/add_16x16.gif"}" alt="edit_16x16.gif" width="8" height="8" /> {i18n key="teleprocedures.title.newType"}</div></a>{/if}

