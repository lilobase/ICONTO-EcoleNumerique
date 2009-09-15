


<br/>
<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.date"}</th>
		{if $ville}<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.ecole"}</th>{/if}
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.objet"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.statut"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.type"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.dern.action"}</th>
	</tr>
 {foreach from=$liste item=r}
 
 	<tr CLASS="list_line{cycle values="0,1"}">
 
    <td>{$r->dateinter|datei18n:"date_short"}</td>
		{if $ville}<td>{$r->ecole_nom|escape}{if $r->ecole_type} ({$r->ecole_type|escape}){/if}</td>{/if}
	 
	  <td><a title="Fiche" href="{copixurl dest="|fiche" id=$r->idinter}">{$r->objet|escape}</a></td>
	  <td CLASS="itv_statut{$r->idstatu}">{$r->nomstat|escape}</td>
	  <td>{$r->nomtype|escape}</td>	  
	  <td align="center">{if $r->datederniere == '0000-00-00 00:00:00'} aucune
	  {else} {$r->datederniere|datei18n:"date_short"} {/if}</td>
  {/foreach}

 
</table>






