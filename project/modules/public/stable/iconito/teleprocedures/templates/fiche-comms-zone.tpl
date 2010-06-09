<div class="comms">


<table class="list">
	<tr>
		<td class=""><div class="teleprocedures_titre">Les &eacute;changes</div></td>
		{if $canCheckVisible}
		<td class="milieu"></td>
		<td class=""><div class="teleprocedures_titre">Les notes internes</div></td>
		{/if}
	</tr>
	
{if count ($list)}



	{foreach from=$list item=infosupp}
	<tr>
		<td class="echange {if !$canCheckVisible}echangeDir{/if}">
		{if $infosupp->info_message}
		{if $infosupp->user.avatar}<img src="{copixurl}{$infosupp->user.avatar}" alt="{$infosupp->user.avatar}" title="" align="right" hspace="2" vspace="2" height="30" />{/if}
		{user label=$infosupp->user.prenom|cat:' '|cat:$infosupp->user.nom userType=$infosupp->user.type userId=$infosupp->user.id linkAttribs='' login=$infosupp->user.login dispMail=0 assign='who'}
		De {i18n key="teleprocedures|teleprocedures.msg.author" who=$who date=$infosupp->dateinfo|datei18n:"date_short" noEscape=1}<br/>
		{$infosupp->info_message|render:$rFiche->type_format}
		{/if}
		</td>
		{if $canCheckVisible}
		<td class="milieu"></td>
		<td class="note">
		{if $infosupp->info_commentaire}
		{if $infosupp->user.avatar}<img src="{copixurl}{$infosupp->user.avatar}" alt="{$infosupp->user.avatar}" title="" align="right" hspace="2" vspace="2" height="30" />{/if}
		{user label=$infosupp->user.prenom|cat:' '|cat:$infosupp->user.nom userType=$infosupp->user.type userId=$infosupp->user.id linkAttribs='' login=$infosupp->user.login dispMail=0 assign='who'}
		De {i18n key="teleprocedures|teleprocedures.msg.author" who=$who date=$infosupp->dateinfo|datei18n:"date_short" noEscape=1}<br/>
		{$infosupp->info_commentaire|render:$rFiche->type_format}

		{/if} 
		</td>
		{/if}
	
	</tr>
	
		
  {/foreach}
	
{/if}


{if $canAddComment}
<div class="noPrint">
	<form action="{copixurl dest="|insertInfoSupp"}" method="post">
	 <input type="hidden" name="id" value="{$rFiche->idinter}"/>
	<tr>
		<td class="echange {if !$canCheckVisible}echangeDir{/if}">Saisissez votre message :<br/>
		{$info_message_edition}</td>
		{if $canCheckVisible}
		<td class="milieu"></td>
		<td class="note">
			Saisissez votre note interne :<br/>
			{$info_commentaire_edition}</td>
		{/if}
	</tr>
</div>
{/if}

	</table>
	
{if $canAddComment}
	{if $canCheckVisible}
		<input class="teleprocedures" type="submit" value="Valider le message et/ou la note interne" />
	{else}
		<input class="teleprocedures" type="submit" value="Valider le message" />
	{/if}
</form>
{/if}
	
	<br/>


</div>




