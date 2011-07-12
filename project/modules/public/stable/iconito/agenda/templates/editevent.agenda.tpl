<h2>{if $toEdit->id_event}{i18n key="agenda.menu.modifEvent"}{else}{i18n key="agenda.menu.ajoutEvent"}{/if}</h2>

{if $showError}
	<div class="mesgErrors">
		{foreach from=$arError item=errors key=index}
		{ulli values=$errors}
		{/foreach}
	</div>
{/if}


{literal}
<script type="text/javascript">
//<![CDATA[
//fonction qui test si la case 'toute la journée' est cochée
//afin de rendre les champs 'heuredeb_event' et 'heurefin_event' inactifs
function inactivechampheure(obj){
	if (obj.checked == true){
		obj.form.heuredeb_event.disabled = 'disabled';
		obj.form.heurefin_event.disabled = 'disabled';
	}
	else{		
		obj.form.heuredeb_event.disabled = '';
		obj.form.heurefin_event.disabled = '';
	}
}

//fonction qui test si la case 'répétition' est cochée
//afin de rendre la zone Répétition inactive
function inactivechamprepetition(obj){
	if (obj.checked == false){
		obj.form.repeat_event.disabled = 'disabled';
		obj.form.endrepeat_event.disabled = 'disabled';
		obj.form.nb_fois.disabled = 'disabled';
		obj.form.dateendrepeat_event.disabled = 'disabled';
		//obj.form.getelementbyid(nbfois).disabled = 'disabled';
		obj.form.endrepeat_event[0].disabled = true;
		obj.form.endrepeat_event[1].disabled = true;
		obj.form.endrepeat_event[2].disabled = true;
	}
	else{		
		obj.form.repeat_event.disabled = '';
		obj.form.endrepeat_event.disabled = '';
		obj.form.nb_fois.disabled = '';
		obj.form.dateendrepeat_event.disabled = '';
		//obj.form.getelementbyid(nbfois).disabled = '';
		obj.form.endrepeat_event[0].disabled = false;
		obj.form.endrepeat_event[1].disabled = false;
		obj.form.endrepeat_event[2].disabled = false;
	}
}
//]]>
</script>
{/literal}


<form action="{copixurl dest="agenda|event|valid"}" method="post" class="copixForm" name="saisieEvent">

<table class="saisieEvent">

	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.agenda"}">{i18n key="agenda.message.agenda"}</label></td>
		<td colspan="3">
			<select name="id_agenda" class="form" style="width: 250px;">
   				{foreach from=$arTitleAgendasAffiches item=title key=idAgenda}
					<option value="{$idAgenda}" {if $idAgenda eq $toEdit->id_agenda}selected="selected"{/if}>{$title}</option>
				{/foreach}
		    </select>
		</td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.titre"}">{i18n key="agenda.message.titre"} *</label></td>
		<td colspan="3"><input class="form" style="width: 400px;" type="text" id="{i18n key="agenda.message.titre"}" name="title_event" value="{$toEdit->title_event|escape}" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.desc"}">{i18n key="agenda.message.desc"}</label></td>
		<td colspan="3" class="form_saisie"><textarea class="form" style="width: 400px; height:70px;" id="desc_event" name="desc_event">{$toEdit->desc_event}</textarea><div>{$wikibuttons_desc}</div></td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.lieu"}">{i18n key="agenda.message.lieu"}</label></td>
		<td colspan="3"><input class="form" style="width:400px;" type="text" id="{i18n key="agenda.message.lieu"}" name="place_event" value="{$toEdit->place_event|escape}" maxlength="100" /></td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.begin"}">{i18n key="agenda.message.begin"} *</label></td>		
		{assign var=myDate value=$toEdit->datedeb_event|datei18n}
		<td class="form_saisie">{inputtext class="datepicker" name="datedeb_event" value=$myDate}</td>
		<td class="form_saisie"><input type="text" class="form" style="width:70px;" name="heuredeb_event" value="{$toEdit->heuredeb_event}" id="heuredeb_event" {if "1" eq $toEdit->alldaylong_event}disabled="disabled"{/if} /> (hh:mm)</td>
		<td class="form_saisie"><input type="checkbox" value="1" name="alldaylong_event" id="alldaylong_event" onchange="inactivechampheure(this)" {if "1" eq $toEdit->alldaylong_event}checked="checked"{/if} /><label for="alldaylong_event">{i18n key="agenda.message.allday"}</label></td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.end"}">{i18n key="agenda.message.end"} *</label></td>
		{assign var=myDate value=$toEdit->datefin_event|datei18n}
        <td class="form_saisie">{inputtext class="datepicker" name="datefin_event" value=$myDate}</td>
		<td class="form_saisie"><input type="text" class="form" style="width:70px;" name="heurefin_event" value="{$toEdit->heurefin_event}" id="heurefin_event" {if "1" eq $toEdit->alldaylong_event}disabled="disabled"{/if} /> (hh:mm)</td>
	</tr>
	<tr>
		<td class="form_libelle"><label for="{i18n key="agenda.message.repetition"}">{i18n key="agenda.message.repetition"}</label></td>
		<td colspan="3" class="form_saisie">
    
		<fieldset>
    <legend><input type="checkbox" value="1" name="repeat" id="{i18n key="agenda.message.repeat"}" onchange="inactivechamprepetition(this)" {if "1" eq $toEdit->repeat}checked="checked"{/if} /><label for="{i18n key="agenda.message.repeat"}">{i18n key="agenda.message.repeat"}</label></legend>
			<table cellspacing="2" cellpadding="2">
				<tr>
					<td class="form_saisie">{i18n key="agenda.message.each"}
					    <select name="repeat_event" {if "0" eq $toEdit->repeat || null eq $toEdit->repeat}disabled="disabled"{/if}>
		    				<option value="everyday_event" {if "everyday_event" eq $toEdit->repeat_event}selected="selected"{/if}>{i18n key="agenda.message.day"}</option>
							<option value="everyweek_event" {if "everyweek_event" eq $toEdit->repeat_event}selected="selected"{/if}>{i18n key="agenda.message.week"}</option>
							<option value="everymonth_event" {if "everymonth_event" eq $toEdit->repeat_event}selected="selected"{/if}>{i18n key="agenda.message.month"}</option>
							<option value="everyyear_event" {if "everyyear_event" eq $toEdit->repeat_event}selected="selected"{/if}>{i18n key="agenda.message.year"}</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td><input type="radio" id="99999999" name="endrepeat_event" value="99999999"{if $toEdit->repeat eq "0" || $toEdit->repeat eq null}disabled{/if} {if "99999999" eq $toEdit->endrepeatdate_event || "99999999" eq $toEdit->endrepeat_event}checked{/if} />{i18n key="agenda.message.undefine"}
					</td>
				</tr>
				<tr>
					<td><input type="radio" id="nbfois" name="endrepeat_event" value="nbfois"{if $toEdit->repeat eq "0" || $toEdit->repeat eq null}disabled{/if} {if "nbfois" eq $toEdit->endrepeat_event || "nbfois" eq $toEdit->endrepeatdate_event}checked{/if} /><input type="text" size="1" value="{$toEdit->nb_fois}" name="nb_fois" {if "0" eq $toEdit->repeat || null eq $toEdit->repeat}disabled="disabled"{/if} /> {i18n key="agenda.message.fois"}
					</td>
				</tr>
				<tr>
					<td><input type="radio" id="date" name="endrepeat_event" value="date"{if $toEdit->repeat eq "0" || $toEdit->repeat eq null}disabled{/if} {if $toEdit->endrepeat_event eq "date" || ($toEdit->endrepeatdate_event neq null && "99999999" neq $toEdit->endrepeatdate_event)}checked{/if} /><label for="{i18n key="agenda.message.repeatsince"}">{i18n key="agenda.message.repeatsince"}</label>
					<!--Si le bouton "indefiniment" est cochée, on ne met pas la date de fin de répétition-->
					{if "99999999" eq $toEdit->endrepeatdate_event}					
						{assign var=myDate value=""}
					{else}
                  		{if $toEdit->dateendrepeat_event eq null}
						   {assign var=myDate value=$toEdit->endrepeatdate_event|datei18n}
                 		 {else}
                     		{assign var=myDate value=$toEdit->dateendrepeat_event|datei18n}
                  		{/if}
					{/if}
		        	<!--Si la case de répétition n'est pas cochée, on grise le champ-->			
					
          {if $toEdit->repeat eq "0" || $toEdit->repeat eq null}
            {inputtext class="datepicker" name="dateendrepeat_event" value=$myDate disabled="true"}
					{else}
             {inputtext class="datepicker" name="dateendrepeat_event" value=$myDate} 
					{/if}
					</td>
				</tr>
			</table>
		</fieldset>
		</td>
	</tr>
  <tr>
  <td colspan="4" class="center form_submit">
	<input type="button" class="button button-cancel" value="{i18n key=copix:common.buttons.cancel}" onclick="javascript:document.location='{copixurl dest="agenda|agenda|vueSemaine"}'" />
	{if $toEdit->id_event}<a class="button button-delete" href="{copixurl dest="agenda|event|delete" id_event=$toEdit->id_event}">{i18n key="agenda|agenda.message.delete"}</a>{/if}
    <input type="submit" class="button button-confirm" value="{i18n key=copix:common.buttons.save}" />
  </td></tr>
</table>


</form>


