
<table align="center" cellspacing="1" BORDER="0" class="filtre">
<tr>
{if $admin}
<form action="{copixurl dest="teleprocedures|admin|admin"}" method="get">
{else}
<form action="{copixurl dest="teleprocedures||listTeleprocedures"}" method="get">
{/if}
<input type="hidden" name="id" value="{$rTelep->id}" />
	<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.filtre.kw"}</td><td><input type="text" name="motcle" value="{$motcle|escape}" CLASS="form motcle" /></td>
	
	{if $canViewComboEcoles}
		<td CLASS="form_libelle">Ecole :</td>
		<td width="" CLASS="form_saisie">{copixzone process='annuaire|comboecolesinville' ville=$rTelep->parent.id value=$ecole fieldName='ecole' attribs='class="form ecoles"' linesSup=$comboEcolesLinesSup}</td>
	{/if}
	
	<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.filtre.type"}</td>
	
	<td width="" CLASS="form_saisie"> 
	
	{i18n key="teleprocedures|teleprocedures.combo.types.all" assign="all"}
	<select name="type" CLASS="form types">
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

