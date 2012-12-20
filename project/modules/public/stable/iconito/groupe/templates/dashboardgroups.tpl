{if $can_group_showlist || $canCreate}
<h1>{i18n key="groupe.groups"}</h1>
<ul>
{if $can_group_showlist}<li><a title="{i18n key="groupe.annuaire"}" href="{copixurl dest="|getListPublic"}">{i18n key="groupe.annuaire"}</a></li>{/if}
{if $canCreate}<li><a title="{i18n key="groupe.btn.addGroup"}" href="{copixurl dest="|getEdit"}">{i18n key="groupe.btn.addGroup"}</a></li>{/if}
</ul>
{/if}

