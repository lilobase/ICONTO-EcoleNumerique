
<h2>{i18n key="blog.nav.droits"}</h2>
	

{if not $errors eq null}
	<div class="mesgErrors">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</div>
{/if}

{if $list}
	
	<form id="form" action="{copixurl dest="blog|admin|doUnsubscribe"}" method="post">
	<input type="hidden" name="id" value="{$blog->id_blog}" />
	<input type="hidden" name="kind" value="{$kind}" />

	<table class="viewItems">
		<tr>
			<th>{i18n key="groupe|groupe.adminMembers.list.number"}</th>
			<th>{i18n key="groupe|groupe.adminMembers.list.login"}</th>
			<th>{i18n key="groupe|groupe.adminMembers.list.name"}</th>
			<th>{i18n key="groupe|groupe.adminMembers.list.firstname"}</th>
			<th>{i18n key="groupe|groupe.adminMembers.list.right"}</th>
			<th>{i18n key="groupe|groupe.adminMembers.list.delete"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$list item=user}
			<tr class="list_line{$cpt%2}">
				<td class="center">{$cpt}</td>
				<td>{user label=$user.login userType=$user.type userId=$user.id login=$user.login dispMail=1}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom}</td>
				<td>{$user.droitnom}</td>

				<td class="center">{assign var=lui value=$user.type|cat:"|"|cat:$user.id}{if $his neq $lui}<input type="checkbox" name="membres[]" value="{$user.type}|{$user.id}" class="noBorder">{/if}</td>
				{counter}
			</tr>
		{/foreach}
		<tr class="liste_footer">
			<td colspan="5"></td>
			<td class="center"><a class="button button-delete" href="javascript: deleteMembres();">{i18n key="groupe|groupe.btn.unsubscribe"}</a></td>
		</tr>
		</table>
		</form>
		
	{else}
	<i>{i18n key="groupe|groupe.noMember"}</i>
	{/if}
	
	
	
	
	<h2>{i18n key="groupe|groupe.adminMembers.add"}</h2>
	
	<form action="{copixurl dest="blog|admin|doSubscribe"}" method="post">
    <table class="editItems">
        <tr>
        	<th class="alignTop">{i18n key="groupe|groupe.adminMembers.list.login"}</th>
            <td><input type="hidden" name="id" value="{$blog->id_blog}" />
            <input type="hidden" name="kind" value="{$kind}" />
            <textarea class="form" style="width: 400px; height: 50px;" name="membres" id="membres">{$membres}</textarea>
            <br /><em>{i18n key="groupe|groupe.adminMembers.addInfo" noEscape=1} {$linkpopup}</em></td>
        </tr>
        <tr>
            <th>{i18n key="groupe|groupe.adminMembers.list.right"}</th>
            <td>{select name="droit" values=$droit_values selected=$droit extra='class="form"'}</td>
         </tr>
         <tr>
         	<td></td>
            <td><input class="button button-confirm" type="submit" value="{i18n key="groupe|groupe.btn.subscribe"}" /></td>
        </tr>
    </table>
    </form>	
	
	