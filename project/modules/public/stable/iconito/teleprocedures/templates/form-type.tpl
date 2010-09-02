


{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL></div>
{/if}

{if $preview and !$errors}
<H3>{i18n key="minimail.preview"}</H3>
<DIV CLASS="minimail_message">
  <DIV><b>{$title}</b></DIV>
  <HR CLASS="minimail_hr" NOSHADE SIZE="1" />
  <DIV>{$message|render:$format}</DIV>
</DIV>
{/if}


<form action="{copixurl dest="admin|formtype"}" method="post" enctype="multipart/form-data" class="copixForm">
<input type="hidden" name="save" value="1" />
<input type="hidden" name="idtype" value="{$type->idtype}" />
<input type="hidden" name="teleprocedure" value="{$type->teleprocedure}" />

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.nom}{oblig}</td>
	  	<td CLASS="form_saisie"><input type="text" name="nom" value="{$type->nom|escape}" class="form" style="width:500px;"></td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.is_online}{oblig}</td>
	  	<td CLASS="form_saisie">{html_radios name="is_online" values=$is_online.values output=$is_online.output checked=$type->is_online}</td>
   </tr>

   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.format}{oblig}</td>
	  	<td CLASS="form_saisie">{html_radios name="format" values=$format.values output=$format.output checked=$type->format}</td>
   </tr>
	 
   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.texte_defaut}</td>
	  	<td CLASS="form_saisie">{$edition_texte_defaut}</td>
   </tr>
	 


   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.responsables}{oblig}</td>
	  	<td CLASS="form_saisie"><textarea class="form" style="width: 300px; height: 30px;" name="responsables" id="responsables">{$type->responsables}</textarea> {$linkpopup_responsables}</td>
   </tr>
	 
   <tr>
      <td CLASS="form_libelle">{i18n key=teleprocedures.type.field.lecteurs}</td>
	  	<td CLASS="form_saisie"><textarea class="form" style="width: 300px; height: 30px;" name="lecteurs" id="lecteurs">{$type->lecteurs}</textarea> {$linkpopup_lecteurs}</td>
   </tr>
		
	 {if !$mailEnabled}
	 	<tr>
			<td CLASS="form_libelle">Emails</td>
			<td CLASS="form_saisie">{i18n key=teleprocedures|teleprocedures.error.noMailEnabled}</td>
		</tr>
	 {else}
   <tr>
	 		<td CLASS="form_libelle">Emails</td>
	  	<td CLASS="form_saisie">
			Pr&eacute;remplissez les informations des mails qui seront envoy&eacute;s &agrave; partir des t&eacute;l&eacute;proc&eacute;dures :
			<br/>
			<div class="telep">{i18n key=teleprocedures.type.field.mail_from}</div><input type="text" name="mail_from" value="{$type->mail_from|escape}" class="form" style="width:300px;"><br/>
			<div class="telep">{i18n key=teleprocedures.type.field.mail_to}</div><input type="text" name="mail_to" value="{$type->mail_to|escape}" class="form" style="width:300px;"><br/>
			<div class="telep">{i18n key=teleprocedures.type.field.mail_cc}</div><input type="text" name="mail_cc" value="{$type->mail_cc|escape}" class="form" style="width:300px;"><br/>
			<div class="telep">{i18n key=teleprocedures.type.field.mail_message}</div><textarea class="form" style="width:400px;height:60px;" name="mail_message" id="mail_message">{$type->mail_message}</textarea>
</td>
   </tr>
	 {/if}
	 
	 
	 
	 
	 
	 

	 <tr><td colspan="2" CLASS="form_submit">
	 
	 <i>{i18n key="kernel|kernel.fields.oblig"}</i>
	 <br/>
	 
<input type="hidden" name="id_blog" value="{$blog->id_blog}">
<input type="submit" class="form_button" value="{i18n key="copix:common.buttons.ok"}" />
<input class="form_button" type="button" value="{i18n key="copix:common.buttons.cancel"}" onclick="javascript:window.location='{copixurl dest="admin|" id=$type->teleprocedure}'" />
	 
	</td>	 
	 </tr>

	 
	 </table>
</form>