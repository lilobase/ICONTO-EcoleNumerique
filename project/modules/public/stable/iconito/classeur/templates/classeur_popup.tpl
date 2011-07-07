<h2>{i18n key="classeur.message.filesToAdd"}</h2>

{if $ppo->classeurs neq null}
  <ul class="classeurs-list">
    {foreach from=$ppo->classeurs item=classeur}
    <li>
      <a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$classeur->id field=$ppo->field format=$ppo->format}">
        {if $classeur->id eq $ppo->classeurPersonnel}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </a>
    </li>
    {/foreach}
  </ul>
{/if}

{if $ppo->dossiers neq null || $ppo->fichiers neq null}
<table id="folder-content">
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>{i18n key="classeur.message.title"}</th>
      <th>{i18n key="classeur.message.type"}</th>
      <th>{i18n key="classeur.message.date"}</th>
      <th>{i18n key="classeur.message.size"}</th>
    </tr>
  </thead>
  <tbody>
    {assign var=index value=1}
    {foreach from=$ppo->dossiers item=dossier}
    <tr class="folder {if $index%2 eq 0}odd{else}even{/if}">
      <td>&nbsp;</td>
      <td><a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeur->id dossierId=$dossier->id field=$ppo->field format=$ppo->format}" title="{i18n key="classeur.message.openFolder" nom=$dossier->nom}">{$dossier->nom|escape}</a></td>
      <td>---</td>
      <td>{$dossier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
      <td>
        {if $dossier->nb_dossiers neq 0}
          {$dossier->nb_dossiers} {if $dossier->nb_dossiers eq 1}dossier{else}dossiers{/if}
        {/if}
        {if $dossier->nb_fichiers neq 0}
          {$dossier->nb_fichiers} {if $dossier->nb_fichiers eq 1}fichier{else}fichiers{/if}
        {/if}
        {$dossier->taille|human_file_size}
      </td>
    </tr>
    {assign var=index value=$index+1}
    {/foreach}
    {foreach from=$ppo->fichiers item=fichier}
    
    {copixurl assign='copixurl'}
    {assign var='positionCoupure' value=$fichier->fichier|strpos:'.'}
    {assign var='extension' value=$fichier->fichier|substr:$positionCoupure}
    
    {assign var=file value=$copixurl|cat:"static/classeur/"|cat:$ppo->classeur->id|cat:"-"|cat:$ppo->classeur->cle|cat:"/"|cat:$fichier->id|cat:"-"|cat:$fichier->cle|cat:$extension}

  	{if $ppo->format eq "fckeditor" OR $ppo->format eq "html" OR $ppo->format eq "ckeditor"}
  		{assign var=htmlDownload value="[["|cat:$file|cat:"|download]]"}
  		{assign var=htmlView value="[["|cat:$file|cat:"|view]]"}
  	{/if}

  	{i18n key="malle|malle.error.unsupportedFormat" format=$ppo->format assign=i18n_unsupportedFormat}
  	
    <tr class="{$fichier->type} {if $index%2 eq 0}odd{else}even{/if}">
      <td class="check-file">
        <input type="hidden" name="item-id" value="{$fichier->id}"/>
    	  <input type="hidden" name="item-name" value="{$fichier|escape}"/>
    		<input type="hidden" name="item-file" value="{$file}"/>
    		<input type="hidden" name="item-field" value="{$ppo->field}"/>
    		<input type="hidden" name="item-format" value="{$ppo->format}"/>
    		<input type="hidden" name="item-durl" value="{$htmlDownload|wiki|urlencode}"/>
    		<input type="hidden" name="item-vurl" value="{$htmlView|wiki|urlencode}"/>
    		<input type="hidden" name="item-err" value="{$i18n_unsupportedFormat|addslashes}"/>
        <input type="checkbox" class="check" name="fichiers[]" value="{$fichier->id}" />
      </td>
      {if $fichier->estUnFavori()}
        <td><a href="{$fichier->getLienFavori()}" title="{i18n key="classeur.message.openFile" titre=$fichier}">{$fichier|escape}</a></td>
        <td>{i18n key="classeur.message.favorite"}</td>
      {else}
        <td><a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeur->id fichierId=$fichier->id}" title="{i18n key="classeur.message.openFile" titre=$fichier}">{$fichier|escape}</a></td>
        <td>{$fichier->getExtension()}</td>
      {/if}
      <td>{$fichier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
      <td>{$fichier->taille|human_file_size}</td>
    </tr>
    {assign var=index value=$index+1}
    {/foreach}
  </tbody>
</table>
{else}
  <p>{i18n key="classeur.message.noFiles"}</p>
{/if}

<div id="popup_actions" class="content-panel">
	<div class="floatright">
		<input id="doinsert" class="button button-confirm" type="button" value="{i18n key="classeur.message.insert"}" />
		<input id="docancel" class="button button-cancel" type="button" value="{i18n key="classeur.message.cancel"}" />
	</div>
	{if $ppo->format neq "id"}
	<div class="">
		<form name="form" id="options">
			{i18n key="classeur.message.mode"}
			<input id="mode-view" type="radio" name="mode" value="view" checked /><label for="mode-view">{i18n key="classeur.message.modeView"}</label>
			<input id="mode-download" type="radio" name="mode" value="download" /><label for="mode-download">{i18n key="classeur.message.modeDownload"}</label>
		</form>
	</div>
	{/if}
</div>

{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
	var dofile;
	var dofield;
	var doformat;
	var dodurl;
	var dovurl;
	var doerr;
	$('#doinsert').click (function() {
	  var domode = $('#options input[name="mode"]:checked').val();
		$('#folder-content :checked').each( function() {
		  
		  doid = $(this).parent('.check-file').children('input[name="item-id"]').val();
		  doname = $(this).parent('.check-file').children('input[name="item-name"]').val();
			dofile = $(this).parent('.check-file').children('input[name="item-file"]').val();
			dofield = $(this).parent('.check-file').children('input[name="item-field"]').val();
			doformat = $(this).parent('.check-file').children('input[name="item-format"]').val();
			dodurl = $(this).parent('.check-file').children('input[name="item-durl"]').val();
			dovurl = $(this).parent('.check-file').children('input[name="item-vurl"]').val();
			doerr = $(this).parent('.check-file').children('input[name="item-err"]').val();
			console.log("---" + dofile + "," + dofield + "," + doformat + "," + dodurl + "," + dovurl + "," + doerr);
			insertDocument (domode, dofile, dofield, doformat, dodurl, dovurl, doerr, doid, doname);
		});
		parent.jQuery.fancybox.close();
	});
	
	$('#docancel').click (function() {
		parent.jQuery.fancybox.close();
	});
});
</script>
{/literal}