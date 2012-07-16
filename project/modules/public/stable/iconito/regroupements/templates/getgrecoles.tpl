{literal}
<script>
<!--
function ville_toggle( ville, mode ) {
	var childs = $('ecolesByVille').descendants();
	var reg = new RegExp('^ville_'+ville+'(_ecole_([0-9]+))?$');
	childs.each(function(node) {
		if (reg.exec(node.id)) {
			// alert(node);
			if(mode=='check') node.setValue(1);
			if(mode=='uncheck') node.setValue(0);
		}
	});
}
-->
</script>
{/literal}


<table border="0" cellpadding="10" cellspacing="10" width="100%" class="regroupements">
<tr height="100">
	<td width="30%" bgcolor="white" valign="top">
		<div class="button_like right"><a href="{copixurl dest="regroupements|ecoles|" groupe="0"}" class="button button-add">Nouveau regroupement</a></div>
		<div>
			<h2>Regroupements existants</h2>
			{if $grecoles_list|@count gt 0}
				<ul>
				{foreach from=$grecoles_list item=grecoles_item}
					<li><a href="{copixurl dest="regroupements|ecoles|" groupe=$grecoles_item->id}">{$grecoles_item->nom}</a></li>
				{/foreach}
				</ul>
			{/if}
		</div>
	</td>
	
	<td width="70%" bgcolor="white" valign="top">
		<h2>{if $grecoles_id}Modifier un regroupement{else}Cr&eacute;er un regroupement{/if}</h2>
		<form method="POST">
			<input id="form_id" name="form_id" type="hidden" value="{$grecoles_infos->id}" />
			<input id="save" name="save" type="hidden" value="1" />
			
			<p class="form_nom">
			<label for="form_nom" class="gras">Nom :</label>
			<input id="form_nom" name="form_nom" value="{$grecoles_infos->nom|escape}" size="50"/>
			</p>
			
			<p class="form_ecoles">
			<label for="form_ecoles" class="gras">Ecoles associ&eacute;es</label>
			<div class="ecolesByVille" id="ecolesByVille">
				<ul class="villes">
					{foreach from=$ecolesByVille item=ville}
						<li class="ville">
							<h1 class="ville">
								{$ville->info->vil_nom}
								<span>
									(<a href="javascript: ville_toggle({$ville->info->vil_id_vi}, 'check');">tout</a>)
									(<a href="javascript: ville_toggle({$ville->info->vil_id_vi}, 'uncheck');">rien</a>)
								</span>
							</h1>
							
							<ul class="ecoles">
							{foreach from=$ville->ecoles item=ecole}
								<li class="ecole">
								{assign var=id_ecole value=$ecole->eco_numero}
								<input type="checkbox" name="ecole_{$id_ecole}" id="ville_{$ville->info->vil_id_vi}_ecole_{$id_ecole}" value="1"{if $grecoles_ecoles.$id_ecole} CHECKED{/if} />
								<label for="ville_{$ville->info->vil_id_vi}_ecole_{$id_ecole}">{$ecole->eco_nom}</label>
								</li>
							{/foreach}
							</ul>
						</li>
					{/foreach}
				</ul>
			</div>
			</p>
			
			<p class="form_submit">
				{if $grecoles_id}<a href="#delete_confirm" onclick="$('#delete_confirm').show();" class="button button-delete">Supprimer ce groupement</a> - {/if}
				<input type="submit" value="Enregistrer" class="button button-save"/>
			</p>
			
			{if $grecoles_id}
			<p id="delete_confirm" style="display: none;">
				<a href="{copixurl dest="regroupements|ecoles|" delete=$grecoles_infos->id}" class="button button-confirm">Confirmez la suppression</a>
				<a href="#" class="button button-cancel" onclick="$('#delete_confirm').hide();">annuler</a>
			</p>
			{/if}
			
		</form>
	</td>
</tr>
</table>
