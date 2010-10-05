


{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL></div>
{elseif not $ok eq null}
	<DIV CLASS="message_ok">
	<UL>
	{foreach from=$ok item=item}
		<LI>{$item}</LI><br/>
	{/foreach}
	</UL></DIV>
{/if}

<div class="actions noPrint">

<form action="{copixurl dest="|changeResponsables"}" method="post">
<input type="hidden" name="id" value="{$rFiche->idinter}"/>

	<table class="" border="0">
		<tr>
			<td width="50%"><div class="linkannuaire">{$linkpopup_responsables}</div>{i18n key=teleprocedures.type.field.responsables}
		<br/><textarea class="form" style="width:350px; height: 80px;" name="responsables" id="responsables">{$rFiche->responsables|escape}</textarea>
			</td>
			<td width="50%"><div class="linkannuaire">{$linkpopup_lecteurs}</div>{i18n key=teleprocedures.type.field.lecteurs}
		<br/><textarea class="form" style="width:350px; height: 80px;" name="lecteurs" id="lecteurs">{$rFiche->lecteurs|escape}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">Saisissez les logins des comptes utilisateurs, en les s&eacute;parant par des virgules, ou cliquez sur les liens ci-dessus pour recherches les personnes via l'annuaire.</td>
		</tr>
	</table>
<p align="center">
<input class="button button-cancel" type="button" value="Retour &agrave; la t&eacute;l&eacute;proc&eacute;dure" onclick="self.location='{copixurl dest="|fiche" id=$rFiche->idinter}';" />
<input class="button button-save" type="submit" value="Valider les modifications" />
</p>
</form>
</form>
</div>

