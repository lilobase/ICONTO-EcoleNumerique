
{$groupes}

<div class="boxes">

{if $modules neq null}
	<p>{i18n key="kernel|kernel.getmodules.listemodules"}</p>
	{foreach from=$modules item=val_modules key=key_modules}
		{assign var="module_type_array" value="_"|explode:$val_modules->module_type|lower}

		<a
		{if $val_modules->module_popup}target="_blank"{/if}
		class="box_M{if isset($this.info.selected) and $this.info.selected} selected{/if}"
		href="{copixurl dest="$module_type_array[1]||go" id=$val_modules->module_id}">
		<img src="{copixresource path="img/kernel/module_`$val_modules->module_type`_M.gif"}" border=0 alt="{$val_modules->module_nom}" title="{$val_modules->module_nom}"><br/>
<span class="modname">{$val_modules->module_nom}</span>
		</a>

	{/foreach}
{else}
	<p>{i18n key="kernel|kernel.getmodules.pasdemodule"}</p>
{/if}
</div>  
<br class="clear" />