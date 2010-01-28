<form method="POST">
	<input type="hidden" name="user_type" value="{$ppo->animateur->user_type}" />
	<input type="hidden" name="user_id"   value="{$ppo->animateur->user_id}"   />
	<input type="hidden" name="save"      value="1" />
	
	<table cellspacing="1" cellpadding="5" border="0" align="center" width="90%" class="comptes_animateurs comptes_animateurs_edit">
		<tr>
			<td class="form_libelle" width="30%">Nom : </td>
			<td class="form_saisie">{$ppo->animateur->user_infos.nom|escape}</td>
		</tr>
		<tr>
			<td class="form_libelle">Pr&eacute;nom : </td>
			<td class="form_saisie">{$ppo->animateur->user_infos.prenom|escape}</td>
		</tr>
		
		<tr>
			<td class="form_libelle">Regroupements de villes : <div class="help">Vous pouvez associer ce compte &agrave; des regroupements de ville pour des t&acirc;ches d'assistance ou d'administration.</div></td>
			<td class="form_saisie">
				{foreach from=$ppo->grvilles item=grville name=grvilles}
				{assign var=id_grville value=$grville->id}
				<div>
					<input type="checkbox" name="groupe_villes_{$grville->id}" id="groupe_villes_{$grville->id}" value="1" {if $ppo->animateur_grville.$id_grville}checked{/if} /> <label for="groupe_villes_{$grville->id}">{$grville->nom|escape}</label>
				</div>
				{/foreach}
			</td>
		</tr>

		<tr>
			<td class="form_libelle">Regroupements d'&eacute;coles : <div class="help">Vous pouvez associer ce compte &agrave; des regroupements d'&eacute;coles pour des t&acirc;ches d'assistance ou d'administration.</div></td>
			<td class="form_saisie">
				{foreach from=$ppo->grecoles item=grecole name=grecoles}
				{assign var=id_grecole value=$grecole->id}
				<div>
					<input type="checkbox" name="groupe_ecoles_{$grecole->id}" id="groupe_ecoles_{$grecole->id}" value="1" {if $ppo->animateur_grecole.$id_grecole}checked{/if} /> <label for="groupe_ecoles_{$grecole->id}">{$grecole->nom|escape}</label>
				</div>
				{/foreach}
			</td>
		</tr>

		<tr>
			<td class="form_libelle">Pouvoirs : <div class="help">Autoriser les actions suivantes, restreintes aux regroupements de villes s&eacute;lectionn&eacute;s ci-dessus.</div></td>
			<td class="form_saisie">
				{foreach from=$ppo->pouvoirs item=pouvoir}
				{assign var=id_pouvoir value=$pouvoir.id}
				<div>
					<input type="checkbox" name="pouvoir_{$pouvoir.id}" id="pouvoir_{$pouvoir.id}" value="1" {if $ppo->animateur->$id_pouvoir}checked{/if} /> <label for="pouvoir_{$pouvoir.id}">{$pouvoir.nom}</label>
				</div>
				{/foreach}
			</td>
		</tr>

		<tr>
			<td class="form_libelle">Annuaire : </td>
			<td class="form_saisie">
				<div>
					<input type="checkbox" name="annuaire" id="annuaire" value="1" {if $ppo->animateur->is_visibleannuaire}checked{/if} /> <label for="annuaire">Visible dans l'annuaire des usagers, en tant que &laquo;AC/TICE&raquo;</label>
				</div>
			</td>
		</tr>

		<tr>
			<td></td>
			<td>
				<input type="submit" class="button_like" value="Enregistrer" />
				 - <a href="#delete_confirm" onclick="$('delete_confirm').style.display = 'block';" class="no_button_like">Supprimer cet animateur</a>

			</td>
		</tr>
	</table>
</form>

<p id="delete_confirm" style="display: none;">
	<a href="{copixurl dest="comptes|animateurs|delete" user_type=$ppo->animateur->user_type user_id=$ppo->animateur->user_id}" class="confirm">Confirmez la suppression</a>
	<a href="#" class="cancel" onclick="$('delete_confirm').style.display = 'none';">annuler</a>
</p>
			