{if $titre}<div class="titre">{$titre}</div>{/if}

{assign var=i value=0}
{assign var=lastType value=''}

<ul>
{foreach from=$list item=ecole}
	{if $ecole.id>0}
		{if $groupBy eq 'type' && $ecole.type neq $lastType}
			<li class="type">
			{if $ecole.type eq 'Elémentaire' || $ecole.type eq 'ElÃ©mentaire'}{i18n key="welcome|welcome.ecoles.type.elem"}
			{elseif $ecole.type eq 'Primaire'}{i18n key="welcome|welcome.ecoles.type.prim"}
			{elseif $ecole.type eq 'Maternelle'}{i18n key="welcome|welcome.ecoles.type.mat"}
			{elseif $ecole.type eq 'Centre de Loisirs'}{i18n key="welcome|welcome.ecoles.type.lois"}
			{elseif $ecole.type}{$ecole.type}
			{/if}
			</li>
		{/if}
		
		<li>
		{if $ajaxpopup}
			<a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}" onClick="return ajaxFicheEcole({$ecole.id});">{$ecole.nom}</a>
		{else}
			<a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom}</a>
		{/if}
		{if $dispType && $ecole.type} ({$ecole.type}) {/if}
		</li>
		
		{assign var=i value=$i+1}
		{assign var=lastType value=$ecole.type}
		
	{/if}
{/foreach}
</ul>
