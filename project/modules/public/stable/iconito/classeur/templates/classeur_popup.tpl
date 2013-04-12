<h2>{i18n key="classeur.message.filesToAdd"}</h2>

<div id="sidebar">
  {copixzone process=classeur|arborescenceClasseurs classeurId=$ppo->classeur->id dossierCourant=$ppo->dossierId field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}
</div>

<div class="content-view">
  <form name="styleForm" id="style-form" style="display: none;">
    <span style="white-space: nowrap;">
      <strong>{i18n key="classeur.popup.align"}</strong>
  
      <input id="align-none" type="radio" name="align" value="" checked />
      <label for="align-none">{i18n key="classeur.popup.align_none"}</label>
  
      <input id="align-left" type="radio" name="align" value="left" />
      <label for="align-left"><img src="{copixurl}themes/default/images/icon-16/align-left.png" alt="{i18n key="classeur.popup.align_left"}" title="{i18n key="classeur.popup.align_left"}" /></label>
  
      <input id="align-center" type="radio" name="align" value="center" />
      <label for="align-center"><img src="{copixurl}themes/default/images/icon-16/align-center.png" alt="{i18n key="classeur.popup.align_center"}" title="{i18n key="classeur.popup.align_center"}" /></label>
  
      <input id="align-right" type="radio" name="align" value="right" />
      <label for="align-right"><img src="{copixurl}themes/default/images/icon-16/align-right.png" alt="{i18n key="classeur.popup.align_right"}" title="{i18n key="classeur.popup.align_right"}" /></label>
      &nbsp;&nbsp;|&nbsp;&nbsp;
    </span>
  
    <span style="white-space: nowrap;">
      <strong>{i18n key="classeur.popup.size"}</strong>
      <input id="size-small" type="radio" name="size" value="small" checked />
      <label for="size-small"><img src="{copixurl}themes/default/images/icon-16/resize-small.png" alt="{i18n key="classeur.popup.size_small"}" title="{i18n key="classeur.popup.size_small"}" /></label>
      <input id="size-medium" type="radio" name="size" value="medium" />
      <label for="size-medium"><img src="{copixurl}themes/default/images/icon-16/resize-middle.png" alt="{i18n key="classeur.popup.size_middle"}" title="{i18n key="classeur.popup.size_middle"}" /></label>
      <input id="size-large" type="radio" name="size" value="large" />
      <label for="size-large"><img src="{copixurl}themes/default/images/icon-16/resize-big.png" alt="{i18n key="classeur.popup.size_large"}" title="{i18n key="classeur.popup.size_large"}" /></label>
      <input id="size-original" type="radio" name="size" value="original" />
      <label for="size-original"><img src="{copixurl}themes/default/images/icon-16/resize-no.png" alt="{i18n key="classeur.popup.size_original"}" title="{i18n key="classeur.popup.size_original"}" /></label>
    </span>
  </form>
  
  <div class="overflow">
  <table id="folder-content" class="listView">
    <thead>
      <tr>
        <th><input type="checkbox" id="check_all" /></th>
        <th>{i18n key="classeur.message.title"}</th>
        <th>{i18n key="classeur.message.type"}</th>
        <th>{i18n key="classeur.message.date"}</th>
        <th>{i18n key="classeur.message.size"}</th>
      </tr>
    </thead>
    <tbody>
      {assign var=index value=1}
      {if $ppo->dossierParent}
        <tr class="folder even">
          <td>&nbsp;</td>
          <td><a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeur->id dossierId=$ppo->dossierParent->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}" title="{i18n key="classeur.message.openFolder" nom=$ppo->dossierParent->nom noEscape=1}">{i18n key="classeur.message.parentFolder"}</a></td>
          <td colspan="4">&nbsp;</td>
        </tr>
        {assign var=index value=2}
      {elseif $ppo->classeurParent}
        <tr class="folder even">
          <td>&nbsp;</td>
          <td>
            {if $ppo->classeurParent->isPersonnel}
              {i18n key="classeur.message.personnalFolder" assign=nom}
            {else}
              {assign var=nom value=$ppo->classeurParent->titre}
            {/if}
            <a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeur->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}" title="{i18n key="classeur.message.openFolder" nom=$nom noEscape=1}">{i18n key="classeur.message.parentFolder"}</a>
          </td>
          <td colspan="4">&nbsp;</td>
        </tr>
        {assign var=index value=2}
      {/if}
      {foreach from=$ppo->dossiers item=dossier}
      <tr class="folder {if $index%2 eq 0}odd{else}even{/if}">
        <td class="center"><a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeur->id dossierId=$dossier->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}" title="{i18n key="classeur.message.openFolder" nom=$dossier->nom noEscape=1}"><img src="{copixurl}themes/default/images/icon-16/icon-folder.png" alt="" /></a></td>
        <td><a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeur->id dossierId=$dossier->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}" title="{i18n key="classeur.message.openFolder" nom=$dossier->nom noEscape=1}">{$dossier->nom|escape}</a></td>
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
  
    	{if $ppo->format eq "fckeditor" OR $ppo->format eq "html" OR $ppo->format eq "ckeditor"}
    		{assign var=htmlDownload value="[["|cat:$fichier->fullUrl|cat:"|download]]"}
    		{assign var=htmlView value="[["|cat:$fichier->fullUrl|cat:"|view]]"}
    	{/if}
  
    	{i18n key="malle|malle.error.unsupportedFormat" format=$ppo->format assign=i18n_unsupportedFormat}
    	
      <tr class="{$fichier->type} {if $index%2 eq 0}odd{else}even{/if}">
        <td class="center check-file">
          <input type="hidden" name="item-id" value="{$fichier->id}"/>
      	  <input type="hidden" name="item-name" value="{$fichier|escape}"/>
      		<input type="hidden" name="item-file" value="{$fichier->fullUrl}"/>
      		<input type="hidden" name="item-image" value="{$fichier->url}"/>
      		<input type="hidden" name="item-field" value="{$ppo->field}"/>
      		<input type="hidden" name="item-format" value="{$ppo->format}"/>
      		<input type="hidden" name="item-durl" value="{$htmlDownload|wiki|urlencode}"/>
      		<input type="hidden" name="item-vurl" value="{$htmlView|wiki|urlencode}"/>
      		<input type="hidden" name="item-err" value="{$i18n_unsupportedFormat|addslashes}"/>
      		<input type="hidden" name="item-ext" value="{$fichier->extension}"/>
          <input type="checkbox" class="check" name="fichiers[]" value="{$fichier->id}" />
        </td>
        {if $fichier->estUnFavori()}
          <td><a href="{$fichier->getLienFavori()}" title="{i18n key="classeur.message.openFile" titre=$fichier}" target="_blank">{$fichier|escape}</a></td>
          <td>{i18n key="classeur.message.favorite"}</td>
        {else}
          <td><a href="{copixurl dest="classeur||telechargerFichier" classeurId=$ppo->classeur->id fichierId=$fichier->id}" title="{i18n key="classeur.message.openFile" titre=$fichier noEscape=1}" target="_blank">{$fichier|escape}</a></td>
          <td>{$fichier->type}</td>
        {/if}
        <td>{$fichier->date_creation|datei18n:"date_short_time"|substr:0:10}</td>
        <td>{$fichier->taille|human_file_size}</td>
      </tr>
      {assign var=index value=$index+1}
      {/foreach}
    </tbody>
  </table>
  
  {if $ppo->niveauUtilisateur >= PROFILE_CCV_MEMBER}
    <a class="addfile button button-add">{i18n key="classeur.message.add"}</a>
    <div class="addfile-form" style="display: none;">
    	<form action="{copixurl dest="|envoieFichierPopup"}" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
          <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
          <input type="hidden" name="dossierTmp" id="dossierTmp" value="{$ppo->dossierTmp}" />
          <input type="hidden" name="field" value="{$ppo->field}"/>
    		  <input type="hidden" name="format" value="{$ppo->format}"/>
    		  <input type="hidden" name="moduleType" value="{$ppo->moduleType}"/>
    		  <input type="hidden" name="moduleId" value="{$ppo->moduleId}"/>
          <input type="file" name="fichiers[]" id="fichiers" />
    	    <a class="button button-cancel">{i18n key="classeur.message.cancel"}</a>
          <input class="button button-confirm" type="submit" value="{i18n key="classeur.message.ok"}" />
    	</form>
    </div>
  {/if}
  </div><!-- End overflow -->
  
  
  {if $ppo->format neq "id"}
    <form name="form" id="options">
      {i18n key="classeur.message.mode"}
      <input id="mode-view" type="radio" name="mode" value="view" checked /><label for="mode-view">{i18n key="classeur.message.modeView"}</label>
      <input id="mode-download" type="radio" name="mode" value="download" /><label for="mode-download">{i18n key="classeur.message.modeDownload"}</label>
    </form>
  {/if}
</div> <!-- End content-view -->

<div id="popup_actions" class="content-panel center">
    <input id="docancel" class="button button-cancel" type="button" value="{i18n key="classeur.message.cancel"}" />
    <input id="doinsert" class="button button-confirm" type="button" value="{i18n key="classeur.message.insert"}" />
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
	var pictureTypes = ["PNG", "png", "JPG", "jpg", "gif", "GIF", "jpe", "JPE"];

	$('#folder-content input[type="checkbox"]').change (function() {
	  
	  var pictureChecked = 0;
	  $('#folder-content td.check-file input[type="checkbox"]:checked').each(function () {
	    if (jQuery.inArray($(this).parent().parent().attr('class').substr(0, 3), pictureTypes) > -1) {
	      
	      pictureChecked = pictureChecked + 1;
	    }
	  });
	  
	  if (pictureChecked > 0) {
	    
	    $('#style-form').show();
	  }
	  else {
	    
	    $('#style-form').hide();
	  }
	});
	
	$('#doinsert').click (function() {
	  
	  <!-- Styles alignement et taille -->
	  var align='';
  	if($('#align-left').is(':checked')) align='L';
  	if($('#align-center').is(':checked')) align='C';
  	if($('#align-right').is(':checked')) align='R';

  	var size='';
  	if($('#size-small').is(':checked')) size='_s64';
  	if($('#size-medium').is(':checked')) size='_240';
  	if($('#size-large').is(':checked')) size='_480';
  	
	  var domode = $('#options input[name="mode"]:checked').val();
		$('#folder-content :checked').each( function() {
		  
		  doid = $(this).parent('.check-file').children('input[name="item-id"]').val();
		  doname = $(this).parent('.check-file').children('input[name="item-name"]').val();
			dofile = $(this).parent('.check-file').children('input[name="item-file"]').val();
			doimage = $(this).parent('.check-file').children('input[name="item-image"]').val();
			dofield = $(this).parent('.check-file').children('input[name="item-field"]').val();
			doformat = $(this).parent('.check-file').children('input[name="item-format"]').val();
			dodurl = $(this).parent('.check-file').children('input[name="item-durl"]').val();
			dovurl = $(this).parent('.check-file').children('input[name="item-vurl"]').val();
			doerr = $(this).parent('.check-file').children('input[name="item-err"]').val();
			doextension = $(this).parent('.check-file').children('input[name="item-ext"]').val();
			doalign = align;
			dosize = size;
			//console.log("---" + dofile + "," + dofield + "," + doformat + "," + dodurl + "," + dovurl + "," + doerr);
			insertDocument (domode, dofile, doimage, dofield, doformat, dodurl, dovurl, doerr, doid, doname, doextension, doalign, dosize);
		});
		parent.jQuery.fancybox.close();
	});
	
	$('#docancel').click (function() {
		parent.jQuery.fancybox.close();
	});

  $('.addfile').click (function() {
    $('.addfile').toggle();
   	$('.addfile-form').toggle();
  });
  
  $('.addfile-form .button-cancel').click (function() {
		$('.addfile').toggle();
		$('.addfile-form').toggle();
	});
});
</script>
{/literal}
