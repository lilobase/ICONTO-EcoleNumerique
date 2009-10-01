
<br/>
{if count ($liste)}

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		{if $canViewDelai}<th CLASS="liste_th"></th>{/if}
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.date"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.dern.action"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.type"}</th>
		<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.objet"}</th>
		{if $ville}<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.ecole"}</th>{/if}
		{if $ville}<th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.role"}</th>{/if}
		
		{* <th CLASS="liste_th">{i18n key="teleprocedures|teleprocedures.intervention.field.statut"}</th> *}
		
	</tr>
 {foreach from=$liste item=r}
 
 	<tr CLASS="list_line{cycle values="0,1"}">
 		{if $canViewDelai}<td align="center">
		{assign var=puce value=-1}
		{if $r->idstatu eq 3}{assign var=puce value=0}
		{elseif $r->depuis<=2}{assign var=puce value=1}
		{elseif $r->depuis<=5}{assign var=puce value=2}
		{elseif $r->depuis>5}{assign var=puce value=3}
		{/if}
		
		
		<img src="{copixresource path="img/teleprocedures/puce_delai`$puce`.gif"}" alt="puce_delai{$puce}.gif" width="14" height="14" />
		</td>{/if}
    <td class="discret" align="center">{$r->dateinter|datei18n:"date_short"}</td>
		<td align="center">{if $r->datederniere=='0000-00-00 00:00:00'} aucune
	  {else} {$r->datederniere|datei18n:"date_short"} {/if}</td>
		<td>{$r->nomtype|escape}</td>
		<td>{if $r->last_visite eq null or $r->last_visite<$r->datederniere}<img src="{copixresource path="img/icon_new.gif"}" alt="icon_new.gif" width="17" height="17" border="0" align="right" />{/if}<a title="Fiche" href="{copixurl dest="|fiche" id=$r->idinter}">{$r->objet|escape}</a></td>	 
		{if $ville}<td class="discret">{$r->ecole_nom|escape}{if $r->ecole_type} ({$r->ecole_type|escape}){/if}</td>{/if}
	 {if $ville}<td class="discret">{$r->droit_nom|escape}</td>{/if}

	  
	  {* <td CLASS="itv_statut{$r->idstatu}">{$r->nomstat|escape}</td> *}
	   
	  
  {/foreach}

 
</table>

<div class="legende">

L&eacute;gende : 

{if $canViewDelai}
<img src="{copixresource path="img/teleprocedures/puce_delai1.gif"}" alt="puce_delai0.gif" width="14" height="14" /> d&eacute;lai inf&eacute;rieur &agrave; 2 jours | <img src="{copixresource path="img/teleprocedures/puce_delai2.gif"}" alt="puce_delai2.gif" width="14" height="14" /> d&eacute;lai entre 3 et 5 jours | <img src="{copixresource path="img/teleprocedures/puce_delai3.gif"}" alt="puce_delai3.gif" width="14" height="14" /> d&eacute;lai sup&eacute;rieur &agrave; 5 jours | <img src="{copixresource path="img/teleprocedures/puce_delai0.gif"}" alt="puce_delai0.gif" width="14" height="14" /> proc&eacute;dure close | 
{/if}

<img src="{copixresource path="img/icon_new.gif"}" alt="icon_new.gif" width="14" height="14" border="0" /> du neuf &agrave; lire

</div>

	{else}
	<div align="center"><i>	Aucune t&eacute;l&eacute;proc&eacute;dure</i></div>
	<br/>

{/if}
