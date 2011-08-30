{if $titre}<div class="titre">{$titre}</div>{/if}
{if count($listLiens)}
<ul>
{foreach from=$listLiens item=liens}  
<li><a href="{$liens->url_blnk}" target="_blank">{$liens->name_blnk}</a></li>
{/foreach}
</ul>
{/if}