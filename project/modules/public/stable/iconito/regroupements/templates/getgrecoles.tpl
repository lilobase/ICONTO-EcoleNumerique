<table border="0" cellpadding="10" cellspacing="10" width="100%" class="regroupements">
<tr height="100">
	<td width="30%" bgcolor="white" valign="top">
		<div class="button_like"><a href="{copixurl dest="regroupements|ecoles|" groupe="0"}" class="button_like">Nouveau regroupement</a></div>
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
			<div class="ecoles">
				<table border="0" width="100%">
					<tr>
						{foreach from=$ecoles item=ecole}
							{assign var=id_ecole value=$ecole->eco_numero}
							<td width="33%"><input type="checkbox" name="ecole_{$id_ecole}" id="ecole_{$id_ecole}" value="1"{if $grecoles_ecoles.$id_ecole} CHECKED{/if} /> <label for="ecole_{$id_ecole}">{$ecole->eco_nom}</label></td>
							{cycle values=",,</tr><tr>"}
						{/foreach}
					</tr>
				</table>
			</div>
			</p>
			
			<p class="form_submit">
				{if $grecoles_id}<a href="#delete_confirm" onclick="$('delete_confirm').style.display = 'block';" class="no_button_like">Supprimer ce groupement</a> - {/if}
				<input class="form_button" type="submit" value="Enregistrer" style="width: 75px;"/>
			</p>
			
			{if $grecoles_id}
			<p id="delete_confirm" style="display: none;">
				<a href="{copixurl dest="regroupements|ecoles|" delete=$grecoles_infos->id}" class="confirm">Confirmez la suppression</a>
				<a href="#" class="cancel" onclick="$('delete_confirm').style.display = 'none';">annuler</a>
			</p>
			{/if}
			
		</form>
	</td>
</tr>
</table>
