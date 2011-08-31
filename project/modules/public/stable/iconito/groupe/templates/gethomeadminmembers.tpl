<script language="Javascript1.2" SRC="{copixurl}js/iconito/module_groupe_admin.js"></script>

{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}


	{if $list}
	
	<FORM NAME="form" ID="form" ACTION="{copixurl dest="|doUnsubscribe"}" METHOD="POST">
	<INPUT TYPE="hidden" NAME="id" VALUE="{$groupe->id}" />
	<H2>{i18n key="groupe.adminMembers.now"}</H2>
	<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		<tr>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.number"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.name"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.firstname"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.type"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.login"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.right"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.dates"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.modif"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.delete"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$list item=user}
			<tr class="list_line{$cpt%2}">
				<td align="center">{$cpt}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom}</td>
                <td>{$user.type|profil}</td>
				<td>{user label=$user.login userType=$user.type userId=$user.id login=$user.login dispMail=1}</td>
				<td>{$user.droitnom}</td>
				<td>
        {if ($user.debut && $user.debut>$today) || ($user.fin && $user.fin<$today)}<div class="members_dates_nok">{/if}
        {if $user.debut && $user.fin} 
        {i18n key="groupe.adminMembers.list.dateDebutFin" debut=$user.debut|datei18n fin=$user.fin|datei18n}
        {elseif $user.debut} 
        {i18n key="groupe.adminMembers.list.dateDebut" debut=$user.debut|datei18n}
        {elseif $user.fin} 
        {i18n key="groupe.adminMembers.list.dateFin" debut=$user.fin|datei18n}
        {else} 
        {i18n key="groupe.adminMembers.list.dateAll"}
        {/if}
        {if ($user.debut && $user.debut>$today) || ($user.fin && $user.fin<$today)}</div>{/if}
        </td>
        <td align="center">{if $user.droit<70}<a class="button button-update" href="{copixurl dest="|getHomeAdminMember" id=$groupe->id user_type=$user.type user_id=$user.id}">{i18n key="groupe.adminMembers.list.modif"}</a>{/if}</td>
				<td ALIGN="CENTER">{assign var=lui value=$user.type|cat:"|"|cat:$user.id}{if $his neq $lui}<input type="checkbox" name="membres[]" value="{$user.type}|{$user.id}" class="noBorder">{/if}</td>
				{counter}
			</tr>
		{/foreach}
		<tr CLASS="liste_footer">
			<td colspan="8"></td>
			<td class="center"><a class="button button-delete" href="javascript:deleteMembres();">{i18n key="groupe.btn.unsubscribe"}</a></td>
		</TR>
		</table>
		</FORM>
		
	{else}
	<i>{i18n key="groupe.noMember"}</i>
	{/if}

	{if $listWaiting}
	
	<FORM NAME="form" ID="form" ACTION="{copixurl dest="|doSubscribeWaiting"}" METHOD="POST">
	<INPUT TYPE="hidden" NAME="id" VALUE="{$groupe->id}" />
	<H2>{i18n key="groupe.adminMembers.waiting"}</H2>
	<table border="0" CLASS="liste" align="CENTER" CELLSPACING=2 CELLPADDING=2>
		<tr>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.number"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.login"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.name"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.firstname"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.subscribeWait"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.subscribe1"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.subscribe0"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$listWaiting item=user}
      {assign var=tmp value=$user.type|cat:"|"|cat:$user.id}
			<tr CLASS="list_line{$cpt%2}">
				<td ALIGN="CENTER">{$cpt}</td>
				<td>{$user.login}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom} {$user.type_nom}</td>
				<td ALIGN="CENTER"><input type=radio name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "WAIT" || $membresW[$tmp] eq ""}CHECKED{/if} value="WAIT" class="noBorder"></td>
				<td ALIGN="CENTER"><input type=radio name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "1"}CHECKED{/if} value="1" class="noBorder"></td>
				<td ALIGN="CENTER"><input type=radio name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "0"}CHECKED{/if} value="0" class="noBorder"></td>
				{counter}
			</tr>
		{/foreach}
		<tr CLASS="liste_footer">
			<TD COLSPAN="6">{i18n key="groupe.adminMembers.addDatesW"}
      <br/>
      {i18n key="groupe.adminMembers.addDates.debut"}&nbsp;: {inputtext class="datepicker" name="debutW" value=$debutW|datei18n} &nbsp; {i18n key="groupe.adminMembers.addDates.fin"}&nbsp;: {inputtext class="datepicker" name="finW" value=$finW|datei18n}
      </TD>
			<TD ALIGN="CENTER"><input class="button button-confirm" type="submit" value="{i18n key="groupe.btn.valid"}" /></TD>
		</TR>
		</table>
		</FORM>
	{/if}

		
	
	
	<h2>{i18n key="groupe.adminMembers.add"}</h2>
	
	<form action="{copixurl dest="|doSubscribe"}" method="post">
    <table cellpadding="1" cellspacing="1" border="0">
        <tr>
        	<td colspan="2">{i18n key="groupe.adminMembers.addInfo" noEscape=1}</td>
        </tr>
        <tr>
            <td valign="top">{i18n key="groupe.adminMembers.list.login"} :</td>
            <td>
            	<input type="hidden" name="id" value="{$groupe->id}" />
            	<textarea class="form" style="width: 400px; height: 50px;" name="membres" id="membres">{$membres}</textarea><br />
                {$linkpopup}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="form_saisie">{i18n key="groupe.adminMembers.addDates"}</td>
        </tr>
        <tr>
            <td>{i18n key="groupe.adminMembers.addDates.debut"}&nbsp;:</td>
            <td>{inputtext class="datepicker" name="debut" value=$debut|datei18n}</td>
        </tr>
        <tr>
            <td>{i18n key="groupe.adminMembers.addDates.fin"}&nbsp;:</td>
            <td>{inputtext class="datepicker" name="fin" value=$fin|datei18n}</td>
        </tr>
	</table>
<div class="center"><a href="{copixurl dest="|getHomeAdmin" id=$groupe->id}" class="button button-cancel">{i18n key="groupe.btn.cancel"}</a> <input class="button button-add" type="submit" value="{i18n key="groupe.btn.subscribe"}" /></div>

</form>	
	
	
	