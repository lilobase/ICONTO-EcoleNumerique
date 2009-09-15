
<h1>{i18n key="blog.nav.droits"}</h1>
	

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}

{if $list}
	
	<FORM NAME="form" ID="form" ACTION="{copixurl dest="blog|admin|doUnsubscribe"}" METHOD="POST">
	<input type="hidden" name="id" value="{$blog->id_blog}" />
	<input type="hidden" name="kind" value="{$kind}" />

	<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		<tr>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.number"}</th>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.login"}</th>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.name"}</th>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.firstname"}</th>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.right"}</th>
			<th CLASS="liste_th">{i18n key="groupe|groupe.adminMembers.list.delete"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$list item=user}
			<tr class="list_line{$cpt%2}">
				<td align="center">{$cpt}</td>
				<td>{user label=$user.login userType=$user.type userId=$user.id login=$user.login dispMail=1}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom}</td>
				<td>{$user.droitnom}</td>

				<td ALIGN="CENTER">{assign var=lui value=$user.type|cat:"|"|cat:$user.id}{if $his neq $lui}<input type="checkbox" name="membres[]" value="{$user.type}|{$user.id}" class="noBorder">{/if}</td>
				{counter}
			</tr>
		{/foreach}
		<tr CLASS="liste_footer">
			<td colspan="5"></td>
			<TD ALIGN="CENTER"><a href="javascript: deleteMembres();">{i18n key="groupe|groupe.btn.unsubscribe"}</a></TD>
		</TR>
		</table>
		</FORM>
		
	{else}
	<i>{i18n key="groupe|groupe.noMember"}</i>
	{/if}
	
	
	
	
	<H2>{i18n key="groupe|groupe.adminMembers.add"}</H2>
	
	<table cellpadding="1" cellspacing="1" border="0" width="500">
<form action="{copixurl dest="blog|admin|doSubscribe"}" method="post">
<input type="hidden" name="id" value="{$blog->id_blog}" />
<input type="hidden" name="kind" value="{$kind}" />
	<TR><td CLASS="form_saisie" COLSPAN=2>
	

<textarea class="form" style="width: 400px; height: 50px;" name="membres" id="membres">{$membres}</textarea>

</TD></TR>
<tr><td CLASS="form_saisie" colspan="2">{i18n key="groupe|groupe.adminMembers.addInfo"} {$linkpopup}</TD></TR>

<tr>
<td CLASS="" colspan="2">

{select name="droit" values=$droit_values selected=$droit extra='class="form"'}

<input style="" class="form_button" type="submit" value="{i18n key="groupe|groupe.btn.subscribe"}" />


</TD>
</tr>







</TABLE>

</form>	
	
	

