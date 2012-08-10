<script language="Javascript1.2" SRC="{copixurl}js/iconito/module_groupe_admin.js"></script>

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
	
	<FORM NAME="form" ID="form" ACTION="{copixurl dest="|doUnsubscribe"}" METHOD="POST">
	<INPUT TYPE="hidden" NAME="id" VALUE="{$groupe->id}" />
	<h2>{i18n key="groupe.adminMembers.now"}</h2>
	<table class="viewItems">
		<tr>
			<th class="extraSmall">{i18n key="groupe.adminMembers.list.select"}</th>
            <th class="extraSmall">{i18n key="groupe.adminMembers.list.number"}</th>
			<th>{i18n key="groupe.adminMembers.list.name"}</th>
			<th>{i18n key="groupe.adminMembers.list.firstname"}</th>
			<th>{i18n key="groupe.adminMembers.list.type"}</th>
			<th>{i18n key="groupe.adminMembers.list.login"}</th>
			<th>{i18n key="groupe.adminMembers.list.right"}</th>
			<th>{i18n key="groupe.adminMembers.list.dates"}</th>
			<th class="actions">{i18n key="groupe.list.action"}</th>
		</tr>

        {counter start=1 assign="cpt"}
        {foreach from=$list item=user}
        <tr class="{if $cpt % 2 == 0}even{else}odd{/if}">
                <td class="center">{assign var=lui value=$user.type|cat:"|"|cat:$user.id}{if $his neq $lui}<input type="checkbox" name="membres[]" value="{$user.type}|{$user.id}" id="check{$user.id}">{/if}</td>
                <td class="center">{$cpt}</td>
                <td>{$user.nom}</td>
                <td>{$user.prenom}</td>
                <td>{$user.type|profil}</td>
                <td>{user label=$user.login userType=$user.type userId=$user.id login=$user.login dispMail=1}</td>
                <td>{$user.droitnom}</td>
                <td>
                    {if ($user.debut && $user.debut>$today) || ($user.fin && $user.fin<$today)}
                        <div class="members_dates_nok">
                    {/if}
                    {if $user.debut && $user.fin}
                        {i18n key="groupe.adminMembers.list.dateDebutFin" debut=$user.debut|datei18n fin=$user.fin|datei18n}
                    {elseif $user.debut}
                        {i18n key="groupe.adminMembers.list.dateDebut" debut=$user.debut|datei18n}
                    {elseif $user.fin}
                        {i18n key="groupe.adminMembers.list.dateFin" debut=$user.fin|datei18n}
                    {else}
                        {i18n key="groupe.adminMembers.list.dateAll"}
                    {/if}
                    {if ($user.debut && $user.debut>$today) || ($user.fin && $user.fin<$today)}
                        </div>
                    {/if}
                </td>
                <td class="center">
                    {assign var=lui value=$user.type|cat:"|"|cat:$user.id}
                    {if $his neq $lui}
                        <a class="button button-update" href="{copixurl dest="|getHomeAdminMember" id=$groupe->id user_type=$user.type user_id=$user.id}">{i18n key="groupe.adminMembers.list.modif"}</a>
                        <a class="button button-delete" href="{copixurl dest="|getHomeAdminMember" id=$groupe->id user_type=$user.type user_id=$user.id}" rel="check{$user.id}">{i18n key="groupe.adminMembers.list.delete"}</a>
                    {/if}
                </td>
                {counter}
            </tr>
        {/foreach}
        </table>
        <p><a class="button button-delete" href="javascript:deleteMembres();">{i18n key="groupe.btn.deleteRightsSelection"}</a></p>
        
		</form>
		
	{else}
	<em>{i18n key="groupe.noMember"}</em>
	{/if}

	{if $listWaiting}
	
	<FORM NAME="form" ID="form" ACTION="{copixurl dest="|doSubscribeWaiting"}" METHOD="POST">
	<INPUT TYPE="hidden" NAME="id" VALUE="{$groupe->id}" />
	<H2>{i18n key="groupe.adminMembers.waiting"}</H2>
	<table class="viewItems">
		<tr>
			<th>{i18n key="groupe.adminMembers.list.number"}</th>
			<th>{i18n key="groupe.adminMembers.list.name"}</th>
			<th>{i18n key="groupe.adminMembers.list.firstname"}</th>
			<th>{i18n key="groupe.adminMembers.list.type"}</th>
			<th>{i18n key="groupe.adminMembers.list.login"}</th>
			<th>{i18n key="groupe.list.action"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$listWaiting item=user}
      {assign var=tmp value=$user.type|cat:"|"|cat:$user.id}
			<tr class="{if $cpt % 2 == 0}even{else}odd{/if}">
				<td class="center">{$cpt}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom} {$user.type_nom}</td>
                <td>{$user.type|profil}</td>
				<td>{$user.login}</td>
				<td class="">
                <input type="radio" id="wait" name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "WAIT" || $membresW[$tmp] eq ""}CHECKED{/if} value="WAIT"> <label for="wait">{i18n key="groupe.adminMembers.list.subscribeWait"}</label><br />
                <input type="radio" id="subscribe1" name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "1"}CHECKED{/if} value="1"> <label for="suscribe1">{i18n key="groupe.adminMembers.list.subscribe1"}</label><br />
                <input type="radio" id="subscribe0" name="membres[{$user.type}|{$user.id}]" {if $membresW[$tmp] eq "0"}CHECKED{/if} value="0"> <label for="subscribe0">{i18n key="groupe.adminMembers.list.subscribe0"}</label>
                 </td>
			{counter}
			</tr>
		{/foreach}
		<tr class="liste_footer">
			<td colspan="5">{i18n key="groupe.adminMembers.addDatesW"}
      <br/>
      {i18n key="groupe.adminMembers.addDates.debut"}&nbsp;: {inputtext class="datepicker" name="debutW" value=$debutW|datei18n} &nbsp; {i18n key="groupe.adminMembers.addDates.fin"}&nbsp;: {inputtext class="datepicker" name="finW" value=$finW|datei18n}
      </td>
			<td class="center"><input class="button button-confirm" type="submit" value="{i18n key="groupe.btn.valid"}" /></td>
		</tr>
		</table>
		</form>
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
	
	
	