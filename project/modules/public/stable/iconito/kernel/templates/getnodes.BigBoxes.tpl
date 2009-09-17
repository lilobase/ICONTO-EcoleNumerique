{if $data neq null}
	{foreach from=$data item=val_grville key=key_grville}
		{if $key_grville|string_format:"%d" == $key_grville}
		{if isset($val_grville.droit)}
			{assign var=this value=$val_grville}
			<a class="box{if isset($this.info.selected) and $this.info.selected} selected{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}" style="background-image: url(/img/iconito/kernel/kernel_{$this.info.type}_light.gif);">
			<p>
			{$this.info.type_nom} - {$this.info.nom}
			({$this.droit})
			</p>
			</a>
		{/if}

		{foreach from=$val_grville item=val_ville key=key_ville}
			{if $key_ville|string_format:"%d" == $key_ville}
			{if isset($val_ville.droit)}
				{assign var=this value=$val_ville}
				<a class="box{if isset($this.info.selected) and $this.info.selected} selected{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}" style="background-image: url(/img/iconito/kernel/kernel_{$this.info.type}_light.gif);">
				<p>
				{$this.info.type_nom} - {$this.info.nom}
				({$this.droit})
				</p>
				</a>
			{/if}

			{foreach from=$val_ville item=val_ecole key=key_ecole}
				{if $key_ecole|string_format:"%d" == $key_ecole}
				{if isset($val_ecole.droit)}
					{assign var=this value=$val_ecole}
					<a class="box{if isset($this.info.selected) and $this.info.selected} selected{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}" style="background-image: url(/img/iconito/kernel/kernel_{$this.info.type}_light.gif);">
					<p>
					{$this.info.type_nom} - {$this.info.nom}
					({$this.droit})
					</p>
					</a>
				{/if}

				{foreach from=$val_ecole item=val_classe key=key_classe}
					{if $key_classe|string_format:"%d" == $key_classe}
					{if isset($val_classe.droit)}
						{assign var=this value=$val_classe}
						<a class="box{if isset($this.info.selected) and $this.info.selected} selected{/if}" href="{copixurl dest="kernel||doSelectHome" type=$this.info.type id=$this.info.id}" style="background-image: url(/img/iconito/kernel/kernel_{$this.info.type}_light.gif);">
						<p>
						{$this.info.type_nom} - {$this.info.nom}
						({$this.droit})
						</p>
						</a>
					{/if}

					{/if}
				{/foreach}
				{/if}
			{/foreach}
			{/if}
		{/foreach}
		{/if}
	{/foreach}
{else}
	Votre identifiant n'existe pas dans la base unique GAEL, vous n'avez donc pas de zone de travail en particulier liée à une ville, une école ou une classe.
{/if}
<br clear="both"/>

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
