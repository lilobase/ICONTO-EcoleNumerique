
<div class="navigation">
<ul>
{foreach from=$tree->groupes key=grville_key item=grville_value}
<li><a href="{copixurl dest="comptes||getNode" type="BU_GRVILLE" id="$grville_key"}">{$grville_value->info.nom}</a>

	<ul>
	{foreach from=$grville_value->villes key=ville_key item=ville_value}
	<li><a href="{copixurl dest="comptes||getNode" type="BU_VILLE" id="$ville_key"}">{$ville_value->info.nom}</a>

		<ul>
		{foreach from=$ville_value->ecoles key=ecole_key item=ecole_value}
		<li><a href="{copixurl dest="comptes||getNode" type="BU_ECOLE" id="$ecole_key"}">{$ecole_value->info.nom}</a>
	
			<ul>
			{foreach from=$ecole_value->classes key=classe_key item=classe_value}
			<li><a href="{copixurl dest="comptes||getNode" type="BU_CLASSE" id="$classe_key"}">{$classe_value->info.nom}</a>
		
			</li>
			{/foreach}
			</ul>
	
		</li>
		{/foreach}
		</ul>

	</li>
	{/foreach}
	</ul>

</li>
{/foreach}
</ul>
</div>