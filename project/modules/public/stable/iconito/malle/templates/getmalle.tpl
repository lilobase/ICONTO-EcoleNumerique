
<script type="text/javascript">
var id = {$id};
var folder = {$folder};
var noSelection = "{i18n key='malle.error.noSelection'}";

</script>

{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#folder-checkall').click (function() { $('#remote-checker :checkbox[name="folders[]"]').attr('checked', true); });
	$('#folder-checknone').click (function() { $('#remote-checker :checkbox[name="folders[]"]').attr('checked', false); });
	$('#file-checkall').click (function() { $('#remote-checker :checkbox[name="files[]"]').attr('checked', true); });
	$('#file-checknone').click (function() { $('#remote-checker :checkbox[name="files[]"]').attr('checked', false); });
	$('.item-rename').click (function() {
    $('.item-link').eq($('.item-rename').index(this)).toggle();
    $('.item-field').eq($('.item-rename').index(this)).toggle();
  });

  $('a.download').click (function() { 
		nb_checked = jQuery('#remote-checker :checked[name="files[]"]').size() + jQuery('#remote-checker :checked[name="folders[]"]').size();
		if (nb_checked > 0) {
			var pars = 'id='+id+'&folder='+folder+'';
			pars += '&'+$('#remote-checker :checked[name="files[]"]').serialize();
			pars += '&'+$('#remote-checker :checked[name="folders[]"]').serialize();
			var url = getActionURL('malle|default|doActionDownloadZip', pars);
			self.location = url;
		} else {
			alert (noSelection);
		}
		return false;
  }); 
  
  
  $('form#formAddWeb').live('submit', function() {
    var nom = $('input[name=nom]').val();
    var url = $('input[name=url]').val();
    if (nom && url && url != 'http://') {
      return true;
    } else {
      alert ('{/literal}{i18n key="malle.error.web.add" addslashes=true}{literal}');
      return false;
    }
  });

  $('a.addweb').fancybox({
  	'onComplete'		: function () { $('input[name=nom]').focus(); }
	});
  
  $('a.addfolder').fancybox({
  	'onComplete'		: function () { $('input[name=new_folder]').focus(); }
	});
  
});
</script>
{/literal}

{$petitpoucet}

<div style="min-height:275px;">

{if not $errors eq null}
	<div class="mesgErrors">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</div>
{/if}

{assign var="tailleFolders" value=0}
{assign var="tailleFiles" value=0}

<form id="remote-checker" name="renameitems" action="{copixurl dest="|doActionRename"}" method="post">
	<input type="hidden" name="id" value="{$id}" />
	<input type="hidden" name="folder" value="{$folder}" />

{if !$folders|@count and !$files|@count}
	{i18n key="malle.emptyFolder"}
{else}
	
<table class="malle-table">
{if $folders neq null}
	{foreach from=$folders item=item}
	<tr class="malle-table-folder">
		<td class="malle-table-icon">
			<IMG src="{copixresource path="img/malle/icon_folder.png"}" />
		</td>
		<td class="malle-table-name">
			<a class="item-link" href="{copixurl dest="|getMalle" id=$id folder=$item->id}">{$item->nom|escape}</a>
			<div class="item-field" style="display: none;">
				<input type="text" name="newFolders[{$item->id}]" value="{$item->nom}" style="width: 400px;" maxlength="200"/>
				<input type="submit" class="button button-confirm" value=""/>
			</div>
		</td>
		<td class="malle-table-edit">
			{if $can.item_rename}<a class="item-rename"><img src="{copixresource path='images/button-action/action_update.png'}" alt="{i18n key='malle.btn.rename'}" title="{i18n key='malle.btn.rename'}" /></a>{/if}
		</td>
		<td class="malle-table-content">
			{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
		<td class="malle-table-action">
			{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			<INPUT TYPE="checkbox" NAME="folders[]" VALUE="{$item->id}">
			{/if}
		</td>
	</tr>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	<tr class="malle-table-file">
    {if $item->isLink()}
      {copixzone process=malle|link malle=$id folder=$folder file=$item can=$can}
    {else}
      
		<td class="malle-table-icon">
			<img src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" />
		</td>
		<td class="malle-table-name">
        
      
			{if $can.file_download}
				<a class="item-link" href="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</a>
			{else}
				<span class="item-link">{$item->nom|escape}</span>
			{/if}
			<div class="item-field" style="display: none;">
				<input type="text" name="newFiles[{$item->id}]" value="{$item->nom}" style="width: 400px;" maxlength="200"/>
				<input type="submit" class="button button-confirm" value=""/>
			</div>
		</td>
		<td class="malle-table-edit">
			{if $can.item_rename}<a class="item-rename"><img src="{copixresource path='images/button-action/action_update.png'}" alt="{i18n key='malle.btn.rename'}" title="{i18n key='malle.btn.rename'}" /></a>{/if}
		</td>
		<td class="malle-table-content">
			{$item->type_text}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
    {/if}
		<td class="malle-table-action">
			{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
  			<INPUT TYPE="checkbox" NAME="files[]" VALUE="{$item->id}" />
			{/if}
		</td>
	</tr>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
	{/foreach}
{/if}
</table>

<div class="malle-footer">
	{i18n key="malle.folders" pNb=$folders|@count}
	{if $folders|@count}({$tailleFolders|human_file_size})
		{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			: {i18n key="malle.check"} 
			<input type="button" class="button" id="folder-checkall" value="{i18n key="malle.checkAll"}">
			<input type="button" class="button" id="folder-checknone" value="{i18n key="malle.checkNothing"}">
		{/if}
	{/if}
	 |
	{i18n key="malle.files" pNb=$files|@count}
	{if $files|@count}({$tailleFiles|human_file_size})
		{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			: {i18n key="malle.check"}
			<input type="button" class="button" id="file-checkall" value="{i18n key="malle.checkAll"}">
			<input type="button" class="button" id="file-checknone" value="{i18n key="malle.checkNothing"}">
		{/if}
	{/if}
</div>

{/if}
</form>

</div>