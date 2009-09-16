
<div id="welcome_ecoles">

{if $titre}<div class="titre">{$titre}</div>{/if}

{*
<img alt="{i18n key="public|public.openPopup"}" title="{i18n key="annuaire|annuaire.fiche"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_popup.png"}" hspace="1" />
*}

{assign var=i value=0}

{foreach from=$list item=ecole}
	{if $ecole.id>0}
		{if $i%$parCols eq 0}
			{if $i>0}</div>{/if}		
			<div style="float:left;width:{$widthColonne};">
		{/if}
		
		{if $ajax}<a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}" onClick="return ajaxFicheEcole({$ecole.id});">{$ecole.nom}</a>{else}<a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom}</a>{/if}{if $ecole.type} ({$ecole.type}){/if}<br/>
		
		{assign var=i value=$i+1}
	{/if}
{/foreach}

	{if $i>0}</div>{/if}		

	<br clear="left" />

</div>
