<table border="0" cellpadding="10" cellspacing="10" width="100%" class="regroupements">
<tr height="100">
	<td width="30%" bgcolor="white" valign="top">
		<div class="button_like"><a href="{copixurl dest="regroupements|villes|" groupe="0"}" class="button button-add">Nouveau regroupement</a></div>
		<div>
			<h2>Regroupements existants</h2>
			{if $grvilles_list|@count gt 0}
				<ul>
				{foreach from=$grvilles_list item=grvilles_item}
					<li><a href="{copixurl dest="regroupements|villes|" groupe=$grvilles_item->id}">{$grvilles_item->nom}</a></li>
				{/foreach}
				</ul>
			{/if}
		</div>
	</td>
	
	<td width="70%" bgcolor="white" valign="top">
		<h2>{if $grvilles_id}Modifier un regroupement{else}Cr&eacute;er un regroupement{/if}</h2>
		<form method="POST">
			<input id="form_id" name="form_id" type="hidden" value="{$grvilles_infos->id}" />
			<input id="save" name="save" type="hidden" value="1" />
			
			<p class="form_nom">
			<label for="form_nom" class="gras">Nom :</label>
			<input id="form_nom" name="form_nom" value="{$grvilles_infos->nom|escape}" size="50"/>
			</p>
			
			<p class="form_villes">
			<label for="form_villes" class="gras">Villes associ&eacute;es</label>
			<div class="villes">
				<table border="0" width="100%">
					<tr>
						{foreach from=$villes item=ville}
							{assign var=id_ville value=$ville->vil_id_vi}
							<td width="33%"><input type="checkbox" name="ville_{$id_ville}" id="ville_{$id_ville}" value="1"{if $grvilles_villes.$id_ville} CHECKED{/if} /> <label for="ville_{$id_ville}">{$ville->vil_nom}</label></td>
							{cycle values=",,</tr><tr>"}
						{/foreach}
					</tr>
				</table>
			</div>
			</p>
			
			<p class="form_submit">
				{if $grvilles_id}<a href="#delete_confirm" onclick="$('#delete_confirm').show();" class="button button-delete">Supprimer ce groupement</a> {/if}
				<input class="button button-save" type="submit" value="Enregistrer" />
			</p>
			
			{if $grvilles_id}
			<p id="delete_confirm" style="display: none;">
				<a href="{copixurl dest="regroupements|villes|" delete=$grvilles_infos->id}" class="button button-confirm">Confirmez la suppression</a>
				<a href="#" class="button button-cancel" onclick="$('#delete_confirm').hide();">annuler</a>
			</p>
			{/if}
			
		</form>
	</td>
</tr>
</table>
