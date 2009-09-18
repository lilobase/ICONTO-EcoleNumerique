{$groupes}

{if $data neq null}
<div class="zone" style="width:520px;">
	{i18n key="kernel|kernel.getnodes.message"}<p>

	{if isset($data.ROOT)}
		{assign var=this value=$data.ROOT}
		<a class="zone{if isset($this.info.selected) and $this.info.selected} sel{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}">
		{$this.info.type_nom} - {$this.info.nom}
		</a><p>
	{/if}
	
	{foreach from=$data item=val_grville key=key_grville}
		{if $key_grville|string_format:"%d" == $key_grville}
		{if isset($val_grville.droit)}
			{assign var=this value=$val_grville}
			<p><a class="zone{if isset($this.info.selected) and $this.info.selected} sel{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}">
			{$this.info.type_nom} - {$this.info.nom}
			</a></p>
		{/if}

		{foreach from=$val_grville item=val_ville key=key_ville}
			{if $key_ville|string_format:"%d" == $key_ville}
			{if isset($val_ville.droit)}
				{assign var=this value=$val_ville}
				<p><a class="zone{if isset($this.info.selected) and $this.info.selected} sel{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}">
				{$this.info.type_nom} - {$this.info.nom}
				</a></p>
			{/if}

			{foreach from=$val_ville item=val_ecole key=key_ecole}
				{if $key_ecole|string_format:"%d" == $key_ecole}
				{if isset($val_ecole.droit)}
					{assign var=this value=$val_ecole}
					<p><a class="zone{if isset($this.info.selected) and $this.info.selected} sel{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}">
					{$this.info.type_nom}{if $this.info.type_nom_plus} ({$this.info.type_nom_plus}){/if} - {$this.info.nom}
					</a></p>
				{/if}

				{foreach from=$val_ecole item=val_classe key=key_classe}
					{if $key_classe|string_format:"%d" == $key_classe}
					{if isset($val_classe.droit)}
						{assign var=this value=$val_classe}
						<p>&nbsp;&nbsp;<a class="zone{if isset($this.info.selected) and $this.info.selected} sel{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}">{$this.info.type_nom} - {$this.info.nom}</a></p>
					{/if}

					{/if}
				{/foreach}
				{/if}
			{/foreach}
			{/if}
		{/foreach}
		{/if}
	{/foreach}
</div>
<br clear="left"/>
{elseif !$data_children}
	{i18n key="kernel|kernel.getnodes.erreur"}
<br clear="left"/>
{/if}


{if $data_children neq null}
<div class="zone" style="width:520px;">
	{foreach from=$data_children item=child}
    <div class="enfant"><div class="classe">{$child.info.classe}</div>{$child.info.prenom} {$child.info.nom}</div>
		<a
		class="box_M"
		href="index.php?module=carnet&desc=default&action=go&id=ELEVE_{$child.info.id}">
		<img src="{copixresource path="img/iconito/kernel/module_MOD_CARNET_M.gif"}" border=0 alt="{i18n key="kernel.codes.mod_carnet"}" title="{i18n key="kernel.codes.mod_carnet"}"><br/>
<span class="modname">{i18n key="kernel.codes.mod_carnet"}</span>
		</a>
	  
    {foreach from=$child.modules item=mod}
    {assign var="module_type_array" value="_"|split:$mod->module_type|lower}
    <a
		class="box_M"
href="index.php?module={$module_type_array[1]}&desc=default&action=go&id={$mod->module_id}">
		<img src="{copixresource path="img/iconito/kernel/module_`$mod->module_type`_M.gif"}" border=0 alt="{$mod->module_nom|htmlentities}" title="{$mod->module_nom|htmlentities}"><br/>
<span class="modname">{$mod->module_nom}</span>
		</a>
    {/foreach}
	{/foreach}
<br clear="left"/>
</div>	

<br/>
{/if}
	
	

<!--

{if $data neq null}
	{foreach from=$data item=val_grville key=key_grville}
		{if $key_grville|string_format:"%d" == $key_grville}
		<h1>
		{if isset($val_grville.droit)}
			<a href="{copixurl dest="kernel||doSelectHome" type=$val_grville.info.type id=$val_grville.info.id}">{$val_grville.info.nom}</a> ({$val_grville.droit})
		{else}
			{$val_grville.info.nom}
		{/if}
		</h1>
		{foreach from=$val_grville item=val_ville key=key_ville}
			{if $key_ville|string_format:"%d" == $key_ville}
			<h2>{$val_ville.info.nom}</h2>
			{foreach from=$val_ville item=val_ecole key=key_ecole}
				{if $key_ecole|string_format:"%d" == $key_ecole}
				<h3>{$val_ecole.info.nom}</h3>
				{foreach from=$val_ecole item=val_classe key=key_classe}
					{if $key_classe|string_format:"%d" == $key_classe}
						<h4>{$val_classe.info.nom}</h4>
					{/if}
				{/foreach}
				{/if}
			{/foreach}
			{/if}
		{/foreach}
		{/if}
	{/foreach}
{else}
	Il n'y a aucun lien avec la base unique.
{/if}

-->
