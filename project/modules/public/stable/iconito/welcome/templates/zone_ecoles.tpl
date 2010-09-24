
{if $titre}<div class="titre">{$titre}</div>{/if}

{assign var=i value=0}
{assign var=lastType value=''}
{assign var=lastVille value=''}

{if $list}
  {foreach from=$list item=ecole}
  	{if $ecole.id>0}
      
      {if $parCols>1}
  			{if $i%$parCols eq 0}
  				{if $i>0}</ul></div>{/if}		
  				<div style="float:left;width:{$widthColonne};">
          <ul>
  			{/if}
  		{/if}
      
  		{if $groupBy eq 'type' && $ecole.type neq $lastType}
  			<li class="type">
  			{if $ecole.type eq 'Elémentaire' || $ecole.type eq 'ElÃ©mentaire'}{i18n key="welcome|welcome.ecoles.type.elem"}
  			{elseif $ecole.type eq 'Primaire'}{i18n key="welcome|welcome.ecoles.type.prim"}
  			{elseif $ecole.type eq 'Maternelle'}{i18n key="welcome|welcome.ecoles.type.mat"}
  			{elseif $ecole.type eq 'Centre de Loisirs'}{i18n key="welcome|welcome.ecoles.type.lois"}
  			{elseif $ecole.type}{$ecole.type|escape}
  			{/if}
  			</li>
  		{elseif $groupBy eq 'ville' && $ecole.ville neq $lastVille}
  			<li class="type">
  			{$ecole.ville_nom|escape}
  			</li>
  		{/if}
  		
  		<li>
  		{if $ajaxpopup}
  			<a class="fancybox" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}">{$ecole.nom|escape}</a>
  		{else}
  			<a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom|escape}</a>
  		{/if}
  		{if $dispType && $ecole.type} ({$ecole.type|escape}) {/if}
  		</li>
  		
  		{assign var=i value=$i+1}
  		{assign var=lastType value=$ecole.type}
  		{assign var=lastVille value=$ecole.ville}
  		
  	{/if}
  {/foreach}
  {if $parCols>1 && $i>0}</ul></div>{/if}
	{if $parCols>1 }
		<br clear="left" />
	{/if}
{else}
  <p>{i18n key=welcome.ecoles.aucune}</p>
{/if}