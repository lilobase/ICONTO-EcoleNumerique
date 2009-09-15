
<form id="form_concerto" action="http://bayonne.espace-famille.net/bayonne/index.do?action=login" method="POST" style="display: none;">
<input name="idSession" value="" type="hidden" />

	<table>
	<tr>
		<td>{i18n key="concerto|concerto.goForm.user"}</td>
		<td>{i18n key="concerto|concerto.goForm.password"}</td>
		<td></td>
	</tr>
	
	<tr>
		<td><input name="codeFamille" value="{$login}" /></td>
		<td><input name="motDePasse" value="{$password}" /></td>
		<td><input type="submit" value="{i18n key="concerto|concerto.goForm.submit"}" class="form_button" /></td>
	</tr>
	</table>
	
</form>

{if $login && $password}
	<p><b>{i18n key="concerto|concerto.goForm.msg"}</b></p>
	<script type="text/javascript" language="Javascript1.2">
		submit_concerto = true;
	</script>
{/if}

<p><a class="button_like" href="{copixurl dest="kernel||getHome"}" title="{i18n key="concerto|concerto.goForm.back"}">{i18n key="concerto|concerto.goForm.back"}</a></p>

