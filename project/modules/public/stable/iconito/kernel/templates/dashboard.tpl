
<div class="dashboard kernel_dash tools_left ink_blue font_dash">
<div class="border_b font_cursive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tableau d'affichage&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
{copixzone process=rssnotifier|dashboardrss}
{foreach from=$nodes key=node_type item=nodes_list}
{foreach from=$nodes_list key=node_id item=node_data}

	{* if $modules neq null *}
	<div class="dashpanel {$node_type|lower}">
		<h1 class="title">
			<span>{if $node_data.type eq "USER_ELE"}{$node_data.prenom|escape} {$node_data.nom|escape} {if !empty($node_data.nom_classe)}({$node_data.nom_classe|escape}){/if}{else}{$node_data.nom|escape}{/if}</span>
		</h1>
		<div class="content">
                    {$node_data.content}
		</div>
		<div class="toolset">
		<ul class="opacity50">
		{foreach from=$node_data.modules item=val_modules key=key_modules}
			{assign var="module_type_array" value="_"|explode:$val_modules->module_type|lower}
			<li>
				{if $val_modules->notification_number gt 0}
				<a class="counter" href="{copixurl dest="kernel||go" ntype=$val_modules->node_type nid=$val_modules->node_id mtype=$module_type_array[1] mid=$val_modules->module_id}" title="{$val_modules->notification_message}">
				<span class="counter-text">{$val_modules->notification_number}</span>
				</a>
				{/if}
				<a
			{if $val_modules->module_popup}target="_blank"{/if}
			class="{$val_modules->module_type}{if isset($this.info.selected) and $this.info.selected} selected{/if}"
			href="{copixurl dest="kernel||go" ntype=$val_modules->node_type nid=$val_modules->node_id mtype=$module_type_array[1] mid=$val_modules->module_id}"
			title="{$val_modules->module_nom}"><span class="valign"></span><span class="label">{$val_modules->module_nom}</span></a></li>
		{/foreach}
		</ul>
		</div>
	</div>
	{* else}
		{i18n key="kernel|kernel.getmodules.pasdemodule"}
	{/if *}
{*			href="{copixurl dest="$module_type_array[1]||go" id=$val_modules->module_id}" *}
{/foreach}
{/foreach}

</div>
