

{if not $errors eq null}
	<div id="dialog-message" class="mesgErrors" title="{i18n key=kernel|kernel.error.problem}">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul></div>
{elseif not $ok eq null}
	<div class="mesgSuccess">
	<ul>
	{foreach from=$ok item=item}
		<li>{$item}</li>
	{/foreach}
	</ul></div>
{/if}

<div class="teleprocedures_titre">D&eacute;tail de la t&eacute;l&eacute;proc&eacute;dure</div>
<div class="demande">

		{assign var=puce value=-1}
		{assign var=puceLib value=""}
		{if $rFiche->idstatu eq 3}{assign var=puce value=0}
		{elseif $rFiche->depuis<=2}{assign var=puce value=1}{assign var=puceLib value=$rFiche->depuis|cat:" jour(s)"}
		{elseif $rFiche->depuis<=5}{assign var=puce value=2}{assign var=puceLib value=$rFiche->depuis|cat:" jours"}
		{elseif $rFiche->depuis>5}{assign var=puce value=3}{assign var=puceLib value=$rFiche->depuis|cat:" jours"}
		{/if}

<div class="entete">
	
	<table width="100%">
	<tr>
	
	<td class="left enteteLeft{if $canViewDelai} entete{$puce}{else} enteteDir{/if}">
		<div class="left">
		T&eacute;l&eacute;proc&eacute;dure initi&eacute;e le<br/>{$rFiche->dateinter|datei18n}<br/>par {user_id id=$rFiche->iduser}
		<div class="statu">{$rFiche->idstatu_nom|escape}</div>
		{if $canViewDelai && $puceLib && $rFiche->depuis>0}Action attendue depuis
		<div class="action">{$puceLib}</div>{/if}
		</div>
	</td>
	
	

	<td class="ecole">
	<div class="ecole">
		
		{if !$print}<div class="imprimer noPrint"><a title="{i18n key="kernel|kernel.btn.print"}" class="button button-print" target="_blank" href="{copixurl dest="|fiche" id=$rFiche->idinter print=1}">{i18n key="kernel|kernel.btn.print"}</a></div>{/if}


		<div class="nom">{$rFiche->ecole_nom|escape}{if $rFiche->ecole_type} ({$rFiche->ecole_type|escape}){/if}</div>
		<span class="lib">{i18n key="teleprocedures|teleprocedures.intervention.field.directeur"}&nbsp;:</span> 
			{if $rFiche->ecole_dir}
				{foreach from=$rFiche->ecole_dir item=dir}
					{user label=$dir.nom|cat:' '|cat:$dir.prenom userType=$dir.type userId=$dir.id login=$dir.login dispMail=1} 
				{/foreach}
			{else}
				<i>Aucun d&eacute;fini</i>
			{/if}<br/>
		<span class="lib">{i18n key="teleprocedures|teleprocedures.intervention.field.tel"}&nbsp;:</span>  {$rFiche->ecole_tel|escape}<br/>
		<br/>
		<span class="lib">{i18n key="teleprocedures|teleprocedures.intervention.field.type"}&nbsp;:</span>  {$rFiche->idtype_nom|escape}<br/>
		<span class="lib">{i18n key="teleprocedures|teleprocedures.intervention.field.objet"}&nbsp;:</span>  {$rFiche->objet|escape}<br/>
    {if !$print}
		<span class="lib noPrint">Description d&eacute;taill&eacute;e :</span> Voir ci-dessous - <a href="#" onClick="$('#telep-details').toggle();">afficher / masquer</a><br/>
    {/if}
	</div>
	</td>
	
	{if $canViewDelai}
	<td class="right">
	<div class="right">
		<div class="lib">Responsable(s) du suivi :</div>
		{foreach from=$rFiche->tabResponsables item=pers}
			{user fromLogin=$pers dispMail=1}<br/>
		{foreachelse}
			<i>Personne</i><br/>
		{/foreach}
		<div class="lib">Lecteur(s) :</div>
		{foreach from=$rFiche->tabLecteurs item=pers}
			{user fromLogin=$pers dispMail=1}<br/>
		{foreachelse}
			<i>Personne</i><br/>
		{/foreach}
		
		{if $canDelegue}<div class="noPrint"><a href="{copixurl dest="|ficheDroits" id=$rFiche->idinter}">Ajouter/modifier un responsable ou un lecteur</a></div>{/if}
	</div>
	</td>
	{/if}
	
	
	</tr>
	</table>

</div> <!-- fin entete -->

<div class="details" id="telep-details">{$rFiche->detail|render:$rFiche->type_format}<div class="fixed"></div></div>

</div> <!-- fin demande -->

{if $print}
  {literal}
  <script type="text/javascript">
  jQuery(document).ready(function(){
    window.print();
  });
  </script>
  {/literal}
{/if}
