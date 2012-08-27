<h3>{i18n key="sysutils|groupes.msg.groupe"} : "{$groupe_infos[0]->titre|escape}"</h3>

<form enctype="multipart/form-data" method="post" action="{copixurl dest="sysutils|groupes|add_admin"}">

<input name="save"   value="1"            type="hidden"/>
<input name="groupe" value="{$groupe_id}" type="hidden"/>

<p>{i18n key="groupes.txt.add_admin"}</p>
<textarea id="new_admins" class="form" rows="2" cols="78" name="new_admins"></textarea>
<script src="/js/iconito/module_annuaire.js" type="text/javascript"></script>
<a class="button button-directory" href="javascript:open_annuaire('new_admins', 'communiquer');">{i18n key="sysutils|groupes.msg.lookup"}</a>

<p class="center">
<a class="button button-cancel" href="{copixurl dest="sysutils|groupes|"}">{i18n key="sysutils|groupes.msg.cancel"}</a>
<input class="button button-confirm" type="submit" value="{i18n key="sysutils|groupes.msg.submit"}">
</p>

</form>
