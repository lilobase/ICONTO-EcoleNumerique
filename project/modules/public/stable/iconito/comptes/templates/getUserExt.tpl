{i18n key="comptes|comptes.expl.getuserext"}

<div style="margin-bottom:15px; margin-right:7px; text-align:right;"><a class="button_like" href="{copixurl dest="comptes||getUserExtMod" id=0}" type="button" value="{i18n key="comptes|comptes.strings.add"}">{i18n key="comptes|comptes.strings.add"}</a></div>

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
		<th CLASS="liste_th" width="1">{i18n key="comptes|comptes.colonne.action"}</th>
	</tr>
	{if $userlist neq null}
		{counter assign="i" name="i" start="1"}
		{foreach from=$userlist item=user}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td align="left">{$user->ext_nom}</td>
				<td align="left">{$user->ext_prenom}</td>
				<td align="left"><i>{$user->bu2user->user_login}</i></td>
				<td align="left"><nobr>
				<a href="{copixurl dest="comptes||getUserExtMod" id=$user->ext_id}">{i18n key="comptes|comptes.strings.mod"}</a>
				{if $user->ext_id != 1}
				- <a href="{copixurl dest="comptes||getUserExtMod" id="-`$user->ext_id`"}">{i18n key="comptes|comptes.strings.del"}</a>
				{/if}
				</nobr>
				</td>
			</tr>
		{/foreach}
	{/if}
<!-- 
	<tr>
		<th class="liste_th" colspan="3"></th>
		<th class="liste_th" align="center">
			<a href="{copixurl dest="comptes||getUserExtMod" id=0}">{i18n key="comptes|comptes.strings.add"}</a>
		</th>
	</tr>
 -->
</table>

<div style="margin-top: 15px; margin-right:7px; text-align:right;"><a class="button_like" href="{copixurl dest="comptes||getUserExtMod" id=0}" type="button" value="{i18n key="comptes|comptes.strings.add"}">{i18n key="comptes|comptes.strings.add"}</a></div>
