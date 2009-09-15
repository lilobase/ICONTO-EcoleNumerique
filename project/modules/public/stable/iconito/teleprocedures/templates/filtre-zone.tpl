
<table align="center" cellspacing="1" BORDER="0">
<tr>
<form action="{copixurl dest="|"}" method="get">
<input type="hidden" name="module" value="teleprocedures" />
{if $admin}
<input type="hidden" name="desc" value="admin" />
<input type="hidden" name="action" value="admin" />
{else}
<input type="hidden" name="action" value="listTeleprocedures" />
{/if}
<input type="hidden" name="id" value="{$rTelep->id}" />
	<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.filtre.kw"}</td><td><input type="text" name="motcle" value="{$motcle|escape}" CLASS="form" /></td>
	
	{if $canViewComboEcoles}
		<td CLASS="form_libelle">Ecole :</td>
		<td width="" CLASS="form_saisie">{$comboEcoles}</td>
	{/if}
	
	<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.filtre.type"}</td>
	
	<td width="" CLASS="form_saisie"> 
	
	{i18n key="teleprocedures|teleprocedures.combo.types.all" assign="all"}
	<select name="type" CLASS="form" STYLE="">
	<option value=""{if !$type} SELECTED{/if}>{$all|escape}</option>
	{foreach from=$arTypes item=t}
		<option value="{$t->idtype}"{if $type eq $t->idtype} SELECTED{/if}>{$t->nom|escape}</option>
	{/foreach}
	</select>
	
	</td>
	
	{if !$canViewComboEcoles}
		<td CLASS="form_saisie" align="right"><label for="clos">{i18n key="teleprocedures|teleprocedures.filtre.masquer.clos"} </label><input type="checkbox" id="clos" name="clos" value="1" {if $clos eq 1}CHECKED{/if} /></td>
		<td><input type="submit" value="{i18n key="kernel|kernel.btn.filtrer"}" CLASS="form_button"></td>
	</tr>
	{else}
		<td rowspan="2"><input type="submit" value="{i18n key="kernel|kernel.btn.filtrer"}" CLASS="form_button"></td>
	</tr>
	<tr>
		<td CLASS="form_saisie" colspan="6" align="right"><label for="clos">{i18n key="teleprocedures|teleprocedures.filtre.masquer.clos"} </label><input type="checkbox" id="clos" name="clos" value="1" {if $clos eq 1}CHECKED{/if} /></td>
	</tr>
	{/if}
	
	
	
</tr>
</table>

