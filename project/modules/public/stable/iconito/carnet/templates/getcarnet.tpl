
<div class="right">
{if !$eleve && $hisEleves|@count>0}
<div class="right"><a class="button button-add" href="{copixurl dest="|getTopicForm" classe=$classe eleve=$eleve}" type="button" value="{i18n key="carnet.newTopic"}">{i18n key="carnet.newTopic"}</a></div>
{elseif ($eleve)}
<div class="right"><a class="button button-add" href="{copixurl dest="|getTopicForm" classe=$classe eleve=$eleve}" type="button" value="{i18n key="carnet.newTopic"}">{i18n key="carnet.newTopic"}</a></div>
{/if}
</div>
<br />

	{if $list neq null}
	
	{if $canWriteClasse || $hisEleves|@count>0}
	<div class="carnet_filtrage_col block">
	<div>{i18n key="carnet.list.filtrageIntro"} :</div>
	
	{if $canWriteClasse}<p></p>&gt; <a href="{copixurl dest="|getCarnet" classe=$classe}">{if !$eleve}<strong>{/if}{i18n key="carnet.list.filtrageAll"}{if !$eleve}</strong>{/if}</a>{/if}
	
	{if $canWriteClasse}<p></p>&gt; <a href="{copixurl dest="|getCarnet" classe=$classe eleve=CLASSE}">{if $eleve eq "CLASSE"}<strong>{/if}{i18n key="carnet.list.filtrageClasse"}{if $eleve eq "CLASSE"}</strong>{/if}</a>{/if}
	
	{if $hisEleves}<p></p>&gt; {i18n key="carnet.list.filtrageEleves"} :<br/>
	{foreach from=$hisEleves item=item}<a href="{copixurl dest="|getCarnet" classe=$classe eleve=$item.id}">{if $eleve eq $item.id}<strong>{/if}{$item.prenom} {$item.nom}{if $eleve eq $item.id}</strong>{/if}</a><br/>{/foreach}{/if}
	</div>
	{/if}
	
	
	
	
		{counter assign="i" name="i"}
	<DIV>
<table class="liste center" style="width:84%;">
	<tr>
		<th CLASS="liste_th">&nbsp;</th>
		<th CLASS="liste_th">{i18n key="carnet.list.topic"}</th>
		<th CLASS="liste_th">{i18n key="carnet.list.concern"}</th>
		<th CLASS="liste_th">{i18n key="carnet.list.answers"}</th>
		<th CLASS="liste_th">{i18n key="carnet.list.lastMessage"}</th>
		<th CLASS="liste_th">{i18n key="carnet.list.creation"}</th>
	</tr>
	
		{foreach from=$list item=item}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
			<TD>{if ($item->last_visite eq null or $item->last_visite<$item->last_msg_date)}<a title="{i18n key="carnet.list.goNewTitle"}" href="{copixurl dest="|getTopic" id=$item->id eleve=$eleve go=new}"><IMG src="{copixresource path="img/icon_new.gif"}" alt="icon_new.gif" WIDTH="17" HEIGHT="17" BORDER="0" /></a>{/if}</TD>
			<td>
			<strong><a href="{copixurl dest="|getTopic" id=$item->id eleve=$eleve}">{$item->titre}</a></strong>
			
			<br/>{$item->message|truncate:70:"...":true}
			
			</TD>
			<TD class="center">{if $nb_eleves_classe eq $item->nb_eleves}{i18n key="carnet.list.allClasse"}{elseif $item->nb_eleves eq 1}{user label=$item->eleves_infos[0]->eleve_nom userType=$item->eleves_infos[0]->eleve_infos.type userId=$item->eleves_infos[0]->eleve_infos.id linkAttribs='STYLE="text-decoration:none;"'}{else}{i18n key="carnet.list.neleves" nb=$item->nb_eleves}{/if}</TD>
			<TD class="center">{$item->nb_messages}</td>
			<TD>{if $item->last_msg_date}{i18n key="carnet.list.lastMessageAuthor" date=$item->last_msg_date|datei18n:"date_short_time" author=""}{user label=$item->last_msg_auteur_nom userType=$item->last_msg_auteur_infos.type userId=$item->last_msg_auteur_infos.id linkAttribs='STYLE="text-decoration:none;"'}{else}<em>{i18n key="carnet.list.lastMessageAuthorNone"}</em>{/if}</TD>
			<TD>{i18n key="carnet.list.creationAuthor" date=$item->date_creation|datei18n:"date_short_time" author=""}{user label=$item->createur_nom userType=$item->createur_infos.type userId=$item->createur_infos.id linkAttribs='STYLE="text-decoration:none;"'}</TD>
			</tr>

			
		{/foreach}
</table><br/>
	{copixresource path="img/icon_new.gif" assign="img"}
	<DIV STYLE="font-size:0.8em;">{i18n key="carnet.list.newExplain" img=$img noEscape=1}</DIV>
	</DIV>
	
	{else}

	<em>{i18n key="carnet.list.noTopic"}</em>

	{/if}
<br class="clearBoth" />
