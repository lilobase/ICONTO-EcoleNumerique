{literal}
<script type="text/javascript">
function add_text(field, url){
    var itemClass = ".file-"+field;
    jQuery(itemClass).val(url);
	}
</script>
{/literal}

{if !empty($ppo->success)}
<div id="dialog-message" title="{i18n key="charte.charte" noEscape=1}">{$ppo->success}</div>
{/if}

<div class="content-info center">
	{i18n key="charte.admin.intro" noEscape=1}
</div>

{foreach from=$ppo->chartes item=charte key=key}
<div class="content-panel charte-edit">
    <h2>{i18n key=$charte.title noEscape=1}</h2>
	<div class="content-info">{i18n key=$charte.info noEscape=1}</div>
	
    {if !empty($ppo->errors.$key)}<p class="ui-state-error" >{$ppo->errors.$key}</p>{/if}
    
    <form action="{copixurl dest="charte|charte|adminAction" typeaction=new_charte target=$key}" method="post">
		<input type="hidden" value="{$key}"/>
		<table class="std">
		<tr>
			<th class="left">
				{i18n key="charte.admin.file" noEscape=1}
			</th>
			<th>
				{i18n key="charte.admin.activate" noEscape=1}
			</th>
		</tr>
		<tr>
			<td>
				<a class="button button-file fancyframe" href="{copixurl dest='malle||getFilePopup' id=$ppo->idMalle field=$key format='text'}">
        			{i18n key="charte.admin.addFile" noEscape=1}
        		</a>
        		<input type="text" name="ca-file_url" class="file-{$key} file-attach" value="{$charte.file_url}" />
			</td>
			<td>
				{html_radios name="ca-activate" checked=$charte.active options=$ppo->radio}
			</td>
		</tr>
		</table>
        <div class="charte-step">
        	<input type="submit" class="button button-save" value="{i18n key="charte.admin.save" noEscape=1}"/>
    		<a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_charte target=$key}" class="button button-delete">
    			{i18n key="charte.admin.supprCharte" noEscape=1}
    		</a>
    		<a href="{copixurl dest="charte|charte|adminAction" typeaction=suppr_validation target=$key}" class="button button-confirm">
    			{i18n key="charte.admin.delUserValid" noEscape=1}
    		</a>
		</div>
    </form>
</div>
{/foreach}