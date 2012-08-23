<form method="POST">
	<input type="hidden" name="user_type" value="{$ppo->ien->user_type}" />
	<input type="hidden" name="user_id"   value="{$ppo->ien->user_id}"   />
	<input type="hidden" name="save"      value="1" />
	
	<table cellspacing="1" cellpadding="5" border="0" align="center" width="90%" class="comptes_animateurs comptes_animateurs_edit">
		<tr>
			<td class="form_libelle" width="30%">Nom : </td>
			<td class="form_saisie">{$ppo->ien->user_infos.nom|escape}</td>
		</tr>
		<tr>
			<td class="form_libelle">Pr&eacute;nom : </td>
			<td class="form_saisie">{$ppo->ien->user_infos.prenom|escape}</td>
		</tr>
		
		<tr>
			<td class="form_libelle">Regroupements de villes : <div class="help">Vous pouvez associer ce compte IEN &agrave; des regroupements de ville.</div></td>
			<td class="form_saisie">
				{foreach from=$ppo->grvilles item=grville name=grvilles}
				{assign var=id_grville value=$grville->id}
				<div>
					<input type="checkbox" name="groupe_villes_{$grville->id}" id="groupe_villes_{$grville->id}" value="1" {if $ppo->ien_grville.$id_grville}checked{/if} /> <label for="groupe_villes_{$grville->id}">{$grville->nom|escape}</label>
				</div>
				{/foreach}
			</td>
		</tr>

		<tr>
			<td class="form_libelle">Regroupements d'&eacute;coles : <div class="help">Vous pouvez associer ce compte IEN &agrave; des regroupements d'&eacute;coles.</div></td>
			<td class="form_saisie">
				{foreach from=$ppo->grecoles item=grecole name=grecoles}
				{assign var=id_grecole value=$grecole->id}
				<div>
					<input type="checkbox" name="groupe_ecoles_{$grecole->id}" id="groupe_ecoles_{$grecole->id}" value="1" {if $ppo->ien_grecole.$id_grecole}checked{/if} /> <label for="groupe_ecoles_{$grecole->id}">{$grecole->nom|escape}</label>
				</div>
				{/foreach}
			</td>
		</tr>

		<tr>
			<td class="form_libelle">Annuaire : </td>
			<td class="form_saisie">
				<div>
					<input type="checkbox" name="annuaire" id="annuaire" value="1" {if $ppo->ien->is_visibleannuaire}checked{/if} /> <label for="annuaire">Visible dans l'annuaire des usagers, en tant que &laquo;IEN&raquo;</label>
				</div>
			</td>
		</tr>

		<tr>
			<td></td>
			<td>
				<a class="button button-delete" href="#delete_confirm" onclick="$('#delete_confirm').show();" class="no_button_like">Supprimer cet IEN</a>
				<input class="button button-save" type="submit" class="button_like" value="Enregistrer" />
				<p id="delete_confirm" style="display: none;">
					<a class="button button-confirm" href="{copixurl dest="comptes|ien|delete" user_type=$ppo->ien->user_type user_id=$ppo->ien->user_id}">Confirmez la suppression</a>
					<a class="button button-cancel" href="#" class="cancel" onclick="$('#delete_confirm').hide();">annuler</a>
				</p>
			</td>
		</tr>
		
	</table>
</form>

			