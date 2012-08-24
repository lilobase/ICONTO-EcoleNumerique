<h2>{i18n key="rssmix.title" noEscape=1} </h2>
<a href="{copixurl dest="rssmix|default|create"}" class="floatright button button-add" >{i18n key="rssmix.add" noEscape=1}</a>
<p class="content-info">{i18n key="rssmix.description" noEscape=1}</p>

{if !empty($ppo->success)}

    <p class="mesgSuccess">{$ppo->success}</p>
    
{/if}

{if empty($ppo->rss)}
    <div class="content-panel">
    {i18n key="rssmix.noUrl" noEscape=1}
    </div>
    {else}
    <table class="viewItems">
        	<thead>
            	<tr>
                    <th>{i18n key="rssmix.label.title}</th>
                	<th>{i18n key="rssmix.label.url noEscape=1}</th>
                    <th class="actions">{i18n key="rssmix.label.actions}</th>
                 </tr>
            </thead>
        	<tbody>
{foreach from=$ppo->rss item=rssItem key=k}
    {if ($k%2)==0}<tr>{else}<tr class="even">{/if}
                <td>{$rssItem.title}</td>
                <td>{$rssItem.url}</td>
                <td class="center"><a href="{copixurl dest="rssmix|default|update" id=$rssItem.id}" class="button button-update " >{i18n key="rssmix.update" noEscape=1} </a> &nbsp; <a href="{copixurl dest="rssmix|default|delete" id=$rssItem.id}" class="button button-delete delete" >{i18n key="rssmix.delete" noEscape=1}</a></td>
            </tr>
    
{/foreach}
 </tbody>
        </table>
{/if}