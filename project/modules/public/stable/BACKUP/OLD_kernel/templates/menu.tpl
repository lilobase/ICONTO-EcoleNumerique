
<div id="menu">
<ul>
	<li{if $ppo->level_0 == 'instruction'} id="current"{/if}><a href="{copixurl dest="instruction||"}">Instruction</a></li>
	<li{if $ppo->level_0 == 'presences'} id="current"{/if}><a href="{copixurl dest="presences||"}">Pr&eacute;sences</a></li>
	<li{if $ppo->level_0 == 'facturation'} id="current"{/if}><a href="{copixurl dest="facturation||"}">Facturation</a></li>
	<li{if $ppo->level_0 == 'personnel'} id="current"{/if}><a href="{copixurl dest="personnel||"}">Personnel</a></li>
	<li{if $ppo->level_0 == 'gestion'} id="current"{/if}><a href="{copixurl dest="gestion||"}">Gestion</a></li>
{if $ppo->user->testCredential ('group:[Admin]')}
	<li{if $ppo->level_0 == 'kernel'} id="current"{/if}><a href="{copixurl dest="kernel||"}">Admin.</a></li>
	<li{if $ppo->level_0 == 'admin'} id="current"{/if}><a href="{copixurl dest="admin||"}">Adm. Copix</a></li>
{/if}
</ul>
</div>


<div id="ss_menu">

&nbsp;

{if $ppo->level_0 == 'instruction'}
<a href="{copixurl dest="instruction|dossiers|"}"{if $ppo->level_1 == 'dossiers'} class="current"{/if}>Dossiers</a>
<a href="{copixurl dest="instruction|commissions|"}"{if $ppo->level_1 == 'commissions'} class="current"{/if}>Commissions</a>

{elseif $ppo->level_0 == ''}
{elseif $ppo->level_0 == 'etats'}
{elseif $ppo->level_0 == 'gestion'}
<a href="{copixurl dest="gestion|structures|"}"{if $ppo->level_1 == 'structures'} class="current"{/if}>Structures</a>
{/if}


</div>

