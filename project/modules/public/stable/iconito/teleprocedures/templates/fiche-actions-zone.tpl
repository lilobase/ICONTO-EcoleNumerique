
<div class="actions noPrint">




{if $canChangeStatut}
<p></p>
<div class="teleprocedures_titre">Changement de statut</div>

<table class="">
<form action="{copixurl dest="|changeStatut"}" method="post">
<input type="hidden" name="id" value="{$rFiche->idinter}"/>
<tr>
<td>
Changer le statut de la t&eacute;l&eacute;proc&eacute;dure
</td>
<td class="statuts">
{foreach from=$arStat item=stat}
<input type="radio" name="idstatu" id="idstatu_{$stat->idstat}" value="{$stat->idstat}" {if $rFiche->idstatu eq $stat->idstat} checked{/if}/><label for="idstatu_{$stat->idstat}"> {$stat->nom}</label>
{/foreach}
</td>
</tr>
</table>
<div align="center"><input class="teleprocedures" type="submit" value="Valider le changement" /></div>
</form>
{/if}



{if $canSendMails}
<div class="teleprocedures_titre">Transmission externe</div>
{if !$mailEnabled}
	<i>{i18n key=teleprocedures|teleprocedures.error.noMailEnabled}</i>
{else}
	<form action="{copixurl dest="|sendMails"}" method="post">
	<input type="hidden" name="id" value="{$rFiche->idinter}"/>
	<table class="">
		<tr>
			<td colspan="2">Transmettre la t&eacute;l&eacute;proc&eacute;dure par mail</td>
			<td rowspan="5" class="message">
			Votre message (sera ajout&eacute; au d&eacute;tail de la t&eacute;l&eacute;proc&eacute;dure initiale)
			<textarea class="form" style="width:400px;height:100px;" name="mail_message" id="mail_message">{$rFiche->mail_message|escape}</textarea>
			</td>
		</tr>
		<tr>
			<td align="right"><nobr>Adresse d'exp&eacute;diteur</nobr></td><td><input type="text" name="mail_from" value="{$rFiche->mail_from|escape}" class="form" style="width:300px;"></td>
			
		</tr>
		<tr>
			<td align="right">Destinataire(s)</td><td><input type="text" name="mail_to" value="{$rFiche->mail_to|escape}" class="form" style="width:300px;"></td>
		</tr>
		<tr>
			<td align="right">Copie(s)</td><td><input type="text" name="mail_cc" value="{$rFiche->mail_cc|escape}" class="form" style="width:300px;"></td>
		</tr>
		<tr>
			<td align="right" colspan="2">Note : S&eacute;parez les adresses par des virgules</td>
		</tr>
	</table>
<div align="center"><input class="teleprocedures" type="submit" value="Envoyer le message" /></div>
	</form>
{/if}
{/if}



</div>

