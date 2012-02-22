
	<DIV CLASS="carnet_message">
	<DIV CLASS="carnet_message_infos{if $session.user_id==$topic->createur}_me{/if}">
  
  {if $topic->avatar}<img src="{copixurl}{$topic->avatar}" alt="{$topic->avatar}" title="" align="right" hspace="2" vspace="2" />{/if}
  
  {if ($topic->createur_infos.type eq 'USER_RES' AND $ppo->canView_USER_RES) OR ($topic->createur_infos.type eq 'USER_ENS' AND $ppo->canView_USER_ENS) }
    {user label=$topic->createur_nom userType=$topic->createur_infos.type userId=$topic->createur_infos.id linkAttribs='STYLE="text-decoration:none;"' login=$topic->createur_infos.login dispMail=0 assign='who'}
  {else}
    {assign var=who value=$topic->createur_nom}
  {/if}
  
  
  {i18n key="carnet.msg.author" who=$who date=$topic->date_creation|datei18n:"date_short_time" noEscape=1} :</DIV>
	<DIV CLASS="carnet_message_message">{$topic->message|render:$topic->format}</DIV>
<DIV CLASS="carnet_concerne">

{assign var=test value=`copixurl dest="|getTopic" id=$topic->id`}



{if $canWriteClasse}
	{if !$eleve or $eleve eq "CLASSE"}
		{i18n key="carnet.msg.concernLinkSel" pNb=$topic->nb_eleves link=$linkClasse noEscape=1}
	{else}
		{i18n key="carnet.msg.concernLink" pNb=$topic->nb_eleves link=$linkClasse noEscape=1}
	{/if}
{else}
	{if !$eleve or $eleve eq "CLASSE"}
		{i18n key="carnet.msg.concernSel" pNb=$topic->nb_eleves noEscape=1}
	{else}
		{i18n key="carnet.msg.concern" pNb=$topic->nb_eleves noEscape=1}
	{/if}
{/if}
		
		{assign var=sep value=""}{foreach from=$topic->eleves item=item}{$sep}{if $canWriteClasse}<A HREF="{copixurl dest="|getTopic" id=$topic->id eleve=$item->eleve}">{/if}{if $eleve eq $item->eleve}<b>{/if}{$item->eleve_infos}{if $eleve eq $item->eleve}</b>{/if}{if $canWriteClasse}</A>{/if}{assign var=sep value=", "}{/foreach}

		</DIV>
		{if $canPrintTopic}
		<DIV CLASS="carnet_message_actions">
		<a class="button button-print" href="{copixurl dest="|getTopic" id=$topic->id eleve=$eleve print=1}">{if $topic->eleves|@count>1}{i18n key="carnet.printNex" nb=$topic->eleves|@count}{else}{i18n key="carnet.print1ex" nb=$topic->eleves|@count}{/if}</a>
		</DIV>
		{/if}
	</DIV>


{if $list neq null}
	
		{foreach from=$list item=item}
		<DIV CLASS="carnet_message" ID="m{$item->id}">
		<DIV CLASS="carnet_message_infos{if $session.user_id==$item->auteur}_me{/if}">
    
    {if $item->avatar}<img src="{copixurl}{$item->avatar}" alt="{$item->avatar}" title="" align="right" hspace="2" vspace="2" />{/if}
    
    {if ($item->auteur_infos.type eq 'USER_RES' AND $ppo->canView_USER_RES) OR ($item->auteur_infos.type eq 'USER_ENS' AND $ppo->canView_USER_ENS) }
      {user label=$item->auteur_nom userType=$item->auteur_infos.type userId=$item->auteur_infos.id linkAttribs='STYLE="text-decoration:none;"' login=$item->auteur_infos.login dispMail=0 assign='who'}
    {else}
      {assign var=who value=$item->auteur_nom}
    {/if}
    
{i18n key="carnet.msg.author" who=$who date=$item->date|datei18n:"date_short_time" noEscape=1}     :
		</DIV>
		<DIV CLASS="carnet_message_message"><DIV CLASS="carnet_message_eleve">{i18n key="carnet.msg.eleve"} : 
    {if $item->eleve_infos.type eq 'USER_ELE' AND $ppo->canView_USER_ELE}
      {user label=$item->eleve_nom userType=$item->eleve_infos.type userId=$item->eleve_infos.id linkAttribs='STYLE="text-decoration:none;"' login=$item->eleve_infos.login dispMail=0}
    {else}
      {$item->eleve_nom}
    {/if}
    </DIV>{$item->message|render:$item->format}</DIV>
		<DIV CLASS="carnet_message_actions">
		<a href="{copixurl dest="|getMessageForm" topic=$topic->id eleve=$item->eleve}">{i18n key="carnet.btn.answer"}</a>
		</DIV></DIV>
		{/foreach}

	{else}

	<br/><DIV>
	<i>{i18n key="carnet.list.noAnswer"}</i>
	</DIV>
	{/if}

{if $eleve && $eleve neq 'CLASSE'}
<br/><DIV CLASS="" ALIGN="LEFT">
<input class="button button-continue" onclick="self.location='{copixurl dest="|getMessageForm" topic=$topic->id eleve=$eleve}'" type="button" value="{i18n key="carnet.btn.answer"}" />
</DIV>
{elseif $topic->nb_eleves eq 1}
<br/><DIV CLASS="" ALIGN="LEFT">
<input class="button button-continue" onclick="self.location='{copixurl dest="|getMessageForm" topic=$topic->id eleve=$topic->eleves.0->eleve}'" type="button" value="{i18n key="carnet.btn.answer"}" />
</DIV>
{/if}


